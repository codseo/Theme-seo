<main id="main" class="site-main col-12">
	
	<?php if( is_active_sidebar( 'mlm-panel-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-panel-top' ); ?>
	<?php endif; ?>
	
	<article class="mlm-panel-wrapper mlm-widget bg-white p-0 mb-4 rounded clearfix">
		
		<?php if( ! is_user_logged_in() ): ?>
			<div class="p-3 clearfix">
				<?php echo do_shortcode( '[mlm-login-form]' ); ?>
			</div>
		<?php else: ?>
			<?php
			$q_args		= mlmFire()->dashboard->get_vars();
			?>
			<div class="row no-gutters">
				<div class="col-12 col-lg-3">
					<div class="mlm-user-panel-widget p-0 clearfix d-none d-lg-block">
						<?php mlmFire()->dashboard->print_avatar_box(); ?>
						<?php mlmFire()->dashboard->print_side_menu( $q_args ); ?>
						<?php mlmFire()->dashboard->print_social_icons(); ?>
					</div>
				</div>
				<div class="col-12 col-lg-9 p-3">
					<?php mlmFire()->dashboard->get_active_section( $q_args ); ?>
				</div>
			</div>
		<?php endif; ?>
		
	</article>
	
	<?php if( is_active_sidebar( 'mlm-panel-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-panel-bottom' ); ?>
	<?php endif; ?>
	
</main>