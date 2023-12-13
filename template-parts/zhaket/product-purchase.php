<?php
global $product;
$post_id		= get_the_ID();
$download_cnt	= get_option('mlm_download_cnt');
$pros_text		= get_option('mlm_pros_text');

if( $download_cnt == 'view' )
{
	$total_sales	= mlm_get_post_views( $post_id );
}
else
{
	$total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
}

$price			= mlm_get_product_price( $post_id );
$percentage		= mlm_product_has_off( $post_id );
$sale_from		= (int)get_post_meta( $post_id, '_sale_price_dates_from', true );
$sale_to		= (int)get_post_meta( $post_id, '_sale_price_dates_to', true );
$publish_time	= get_post_meta( $post_id, 'mlm_file_publish', true );
?>

<div class="purchase-product-widget mb-4 clearfix">
	<?php if( time() > $sale_from && $sale_to > time() && $product->is_on_sale() ): ?>
		<div class="counter-wrapper mb-4 clearfix d-none">
			<h3 class="mlm-box-title sm mb-2"><?php _e( 'Special sale until', 'mlm' ); ?></h3>
			<div class="counter-box clearfix">
				<span class="icon icon-alarmclock text-primary"></span>
				<span class="mlm-countdown" data-time="<?php echo date( 'Y-m-d 23:59:59', $sale_to ); ?>"></span>
			</div>
		</div>
	<?php elseif( $product->is_on_backorder() || ! $product->is_in_stock() && ! empty( $publish_time ) ): ?>
		<div class="counter-wrapper mb-4 clearfix d-none">
			<h3 class="mlm-box-title sm mb-2">
				<?php if( mlm_check_course( $post_id ) ): ?>
					<?php _e( 'Countdown for course start', 'mlm' ); ?>
				<?php else: ?>
					<?php _e( 'Countdown for product publish', 'mlm' ); ?>
				<?php endif; ?>
			</h3>
			<div class="counter-box clearfix">
				<span class="icon icon-alarmclock text-primary"></span>
				<span class="mlm-countdown" data-time="<?php echo $publish_time; ?>"></span>
			</div>
		</div>
	<?php endif; ?>
	<div class="slide-price position-relative text-center mb-4">
		<svg viewBox="0 0 462 103.7"><g transform="translate(-11249.401 1012)"><path class="st0" d="M11709.9-971.2h1.4v-36.9c0-2.2-1.8-4-4-4h-454c-2.2 0-4 1.8-4 4v36.8h1c5.9.2 10.7 5.1 10.7 11s-4.7 10.8-10.7 11h-1v36.9c0 2.2 1.8 4 4 4h454c2.2 0 4-1.8 4-4v-37l-1.1.1h-.4c-6.1 0-11-4.9-11-11 .1-5.9 5.1-10.9 11.1-10.9zm-13 11c0 7 5.6 12.7 12.5 13v34.9c0 1.1-.9 2-2 2h-454c-1.1 0-2-.9-2-2v-34.9c6.5-.7 11.7-6.3 11.6-12.9 0-6.7-5.1-12.3-11.6-12.9v-34.9c0-1.1.9-2 2-2h454c1.1 0 2 .9 2 2v34.8c-6.9.2-12.5 5.9-12.5 12.9z"></path><path class="st0" d="M11479.9-927.7c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .6-.4 1-1 1zm0-18.1c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .5-.4 1-1 1zm0-18.1c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .5-.4 1-1 1zm0-18.2c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .6-.4 1-1 1z"></path></g></svg>
		<div class="row align-items-center">
			<div class="col-6">
				<div class="item-sales text-dark">
					<svg viewBox="0 0 34 43.3"><g transform="translate(7140 135)"><path fill="#E6E6E6" d="M-7112-92.7h-22c-2.7 0-5-2.2-5-4.9v-3.1h32v3c0 2.7-2.2 5-5 5 .1 0 0 0 0 0z"></path><path fill="#FEA000" d="M-7139-105.7h32v5h-32z"></path><path fill="#4D4D4D" d="M-7116-127.7v-.4c0-1.9-.8-3.6-2.1-4.9s-3.1-2-4.9-2c-1.9 0-3.6.7-4.9 2-1.3 1.3-2 3-2.1 4.9v.4h-10V-97c.2 1.3.8 2.6 1.7 3.6h.1c.3.2.5.5.8.7 0 0 .1 0 .1.1.3.2.6.3.9.5h.1c.3.1.7.2 1 .3h.1c.4.1.7.1 1.1.1H-7111.9c3.3 0 5.9-2.7 5.9-6v-30h-10zm-12-.4c0-1.3.5-2.6 1.5-3.5s2.2-1.5 3.5-1.4c1.3 0 2.6.5 3.5 1.4.9.9 1.5 2.2 1.5 3.5v.4h-10v-.4zm20 30.4c0 2.2-1.7 4-3.9 4l-.1 1v-1h-22c-1.1 0-2.1-.4-2.8-1.1-.8-.7-1.2-1.7-1.2-2.9v-2h30v2zm-30-4v-3h30v3h-30zm0-5v-19h8v5c0 .6.4 1 1 1s1-.4 1-1v-5h10v5c0 .6.4 1 1 1s1-.4 1-1v-5h8v19h-30z"></path></g></svg>
					<?php if( $percentage ): ?>
						<span class="item-value ellipsis font-28 bold-600 text-secondary">
							<?php echo $percentage .'%'; ?>
						</span>
						<span class="item-label ellipsis font-12">
							<?php _e( 'off', 'mlm' ); ?>
						</span>
					<?php else: ?>
						<span class="item-value ellipsis font-28 bold-600 text-secondary">
							<?php echo $total_sales; ?>
						</span>
						<span class="item-label ellipsis font-12">
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
					<?php endif; ?>
				</div>
			</div>
			<div class="col-6">
				<div class="item-price text-black">
					<?php if( $price == 0 && ! $percentage ): ?>
						<span class="item-value ellipsis font-20 bold-600"><?php _e( 'Free', 'mlm' ); ?></span>
					<?php else: ?>
						<span class="item-value ellipsis font-20 bold-600"><?php echo $product->get_price_html(); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="slide-medals mb-4 d-none d-lg-block">
		<?php mlmFire()->medal->print_product_medals( get_the_ID(), 'mlm-product-medal-nav mlm-product-medal-nav nav m-0 p-0 d-none d-md-flex' ); ?>
	</div>
	<?php if( ! empty( $pros_text ) ): ?>
		<?php
		$items	= array();
		$items	= explode("\r\n",$pros_text);
		?>
		<div class="item-desc mb-4 p-3 clearfix">
			<div class="text-14 bold-600 text-secondary mb-2"><?php _e( 'What you got by purchasing this product:', 'mlm' ); ?></div>
			<?php foreach( $items as $item ): ?>
				<div class="dotted text-14 bold-600 text-secondary mb-2"><?php echo $item; ?></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php
	mlm_add_to_cart_btn( $post_id, 'btn btn-block btn-buy p-3 font-15 bold-600', true, false, true );
	?>
    <?php
    /*custom code*/
    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
     if($user_id != 0 && mlmFire()->plan->check_user_limit( $post_id, $user_id ) == 5) { ?>
         <div class="alert alert-danger mt-2">
             <strong>محدودیت دانلود!</strong> تعداد دانلود روزانه ی شما به اتمام رسیده است.
         </div>
         <?php
     }
    $mlm_button_2_link = get_post_meta($post_id, 'mlm_button_2_link', true) ? get_post_meta($post_id, 'mlm_button_2_link', true) : '';
    if(!empty($mlm_button_2_link))
    {
        $mlm_button_2_text = get_post_meta($post_id, 'mlm_button_2_text', true) ? get_post_meta($post_id, 'mlm_button_2_text', true) : '';
        ?>
        <a href="<?php echo $mlm_button_2_link; ?>" target="_blank" class="btn btn-block btn-buy font-15 button_2_link p-3 mb-2 mt-2 bold-600 "><?php echo $mlm_button_2_text; ?></a>
        <?php
    }
    ?>
	<div class="item-help font-13 bold-400 text-secondary text-justify mt-2">
		<?php echo mlmFire()->plan->get_subscription_text( $post_id, get_current_user_id() ); ?>
	</div>
</div>

<div class="sr-only" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<meta itemprop="price" content="<?php echo strip_tags( $product->get_price() ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency_symbol(); ?>" />
	<meta itemprop="availability" content="InStock" />
	<meta itemprop="priceValidUntil" content="<?php echo date( 'Y-m-d', strtotime('+1 Year') ); ?>" />
	<meta itemprop="url" content="<?php echo wp_get_shortlink( $post_id ); ?>" />
</div>