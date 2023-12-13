<main id="main" class="site-main col-12">
	
	<?php if( is_active_sidebar( 'mlm-page-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-page-top' ); ?>
	<?php endif; ?>
	
	<?php
	while( have_posts() ): the_post();
		
		get_template_part( 'template-parts/content', 'page' );
		
		if( comments_open() || get_comments_number() )
		{
			comments_template();
		}
		
	endwhile;
	?>
	
	<?php if( is_active_sidebar( 'mlm-page-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-page-bottom' ); ?>
	<?php endif; ?>
	
</main>