<?php 
// Get the global header template.
get_header(); 

// Determine the demo template to display based on the selected seo option.
$demoTemplate = ( 'seokar' === seo_selected_demo() ) ? 'seokar/single' : 'default/single';

// Include the correct template part for single posts.
get_template_part( 'template-parts/' . $demoTemplate );

// Get the global footer template.
get_footer(); 
?>