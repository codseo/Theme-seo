<?php defined('ABSPATH') exit; ?>

<?php get_header(); ?>

	<?php
	$demo = seo_selected_demo(); // Changed from 'mlm_selected_demo()' to 'seo_selected_demo()'
	
	if($demo == 'seokar') // Changed from 'zhaket' to 'seokar'
	{
		get_template_part('template-parts/seokar/shop'); // Changed from 'template-parts/zhaket/shop' to 'template-parts/seokar/shop'
	}
	else
	{
		get_template_part('template-parts/default/shop'); // No change here
	}
	?>
	
<?php get_footer(); ?>