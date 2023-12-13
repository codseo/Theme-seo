<main id="main" class="site-main col-12">
	
	<div class="mlm-widget bg-white p-0 mb-4 rounded-lg clearfix">		
		<?php get_template_part( 'template-parts/home', 'slider' ); ?>
		<?php get_template_part( 'template-parts/home', 'search' ); ?>
	</div>
	
	<?php if( is_active_sidebar( 'mlm-home-3' ) ): ?>
		<?php dynamic_sidebar( 'mlm-home-3' ); ?>
	<?php endif; ?>
	
	<?php get_template_part( 'template-parts/home', 'products' ); ?>
	
	<?php if( is_active_sidebar( 'mlm-home-1' ) ): ?>
		<?php dynamic_sidebar( 'mlm-home-1' ); ?>
	<?php endif; ?>
	
	<?php get_template_part( 'template-parts/home', 'top-user' ); ?>
	
	<?php if( is_active_sidebar( 'mlm-home-2' ) ): ?>
		<?php dynamic_sidebar( 'mlm-home-2' ); ?>
	<?php endif; ?>
	
</main>