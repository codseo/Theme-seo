<div class="mlm-form-wrapper mlm-login-form-wrapper clearfix">

	<?php if( count( $attributes['errors'] ) > 0 ): ?>
		<div class="alert alert-danger">
			<?php foreach( $attributes['errors'] as $error ): ?>
				<?php echo $error; ?><br />
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if( $attributes['logged_out'] ): ?>
		<div class="alert alert-warning"><?php _e( 'Signed out successfully. Want to sign in again?', 'mlm' ); ?></div>
	<?php endif; ?>

	<?php if( $attributes['registered'] ): ?>
		<div class="alert alert-warning"><?php _e( 'Registered successfully. Want to sign in?', 'mlm' ); ?></div>
	<?php endif; ?>

	<?php if( $attributes['lost_password_sent'] ): ?>
		<div class="alert alert-warning"><?php _e( 'Check your email inbox for password reset link.', 'mlm' ); ?></div>
	<?php endif; ?>

	<?php if( $attributes['password_updated'] ): ?>
		<div class="alert alert-warning"><?php _e( 'Password changed. You can sign in using the new password.', 'mlm' ); ?></div>
	<?php endif; ?>

	<?php do_action( 'wordpress_social_login' ); ?>

	<form name="loginform" id="mlm-login-form" action="<?php echo wp_login_url(); ?>" method="post">
		<div class="form-group login-username">
			<label for=""><?php _e( 'Email, Phone or Login', 'mlm' ); ?> <i class="text-danger">*</i></label>
			<input type="text" name="log" id="" class="form-control m-0 rounded-pill" value="" size="20">
		</div>
		<div class="form-group login-password">
			<label for=""><?php _e( 'Password', 'mlm' ); ?> <i class="text-danger">*</i></label>
			<input type="password" name="pwd" id="" class="form-control m-0 rounded-pill" value="" size="20">
		</div>
		<div class="form-group login-remember">
			<div class="form-check">
				<label class="form-check-label" for="">
					<input type="checkbox" name="rememberme" class="form-check-input" value="forever">
					<?php _e( 'Remember me', 'mlm' ); ?>
				</label>
			</div>
		</div>
		<div class="form-group login-submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary btn-block rounded-pill" value="<?php _e('Login', 'mlm'); ?>" data-verify="<?php echo wp_create_nonce('mlm_lavinap'); ?>">
			<input type="hidden" name="redirect_to" value="<?php echo $attributes['redirect']; ?>">
			<input type="hidden" name="mlm_recaptcha" data-reason="login" value="">
			<input type="hidden" name="mlm_return" value="<?php $attributes['_return']; ?>">
			<input type="hidden" name="mlm_must_return" value="<?php ( $attributes['_return'] ) ? 'yes' : 'no'; ?>">
		</div>
	</form>
	<?php $demo = mlm_selected_demo(); ?>
	<?php if( $demo == 'zhaket' ): ?>
		<nav class="auth-nav nav m-0 p-0 align-items-center justify-content-center">
			<a href="<?php echo mlm_page_url('register'); ?>" class="btn btn-light m-1"><?php _e( 'Register', 'mlm' ); ?></a>
			<a href="<?php echo mlm_page_url('lost'); ?>" class="btn btn-light m-1"><?php _e( 'Forgot password', 'mlm' ); ?></a>
		</nav>
	<?php endif; ?>
</div>