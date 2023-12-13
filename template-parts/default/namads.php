<main id="main" class="site-main col-12">

	<?php if( is_active_sidebar( 'mlm-page-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-page-top' ); ?>
	<?php endif; ?>
	
	<?php while( have_posts() ): the_post(); ?>
		
		<article id="article-<?php the_ID(); ?>" class="mlm-single-post">
			
			<header class="page-header my-4 p-0 clearfix">
				<?php mlm_breadcrumbs(); ?>
				<?php the_title( '<h1 class="page-title entry-title mlm-box-title m-0">', '</h1>' ); ?>
			</header>
			
			<?php if( is_active_sidebar( 'mlm-namads' ) ): ?>
				<aside id="mlm-site-licenses" class="row justify-content-center">
					<?php dynamic_sidebar( 'mlm-namads' ); ?>
				</aside>
			<?php endif; ?>
			
		</article>
		
	<?php endwhile; ?>
	
	<?php if( is_active_sidebar( 'mlm-page-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-page-bottom' ); ?>
	<?php endif; ?>
	
</main>