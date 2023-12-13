<?php
$post_id		= get_the_ID();
$vendor_id		= get_the_author_meta( 'ID' );
$vendor_name	= get_the_author();
$user_obj		= get_userdata( $vendor_id );
$user_bio		= $user_obj->description;
$product		= wc_get_product( $post_id );
$price			= mlm_get_product_price( $post_id );
$login_req		= get_option('mlm_login_req');
$download_cnt	= get_option('mlm_download_cnt');
$percentage		= mlm_product_has_off();

if( $download_cnt == 'view' )
{
	$total_sales	= mlm_get_post_views( $post_id );
}
else
{
	$total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
}
?>

<div class="archive-item p-0 mb-4 bg-white position-relative clearfix">
	<a href="<?php the_permalink(); ?>" class="item-image d-block position-relative clearfix">
		<img src="<?php mlm_image_url( $post_id, 'medium' ); ?>" class="position-absolute" alt="<?php the_title_attribute(); ?>">
		<?php if( $percentage ): ?>
			<div class="item-off position-absolute text-center text-light clearfix">
				<svg viewBox="0 0 68 65"><path d="M67.697 32.349c0 3.466-3.591 6.323-4.684 9.461s.141 7.666-1.826 10.367c-1.968 2.7-6.573 2.95-9.368 4.934-2.795 1.982-4.31 6.245-7.557 7.322s-6.948-1.483-10.414-1.483c-3.466 0-7.292 2.498-10.414 1.483s-4.855-5.355-7.557-7.322c-2.701-1.968-7.307-2.202-9.368-4.934s-.765-7.12-1.827-10.367C3.623 38.563 0 35.83 0 32.349c0-3.482 3.591-6.323 4.684-9.461s-.141-7.666 1.827-10.367 6.573-2.951 9.368-4.934 4.309-6.245 7.557-7.322 6.948 1.483 10.414 1.483c3.465 0 7.292-2.499 10.414-1.483 3.122 1.015 4.855 5.355 7.557 7.322s7.307 2.201 9.368 4.934c2.061 2.732.765 7.12 1.826 10.367s4.682 5.995 4.682 9.461z"></path></svg>
				<span class="price d-block position-relative pt-2 font-16 bold-600">
					<?php echo $percentage . '%'; ?>
					<span class="c d-block font-10 bold-300 text-light"><?php _e( 'off', 'mlm' ); ?></span>
				</span>
			</div>
		<?php endif; ?>
		<div class="item-avatar position-absolute clearfix">
			<div class="row align-items-center no-gutters mx-n1">
				<div class="avatar-col col px-1">
					<?php echo get_avatar( $vendor_id, 64, NULL , $vendor_name, array( 'class' => 'd-block rounded-circle' ) ); ?>
				</div>
				<div class="name-col col px-1">
					<span class="item-vendor ellipsis text-white font-15 bold-600"><?php echo $vendor_name; ?></span>
					<span class="item-bio ellipsis text-white font-12 bold-400">
						<?php echo $user_bio; ?>
					</span>
				</div>
			</div>
		</div>
	</a>
	<div class="item-content position-relative">
		<div class="inside">
			<div class="item-title px-3 my-3 font-15 bold-600 overflow-hidden">
				<a href="<?php the_permalink(); ?>" class="text-secondary"><?php the_title(); ?></a>
			</div>
			<div class="item-text overflow-hidden">
				<div class="px-3 pb-4 font-12 bold-400 text-justify text-grey">
					<?php mlm_excerpt(); ?>
				</div>
			</div>
			<div class="item-meta py-2 px-1 text-center">
				<div class="row no-gutters mx-n1">
					<div class="col px-1">
						<div class="item-sale ellipsis font-15 bold-600 text-secondary">
							<?php echo $total_sales; ?> 
							<span class="text-grey font-12 bold-300">
								<?php if( $download_cnt == 'view' ): ?>
									<?php echo _nx( 'view', 'views', $total_sales, 'view count', 'mlm' ); ?>
								<?php elseif( mlm_check_course( $post_id ) ): ?>
									<?php echo _nx( 'student', 'students', $total_sales, 'students count', 'mlm' ); ?>
								<?php elseif( $product->is_downloadable() ): ?>
									<?php echo _nx( 'download', 'downloads', $total_sales, 'download count', 'mlm' ); ?>
								<?php else: ?>
									<?php echo _nx( 'delivery', 'deliveries', $total_sales, 'delivery count', 'mlm' ); ?>
								<?php endif; ?>
							</span>
						</div>
					</div>
					<div class="rate-col col px-1">
						<div class="item-rate ellipsis font-15 bold-600 text-secondary">
							 <?php echo mlmFire()->rating->get_average( $post_id ); ?>
						</div>
					</div>
					<div class="col px-1 position-relative">
						<?php /*if( $percentage ): ?>
							<span class="off-price position-absolute font-10 text-secondary"><?php echo mlm_filter( $product->get_regular_price() ); ?></span>
						<?php endif; */ ?>
						<div class="item-price ellipsis font-15 bold-600 text-warning">
							<?php if( $price == 0 ): ?>
								<?php _e( 'free', 'mlm' ); ?>
							<?php else: ?>
								<?php echo $product->get_price_html(); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="item-add-to-cart overflow-hidden">
				<div class="p-3">
					<?php mlm_add_to_cart_btn( $post_id, 'btn btn-block btn-success p-2 ellipsis font-15' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>