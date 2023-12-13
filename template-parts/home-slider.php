<?php
$slides	= array();

for( $i = 1; $i <= 8; $i++ )
{
	$slide_txt	= get_option( 'mlm_slide_txt_' . $i );
	$slide_url	= esc_url( get_option( 'mlm_slide_url_' . $i ) );
	$slide_img	= esc_url( get_option( 'mlm_slide_img_' . $i ) );
	
	if( empty( $slide_url ) )
	{
		$slide_url = '#';
	}

	if( ! empty( $slide_img ) && ! empty( $slide_txt ) )
	{
		$slides[] = array(
			'txt'	=> $slide_txt,
			'url'	=> $slide_url,
			'img'	=> $slide_img,
		);
	}
}
?>

<?php if( is_array( $slides ) && count( $slides ) > 0 ): ?>
	<div class="mlm-main-slider swiper-container ltr overflow-hidden clearfix">
		<div class="swiper-wrapper">
			<?php foreach( $slides as $slide ): ?>
				<div class="swiper-slide">
					<a href="<?php echo $slide['url']; ?>" class="d-block" title="<?php echo $slide['txt']; ?>">
						<img src="<?php echo $slide['img']; ?>" class="w-100" alt="<?php echo $slide['txt']; ?>">
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="swiper-pagination"></div>
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
	</div>
<?php endif; ?>