<div class="mlm-form-wrapper mlm-reset-form-wrapper clearfix">
	<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
		<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
		
		<?php if( count( $attributes['errors'] ) > 0 ): ?>
			<div class="alert alert-danger">
				<?php foreach( $attributes['errors'] as $error ): ?>
					<?php echo $error; ?><br />
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<div class="alert alert-dark"><?php echo wp_get_password_hint(); ?></div>
		<div class="form-group">
			<label for="pass1"><?php _e( 'New password', 'mlm' ); ?></label>
			<input type="password" class="form-control input" name="pass1" id="pass1" size="20" value="" autocomplete="off">
		</div>
		<div class="form-group">
			<label for="pass2"><?php _e( 'New password repeat', 'mlm' ); ?></label>
			<input type="password" class="form-control input" name="pass2" id="pass2" size="20" value="" autocomplete="off">
		</div>
		<div class="form-group">
			<input type="submit" name="submit" id="resetpass-button" value="<?php _e( 'Change password', 'mlm' ); ?>" class="btn btn-primary btn-block" />
		</div>
	</form>
</div>