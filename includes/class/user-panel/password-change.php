<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id		= get_current_user_id();
$password_url	= trailingslashit( mlm_page_url('panel') ) . 'section/change-password/';
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Change password', 'mlm' ); ?></h3>

<div class="alert alert-warning"><?php echo wp_get_password_hint(); ?></div>

<form id="mlm_change_pass_form" action="<?php echo $password_url; ?>" method="post">
	<div class="form-group">
		<label for="mlm_pass"><?php _e( 'Current password', 'mlm' ); ?> <i class="text-danger">*</i></label>
		<input type="password" name="mlm_pass" id="mlm_pass" class="form-control" dir="ltr" placeholder="<?php _e( "Enter your account's current password", 'mlm' ); ?>">
	</div>
	<div class="form-group">
		<label for="mlm_new_pass"><?php _e( 'New password', 'mlm' ); ?> <i class="text-danger">*</i></label>
		<input type="password" name="mlm_new_pass" id="mlm_new_pass" class="form-control" dir="ltr" placeholder="<?php _e( 'Password must be at least 7 characters', 'mlm' ); ?>">
	</div>
	<div class="form-group">
		<?php wp_nonce_field( 'mlm_settings_dojon', 'mlm_security' ); ?>
		<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Change password', 'mlm' ); ?></button>
	</div>
</form>