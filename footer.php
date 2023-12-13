<?php
$demo = seo_selected_demo(); // Changed 'mlm' to 'seo'

if ($demo == 'seokar') { // Changed 'zhaket' to 'seokar'
	get_template_part('template-parts/seokar/footer'); // Updated directory and file references accordingly
} else {
	get_template_part('template-parts/default/footer'); // Unchanged directory/file reference as this remains default
}

wp_footer(); // Standard WordPress function, no change required

get_template_part('template-parts/verify-modal'); // Unchanged directory/file reference as this is unrelated to the renaming
?>

</body>
</html>