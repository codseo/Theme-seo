<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'moderate_comments' ) )
{
	wp_die('You are not allowed here !');
}

$post_type	= get_post_field( 'post_type', $attributes['pid'] );

if( ! mlm_post_exists( $attributes['pid'] ) || $post_type != 'product' )
{
	wp_die( '<div class="mlm_alert alert-danger">'. __( 'Course ID is invalid.', 'mlm' ) .'</div>' );
}
?>

<h1 class="wp-heading-inline">
	<?php echo mlm_get_post_title( $attributes['pid'] ); ?> - <?php _e( 'articles and lessons', 'mlm' ); ?>
	<a href="#mlm-modal" class="page-title-action button-primary" data-target="#mlm-new-chapter-modal"><?php _e( 'Add article', 'mlm' ); ?></a>
</h1>
<hr class="wp-header-end">
<div class="clear clearfix" style="margin-bottom:15px;"></div>

<div class="mlm-course-chapters-wrapper">

	<?php if( ! empty( $attributes['query'] ) ): ?>

		<?php foreach( $attributes['query'] as $chapter ): ?>

			<?php
			$chapter_data	= maybe_unserialize( $chapter->course_data );
			$image_atts		= wp_get_attachment_image_src( $chapter_data['image_id'], 'thumbnail' );
			$course_obj		= mlmFire()->db->query_rows(
				"SELECT * FROM {TABLE} WHERE parent_id = %d ORDER BY priority ASC",
				array( $chapter->id ),
				'course'
			);

			if( ! $image_atts )
			{
				$image_url		= IMAGES . '/no-thumbnail.png';
			}
			else
			{
				$image_url		= $image_atts[0];
			}
			?>
			<div class="chapter-item" data-id="<?php echo $chapter->id; ?>" data-image="<?php echo $chapter_data['image_id']; ?>" data-priority="<?php echo $chapter->priority; ?>">
				<img src="<?php echo $image_url; ?>" class="chapter-image" alt="<?php echo $chapter_data['title']; ?>">
				<span class="chapter-title"><?php echo $chapter_data['title']; ?></span>
				<span class="chapter-text"><?php echo $chapter_data['text']; ?></span>
				<div class="chapter-option">
					<a href="#mlm-lesson-modal" class="artir" data-chapter="<?php echo $chapter->id; ?>"><?php _e( 'add lesson', 'mlm' ); ?></a>
					<a href="#mlm-edit-chapter" class="degis" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'edit', 'mlm' ); ?></a>
					<a href="#mlm-delete-chapter" class="sil" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'delete', 'mlm' ); ?></a>
				</div>
				<?php if( ! empty( $course_obj ) ): ?>
					<div class="mlm-lessons-wrapper">
						<?php foreach( $course_obj as $lesson ): ?>
							<?php
							$lesson_data	= maybe_unserialize( $lesson->course_data );
							$lesson_links	= isset( $lesson_data['links'] ) ? $lesson_data['links'] : array();
							?>
							<div class="lesson-item" data-id="<?php echo $lesson->id; ?>" data-priority="<?php echo $lesson->priority; ?>" data-status="<?php echo $lesson_data['status']; ?>" data-chapter="<?php echo $chapter->id; ?>">
								<span class="lesson-title"><?php echo $lesson_data['title']; ?></span>
								[ <span class="lesson-text"><?php echo $lesson_data['text']; ?></span> ]
								<textarea class="lesson-content"><?php echo stripslashes( $lesson_data['content'] ); ?></textarea>
								<textarea class="lesson-links" style="display:none"><?php echo json_encode($lesson_links); ?></textarea>
								<div class="lesson-option">
									<a href="#mlm-edit-lesson" class="degis" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'edit', 'mlm' ); ?></a>
									<a href="#mlm-delete-lesson" class="sil" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'delete', 'mlm' ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		<?php endforeach; ?>

	<?php else: ?>

		<div class="mlm_alert alert-danger"><?php _e( 'No articles found.', 'mlm' ); ?></div>

	<?php endif; ?>

</div>

