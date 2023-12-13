<?php get_header(); ?>

	<?php
	$demo = seo_selected_demo();
	
	if( $demo == 'seokar' )
	{
		get_template_part( 'template-parts/seokar/page' );
	}
	else
	{
		get_template_part( 'template-parts/default/page' );
	}
	?>
	
<?php get_footer(); ?>