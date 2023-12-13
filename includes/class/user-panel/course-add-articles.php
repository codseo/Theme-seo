<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$error			= false;
$user_id		= get_current_user_id();
$products_url	= trailingslashit( mlm_page_url('panel') ) . 'section/products-all/';
$submit_url		= trailingslashit( mlm_page_url('panel') ) . 'section/course-new/';
$post_id		= $attributes['mid'];
$nonce			= wp_create_nonce('mlm_lhsaugpqytsr');

if( $post_id )
{
	$author		= get_post_field( 'post_author', $post_id );
	$type		= get_post_field( 'post_type', $post_id );
	$status		= get_post_field( 'post_status', $post_id );

	if( ! mlm_post_exists( $post_id ) || ! mlm_check_course( $post_id ) )
	{
		$error	= __( 'Course ID is invalid.', 'mlm' );
	}
	elseif( $author != $user_id || $type != 'product' || ! in_array( $status, array( 'publish', 'pending', 'draft' ) ) )
	{
		$error	= __( 'You are not allowed here.', 'mlm' );
	}
}
else
{
	$error	= __( 'Course ID is invalid.', 'mlm' );
}
?>



<?php if( ! empty( $error ) ): ?>

	<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Course articles and lessons', 'mlm' ); ?></h3>

	<div class="mlm-filter-bar mb-3 p-0 clearfix">
		<a href="<?php echo $products_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All products', 'mlm' ); ?></a>
	</div>

	<div class="alert alert-danger"><?php echo $error; ?></div>

	<?php return; ?>