<div class="mlm-modal iziModal" id="mlm-new-chapter-modal" data-iziModal-fullscreen="false" data-iziModal-title="<?php _e( 'Add/Update article', 'mlm' ); ?>">
	<form name="mlm_new_chapter_form" action="<?php echo esc_url( $attributes['url'] ); ?>" method="post">
		<div class="mlm-form-group">
			<button class="upload_image_button button button-secondary"><?php _e( 'Select article image', 'mlm' ); ?></button>
			<input type="hidden" name="mlm_image" class="image_id" value="">
			<div class="mlm-image clearfix" data-default="<?php echo IMAGES . '/no-thumbnail.png'; ?>">
				<img src="<?php echo IMAGES . '/no-thumbnail.png'; ?>" alt="Chapter image" style="margin: 0 auto;">
			</div>
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Article number', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="number" name="mlm_number" class="large-text" value="" min="1" step="1">
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Article title', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="text" name="mlm_title" class="large-text" value="">
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Short description', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="text" name="mlm_desc" class="large-text" value="">
		</div>
		<div class="clearfix">
			<input type="hidden" name="mlm_id" value="">
			<input type="hidden" name="mlm_post" value="<?php echo $attributes['pid']; ?>">
			<input type="hidden" name="mlm_nonce" value="<?php echo $attributes['nonce']; ?>">
			<button type="submit" class="button button-primary button-block"><?php _e( 'Save', 'mlm' ); ?></button>
		</div>
	</form>
</div>

<div class="mlm-course-lesson-box-wrap clearfix">
	<form name="mlm_new_lesson_form" action="<?php echo esc_url( $attributes['url'] ); ?>" method="post">
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Lesson number', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="number" name="mlm_number" class="large-text" value="" min="1" step="1">
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Lesson title', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="text" name="mlm_title" class="large-text" value="">
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Short description', 'mlm' ); ?> <i class="red">*</i></label>
			<input type="text" name="mlm_desc" class="large-text" value="">
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Access', 'mlm' ); ?> <i class="red">*</i></label>
			<select name="mlm_status" class="regular-text button-block" style="max-width: 100%;">
				<option value="free"><?php _e( 'Free', 'mlm' ); ?></option>
				<option value="vip"><?php _e( 'purchased users only', 'mlm' ); ?></option>
			</select>
		</div>
		<div class="mlm-form-group">
			<label for=""><?php _e( 'Lesson content', 'mlm' ); ?></label>
			<?php
			wp_editor( '', 'mlm_content', array(
				'textarea_name'	=> 'mlm_content',
				'media_buttons'	=> true,
				'editor_height'	=> 300,
				'teeny'			=> false,
				'quicktags'		=> true
			) );
			?>
		</div>
		<div class="mlm-upload-group mlm-form-group clearfix">
			<label for=""><?php _e( 'Links', 'mlm' ); ?></label>
			<div class="mlm-file-template mb-3">
				<div class="mlm-form-group">
					<input type="text" name="mlm_file[i][0][name]" class="name regular-text button-block" value="" placeholder="<?php _e( 'Description', 'mlm' ); ?>">
				</div>
				<div class="mlm-form-group">
					<input type="text" name="mlm_file[i][0][file]" class="file regular-text button-block ltr" value="" placeholder="<?php _e( 'Upload or insert download link', 'mlm' ); ?>">
				</div>
				<div class="mlm-form-group">
					<button class="mlm-remove-upload-btn button button-small button-secondary" type="button"><?php _e( 'Delete', 'mlm' ); ?></button>
					<button class="mlm-upload-file-btn button button-small button-secondary" type="button"><?php _e( 'Upload file', 'mlm' ); ?></button>
				</div>
			</div>
			<button type="button" class="mlm-new-upload-field button button-small button-primary"><?php _e( 'Add new field', 'mlm' ); ?></button>
		</div>
		<div class="clearfix">
			<input type="hidden" name="mlm_id" value="">
			<input type="hidden" name="mlm_chapter" value="">
			<input type="hidden" name="mlm_post" value="<?php echo $attributes['pid']; ?>">
			<input type="hidden" name="mlm_nonce" value="<?php echo $attributes['nonce']; ?>">
			<button type="submit" class="button button-primary button-block"><?php _e( 'Save', 'mlm' ); ?></button>
		</div>
	</form>
</div>