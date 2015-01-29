<?php

/**
 * Plugin Name: Gravity Forms: Force SSL
 * Plugin URI:
 * Description: An addon to Gravity Forms to add an option to force your forms to be loaded SSL only.
 * Version: 1.4.1
 * Author: Timothy Wood (@codearachnid)
 * Author URI: http://www.codearachnid.com
 * Author Email: tim@imaginesimplicity.com
 * Text Domain: 'gf-force-ssl'
 * License:
 *
 *     Copyright 2013 Imagine Simplicity (tim@imaginesimplicity.com)
 *     License: GNU General Public License v3.0
 *     License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author codearachnid
 *
 */

if ( !defined( 'ABSPATH' ) )
	die( '-1' );

if ( !class_exists( 'gf_force_ssl' ) ) {
	class gf_force_ssl {

		private static $_this;
		public $path;
		const MIN_WP_VERSION = '3.5';

		function __construct() {

			$this->path = trailingslashit( dirname( __FILE__ ) );

			add_action( 'init', array( $this, 'init' ) );
			add_action( 'the_posts', array( $this, 'check_for_shortcode' ) );
			add_filter( 'gform_form_settings', array( $this, 'form_settings' ), 10, 2 );
			add_filter( 'gform_pre_form_settings_save', array( $this, 'form_settings_save' ) );
		}

		function init() {
			if ( is_admin() ) {
				// add settings page into Gravity Forms > Settings
				GFForms::add_settings_page( __( 'Force SSL', 'gf-force-ssl' ), array( $this, 'plugin_settings_page' ) );
			}
		}

		function check_for_shortcode( $posts ){
			$pattern = get_shortcode_regex();
			foreach( $posts as $post ){
				$check = preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'gravityform', $matches[2] );
				$check_alt = preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'gravityforms', $matches[2] );
				if( !empty($matches[3]) ) {
					$attributes = shortcode_parse_atts( trim( $matches[3][0]) );
					if( !empty($attributes['id']) && !is_ssl() && $this->check_force( $attributes['id'] ) )
						$this->force_ssl( $post->ID );
				}
			}
			return $posts;
		}

		function check_force( $id ){

			if( get_option( 'gform_force_ssl_all' ) == '1' )
				return true;

			$form_meta = GFFormsModel::get_form_meta_by_id( $id );

			if( !empty( $form_meta[0][ 'force_ssl' ] ) )
				return $form_meta[0][ 'force_ssl' ];

			return false;
		}

		function force_ssl( $post_id = null ){
			if( is_admin() )
				return;

			$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
			$goto = str_replace( 'http://', 'https://', get_permalink( $post_id ) );
			if( !empty( $_GET ) ) {
				$goto .= '?' . http_build_query($_GET);
			}
			wp_redirect( $goto, 301 );
			exit;
		}

		function form_settings( $settings, $form ) {
			ob_start();

			// include the fields for form settings
			include $this->path . '/form-settings.php';

			$settings['Restrictions']['force_ssl'] = ob_get_clean();
			return $settings;
		}
		function form_settings_save( $form ) {
			$form['force_ssl'] = rgpost( 'force_ssl' );
			return $form;
		}

		function plugin_settings_page() {

			if ( ! empty( $_POST ) && check_admin_referer( 'gforms_update_force_ssl', 'gforms_update_force_ssl' ) ) {
			   update_option("gform_force_ssl_all", rgpost("force_ssl_all"));
			}

			$form['force_ssl_all'] = get_option( 'gform_force_ssl_all' );

			include $this->path . '/plugin-settings.php';
		}

		/**
		 * Check the minimum WP version
		 *
		 * @static
		 * @return bool Whether the test passed
		 */
		public static function prerequisites() {;
			$pass = TRUE;
			$pass = $pass && version_compare( get_bloginfo( 'version' ), self::MIN_WP_VERSION, '>=' );
			$pass = $pass && class_exists( 'RGForms' ) && class_exists( 'RGFormsModel' );
			return $pass;
		}

		/**
		 * Display fail notices
		 *
		 * @static
		 * @return void
		 */
		public static function fail_notices() {
			printf( '<div class="error"><p>%s</p></div>',
				sprintf( __( 'Gravity Forms: Force SSL requires WordPress v%s or higher.', 'wp-plugin-framework' ),
					self::MIN_WP_VERSION
				) );
		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return static $_this instance
		 * @readlink http://eamann.com/tech/the-case-for-singletons/
		 */
		public static function instance() {
			if ( !isset( self::$_this ) ) {
				$className = __CLASS__;
				self::$_this = new $className;
			}
			return self::$_this;
		}
	}

	/**
	 * Instantiate class and set up WordPress actions.
	 *
	 * @return void
	 */
	function load_gf_force_ssl() {

		// we assume class_exists( 'WPPluginFramework' ) is true
		if ( apply_filters( 'load_gf_force_ssl/pre_check', gf_force_ssl::prerequisites() ) ) {

			// when plugin is activated let's load the instance to get the ball rolling
			add_action( 'init', array( 'gf_force_ssl', 'instance' ), -100, 0 );

		} else {

			// let the user know prerequisites weren't met
			add_action( 'admin_head', array( 'gf_force_ssl', 'fail_notices' ), 0, 0 );

		}
	}

	// high priority so that it's not too late for addon overrides
	add_action( 'plugins_loaded', 'load_gf_force_ssl' );

}
