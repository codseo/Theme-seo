<?php
$gallery	= get_post_meta( get_the_ID(), '_product_image_gallery', true );
$image_ids	= array();

if( ! empty( $gallery ) )
{
	$image_ids = explode( ",", $gallery );
}

if( ! is_array( $image_ids ) || count( $image_ids ) < 1 )
{
	return;
}
?>

<div class="mlm-product-gallery-widget mb-4 clearfix">
	<h3 class="mlm-box-title icon icon-image sm mb-2"><?php _e( 'Gallery', 'mlm' ); ?></h3>
	<div class="row no-gutters mx-n1">
		<?php foreach( $image_ids as $attachment_id ): ?>
			<?php
			$thum_atts		= '';
			$full_atts		= '';
			$thum_atts		= wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$full_atts		= wp_get_attachment_image_src( $attachment_id, 'full' );
			$thum_src		= $thum_atts[0];
			$full_src		= $full_atts[0];
			?>
			<div class="col-3 mb-2 px-1">
				<a href="<?php echo esc_url( $full_src ); ?>" class="d-block position-relative" data-lightbox="product-gallery">
					<img src="<?php echo esc_url( $thum_src ); ?>" class="position-absolute w-100 h-100" alt="<?php the_title_attribute(); ?>" />
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>