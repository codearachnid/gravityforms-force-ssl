<?php

if ( !defined( 'ABSPATH' ) )
	die( '-1' );

?>
<tr>
	<th>
		<?php _e( 'Force SSL', 'gf-force-ssl' ); ?>
		<a href="#" onclick="return false;" class="tooltip tooltip_form_require_login" tooltip="<h6><?php _e( 'Require page to be https', 'gf-force-ssl' ); ?></h6>"><a href='#' onclick='return false;' class='gf_tooltip tooltip' title='<h6><?php _e( 'Require page to be https', 'gf-force-ssl' ); ?></h6><?php _e( 'Check this option to require the page to be https to view this form. Will redirect to the SSL version of this page.<br /><br /><b>Note:</b> will preserve any query string parameters.', 'gf-force-ssl' ); ?>'><i class='fa fa-question-circle'></i></a></a>
	</th>
	<td>
		<input type="checkbox" value="1" name="force_ssl" <?php checked( rgar( $form, 'force_ssl' ) ); ?> />
		<label for="force_ssl"><?php _e( 'Require page to be https', 'gf-force-ssl' ); ?></label>
	</td>
</tr>