<?php endif; ?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php echo mlm_get_post_title( $post_id ); ?> - <?php _e( 'articles and lessons', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-success btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_new_chapter_modal"><?php _e( 'Add article', 'mlm' ); ?></a>
	<a href="<?php echo $products_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All products', 'mlm' ); ?></a>
</div>

<div class="mlm-course-chapters-wrapper clearfix">

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
			<div class="chapter-item position-relative overflow-hidden mb-3 p-3 shadow-sm border border-light rounded" data-id="<?php echo $chapter->id; ?>" data-image="<?php echo $chapter_data['image_id']; ?>" data-priority="<?php echo $chapter->priority; ?>">
				<div class="row no-gutters">
					<div class="col mlm-chapter-image-col">
						<img src="<?php echo $image_url; ?>" class="chapter-image" alt="<?php echo $chapter_data['title']; ?>">
					</div>
					<div class="col pr-3">
						<span class="chapter-title d-block bold-600 text-dark font-14"><?php echo $chapter_data['title']; ?></span>
						<span class="chapter-text d-block mb-2 bold-300 text-secondary font-12"><?php echo $chapter_data['text']; ?></span>
						<div class="chapter-option">
							<a href="#mlm-lesson-modal" class="btn btn-sm btn-success py-0 font-10" data-chapter="<?php echo $chapter->id; ?>"><?php _e( 'add lesson', 'mlm' ); ?></a>
							<a href="#mlm-edit-chapter" class="btn btn-sm btn-secondary py-0 font-10" data-verify="<?php echo $nonce; ?>"><?php _e( 'edit', 'mlm' ); ?></a>
							<a href="#mlm-delete-chapter" class="btn btn-sm btn-danger py-0 font-10" data-verify="<?php echo $nonce; ?>"><?php _e( 'delete', 'mlm' ); ?></a>
						</div>
					</div>
				</div>
				<?php if( ! empty( $course_obj ) ): ?>
					<div class="mlm-lessons-wrapper mt-3 clearfix">
						<?php foreach( $course_obj as $lesson ): ?>
							<?php
							$lesson_data	= maybe_unserialize( $lesson->course_data );
							$lesson_links	= isset( $lesson_data['links'] ) ? $lesson_data['links'] : array();
							?>
							<div class="lesson-item p-2 m-0 border-top border-light overflow-hidden" data-id="<?php echo $lesson->id; ?>" data-priority="<?php echo $lesson->priority; ?>" data-status="<?php echo $lesson_data['status']; ?>" data-chapter="<?php echo $chapter->id; ?>">
								<span class="lesson-title d-block font-12 bold-600 text-dark"><?php echo $lesson_data['title']; ?></span>
								[ <span class="lesson-text text-secondary font-10"><?php echo $lesson_data['text']; ?></span> ]
								<textarea class="lesson-content d-none"><?php echo stripslashes( $lesson_data['content'] ); ?></textarea>
								<textarea class="lesson-links d-none"><?php echo json_encode($lesson_links); ?></textarea>
								<div class="lesson-option">
									<a href="#mlm-edit-lesson" class="btn btn-sm btn-secondary py-0 font-10" data-verify="<?php echo $nonce; ?>"><?php _e( 'edit', 'mlm' ); ?></a>
									<a href="#mlm-delete-lesson" class="btn btn-sm btn-danger py-0 font-10" data-verify="<?php echo $nonce; ?>"><?php _e( 'delete', 'mlm' ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		<?php endforeach; ?>

	<?php else: ?>

		<div class="alert alert-warning"><?php _e( 'No articles found.', 'mlm' ); ?></div>

	<?php endif; ?>

</div>

<div class="modal fade" id="mlm_new_chapter_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Add/Update article', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="mlm_new_chapter_form" action="<?php echo esc_url( $products_url ); ?>" method="post">
					<div class="form-group">
						<div class="mlm-image-preview mb-2 text-center" data-default="<?php echo IMAGES . '/no-thumbnail.png'; ?>">
							<img src="<?php echo IMAGES . '/no-thumbnail.png'; ?>" class="thumbnail rounded" alt="post-image">
						</div>
						<input type="hidden" name="mlm_image" class="image_id" id="mlm_image" value="">
						<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Select article image', 'mlm' ); ?></button>
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Article number', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="number" name="mlm_number" class="form-control" value="" min="1" step="1">
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Article title', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="text" name="mlm_title" class="form-control" value="">
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Short description', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="text" name="mlm_desc" class="form-control" value="">
					</div>
					<div class="clearfix">
						<input type="hidden" name="mlm_id" value="">
						<input type="hidden" name="mlm_post" value="<?php echo $post_id; ?>">
						<input type="hidden" name="mlm_nonce" value="<?php echo $nonce; ?>">
						<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Save', 'mlm' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="mlm_new_lesson_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Add/Update lesson', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="mlm_new_lesson_form" action="<?php echo esc_url( $products_url ); ?>" method="post">
					<div class="form-group">
						<label for=""><?php _e( 'Lesson number', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="number" name="mlm_number" class="form-control" value="" min="1" step="1">
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Lesson title', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="text" name="mlm_title" class="form-control" value="">
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Short description', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<input type="text" name="mlm_desc" class="form-control" value="">
					</div>
					<div class="form-group">
						<label for=""><?php _e( 'Access', 'mlm' ); ?> <i class="text-danger">*</i></label>
						<select name="mlm_status" class="form-control">
							<option value="free"><?php _e( 'Free', 'mlm' ); ?></option>
							<option value="vip"><?php _e( 'purchased users only', 'mlm' ); ?></option>
						</select>
					</div>
					<div class="form-group">
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
					<div class="mlm-upload-group mb-3 clearfix">
						<label for=""><?php _e( 'Links', 'mlm' ); ?></label>
						<?php
						$ftp		= mlm_ftp_upload();
						$upNonce	= wp_create_nonce('mlm_bngadsrwa');
						?>
						<div class="mlm-file-template mb-3">
							<div class="input-group mb-1">
								<input type="text" name="mlm_file[i][0][name]" class="name form-control" value="" placeholder="<?php _e( 'Description', 'mlm' ); ?>">
								<div class="input-group-append">
									<button class="mlm-remove-upload-btn btn btn-outline-danger" type="button"><?php _e( 'Delete', 'mlm' ); ?></button>
								</div>
							</div>
							<div class="input-group">
								<input type="text" name="mlm_file[i][0][file]" class="file form-control ltr" value="" placeholder="<?php _e( 'Upload or insert download link', 'mlm' ); ?>">
								<div class="input-group-append">
									<?php if( $ftp ): ?>
										<div class="mlm-ftp-upload-holder">
											<input type="file" class="upload-toggle" data-verify="<?php echo $upNonce; ?>">
											<button class="btn btn-outline-secondary rounded-0" type="button"><?php _e( 'Upload file', 'mlm' ); ?></button>
										</div>
									<?php else: ?>
										<button class="mlm-upload-file-btn btn btn-outline-secondary rounded-0" type="button"><?php _e( 'Upload file', 'mlm' ); ?></button>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<button type="button" class="mlm-new-upload-field btn btn-secondary btn-block"><?php _e( 'Add new field', 'mlm' ); ?></button>
						<?php if( $ftp ): ?>
							<div class="mlm-ftp-upload-wrap mt-1 clearfix">
								<div class="progress">
									<div class="progress-bar bg-success" aria-valuemin="0" aria-valuemax="100" width="0%"></div>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="clearfix">
						<input type="hidden" name="mlm_id" value="">
						<input type="hidden" name="mlm_chapter" value="">
						<input type="hidden" name="mlm_post" value="<?php echo $post_id; ?>">
						<input type="hidden" name="mlm_nonce" value="<?php echo $nonce; ?>">
						<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Save', 'mlm' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>