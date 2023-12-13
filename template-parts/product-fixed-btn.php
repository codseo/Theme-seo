<?php
global $product;
$post_id		= get_the_ID();
$fixed_btn		= get_option('mlm_fixed_btn');
$fixed_btn_lg	= get_option('mlm_fixed_btn_lg');

if( ( $fixed_btn == 'no' && $fixed_btn_lg == 'no' ) || $product->is_on_backorder() || ! $product->is_in_stock() )
{
	return;
}
?>

<div class="mlm-product-fixed-widget mlm-widget bg-white position-fixed m-0 px-3 py-1 clearfix d-block d-md-none border-top">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-md-7 d-none d-md-flex">
				<?php the_title( '<h4 class="m-0 font-14 bold-600 text-secondary">', '</h4>' ); ?>
			</div>
			<div class="col-12 col-md-5">
				<?php mlm_add_to_cart_btn( $post_id, 'btn btn-primary btn-block', true, true ); ?>
			</div>
		</div>
	</div>
</div>