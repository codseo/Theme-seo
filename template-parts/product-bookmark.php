<?php
$post_id		= get_the_ID();
$bookmarked		= mlmFire()->rating->check_post_bookmark( $post_id );

if( $bookmarked )
{
	$ic	= 'btn-danger';
	$tx	= __( 'Remove from bookmarks', 'mlm' );
}
else
{
	$ic	= 'btn-success';
	$tx	= __( 'Bookmark this', 'mlm' );
}
?>

<div class="mlm-product-bookmark-widget mb-4 clearfix">
	<a href="#mlm-bookmark-post" class="btn btn-block <?php echo $ic; ?> <?php if( $bookmarked ) echo 'bookmarked'; ?> " data-id="<?php echo $post_id; ?>" data-verify="<?php echo wp_create_nonce('mlm_pogtrawz'); ?>"><?php echo $tx; ?></a>
</div>