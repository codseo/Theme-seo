<?php get_header(); ?>
	
	<?php
	$demo = seo_selected_demo(); // Changed from 'mlm_selected_demo' to 'seo_selected_demo'
	
	if($demo == 'seokar') // Changed from 'zhaket' to 'seokar'
	{
		get_template_part('template-parts/seokar/404'); // Changed from 'zhaket' to 'seokar'
	}
	else
	{
		get_template_part('template-parts/default/404'); // Left unchanged
	}
	?>
	
<?php get_footer(); ?>