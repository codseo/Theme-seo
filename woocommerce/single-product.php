<?php get_header(); ?>
	
	<?php
	$demo = seo_selected_demo(); // Change 'mlm_selected_demo' to 'seo_selected_demo'
	
	if( $demo == 'seokar' ) // Change 'zhaket' to 'seokar'
	{
		get_template_part( 'template-parts/seokar/product' ); // Change directory name to 'seokar' from 'zhaket'
	}
	else
	{
		get_template_part( 'template-parts/default/product' ); // No change here
	}
	?>
	
<?php get_footer(); ?>