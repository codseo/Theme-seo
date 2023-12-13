<?php
$title	= urlencode( the_title_attribute( 'echo=0' ) );
$url	= mlmFire()->referral->add_ref_to_url( get_permalink() );
?>

<div class="dropdown clearfix dropleft">
	<button class="mlm-share-btn btn btn-light border-0 font-11 py-0 icon icon-share2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php _e( 'Share', 'mlm' ); ?></button>
	<div class="mlm-share-dropdown dropdown-menu">
		<a target="_blank" href="https://wa.me/?text=<?php echo $url; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-whatsapp"><?php _e( 'Whatsapp', 'mlm' ); ?></a>
		<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&url=<?php echo $url; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-twitter"><?php _e( 'Twitter', 'mlm' ); ?></a>
		<a target="_blank" href="https://telegram.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" class="dropdown-item d-block m-0 px-4 py-2 icon icon-telegram"><?php _e( 'Telegram', 'mlm' ); ?></a>
	</div>
</div>