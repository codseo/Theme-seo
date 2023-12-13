<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id		= ( current_user_can('moderate_comments') ) ? 0 : get_current_user_id();
$nonce			= wp_create_nonce( 'mlm_rakonojipan' );
$coupons_url	= trailingslashit( mlm_page_url('panel') ) . 'section/coupons/';
$submit_url		= trailingslashit( mlm_page_url('panel') ) . 'section/coupons-new/';
$search			= isset( $_GET['search'] ) ? esc_attr( $_GET['search'] ): '';
$query			= new WP_Query( array(
	'post_type' 		=> 'shop_coupon',
	'author'			=> $user_id,
	'post_status'		=> 'publish',
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
	's'					=> $search
) );
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Coupons', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_search"><?php _e( 'Search coupons', 'mlm' ); ?></a> 
	<a href="<?php echo $submit_url; ?>" class="btn btn-success btn-sm float-left mr-1 my-1"><?php _e( 'Add new coupon', 'mlm' ); ?></a> 
</div>

<?php if( isset( $_GET['submited'] ) && $_GET['submited'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Coupon submitted successfully.', 'mlm' ); ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php elseif( isset( $_GET['updated'] ) && $_GET['updated'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Coupon updated successfully.', 'mlm' ); ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>

<?php if( ! empty( $search ) ): ?>
	<h4 class="panel-box-title d-block mb-3 p-2 border-bottom"><?php printf( __( 'Search results for %s', 'mlm' ), $search ); ?></h4>
<?php endif; ?>
		
<?php if( $query->have_posts() ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-posts-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="md" scope="col"><?php _e( 'Code', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Amount', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Expires at', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Tools', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<?php
					$post_id	= get_the_ID();
					$status		= get_post_field( 'post_status', $post_id );
					$amount		= get_post_meta( $post_id, 'coupon_amount', true );
					$expire		= (int)get_post_meta( $post_id, 'date_expires', true );
					$class		= ( $expire > time() ) ? '' : 'table-danger';
					?>
					<tr class="<?php echo $class; ?>">
						<th scope="row"><?php the_title(); ?></th>
						<td><?php printf( __( '%d%%', 'mlm' ), $amount ); ?></td>
						<td><?php echo ( empty( $expire ) ) ? __( 'Unlimited', 'mlm' ) : date_i18n( 'j F Y', $expire ); ?></td>
						<td>
							<a href="<?php echo $submit_url . 'mid/'. $post_id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'Edit', 'mlm' ); ?></a>
							<a href="#mlm-delete-discount" class="btn btn-sm btn-light py-0 float-right m-1 font-11" data-id="<?php echo $post_id; ?>" data-verify="<?php echo $nonce; ?>"><?php _e( 'Delete', 'mlm' ); ?></a>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'You have no coupon codes yet.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_search" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Search coupons', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo $coupons_url; ?>" method="get">
					<div class="form-group">
						<label for="mlm_keyword"><?php _e( 'Keyword', 'mlm' ); ?></label>
						<input type="text" class="form-control" id="mlm_keyword" name="search" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>">
					</div>
					<div class="clearfix">
						<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Search', 'mlm' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>