<?php
$post_id		= get_the_ID();
$vendor_id		= get_the_author_meta( 'ID' );
$verified		= mlmFire()->dashboard->get_account_status( $vendor_id );
$vendor_name	= get_the_author();
$product		= wc_get_product( $post_id );
$file_type		= get_post_meta( $post_id, 'mlm_file_type', true );
$page_count		= get_post_meta( $post_id, 'mlm_page_count', true );
$percentage		= mlm_product_has_off();
$price			= mlm_get_product_price( $post_id );
$login_req		= get_option('mlm_login_req');
$download_cnt	= get_option('mlm_download_cnt');

if( $download_cnt == 'view' )
{
	$total_sales	= mlm_get_post_views( $post_id );
}
else
{
	$total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
}
?>

<article class="mlm-product bg-white p-0 mb-3 rounded transition clearfix">
	<header class="item-header position-relative rounded-top overflow-hidden transition">
		<a href="<?php the_permalink(); ?>" class="d-block" title="<?php the_title_attribute(); ?>" rel="bookmark">
			<img src="<?php mlm_image_url( $post_id, 'medium' ); ?>" class="position-absolute" alt="<?php the_title_attribute(); ?>">
			<?php if( $product->is_featured() ): ?>
				<span class="vip icon icon-star-full position-absolute" data-toggle="tooltip" data-placement="right" title="<?php _e( 'Featured product', 'mlm' ); ?>"></span>
			<?php endif; ?>
			<?php if( $percentage > 0 ): ?>
				<span class="off bg-danger rounded-pill position-absolute px-2 text-white font-10"><?php echo $percentage .'% '; ?><?php _e( 'off', 'mlm' ); ?></span>
			<?php endif; ?>
		</a>
	</header>
	<h4 class="item-title p-3 m-0">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
	</h4>
	<footer class="item-footer px-3 m-0 clearfix">
		<div class="row">
			<div class="col-6">
				<span class="item-meta my-1 download icon icon-download1 float-right">
					<?php echo $total_sales; ?> 
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
			<div class="col-6">
				<?php if( is_user_logged_in() || $login_req == 'no' ): ?>
					<span class="item-meta my-1 price icon icon-wallet float-left">
						<?php if( $price == 0 ): ?>
							<?php _e( 'free', 'mlm' ); ?>
						<?php else: ?>
							<?php echo $product->get_price_html(); ?>
						<?php endif; ?>
					</span>
				<?php elseif( ! empty( $page_count ) ): ?>
					<?php
					$types	= mlmFire()->wp_admin->supported_file_types();
					$icon	= isset( $types[$file_type]['icon'] ) ? $types[$file_type]['icon'] : 'icon-book-open';
					?>
					<span class="item-meta my-1 price icon float-left <?php echo $icon; ?>"><?php echo $page_count; ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="row align-items-center">
			<div class="col-6 border-top">
				<a href="<?php echo esc_url( get_author_posts_url( $vendor_id ) ); ?>" class="item-vendor float-right my-2 <?php if( $verified ) echo 'verified'; ?>">
					<?php echo get_avatar( $vendor_id, 32, NULL , $vendor_name, array( 'class' => 'rounded-circle float-right ml-2' ) ); ?>
					<?php echo $vendor_name; ?>
				</a>
			</div>
			<div class="col-6 border-top">
				<?php mlm_add_to_cart_btn( $post_id, 'item-purchase btn btn-primary btn-block d-block rounded-pill my-2 py-0' ); ?>
			</div>
		</div>
	</footer>
</article>