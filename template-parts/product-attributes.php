<?php
global $product;
ob_start();	
wc_display_product_attributes( $product );
$attributes = ob_get_contents();
ob_end_clean();
ob_start();
?>

<?php if( ! empty( $attributes ) ): ?>
	<div class="mlm-product-meta-box m-0 p-0 clearfix">
		<h3 class="mlm-box-title icon icon-presentation sm mb-2">
			<?php _e( 'Product details', 'mlm' ); ?>
		</h3>
		<?php echo $attributes; ?>
	</div>
<?php endif; ?>