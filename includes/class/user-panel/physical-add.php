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
$demo 			= mlm_selected_demo();
$user_id		= get_current_user_id();
$products_url	= trailingslashit( mlm_page_url('panel') ) . 'section/products-all/';
$submit_url		= trailingslashit( mlm_page_url('panel') ) . 'section/physical-new/';
$post_id		= $attributes['mid'];
$default		= esc_url( IMAGES .'/no-thumbnail.png' );
$all_cats		= mlm_category_list( 0, 'product_cat' );
/*$all_tags		= mlm_category_list( 0, 'product_tag' );*/

$mlm_title = $mlm_content = $mlm_thumb = $mlm_price = $mlm_weight = $mlm_quantity = $mlm_percent = $mlm_reject = $mlm_sale_price = $mlm_button_text = $mlm_button_link = $mlm_button_2_text = $mlm_button_2_link = '';
$mlm_thumb_image = $mlm_image_one = $mlm_image_two = '';
$post_cats		= array();
$post_tags		= array();
$saved_fields	= array();
$fields_type	= mlm_custom_fields_type();
$weight_unit    = get_option( 'woocommerce_weight_unit' );

if( $post_id )
{
	$author		= get_post_field( 'post_author', $post_id );
	$type		= get_post_field( 'post_type', $post_id );
	$status		= get_post_field( 'post_status', $post_id );

	if( ! mlm_post_exists( $post_id ) )
	{
		$error	= __( 'Product ID is invalid.', 'mlm' );
	}
	elseif( ! wp_verify_nonce( $attributes['verify'], 'mlm_edit_jibella' ) || $author != $user_id || $type != 'product' || ! in_array( $status, array( 'publish', 'pending', 'draft' ) ) )
	{
		$error	= __( 'You are not allowed here.', 'mlm' );
	}
	else
	{
		$mlm_title			= get_the_title( $post_id );
		$mlm_content		= get_post_field( 'post_content', $post_id );
		$mlm_thumb			= get_post_meta( $post_id, '_thumbnail_id', true );
		$post_cats			= mlm_category_list( $post_id, 'product_cat' );
		$post_tags			= mlm_category_list( $post_id, 'product_tag' );
		$mlm_reject			= get_post_meta( $post_id, 'mlm_reject', true );
		$mlm_price			= get_post_meta( $post_id, '_regular_price', true );
		$mlm_sale_price		= get_post_meta( $post_id, '_sale_price', true );
		$mlm_weight			= get_post_meta( $post_id, '_weight', true );
		$mlm_quantity		= get_post_meta( $post_id, '_stock', true );
		$mlm_percent		= get_post_meta( $post_id, 'mlm_ref_value', true );
		$mlm_button_text	= get_post_meta( $post_id, 'mlm_button_text', true );
		$mlm_button_link	= get_post_meta( $post_id, 'mlm_button_link', true );
        $mlm_button_2_text	= get_post_meta( $post_id, 'mlm_button_2_text', true );
        $mlm_button_2_link	= get_post_meta( $post_id, 'mlm_button_2_link', true );
		$mlm_thumb_image	= get_post_meta( $post_id, 'mlm_image_thumb', true );
		$mlm_image_one		= get_post_meta( $post_id, 'mlm_image_one', true );
		$mlm_image_two		= get_post_meta( $post_id, 'mlm_image_two', true );

		if( $fields_type == 'custom' )
		{
			$saved_fields	= get_post_meta( $post_id, 'mlm_saved_fields', true );
		}
	}
}

$thumbnail	= ( ! empty( $mlm_thumb ) && wp_get_attachment_url( $mlm_thumb ) ) ? wp_get_attachment_url( $mlm_thumb ) : $default;
$thumb_img	= empty( $mlm_thumb_image ) ? $default : $mlm_thumb_image;
$image_one	= empty( $mlm_image_one ) ? $default : $mlm_image_one;
$image_two	= empty( $mlm_image_two ) ? $default : $mlm_image_two;

if( ! is_array( $post_cats ) )
{
	$post_cats = array();
}

