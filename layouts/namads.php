<?php
/* Template Name: Logos page */
?>

<?php get_header(); ?>
	
	<?php
	$demo = mlm_selected_demo();
	
	if( $demo == 'zhaket' )
	{
		get_template_part( 'template-parts/zhaket/namads' );
	}
	else
	{
		get_template_part( 'template-parts/default/namads' );
	}
	?>
	
<?php get_footer(); ?>