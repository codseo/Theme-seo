<?php
$post_id		= get_the_ID();
$image_thumb	= get_post_meta( $post_id, 'mlm_image_thumb', true );
$slide_image	= get_post_meta( $post_id, 'mlm_image_one', true );
$vendor_id		= get_the_author_meta( 'ID' );
$user_obj		= get_userdata( $vendor_id );
$product		= wc_get_product( $post_id );
$percentage		= mlm_product_has_off();
$download_cnt	= get_option('mlm_download_cnt');

if( empty( $image_thumb ) )
{
	$image_thumb = IMAGES . '/no-thumbnail.png';
}

if( empty( $slide_image ) )
{
	$slide_image = IMAGES . '/no-thumbnail.png';
}

if( $download_cnt == 'view' )
{
	$total_sales	= mlm_get_post_views( $post_id );
}
else
{
	$total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
}

if( $download_cnt == 'view' )
{
	$total_sales = sprintf(
		_nx(
			'%1$s view',
			'%1$s views',
			$total_sales,
			'view count',
			'mlm'
		),
		$total_sales
	);
}
elseif( mlm_check_course( $post_id ) )
{
	$total_sales = sprintf(
		_nx(
			'%1$s student',
			'%1$s students',
			$total_sales,
			'students count',
			'mlm'
		),
		$total_sales
	);
}
elseif( $product->is_downloadable() )
{
	$total_sales = sprintf(
		_nx(
			'%1$s sale',
			'%1$s sales',
			$total_sales,
			'sales count',
			'mlm'
		),
		$total_sales
	);
}
else
{
	$total_sales = sprintf(
		_nx(
			'%1$s delivery',
			'%1$s deliveries',
			$total_sales,
			'sale count',
			'mlm'
		),
		$total_sales
	);
}
?>

<a href="<?php the_permalink(); ?>" class="product-item position-relative d-block" 
data-image="<?php echo $slide_image; ?>" 
data-vendor="<?php echo get_the_author(); ?>" 
data-bio="<?php echo $user_obj->description; ?>" 
data-avatar="<?php echo get_avatar_url( $vendor_id ); ?>" 
data-title="<?php the_title_attribute(); ?>" 
data-text="<?php mlm_excerpt( 150 ); ?>" 
data-sale="<?php echo $total_sales; ?>" 
data-price="<?php echo mlm_get_product_price( $post_id ); ?>" 
data-rate="(<?php echo mlmFire()->rating->total_count( $post_id ); ?>) <?php echo mlmFire()->rating->get_average( $post_id ); ?>">
	<?php if( $percentage ): ?>
		<div class="item-off position-absolute text-center text-light clearfix">
			<svg viewBox="0 0 68 65"><path d="M67.697 32.349c0 3.466-3.591 6.323-4.684 9.461s.141 7.666-1.826 10.367c-1.968 2.7-6.573 2.95-9.368 4.934-2.795 1.982-4.31 6.245-7.557 7.322s-6.948-1.483-10.414-1.483c-3.466 0-7.292 2.498-10.414 1.483s-4.855-5.355-7.557-7.322c-2.701-1.968-7.307-2.202-9.368-4.934s-.765-7.12-1.827-10.367C3.623 38.563 0 35.83 0 32.349c0-3.482 3.591-6.323 4.684-9.461s-.141-7.666 1.827-10.367 6.573-2.951 9.368-4.934 4.309-6.245 7.557-7.322 6.948 1.483 10.414 1.483c3.465 0 7.292-2.499 10.414-1.483 3.122 1.015 4.855 5.355 7.557 7.322s7.307 2.201 9.368 4.934c2.061 2.732.765 7.12 1.826 10.367s4.682 5.995 4.682 9.461z"></path></svg>
			<span class="price d-block position-relative pt-1 font-14 bold-600">
				<?php echo $percentage . '%'; ?>
				<span class="c d-block font-10 bold-300 text-light"><?php _e( 'off', 'mlm' ); ?></span>
			</span>
		</div>
	<?php endif; ?>
	<img src="<?php echo $image_thumb; ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
</a>