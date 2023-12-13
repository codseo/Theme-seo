<?php
global $product;
$post_id		= get_the_ID();
$download_popup	= false;
$login_req		= get_option('mlm_login_req');
$download_cnt	= get_option('mlm_download_cnt');
$fields_type	= mlm_custom_fields_type();

if( $fields_type != 'custom' )
{
    $file_type		= get_post_meta( $post_id, 'mlm_file_type', true );
    $page_count		= get_post_meta( $post_id, 'mlm_page_count', true );
    $part_count		= get_post_meta( $post_id, 'mlm_part_count', true );
    $file_author	= get_post_meta( $post_id, 'mlm_file_author', true );

    $types	= mlmFire()->wp_admin->supported_file_types();
    $icon	= isset( $types[$file_type]['icon'] ) ? $types[$file_type]['icon'] : 'icon-book-open';
    $title	= isset( $types[$file_type]['title'] ) ? $types[$file_type]['title'] : __( 'Pages count', 'mlm' );
}

if( $download_cnt == 'view' )
{
    $total_sales	= mlm_get_post_views( $post_id );
}
else
{
    $total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
}

$price	= mlm_get_product_price( $post_id );
?>

<div class="mlm-purchase-product-widget mb-4 clearfix">

    <?php
    $sale_from		= (int)get_post_meta( $post_id, '_sale_price_dates_from', true );
    $sale_to		= (int)get_post_meta( $post_id, '_sale_price_dates_to', true );
    $publish_time	= get_post_meta( $post_id, 'mlm_file_publish', true );
    ?>

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

    <?php if( ( is_user_logged_in() || $login_req == 'no' ) && ! $product->is_on_backorder() && $product->is_in_stock() ): ?>
        <div class="row justify-content-between">
            <div class="col-auto">
                <span class="t bold-600"><?php _e( 'Product price', 'mlm' ); ?></span>
            </div>
            <div class="col-auto">
				<span class="t bold-600 text-primary">
					<?php if( $price == 0 ): ?>
                        <?php _e( 'Free', 'mlm' ); ?>
                    <?php else: ?>
                        <?php echo $product->get_price_html(); ?>
                    <?php endif; ?>
				</span>
            </div>
        </div>
    <?php endif; ?>
    <?php if( $fields_type != 'custom' && ! empty( $file_author ) ): ?>
        <div class="owner position-relative text-center mt-2 mb-3 clearfix">
            <span class="v d-block position-relative py-0 px-3 mx-auto border border-light font-12 bg-white rounded overflow-hidden"><?php echo $file_author; ?></span>
        </div>
    <?php endif; ?>
    <?php if( $fields_type != 'custom' ): ?>
        <div class="row">
            <div class="col-4">
                <div class="meta-item my-2 text-center">
                    <span class="icon icon-download1 d-block"></span>
                    <span class="v d-block font-12">
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
            </div>
            <?php if( ! empty( $page_count ) ): ?>
                <div class="col-4">
                    <div class="meta-item my-2 text-center">
                        <span class="icon d-block <?php echo $icon; ?>"></span>
                        <span class="v d-block font-12"><?php echo $page_count; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <?php if( ! empty( $part_count ) ): ?>
                <div class="col-4">
                    <div class="meta-item my-2 text-center">
                        <span class="icon icon-lightbulb d-block"></span>
                        <span class="v d-block font-12"><?php echo $part_count; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="mlm-purchase-btn my-2 clearfix">
        <?php mlm_add_to_cart_btn( $post_id, 'btn btn-block btn-success p-2 ellipsis font-15', true, false, true ); ?>
    </div>
    <?php
    /*custom code*/
    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    if($user_id != 0 && mlmFire()->plan->check_user_limit( $post_id, $user_id ) == 5) { ?>
        <div class="alert alert-danger" style="font-size:10px;">
            <strong>محدودیت دانلود!</strong> تعداد دانلود روزانه ی شما به اتمام رسیده است.
        </div>
        <?php
    }
    $mlm_button_2_link = get_post_meta($post_id, 'mlm_button_2_link', true) ? get_post_meta($post_id, 'mlm_button_2_link', true) : '';
    if(!empty($mlm_button_2_link))
    {
        $mlm_button_2_text = get_post_meta($post_id, 'mlm_button_2_text', true) ? get_post_meta($post_id, 'mlm_button_2_text', true) : '';
        ?>
        <a href="<?php echo $mlm_button_2_link; ?>" target="_blank" class="btn btn-block btn-buy font-15 button_2_link mb-2 mt-2 bold-600 "><?php echo $mlm_button_2_text; ?></a>
        <?php
    }
    ?>

    <div class="mlm-subscribe-text my-2 text-justify font-12 text-secondary clearfix">
        <?php echo mlmFire()->plan->get_subscription_text( $post_id, get_current_user_id() ); ?>
    </div>
    <div class="my-2 clearfix">
        <?php get_template_part( 'template-parts/content', 'preview' ); ?>
    </div>
    <?php get_template_part( 'template-parts/content', 'rating' ); ?>
</div>

<div class="sr-only" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <meta itemprop="price" content="<?php echo strip_tags( $product->get_price() ); ?>" />
    <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency_symbol(); ?>" />
    <meta itemprop="availability" content="InStock" />
    <meta itemprop="priceValidUntil" content="<?php echo date( 'Y-m-d', strtotime('+1 Year') ); ?>" />
    <meta itemprop="url" content="<?php echo wp_get_shortlink( $post_id ); ?>" />
</div>