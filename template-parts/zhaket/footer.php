<?php
$site_logo	= get_option( 'mlm_logo_footer' );
$copyright	= get_option( 'mlm_copyright' );
?>

<footer id="footer" class="app-footer mt-4 py-4 clearfix">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-md-5">
				<?php
				if( has_nav_menu( 'footer-right' ) ):
					wp_nav_menu( array(
						'theme_location'	=> 'footer-right',
						'container'			=> false,
						'echo'				=> true,
						'depth'				=> 1,
						'menu_class'		=> 'footer-nav mx-0 my-2 p-0 d-block'
					) );
				endif;
				?>
			</div>
			<div class="col-12 col-md-2 text-center">
				<?php if( ! empty( $site_logo ) ): ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo d-inline-block my-2" title="Home" rel="home">
						<img src="<?php echo esc_url( $site_logo ); ?>" class="img-fluid" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				<?php endif; ?>
			</div>
			<div class="col-12 col-md-5">
				<?php
				if( has_nav_menu( 'footer-left' ) ):
					wp_nav_menu( array(
						'theme_location'	=> 'footer-left',
						'container'			=> false,
						'echo'				=> true,
						'depth'				=> 1,
						'menu_class'		=> 'footer-nav mx-0 my-2 p-0 d-block text-left'
					) );
				endif;
				?>
			</div>
		</div>
		<div class="footer-items mt-3 pt-3 clearfix">
			<div class="row align-items-center justify-content-between">
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
		</div>
		<?php if( ! empty( $copyright  ) ): ?>
			<div class="mlm-copyright py-2">
				<p class="m-0 p-0 bold-300 text-light font-12">© <?php echo $copyright; ?></p>
			</div>
		<?php endif; ?>
	</div>
</footer>