if( ! is_array( $post_tags ) )
{
	$post_tags = array();
}
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Add/Update product', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="<?php echo $products_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All products', 'mlm' ); ?></a>
</div>

<?php if( ! empty( $error ) ): ?>

	<div class="alert alert-danger"><?php echo $error; ?></div>

<?php else: ?>

	<div class="alert alert-danger text-justify">
		<?php if( ! empty( $mlm_reject ) ): ?>
			<?php echo $mlm_reject; ?>
		<?php else: ?>
			- <?php _e( 'Product description must have at least 300 characters', 'mlm' ); ?><br />
			- <?php _e( 'You can not copy description text from other sites', 'mlm' ); ?><br />
			- <?php _e( 'Product description must be real and without any exaggeration', 'mlm' ); ?><br />
			- <?php _e( 'Product image size must be 1280*800 pixels', 'mlm' ); ?><br />
			- <?php _e( 'In case that a customer submits a support ticket for the product; you have to answer the ticket within 30 hours', 'mlm' ); ?><br />
			- <?php _e( 'Product will not be published if any of these terms not matched', 'mlm' ); ?>
		<?php endif; ?>
	</div>

	<form id="mlm_submit_physical_form" action="<?php echo $products_url; ?>" method="post">
		<div class="form-group">
			<div class="mlm-image-preview mb-2 text-center">
				<img src="<?php echo $thumbnail; ?>" class="thumbnail rounded" alt="post-image">
			</div>
			<input type="hidden" name="mlm_thumb" class="image_id" id="mlm_thumb" value="<?php echo $mlm_thumb; ?>">
			<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select image', 'mlm' ); ?></button>
			<?php if( $demo == 'zhaket' ): ?>
				<div class="row">
					<div class="col-12">
						<button type="button" class="btn btn-success btn-block mt-2" data-toggle="modal" data-target="#mlm_product_images"><?php _e( 'Product images', 'mlm' ); ?></button>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="form-row">
			<div class="form-group col-12 col-md-6">
				<label for="mlm_title"><?php _e( 'Product title', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_title" class="form-control" id="mlm_title" value="<?php echo $mlm_title; ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_percent"><?php _e( 'Referrer percent', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_percent" id="mlm_percent" class="form-control">
					<?php for( $i = 0; $i <= 80; $i = $i + 5 ): ?>
						<option value="<?php echo $i; ?>" <?php selected( $mlm_percent, $i ); ?>><?php echo $i; ?> <?php _e( 'percent', 'mlm' ); ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_price"><?php _e( 'Product price', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<div class="input-group">
					<input type="number" name="mlm_price" id="mlm_price" value="<?php echo $mlm_price; ?>" class="form-control ltr" min="0">
					<div class="input-group-append">
						<span class="input-group-text font-10"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
					</div>
				</div>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_sale_price"><?php _e( 'Product off price', 'mlm' ); ?></label>
				<div class="input-group">
					<input type="number" name="mlm_sale_price" id="mlm_sale_price" value="<?php echo $mlm_sale_price; ?>" class="form-control ltr" min="0">
					<div class="input-group-append">
						<span class="input-group-text font-10"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
					</div>
				</div>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_weight"><?php _e( 'Product weight', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<div class="input-group">
					<input type="number" name="mlm_weight" id="mlm_weight" value="<?php echo $mlm_weight; ?>" class="form-control ltr" min="0">
					<div class="input-group-append">
						<span class="input-group-text font-10"><?php echo $weight_unit; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_quantity"><?php _e( 'In stock quantity', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="number" name="mlm_quantity" id="mlm_quantity" value="<?php echo $mlm_quantity; ?>" class="form-control ltr" min="0">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_button_text"><?php _e( 'Custom button title', 'mlm' ); ?></label>
				<input type="text" name="mlm_button_text" class="form-control" id="mlm_button_text" value="<?php echo $mlm_button_text; ?>" placeholder="<?php _e( 'e.g. Demo', 'mlm' ); ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_button_link"><?php _e( 'Custom button link', 'mlm' ); ?></label>
				<input type="text" name="mlm_button_link" class="form-control" id="mlm_button_link" value="<?php echo $mlm_button_link; ?>" placeholder="<?php _e( 'e.g. demo URL', 'mlm' ); ?>">
			</div>
            <div class="form-group col-12 col-md-6">
                <label for="mlm_button_2_text"><?php _e( 'Custom button 2 title', 'mlm' ); ?></label>
                <input type="text" name="mlm_button_2_text" class="form-control" id="mlm_button_2_text" value="<?php echo $mlm_button_2_text; ?>" placeholder="<?php _e( 'e.g. Demo', 'mlm' ); ?>">
            </div>
            <div class="form-group col-12 col-md-6">
                <label for="mlm_button_2_link"><?php _e( 'Custom button 2 link', 'mlm' ); ?></label>
                <input type="text" name="mlm_button_2_link" class="form-control" id="mlm_button_2_link" value="<?php echo $mlm_button_2_link; ?>" placeholder="<?php _e( 'e.g. demo URL', 'mlm' ); ?>">
            </div>
			<?php if( $fields_type == 'custom' ): ?>
				<div class="form-group col-12">
					<div class="form-row" id="mlm-custom-fields-wrap">
						<?php mlmFire()->dashboard->custom_fields( $saved_fields ); ?>
					</div>
				</div>
			<?php endif; ?>
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
					<option value=""><?php _e( 'Select', 'mlm' ); ?></option>
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
			<label for="mlm_stock"><?php _e( 'Stock status', 'mlm' ); ?></label>
			<select name="mlm_stock" class="form-control" id="mlm_stock">
				<option value="yes"><?php _e('In stock', 'mlm'); ?></option>
				<option value="no"><?php _e('Out of stock', 'mlm'); ?></option>
			</select>
		</div>
		<div class="form-group">
			<input type="hidden" name="mlm_id" id="mlm_id" value="<?php echo $post_id; ?>">
			<?php wp_nonce_field( 'mlm_submit_abilia', 'mlm_security' ); ?>
			<div class="row no-gutters">
				<div class="col mlm-save-draft-col">
					<button type="button" class="mlm-save-draft-btn btn btn-warning btn-block"><?php _e( 'Save draft', 'mlm' ); ?></button>
				</div>
				<div class="col pr-2">
					<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Send for moderation', 'mlm' ); ?></button>
				</div>
			</div>
		</div>

		<?php if( $demo == 'zhaket' ): ?>
			<div class="modal fade" id="mlm_product_images" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php _e( 'Product images', 'mlm' ); ?></h5>
							<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body slimscroll h-100" style="height:300px;">
							<div class="form-group">
								<label for="mlm_tag"><?php _e( 'Thumb image (80*80)', 'mlm' ); ?></label>
								<div class="mlm-image-preview mb-2 text-center">
									<img src="<?php echo $thumb_img; ?>" class="thumbnail rounded" alt="post-image">
								</div>
								<input type="hidden" name="mlm_thumb_image" class="image" id="mlm_thumb_image" value="<?php echo $mlm_thumb_image; ?>">
								<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select', 'mlm' ); ?></button>
							</div>
							<div class="form-group">
								<label for="mlm_tag"><?php _e( 'Main image (700*700)', 'mlm' ); ?></label>
								<div class="mlm-image-preview mb-2 text-center">
									<img src="<?php echo $image_one; ?>" class="thumbnail rounded" alt="post-image">
								</div>
								<input type="hidden" name="mlm_image_one" class="image" id="mlm_image_one" value="<?php echo $mlm_image_one; ?>">
								<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select', 'mlm' ); ?></button>
							</div>
							<div class="form-group">
								<label for="mlm_tag"><?php _e( 'Secondary image (700*700)', 'mlm' ); ?></label>
								<div class="mlm-image-preview mb-2 text-center">
									<img src="<?php echo $image_two; ?>" class="thumbnail rounded" alt="post-image">
								</div>
								<input type="hidden" name="mlm_image_two" class="image" id="mlm_image_two" value="<?php echo $mlm_image_two; ?>">
								<button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select', 'mlm' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</form>

<?php endif; ?>