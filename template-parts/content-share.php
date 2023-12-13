<?php
$title	= urlencode( the_title_attribute( 'echo=0' ) );
$url	= esc_url( get_permalink() );
/*
$thumb	= mlm_image_url( get_the_ID(), 'large', false );
$site	= get_bloginfo('name');
*/
?>

<div class="dropdown col-auto my-1 mr-auto">
	<button class="mlm-share-btn btn btn-light border-0 py-1 px-3 dropdown-toggle icon icon-share2" type="button" id="mlm-share-post-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php _e( 'Share on', 'mlm' ); ?> </button>
	<div class="mlm-share-dropdown dropdown-menu" aria-labelledby="mlm-share-post-btn">
		<a target="_blank" href="https://wa.me/?text=<?php echo $url; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-whatsapp"><?php _e( 'Whatsapp', 'mlm' ); ?></a>
		<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&url=<?php echo $url; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-twitter"><?php _e( 'Twitter', 'mlm' ); ?></a>
		<a target="_blank" href="https://telegram.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-telegram"><?php _e( 'Telegram', 'mlm' ); ?></a>
	</div>
</div>