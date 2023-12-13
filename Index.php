<?php get_header(); ?>

	<?php
	$demo = seo_selected_demo();
	
	if( $demo == 'seokar' )
	{
		get_template_part( 'template-parts/seokar/blog' );
	}
	else
	{
		get_template_part( 'template-parts/default/blog' );
	}
	?>
	
<?php get_footer(); ?>