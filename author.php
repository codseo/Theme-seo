<?php get_header(); ?>

	<?php
	$demo = seo_selected_demo(); // Function name changed from 'mlm' to 'seo'
	
	if ($demo == 'seokar') { // Demo check changed from 'zhaket' to 'seokar'
		get_template_part('template-parts/seokar/author'); // Updated path to match new naming convention
	} else {
		get_template_part('template-parts/default/author'); // Default path remains unchanged
	}
	?>
	
<?php get_footer(); ?>