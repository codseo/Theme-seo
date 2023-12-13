<?php
$post_id		= get_the_ID();
$vendor_id		= get_the_author_meta( 'ID' );
$verified		= mlmFire()->dashboard->get_account_status( $vendor_id );
$vendor_name	= get_the_author();
$product		= wc_get_product( $post_id );
$sale_from		= (int)get_post_meta( $post_id, '_sale_price_dates_from', true );
$sale_to		= (int)get_post_meta( $post_id, '_sale_price_dates_to', true );
$login_req		= get_option('mlm_login_req');
$price			= mlm_get_product_price( $post_id );
?>

<article class="mlm-product-offer position-relative p-3 clearfix">
	<span class="sale-badge bg-primary position-absolute text-white px-3 py-1 font-12"><?php _e( 'Special offers', 'mlm' ); ?></span>
	<div class="row align-items-end">
		<div class="col-12 col-md-6">
			<?php if( time() > $sale_from && $sale_to > time() ): ?>
				<div class="counter-wrapper my-3 pt-5 clearfix d-none">
					<h3 class="mlm-box-title sm mb-2"><?php _e( 'Special sale until', 'mlm' ); ?></h3>
					<div class="counter-box clearfix">
						<span class="icon icon-alarmclock text-primary"></span>
						<span class="mlm-countdown" data-time="<?php echo date( 'Y-m-d 23:59:59', $sale_to ); ?>"></span>
					</div>
				</div>
			<?php else: ?>
				<div class="my-3 pt-5 clearfix">
					<div class="alert alert-danger border-0 rounded-pill m-0"><?php _e( 'Offer expired', 'mlm' ); ?></div>
				</div>
			<?php endif; ?>
			<div class="purchase-wrapper my-3">
				<?php mlm_add_to_cart_btn( $post_id, 'btn btn-primary btn-block d-block rounded-pill ellipsis' ); ?>
			</div>
			<?php /*
			<div class="all-wrapper my-3">
				<a href="#" class="btn btn-primary btn-block d-block rounded-pill ellipsis"><?php _e( 'See all', 'mlm' ); ?></a>
			</div> */ ?>
		</div>
		<div class="col-12 col-md-6">
			<div class="item-footer px-3 mb-2 clearfix">
				<div class="row">
					<div class="col-6">
						<a href="<?php echo esc_url( get_author_posts_url( $vendor_id ) ); ?>" class="item-vendor float-right my-2 <?php if( $verified ) echo 'verified'; ?>">
							<?php echo get_avatar( $vendor_id, 32, NULL , $vendor_name, array( 'class' => 'rounded-circle float-right ml-2' ) ); ?>
							<?php echo $vendor_name; ?>
						</a>
					</div>
					<div class="col-6">
						<span class="item-meta my-1 price float-left font-14 bold-600">
							<?php if( $price == 0 ): ?>
								<?php _e( 'Free', 'mlm' ); ?>
							<?php else: ?>
								<?php echo $product->get_price_html(); ?>
							<?php endif; ?>
						</span>
					</div>
				</div>
			</div>
			<div class="item-header position-relative overflow-hidden transition">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
					<img src="<?php mlm_image_url( $post_id, 'medium' ); ?>" class="img-fluid w-100 h-auto" alt="<?php the_title_attribute(); ?>" />
				</a>
			</div>
		</div>
	</div>
</article>