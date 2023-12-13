<?php
$demo = mlm_selected_demo();

if( $demo == 'zhaket' )
{
	get_header('panel');
	get_template_part( 'template-parts/zhaket/panel' );
	get_footer('panel');
}
else
{
	get_header();
	get_template_part( 'template-parts/default/panel' );
	get_footer();
}