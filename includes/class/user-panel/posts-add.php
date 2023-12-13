<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$error		= false;
$user_id	= get_current_user_id();
$posts_url	= trailingslashit( mlm_page_url('panel') ) . 'section/posts-all/';
$submit_url	= trailingslashit( mlm_page_url('panel') ) . 'section/posts-new/';
$post_id	= $attributes['mid'];
$default	= esc_url( IMAGES .'/no-thumbnail.png' );
$all_cats	= mlm_category_list( 0, 'category' );
/*$all_tags	= mlm_category_list( 0, 'post_tag' );*/

$mlm_title = $mlm_content = $mlm_thumb = $mlm_reject = '';
$post_cats = array();
$post_tags = array();

if( $post_id )
{
	$author		= get_post_field( 'post_author', $post_id );
	$type		= get_post_field( 'post_type', $post_id );
	$status		= get_post_field( 'post_status', $post_id );
	
	if( ! mlm_post_exists( $post_id ) )
	{
		$error	= __( 'Post ID is invalid.', 'mlm' );
	}
	elseif( ! wp_verify_nonce( $attributes['verify'], 'mlm_edit_jibella' ) || $author != $user_id || $type != 'post' || ( $status != 'publish' && $status != 'pending' ) )
	{
		$error	= __( 'You are not allowed here.', 'mlm' );
	}
	else
	{
		$mlm_title			= get_the_title( $post_id );
		$mlm_content		= get_post_field( 'post_content', $post_id );
		$mlm_thumb			= get_post_meta( $post_id, '_thumbnail_id', true );
		$mlm_reject			= get_post_meta( $post_id, 'mlm_reject', true );
		$post_cats			= mlm_category_list( $post_id, 'category' );
		$post_tags			= mlm_category_list( $post_id, 'post_tag' );
	}
}

$thumbnail	= ( ! empty( $mlm_thumb ) && wp_get_attachment_url( $mlm_thumb ) ) ? wp_get_attachment_url( $mlm_thumb ) : $default;

if( ! is_array( $post_cats ) )
{
	$post_cats = array();
}

if( ! is_array( $post_tags ) )
{
	$post_tags = array();
}
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Add/Update post', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="<?php echo $posts_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All posts', 'mlm' ); ?></a> 
</div>

<?php if( ! empty( $error ) ): ?>
	
	<div class="alert alert-danger"><?php echo $error; ?></div>
	
<?php else: ?>
	
	<div class="alert alert-danger text-justify">
		<?php if( ! empty( $mlm_reject ) ): ?>
			<?php echo $mlm_reject; ?>
		<?php else: ?>
			- <?php _e( 'Post description must have at least 300 characters', 'mlm' ); ?><br />
			- <?php _e( 'You can not copy post from other sites', 'mlm' ); ?><br />
			- <?php _e( 'Post image size must be 1280*800 pixels', 'mlm' ); ?><br />
			- <?php _e( 'Post will not be published if any of these terms not matched', 'mlm' ); ?>
		<?php endif; ?>
	</div>
	
	<form id="mlm_submit_post_form" action="<?php echo $posts_url; ?>" method="post">
		<div class="form-group">
			<div class="mlm-image-preview mb-2 text-center">
				<img src="<?php echo $thumbnail; ?>" class="post-image thumbnail rounded" alt="post-image">
			</div>
			<input type="hidden" name="mlm_thumb" class="image_id" id="mlm_thumb" value="<?php echo $mlm_thumb; ?>">
			<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select image', 'mlm' ); ?></button>
		</div>
		<div class="form-group">
			<label for="mlm_title"><?php _e( 'Post title', 'mlm' ); ?> <i class="text-danger">*</i></label>
			<input type="text" name="mlm_title" class="form-control" id="mlm_title" value="<?php echo $mlm_title; ?>">
		</div>
		<div class="form-group">
			<?php
			wp_editor( $mlm_content, 'mlm_content', array(
				'textarea_name'	=> 'mlm_content',
				'media_buttons'	=> true,
				'editor_height'	=> 300,
				'teeny'			=> false,
				'quicktags'		=> true
			) );
			?>
		</div>
		<div class="form-row">
			<div class="form-group col-12 col-md-6">
				<label for="mlm_cat"><?php _e( 'Category', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_cat" class="form-control" id="mlm_cat" multiple="multiple">
					<?php foreach( (array) $all_cats as $cat ): ?>
						<option value="<?php echo $cat['id']; ?>" <?php if( in_array( $cat['id'], $post_cats ) ) echo 'selected="selected"'; ?>><?php echo $cat['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_tag"><?php _e( 'Tags', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_tag" class="form-control" id="mlm_tag" multiple="multiple">
					<option value=""><?php _e( 'select', 'mlm' ); ?></option>
					<?php if( is_array( $post_tags ) && count( $post_tags ) > 0 ): ?>
						<?php foreach( (array) $post_tags as $tag ): ?>
							<?php $term_obj = get_term( $tag ); ?>
							<?php if( ! empty( $term_obj ) && ! is_wp_error( $term_obj ) ): ?>
								<option value="<?php echo $tag; ?>" selected="selected"><?php echo $term_obj->name; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="mlm_id" id="mlm_id" value="<?php echo $post_id; ?>">
			<?php wp_nonce_field( 'mlm_submit_abilia', 'mlm_security' ); ?>
			<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Save', 'mlm' ); ?></button>
		</div>		
	</form>

<?php endif; ?>