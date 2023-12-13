<?php
$post_id	= get_the_ID();

if( ! mlm_check_course( $post_id ) )
{
	return;
}

$course_fill	= (int)get_post_meta( $post->ID, 'mlm_course_fill', true );

if( $course_fill >= 100 )
{
	$class		= 'bg-danger';
	$percent	= 100;
	$text		= __( 'Completed', 'mlm' );
}
elseif( $course_fill < 1 )
{
	$class		= 'bg-warning';
	$percent	= 20;
	$text		= __( 'Starts soon', 'mlm' );
}
else
{
	$class		= 'bg-primary';
	$percent	= $course_fill;
	$text		= __( 'In progress', 'mlm' );
}

if( $course_fill < 20 )
{
	$percent = 20;
}
?>

<div class="mlm-product-teacher-widget mb-4 clearfix">
	<h3 class="mlm-box-title icon icon-presentation sm mb-2"><?php _e( 'Course status', 'mlm' ); ?></h3>
	<div class="mb-2 clearfix">
		<img alt="<?php _e( 'Course status', 'mlm' ); ?>" src="<?php echo IMAGES; ?>/course.svg" class="d-block mx-auto" width="128" height="128">
	</div>
	<div class="text-center mb-3 clearfix">
		<span class="d-inline-block text-dark font-16 bold-600"><?php echo $text; ?></span>
	</div>
	<div class="progress">
		<div class="progress-bar <?php echo $class; ?>" role="progressbar" style="width: <?php echo $percent; ?>%;" aria-valuenow="<?php echo $course_fill; ?>" aria-valuemin="0" aria-valuemax="100"><?php printf( __( '%s %%', 'mlm' ), $course_fill ); ?></div>
	</div>
</div>