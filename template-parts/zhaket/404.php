<header class="page-header m-0 clearfix">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h2 class="font-28 bold-400 text-white ellipsis my-3">
					<?php _e( "404 - Not found", "mlm" ); ?>
				</h2>
			</div>
			<div class="col-auto">
				<?php mlm_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</header>

<section id="primary" class="content-area">
	<main id="app-main-content" class="site-main container">
		
		<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
			<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
		<?php endif; ?>
	
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	</main>
</section>