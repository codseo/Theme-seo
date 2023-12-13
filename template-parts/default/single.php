<main id="main" class="site-main col-12 col-lg-8">
	
	<?php if( is_active_sidebar( 'mlm-post-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-post-top' ); ?>
	<?php endif; ?>
	
	<?php
	while( have_posts() ): the_post();
		
		get_template_part( 'template-parts/content', 'single' );
		
		if( comments_open() || get_comments_number() )
		{
			comments_template();
		}
		
	endwhile;
	
	get_template_part( 'template-parts/related', 'posts' );
	?>
	
	<?php if( is_active_sidebar( 'mlm-post-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-post-bottom' ); ?>
	<?php endif; ?>
	
</main>

<?php if( is_active_sidebar( 'mlm-sidebar' ) ): ?>
	<aside id="sidebar" class="mlm-sidebar col-12 col-lg-4">
		<?php dynamic_sidebar( 'mlm-sidebar' ); ?>
	</aside>
<?php endif; ?>