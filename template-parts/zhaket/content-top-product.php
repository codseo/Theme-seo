<?php
$post_id		= get_the_ID();
$price			= mlm_get_product_price( $post_id );
?>

<div class="product-item position-relative clearfix">
	<a href="<?php the_permalink(); ?>" class="item-image d-block position-relative">
		<img src="<?php mlm_image_url( $post_id, 'medium' ); ?>" class="d-block position-absolute" alt="<?php the_title_attribute(); ?>">
		<div class="item-price position-absolute text-center text-secondary clearfix">
			<svg viewBox="0 0 68 65"><path d="M67.697 32.349c0 3.466-3.591 6.323-4.684 9.461s.141 7.666-1.826 10.367c-1.968 2.7-6.573 2.95-9.368 4.934-2.795 1.982-4.31 6.245-7.557 7.322s-6.948-1.483-10.414-1.483c-3.466 0-7.292 2.498-10.414 1.483s-4.855-5.355-7.557-7.322c-2.701-1.968-7.307-2.202-9.368-4.934s-.765-7.12-1.827-10.367C3.623 38.563 0 35.83 0 32.349c0-3.482 3.591-6.323 4.684-9.461s-.141-7.666 1.827-10.367 6.573-2.951 9.368-4.934 4.309-6.245 7.557-7.322 6.948 1.483 10.414 1.483c3.465 0 7.292-2.499 10.414-1.483 3.122 1.015 4.855 5.355 7.557 7.322s7.307 2.201 9.368 4.934c2.061 2.732.765 7.12 1.826 10.367s4.682 5.995 4.682 9.461z"></path></svg>
			<span class="price d-block position-relative pt-3 font-18 bold-600">
				<?php if( $price == 0 ): ?>
					<?php _e( 'free', 'mlm' ); ?>
				<?php else: ?>
					<?php echo $price; ?>
					<?php if( function_exists('get_woocommerce_currency_symbol') ): ?>
						<span class="c d-block font-10 bold-300 text-secondary"><?php echo get_woocommerce_currency_symbol(); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</span>
		</div>
	</a>
	<div class="item-title p-2 m-0 font-15 bold-600 overflow-hidden">
		<a href="<?php the_permalink(); ?>" class="text-secondary"><?php the_title(); ?></a>
	</div>
</div>