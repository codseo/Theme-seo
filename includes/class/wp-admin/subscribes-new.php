<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly.');
}
?>

<h1 class="wp-heading-inline"><?php _e( 'New plan', 'mlm' ); ?></h1>
<a href="<?php echo esc_url( admin_url( 'admin.php?page=mlm-subscribes' ) ); ?>" class="page-title-action"><?php _e( 'Return', 'mlm' ); ?></a>
<hr class="wp-header-end">

<form id="mlm_new_subscribe_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_user"><?php _e( 'Select user', 'mlm' ); ?><label></th>
				<td>
					<?php wp_dropdown_users( $attributes['args'] ); ?>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_plan"><?php _e( 'Select plan', 'mlm' ); ?><label></th>
				<td>
					<?php if( is_array( $attributes['plans'] ) && count( $attributes['plans'] ) > 0 ): ?>
						<select name="mlm_plan" id="mlm_plan" class="regular-text mlm-select">
							<?php foreach( $attributes['plans'] as $key => $value ): ?>
								<option value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
							<?php endforeach; ?>
						</select>
					<?php else: ?>
						<p class="descripton"><?php _e( 'No plans defined.', 'mlm' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field( 'mlm_takafopij', 'mlm_security' ); ?>
		<button type="submit" class="button button-primary button-large"><?php _e( 'Submit', 'mlm' ); ?></button>
	</p>
</form>