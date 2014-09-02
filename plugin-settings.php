<?php

if ( !defined( 'ABSPATH' ) )
	die( '-1' );

?>
<form method="post">
	<?php wp_nonce_field('gforms_update_force_ssl', 'gforms_update_force_ssl') ?>
    <h3><span><i class="fa fa-cogs"></i> <?php _e( 'Force SSL', 'gf-force-ssl' ); ?></span></h3>
    <table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="force_ssl_all"><?php _e( 'Force All Forms SSL', 'gf-force-ssl' ); ?></label>
					<?php // gform_tooltip("settings_license_key") ?>
					<a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_require_login" tooltip="<h6><?php _e( 'Require all forms to be https', 'gf-force-ssl' ); ?></h6>"><a href='#' onclick='return false;' class='gf_tooltip tooltip' title='<h6><?php _e( 'Require all forms to be https', 'gf-force-ssl' ); ?></h6><?php _e( 'Check this option to require all forms on your site to be https to view this form. Will redirect to the SSL version of the form page.<br /><br /><b>Note:</b> will preserve any query string parameters.', 'gf-force-ssl' ); ?>'><i class='fa fa-question-circle'></i></a></a>
				</th>
				<td>
					<input type="checkbox" value="1" name="force_ssl_all" <?php checked( rgar( $form, 'force_ssl_all' ) ); ?> />
					<label for="force_ssl_all"><?php _e( 'Require page to be https', 'gf-force-ssl' ); ?></label>
					<span class="gf_settings_description"><?php _e("Check this if you wish to force all forms to be forced to use SSL."); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit" style="text-align: left;">
		<input type="submit" name="submit" value="Save Settings" class="button-primary gfbutton">
	</p>
</form>