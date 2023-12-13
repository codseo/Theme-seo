<?php defined('ABSPATH') exit; ?>

<?php get_header(); ?>
	
	<?php
	$demo = seo_selected_demo(); // Changed 'mlm_selected_demo' to 'seo_selected_demo'
	
	if ($demo == 'seokar') // Changed 'zhaket' to 'seokar'
	{
		get_template_part('template-parts/seokar/shop'); // Updated the path to 'seokar' from 'zhaket'
	}
	else
	{
		get_template_part('template-parts/default/shop'); // Default shop template part is unchanged
	}
	?>
	
<?php get_footer(); ?>