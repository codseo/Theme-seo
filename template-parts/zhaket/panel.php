<div class="page-header mini m-0 clearfix">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h2 class="font-28 bold-400 text-white ellipsis my-3">
					<span class="icon icon-profile-male"></span>
					<?php _e( 'Dashboard', 'mlm' ); ?>
				</h2>
			</div>
			<div class="col-auto">
				<?php mlm_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>
<section id="primary" class="content-area">
	
	<?php if( ! is_user_logged_in() ): ?>
		
		<main id="app-main-content" class="site-main mt-5 container">
			<div class="mlm-widget bg-white p-4 mb-4 clearfix">
				<?php echo do_shortcode( '[mlm-login-form]' ); ?>
			</div>
		</main>
		
	<?php else: ?>
	
		<main class="app-panel-content mlm-panel-wrapper position-relative m-0 clearfix">
			<div class="dashboard-menu transition clearfix">
				<div class="h-100 slimscroll pb-5">
					
					<?php
					$q_args	= mlmFire()->dashboard->get_vars();
					mlmFire()->dashboard->print_zhaket_menu( $q_args );
					?>
					
				</div>
				<button type="button" class="app-close-mobile-btn btn btn-warning p-0 no-shadow rounded-0 text-white d-block d-md-none transition">
					<span class="font-32 d-block bold-600">×</span>
				</button>
			</div>
			<div class="dashboard-content py-4 clearfix">
			
				<?php mlmFire()->dashboard->get_active_section( $q_args ); ?>
				
			</div>
		</main>
		
	<?php endif; ?>
	
</section>