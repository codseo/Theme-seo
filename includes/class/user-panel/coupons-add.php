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
$nonce			= wp_create_nonce( 'mlm_rakonojipan' );
$coupons_url	= trailingslashit( mlm_page_url('panel') ) . 'section/coupons/';
$submit_url		= trailingslashit( mlm_page_url('panel') ) . 'section/coupons-new/';
$post_id		= $attributes['mid'];
$query			= new WP_Query( array(
	'post_type' 	=> 'product',
	'author'		=> $user_id,
	'post_status'	=> 'publish',
	'posts_per_page'=> -1,
) );

$mlm_code = $mlm_amount = $mlm_type = $mlm_expire = '';
$coupon_ids	= array();

if( $post_id )
{
	$author		= get_post_field( 'post_author', $post_id );
	$type		= get_post_field( 'post_type', $post_id );
	$status		= get_post_field( 'post_status', $post_id );
	
	if( ! mlm_post_exists( $post_id ) )
	{
		$error	= __( 'Coupon ID is invalid.', 'mlm' );
	}
	elseif( ! wp_verify_nonce( $attributes['verify'], 'mlm_rakonojipan' ) || $type != 'shop_coupon' || $status != 'publish' || ( $author != $user_id && ! current_user_can('moderate_comments') ) )
	{
		$error	= __( 'You are not allowed here.', 'mlm' );
	}
	else
	{
		$mlm_code		= get_the_title( $post_id );
		$mlm_amount		= get_post_meta( $post_id, 'coupon_amount', true );
		$mlm_type		= get_post_meta( $post_id, 'mlm_type', true );
		$mlm_expire		= get_post_meta( $post_id, 'date_expires', true );
		$product_ids	= get_post_meta( $post_id, 'product_ids', true );
		$coupon_ids		= explode( ',', $product_ids );
		
		if( ! empty( $mlm_expire ) )
		{
			$mlm_expire	= mlm_jdate( 'Y-m-d', $mlm_expire, '', 'Asia/Tehran', 'en' );
		}
	}
}
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Add/Update coupon', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="<?php echo $coupons_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All coupons', 'mlm' ); ?></a> 
</div>

<?php if( ! empty( $error ) ): ?>
	
	<div class="alert alert-danger"><?php echo $error; ?></div>
	
<?php elseif( $query->have_posts() ): ?>
	
	<form id="mlm_submit_coupon_form" action="<?php echo $submit_url; ?>" method="post">
		<div class="form-row">
			<div class="form-group col-12 col-md-6">
				<label for="mlm_code"><?php _e( 'Coupon code', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_code" class="form-control" id="mlm_code" value="<?php echo $mlm_code; ?>" placeholder="<?php _e( 'e.g.: off15', 'mlm' ); ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_amount"><?php _e( 'Off percent', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="number" name="mlm_amount" class="form-control" id="mlm_amount" value="<?php echo $mlm_amount; ?>" min="1" max="100">
			</div>
			<div class="form-group col-12">
				<label for="mlm_products"><?php _e( 'Activate for', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_products" class="form-control" id="mlm_products" multiple="multiple">
					<?php while( $query->have_posts() ): $query->the_post(); ?>
						<option value="<?php echo get_the_ID(); ?>" <?php if( in_array( get_the_ID(), $coupon_ids ) ) echo 'selected="selected"'; ?>><?php the_title_attribute(); ?></option>
					<?php endwhile; wp_reset_postdata(); ?>
				</select>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_type"><?php _e( 'Coupon type type', 'mlm' ); ?></label>
				<select name="mlm_type" class="form-control" id="mlm_type">
					<option value="public" <?php selected( $mlm_type, 'public' ); ?>><?php _e( 'Public', 'mlm' ); ?></option>
					<option value="private" <?php selected( $mlm_type, 'private' ); ?>><?php _e( 'Private', 'mlm' ); ?></option>
				</select>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_expire"><?php _e( 'Expires at', 'mlm' ); ?></label>
				<input type="text" name="mlm_expire" class="form-control mlm-expire" id="mlm_expire" value="<?php echo $mlm_expire; ?>">
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="mlm_id" id="mlm_id" value="<?php echo $post_id; ?>">
			<?php wp_nonce_field( 'mlm_rakonojipan', 'mlm_security' ); ?>
			<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Save', 'mlm' ); ?></button>
		</div>		
	</form>

<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'You must have at least one published product to add a coupon code.', 'mlm' ); ?></div>
	
<?php endif; ?>