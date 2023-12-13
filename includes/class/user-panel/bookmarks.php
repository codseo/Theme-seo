<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id		= get_current_user_id();
$bookmarks_url	= trailingslashit( mlm_page_url('panel') ) . 'section/bookmarks/';
$bookmarks		= mlmFire()->rating->get_bookmarks( $user_id );
$post_books		= array();
$product_books	= array();

foreach( (array)$bookmarks as $key => $value )
{
	if( $value == 'product' )
	{
		$product_books[] = $key;
	}
	elseif( $value == 'post' )
	{
		$post_books[] = $key;
	}
}
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Bookmarks', 'mlm' ); ?></h3>

<?php if( ! empty( $product_books ) ): ?>
	<div class="table-responsive">
		<table class="mlm-table mlm-bookmark-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'Image', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Title', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Tools', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $product_books as $post_id ): ?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border" alt="post-image">
						</th>
						<td>
							<a class="title" href="<?php echo get_the_permalink( $post_id ); ?>"><?php echo mlm_get_post_title( $post_id ); ?></a>
							<div class="mlm-product-price font-12">
								<?php mlm_product_price( $post_id ); ?>
							</div>
						</td>
						<td>
							<a href="#mlm-bookmark-post" class="btn btn-secondary btn-sm py-0 btn-block bookmarked" data-id="<?php echo $post_id; ?>" data-verify="<?php echo wp_create_nonce('mlm_pogtrawz'); ?>" data-remove="yes"><?php _e( 'Remove this item', 'mlm' ); ?></a>
							<a href="#mlm-add-to-cart" class="btn btn-primary btn-sm py-0 btn-block" data-id="<?php echo $post_id; ?>"><?php _e( 'Add to cart', 'mlm' ); ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="alert alert-warning"><?php _e( 'Your bookmarks list is empty.', 'mlm' ); ?></div>
<?php endif; ?>