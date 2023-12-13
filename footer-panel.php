<?php
$demo = seo_selected_demo(); // Replace 'mlm' with 'seo' in function name

if( $demo == 'seokar' ) // Replace 'zhaket' with 'seokar'
{
	get_template_part( 'template-parts/seokar/footer-panel' ); // Update the folder name as per the new demo value
}
else
{
	get_template_part( 'template-parts/default/footer' ); // No change required here
}
?>

<?php wp_footer(); ?>

<?php get_template_part( 'template-parts/verify-modal' ); // No change required here ?>

</body>
</html>