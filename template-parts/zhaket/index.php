<?php get_template_part( 'template-parts/zhaket/home', 'search' ); ?>

<section id="primary" class="content-area">
	<main id="app-main-content" class="site-main">
		<?php get_template_part( 'template-parts/zhaket/home', 'tabs' ); ?>
		
		<?php if( is_active_sidebar( 'mlm-home-1' ) ): ?>
			<?php dynamic_sidebar( 'mlm-home-1' ); ?>
		<?php endif; ?>
	</main>
</section>