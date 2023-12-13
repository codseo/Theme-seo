<main id="main" class="site-main col-12">

	<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
	<?php endif; ?>
			
	<?php if( have_posts() ): ?>
		
		<header class="page-header my-4 p-0 clearfix">
			<?php mlm_breadcrumbs(); ?>
			<?php the_archive_title( '<h1 class="page-title mlm-box-title m-0">', '</h1>' ); ?>
			<?php /*the_archive_description( '<div class="taxonomy-description font-12 text-justify text-secondary">', '</div>' );*/ ?>
		</header>
		
		<div class="mlm-archive mb-4 clearfix">
			<div class="row">
				<?php while( have_posts() ): the_post(); ?>
					<div class="col-12 col-md-6 col-lg-4">
						<?php get_template_part( 'template-parts/content', 'product' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
			
		<?php mlm_navigation(); ?>
		
	<?php else: ?>

		<?php get_template_part( 'template-parts/content', 'none' ); ?>
		
	<?php endif; ?>
	
	<?php if( is_active_sidebar( 'mlm-archive-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-archive-bottom' ); ?>
	<?php endif; ?>
	
</main>