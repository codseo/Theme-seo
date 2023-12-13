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
$user_obj		= get_userdata( $user_id );
$user_email		= $user_obj->user_email;
$courses_url	= trailingslashit( mlm_page_url('panel') ) . 'section/courses/';
$query			= new WP_Query( array(
	'post_type' 		=> 'product',
	'post_status'		=> 'publish',
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
	'meta_query'		=> array( array(
		'key'		=> 'mlm_is_course',
		'value'		=> 'yes',
		'compare'	=> '=',
	) ),
) );
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Courses', 'mlm' ); ?></h3>

<?php if( $query->have_posts() ): ?>
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
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<?php
					$post_id	= get_the_ID();
					$access		= mlmFire()->plan->check_user_access( $post_id, $user_id );
					?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border" alt="post-image">
						</th>
						<td>
							<a target="_blank" href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
							<div class="mlm-product-price font-12">
								<?php mlm_product_price(); ?>
							</div>
						</td>
						<td>
							<?php if( ( $access && mlm_check_course( $post_id ) ) || ( function_exists('wc_customer_bought_product') && wc_customer_bought_product( $user_email, $user_id, $post_id ) ) ): ?>
								<a href="<?php the_permalink(); ?>#mlm-scroll-to-course" class="btn btn-primary btn-sm py-0 btn-block"><?php _e( 'view articles', 'mlm' ); ?></a>
							<?php else: ?>
								<a href="<?php the_permalink(); ?>" class="btn btn-secondary btn-sm py-0 btn-block"><?php _e( 'view details', 'mlm' ); ?></a>
								<a href="#mlm-add-to-cart" class="btn btn-primary btn-sm py-0 btn-block" data-id="<?php echo $post_id; ?>"><?php _e( 'add to cart', 'mlm' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	<?php mlm_navigation( $query ); ?>
<?php else: ?>
	<div class="alert alert-warning"><?php _e( 'No courses found.', 'mlm' ); ?></div>
<?php endif; ?>