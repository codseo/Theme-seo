<?php
$post_id	= get_the_ID();

if( ! mlm_check_course( $post_id ) )
{
	return;
}

$teacher_name	= get_post_meta( $post_id, 'mlm_teacher_name', true );
$teacher_image	= get_post_meta( $post_id, 'mlm_teacher_image', true );
$teacher_bio	= get_post_meta( $post_id, 'mlm_teacher_bio', true );
$image_src		= empty( $teacher_image ) ? IMAGES . '/avatar.svg' : $teacher_image;

if( empty( $teacher_name ) )
{
	return false;
}
?>

<div class="mlm-product-teacher-widget mb-4 clearfix">
	<h3 class="mlm-box-title icon icon-presentation sm mb-2"><?php _e( 'Course teacher', 'mlm' ); ?></h3>
	<div class="teacher-image mb-2 clearfix">
		<img alt="<?php echo $teacher_name; ?>" src="<?php echo $image_src; ?>" class="avatar avatar-128 photo rounded-circle d-block mx-auto" height="128" width="128">
	</div>
	<div class="teacher-name text-center mb-3 clearfix">
		<span class="d-inline-block text-dark font-16 bold-300"><?php echo $teacher_name; ?></span>
	</div>
	<?php if( ! empty( $teacher_bio ) ): ?>
		<div class="teacher-bio text-justify text-secondary mb-2 clearfix">
			<?php echo $teacher_bio; ?>
		</div>
	<?php endif; ?>
</div>