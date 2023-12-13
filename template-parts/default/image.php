<main id="main" class="site-main col-12 col-lg-8">
			
	<?php
	while( have_posts() ): the_post();
		get_template_part( 'template-parts/content', 'image' );
	endwhile;
	?>
	
</main>

<?php get_sidebar(); ?>