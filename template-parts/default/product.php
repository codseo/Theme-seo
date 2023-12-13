<main id="main" class="site-main col-12">
	
	<?php if( is_active_sidebar( 'mlm-product-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-product-top' ); ?>
	<?php endif; ?>
	
	<?php while( have_posts() ): the_post(); ?>
	
		<?php get_template_part( 'template-parts/content', 'single-product' ); ?>
	
		<?php get_template_part( 'template-parts/related', 'vendor-products' ); ?>
		
		<?php get_template_part( 'template-parts/related', 'vendor-courses' ); ?>
	
		<?php get_template_part( 'template-parts/related', 'products' ); ?>
	
	<?php endwhile; ?>
	
	<?php if( is_active_sidebar( 'mlm-product-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-product-bottom' ); ?>
	<?php endif; ?>
	
</main>