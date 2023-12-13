<?php
$title	= urlencode( the_title_attribute( 'echo=0' ) );
$url	= esc_url( get_permalink() );
/*
$thumb	= mlm_image_url( get_the_ID(), 'large', false );
$site	= get_bloginfo('name');
*/
?>

<nav class="mlm-share-nav nav m-0 py-2 px-0 mx-0 mb-3">
	<span class="nav-link title icon icon-share2">
		<?php _e( 'Share', 'mlm' ); ?>
	</span>
	<a target="_blank" href="https://wa.me/?text=<?php echo $url; ?>" class="nav-link icon icon-whatsapp"></a>
	<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&url=<?php echo $url; ?>" class="nav-link icon icon-twitter"></a>
	<a target="_blank" href="https://telegram.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" class="nav-link icon icon-telegram"></a>
</nav>