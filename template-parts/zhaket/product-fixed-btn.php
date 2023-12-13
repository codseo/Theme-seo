<?php
global $product;
$post_id		= get_the_ID();
$fixed_btn		= get_option('mlm_fixed_btn');
$fixed_btn_lg	= get_option('mlm_fixed_btn_lg');
$total_view		= mlm_get_post_views( $post_id );

if( $fixed_btn == 'no' || $product->is_on_backorder() || ! $product->is_in_stock() )
{
	return;
}
?>

<div class="mlm-product-fixed-widget mlm-widget bg-success position-fixed m-0 p-0 clearfix d-block d-md-none">
	<div class="container-fluid position-relative px-3 py-2">
		<div class="row no-gutters mx-n1 align-items-center text-center text-white font-10 bold-500">
			<div class="code-col col px-1">
				<span class="ellipsis"><?php echo $post_id; ?></span>
				<span class="ellipsis"><?php _e( 'Product ID', 'mlm' ); ?></span>
			</div>
			<div class="view-col col px-1">
				<span class="ellipsis"><?php echo $total_view; ?></span>
				<span class="ellipsis"><?php echo _nx( 'view', 'views', $total_view, 'view count', 'mlm' ); ?></span>
			</div>
			<div class="price-col col px-1">
				<?php mlm_add_to_cart_btn( $post_id, 'btn btn-primary btn-block', true, true ); ?>
			</div>
		</div>
	</div>
</div>