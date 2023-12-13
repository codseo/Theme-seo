<?php
$site_logo	= get_option( 'mlm_logo_footer' );
?>

<footer id="footer" class="app-footer py-4 clearfix">
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
	</div>
</footer>