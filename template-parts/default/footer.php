	</div>
</section>

<?php
$copyright		= get_option( 'mlm_copyright' );
?>

<footer id="footer" class="mlm-footer m-0 p-0 bg-dark clearfix">
	<div class="container">
		<?php get_template_part( 'template-parts/stats', 'bar' ); ?>
		<div class="row">
			<div class="col-12 col-md-6 col-lg-4">
				<?php if( is_active_sidebar( 'mlm-footer-1' ) ): ?>
					<?php dynamic_sidebar( 'mlm-footer-1' ); ?>
				<?php endif; ?>
			</div>
			<div class="col-12 col-md-6 col-lg-4">
				<?php if( is_active_sidebar( 'mlm-footer-2' ) ): ?>
					<?php dynamic_sidebar( 'mlm-footer-2' ); ?>
				<?php endif; ?>
			</div>
			<div class="col-12 col-md-6 col-lg-4">
				<?php if( is_active_sidebar( 'mlm-footer-3' ) ): ?>
					<?php dynamic_sidebar( 'mlm-footer-3' ); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php if( ! empty( $copyright  ) ): ?>
			<div class="mlm-copyright py-2">
				<p class="m-0 p-0 bold-300 text-light">© <?php echo $copyright; ?></p>
			</div>
		<?php endif; ?>
	</div>
</footer>

<?php if( is_active_sidebar( 'mlm-cart' ) ): ?>
	<div class="modal fade" id="mlm-cart-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php _e( "Shopping cart", 'mlm' ); ?></h4>
					<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php dynamic_sidebar( 'mlm-cart' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if( ! is_user_logged_in() ): ?>
	<div class="modal fade" id="mlm-login-register-popup" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body p-0">
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<div class="row no-gutters">
						<div class="login-box-col col-12 col-md-7">
							<div class="mlm-popup-form mlm-popup-login-form m-0 p-3 clearfix acik">
								<h3 class="mlm-box-title mb-3 py-2"><?php _e( "Login", 'mlm' ); ?></h3>
								<?php echo do_shortcode('[mlm-login-form]'); ?>
								<div class="text-center clearfix">
									<a href="#mlm-toggle-register-form" class="text-dark bold-300"><?php _e( "Register", 'mlm' ); ?></a>
									<span class="text-warning d-inline-block mx-2"> / </span>
									<a href="#mlm-toggle-password-form" class="text-dark bold-300"><?php _e( "Forgot password", 'mlm' ); ?></a>
								</div>
							</div>
							<div class="mlm-popup-form mlm-popup-register-form m-0 p-3 clearfix">
								<h3 class="mlm-box-title mb-3 py-2"><?php _e( "Register", 'mlm' ); ?></h3>
								<?php echo do_shortcode('[mlm-register-form]'); ?>
								<div class="text-center clearfix">
									<a href="#mlm-toggle-login-form" class="text-dark bold-300"><?php _e( "Login", 'mlm' ); ?></a>
									<span class="text-warning d-inline-block mx-2"> / </span>
									<a href="#mlm-toggle-password-form" class="text-dark bold-300"><?php _e( "Forgot password", 'mlm' ); ?></a>
								</div>
							</div>
							<div class="mlm-popup-form mlm-popup-password-form m-0 p-3 clearfix">
								<h3 class="mlm-box-title mb-3 py-2"><?php _e( "Recover password", 'mlm' ); ?></h3>
								<?php echo do_shortcode('[mlm-password-lost-form]'); ?>
								<div class="text-center clearfix">
									<a href="#mlm-toggle-login-form" class="text-dark bold-300"><?php _e( "Login", 'mlm' ); ?></a>
									<span class="text-warning d-inline-block mx-2"> / </span>
									<a href="#mlm-toggle-register-form" class="text-dark bold-300"><?php _e( "Register", 'mlm' ); ?></a>
								</div>
							</div>
						</div>
						<div class="mlm-popup-login-cover col-5 d-none d-md-flex"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>