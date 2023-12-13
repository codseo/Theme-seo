<?php
$post_id		= get_the_ID();
$bookmarked		= mlmFire()->rating->check_post_bookmark( $post_id );

if( $bookmarked )
{
	$ic	= 'icon-heart';
	$tx	= __( 'Remove from bookmarks', 'mlm' );
}
else
{
	$ic	= 'icon-heart1';
	$tx	= __( 'Bookmark this', 'mlm' );
}
?>

<div class="mlm-product-bookmark-btn mb-3 clearfix">
	<a href="#mlm-bookmark-post" class="btn text-danger font-20 p-0 no-shadow <?php echo $ic; ?> <?php if( $bookmarked ) echo 'bookmarked'; ?> " data-id="<?php echo $post_id; ?>" data-verify="<?php echo wp_create_nonce('mlm_pogtrawz'); ?>"></a>
</div>