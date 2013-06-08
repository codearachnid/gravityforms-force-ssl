<?php

if ( !defined( 'ABSPATH' ) )
	die( '-1' );

?>
<tr>
	<th>
		<?php _e( 'Force SSL', 'gf-force-ssl' ); ?>
		<a href="#" onclick="return false;" class="tooltip tooltip_form_require_login" tooltip="<h6><?php _e( 'Require user to be logged in', 'gf-force-ssl' ); ?></h6><?php _e( 'Check this option to require a user to be logged in to view this form', 'gf-force-ssl' ); ?>">(?)</a>
	</th>
	<td>
		<input type="checkbox" value="<?php echo rgar( $form, 'gf_force_ssl' ); ?>" name="gf_force_ssl" />
		<label for="gf_force_ssl"><?php _e( 'Require user to be logged in', 'gf-force-ssl' ); ?></label>
	</td>
</tr>