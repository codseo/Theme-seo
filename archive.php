<?php get_header(); ?>

	<?php
	$demo = mlm_selected_demo();
	
	if( $demo == 'zhaket' )
	{
		get_template_part( 'template-parts/zhaket/blog' );
	}
	else
	{
		get_template_part( 'template-parts/default/blog' );
	}
	?>
	
<?php get_footer(); ?>