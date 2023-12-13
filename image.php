<?php get_header(); ?>
	
	<?php
	$demo = seo_selected_demo();
	
	if( $demo == 'seokar' )
	{
		get_template_part( 'template-parts/seokar/image' );
	}
	else
	{
		get_template_part( 'template-parts/default/image' );
	}
	?>
	
<?php get_footer(); ?>