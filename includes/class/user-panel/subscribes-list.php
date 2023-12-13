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
$plans_url		= trailingslashit( mlm_page_url('panel') ) . 'section/subscribe-history/';
$plan_data		= mlmFire()->plan->get_plans( $attributes['mid'] );
$plan_name		= isset( $plan_data['name'] ) ? $plan_data['name'] : 'پلن';
$query			= new WP_Query( array(
	'post_type' 		=> 'product',
	'post_status'		=> 'publish',
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
	'tax_query'			=> array( array(
		'taxonomy'	=> 'plans',
		'field'		=> 'term_id',
		'terms'		=> $attributes['mid'],
	) )
) );
?>

<h3 class="mlm-box-title sm mb-0 py-2"><?php _e( 'Downloads', 'mlm' ); ?></h3>
<p class="text-justify font-12 mb-4 text-secondary"><?php printf( __( 'Available products for %s', 'mlm' ), $plan_name ); ?></p>
		
<?php if( $query->have_posts() ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-posts-table table table-borderless table-hover border-0">
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
                    /*custom code*/
                    $access	= true;
					?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border" alt="post-image">
						</th>
						<td>
							<a target="_blank" href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
						</td>
						<td>
							<?php if( $access ): ?>
								<a href="<?php the_permalink(); ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'View & download', 'mlm' ); ?></a>
							<?php else: ?>
								<a href="#" class="mlm-need-to-purchase-plan-btn btn btn-sm btn-secondary py-0 float-right m-1 font-11" disabled="disabled"><?php _e( 'View & download', 'mlm' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No downloads available for the selected plan.', 'mlm' ); ?></div>
	
<?php endif; ?>