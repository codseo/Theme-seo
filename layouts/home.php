<?php
/* Template Name: Homepage */
?>

<?php get_header(); ?>

	<?php
	$demo = mlm_selected_demo();
	
	if( $demo == 'zhaket' )
	{
		get_template_part( 'template-parts/zhaket/index' );
	}
	else
	{
		get_template_part( 'template-parts/default/index' );
	}
	?>
	
<?php get_footer(); ?>