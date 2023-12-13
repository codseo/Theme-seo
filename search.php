<?php get_header(); ?>
	
	<?php
	$demo = seo_selected_demo();
	
	if( $demo == 'seokar' ) {
		get_template_part( 'template-parts/seokar/search' );
	} else {
		get_template_part( 'template-parts/default/search' );
	}
	?>
	
<?php get_footer(); ?>