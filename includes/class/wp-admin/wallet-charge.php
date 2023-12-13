<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

$args = array(
	'show_option_all'			=> 0, // string
	'show_option_none'			=> __( 'select user ...', 'mlm' ), // string
	'hide_if_only_one_author'	=> 0, // string
	'selected'					=> 0,
	'include_selected'			=> 1,
	'class'						=> 'regular-text mlm-select',
	'name'						=> 'mlm_user',
	'id'						=> 'mlm_user',
);
?>

<h1 class="wp-heading-inline"><?php _e( 'Charge wallet', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<form id="mlm_charge_wallet_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_user"><?php _e( 'User', 'mlm' ); ?><label></th>
				<td>
					<?php wp_dropdown_users( $args ); ?>
				</td>
			</tr>		
			<tr class="mlm-form-wrap">
				<th><label for="mlm_type"><?php _e( 'Type', 'mlm' ); ?><label></th>
				<td>
					<select name="mlm_type" id="mlm_type" class="regular-text">
						<option value="1"><?php _e( 'Increase wallet amount', 'mlm' ); ?></option>
						<option value="2"><?php _e( 'Decrease wallet amount', 'mlm' ); ?></option>
					</select>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_amount"><?php _e( 'Amount', 'mlm' ); ?><label></th>
				<td>
					<input type="number" name="mlm_amount" id="mlm_amount" class="regular-text" min="0" placeholder="" />
					<p class="description"><?php _e( 'Enter a numeric value.', 'mlm' ); ?></p>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_desc"><?php _e( 'Description', 'mlm' ); ?><label></th>
				<td>
					<input type="text" name="mlm_desc" id="mlm_desc" class="regular-text" />
					<p class="description"><?php _e( 'Description will be displayed to the user.', 'mlm' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php wp_nonce_field( 'mlm_charge_wiks', 'mlm_verify' ); ?>
	<button type="submit" class="button button-primary button-large"><?php _e( 'Save', 'mlm' ); ?></button>
</form>