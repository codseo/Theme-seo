<?php

if( ! defined( 'ABSPATH' ) )
{
	die( 'You are not allowed to call this page directly !' );
}
?>

<h1 class="wp-heading-inline"><?php _e( 'Activate license', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<div class="mlm-license clearfix">
	<?php if( $attributes['activated'] ): ?>
		<div class="mlm_alert alert-success"><?php _e( 'Theme activated already.', 'mlm' ); ?></div>
	<?php else: ?>
		<div class="mlm_alert alert-danger"><?php _e( 'Theme not activated yet.', 'mlm' ); ?></div>
	<?php endif; ?>
	<form name="mlm-license-form" method="post" action="<?php menu_page_url('mlm-license' ); ?>">
		<table class="form-table">
			<tbody>
				<tr class="mlm-form-wrap">
					<th><label for="mlm_license"><?php _e( 'License code', 'mlm' ); ?><label></th>
					<td>
						<input type="text" name="mlm_license" class="regular-text" id="mlm_license" value="<?php echo $attributes['license']; ?>">
						<p class="description"><?php _e( 'Enter your license code.', 'mlm' ); ?></p>
					</td>
				</tbody>
			</tr>
		</table>
		<p class="submit">
			<?php wp_nonce_field( 'mlm_uyfaloji', 'mlm_verify' ); ?>
			<input type="submit" name="submit" class="button button-primary button-large" value="<?php _e( 'Activate theme', 'mlm' ); ?>">
		</p>
	</form>
</div>