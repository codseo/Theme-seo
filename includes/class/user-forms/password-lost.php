<div class="mlm-form-wrapper mlm-lost-form-wrapper clearfix">
	
	<?php if( count( $attributes['errors'] ) > 0 ): ?>
		<div class="alert alert-danger">
			<?php foreach( $attributes['errors'] as $error ): ?>
				<?php echo $error; ?><br />
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if( $attributes['verified'] ): ?>
		
		<div class="alert alert-warning"><?php echo wp_get_password_hint(); ?></div>
		<form id="mlm_new_password_form" action="<?php echo mlm_page_url( 'lost' ); ?>" method="post">
			<div class="form-row">
				<div class="form-group col-12">
					<label for=""><?php _e( 'Login', 'mlm' ); ?></label>
					<input type="text" name="mlm_login" id="" class="form-control m-0 rounded-pill" value="<?php echo $attributes['verified']; ?>" dir="ltr" disabled="disabled">
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for=""><?php _e( 'Password', 'mlm' ); ?> <i class="text-danger">*</i></label>
					<input type="password" name="mlm_pass" id="" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( "Don't use weak passwords.", 'mlm' ); ?>">
				</div>
				<div class="form-group col-12 col-lg-6">
					<label for=""><?php _e( 'Repeat password', 'mlm' ); ?> <i class="text-danger">*</i></label>
					<input type="password" name="mlm_repeat" id="" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( 'Confirm your password', 'mlm' ); ?>">
				</div>
				<div class="form-group col-12">
					<input type="hidden" name="mlm_recaptcha" data-reason="lost" value="">
					<button type="submit" class="mlm-submit-btn btn btn-primary btn-block rounded-pill" data-verify="<?php echo wp_create_nonce( 'mlm_password_nemab' ); ?>"><?php _e( 'Recover password', 'mlm' ); ?></button>
				</div>
			</div>
		</form>
		
	<?php else: ?>
		
		<div class="alert alert-warning"><?php _e( 'Enter user login, email or mobile number.', 'mlm' ); ?></div>
		<form id="mlm_lost_password_form" action="<?php echo mlm_page_url( 'lost' ); ?>" method="post">
			<div class="form-group">
				<label for=""><?php _e( 'Login or Mobile', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<div class="input-group m-0 mlm-rounded">
					<input type="text" name="mlm_login" class="form-control m-0">
					<div class="input-group-append">
						<button class="mlm-send-code-btn btn btn-outline-danger" type="button"><?php _e( 'Send code', 'mlm' ); ?></button>
					</div>
				</div>
			</div>
			<div class="form-group d-none">
				<label for=""><?php _e( 'Verify code', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_code" id="" class="form-control m-0 rounded-pill" placeholder="<?php _e( 'Enter the verification code you received', 'mlm' ); ?>" />
			</div>
			<div class="form-group d-none">
				<input type="hidden" name="mlm_recaptcha" data-reason="lost" value="">
				<button type="submit" class="mlm-submit-btn btn btn-primary btn-block rounded-pill" data-verify="<?php echo wp_create_nonce( 'mlm_activate_nemab' ); ?>"><?php _e( 'Confirm & continue', 'mlm' ); ?></button>
			</div>
		</form>
	
	<?php endif; ?>
	<?php $demo = mlm_selected_demo(); ?>
	<?php if( $demo == 'zhaket' ): ?>
		<nav class="auth-nav nav m-0 p-0 align-items-center justify-content-center">
			<a href="<?php echo mlm_page_url('login'); ?>" class="btn btn-light m-1"><?php _e( 'Login', 'mlm' ); ?></a>
			<a href="<?php echo mlm_page_url('register'); ?>" class="btn btn-light m-1"><?php _e( 'Register', 'mlm' ); ?></a>
		</nav>
	<?php endif; ?>
</div>