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
$nonce			= wp_create_nonce( 'mlm_edit_jibella' );
$products_url	= trailingslashit( mlm_page_url('panel') ) . 'section/products-all/';
$submit_url		= trailingslashit( mlm_page_url('panel') ) . 'section/products-new/';
$course_url		= trailingslashit( mlm_page_url('panel') ) . 'section/course-new/';
$physical_url	= trailingslashit( mlm_page_url('panel') ) . 'section/physical-new/';
$search			= isset( $_GET['search'] ) ? esc_attr( $_GET['search'] ): '';
$status			= isset( $_GET['status'] ) ? esc_attr( $_GET['status'] ): '';
$args			= array(
	'post_type' 		=> 'product',
	'author'			=> $user_id,
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
	's'					=> $search
);

switch( $status )
{
	case 'publish_p':
		$args['post_status']	= 'publish';
		$args['meta_query']		= array(
			'relation'	=> 'OR',
			array(
				'key'		=> 'mlm_is_course',
				'value'		=> 'yes',
				'compare'	=> '!=',
			),
			array(
				'key'		=> 'mlm_is_course',
				'compare'	=> 'NOT EXISTS',
			),
		);
		break;
		
	case 'pending_p':
		$args['post_status']	= 'pending';
		$args['meta_query']		= array(
			'relation'	=> 'OR',
			array(
				'key'		=> 'mlm_is_course',
				'value'		=> 'yes',
				'compare'	=> '!=',
			),
			array(
				'key'		=> 'mlm_is_course',
				'compare'	=> 'NOT EXISTS',
			),
		);
		break;
		
	case 'draft_p':
		$args['post_status']	= 'draft';
		$args['meta_query']		= array(
			'relation'	=> 'OR',
			array(
				'key'		=> 'mlm_is_course',
				'value'		=> 'yes',
				'compare'	=> '!=',
			),
			array(
				'key'		=> 'mlm_is_course',
				'compare'	=> 'NOT EXISTS',
			),
		);
		break;
		
	case 'publish_c':
		$args['post_status']	= 'publish';
		$args['meta_query']		= array( array(
			'key'		=> 'mlm_is_course',
			'value'		=> 'yes',
			'compare'	=> '=',
		) );
		break;
		
	case 'pending_c':
		$args['post_status']	= 'pending';
		$args['meta_query']		= array( array(
			'key'		=> 'mlm_is_course',
			'value'		=> 'yes',
			'compare'	=> '=',
		) );
		break;
		
	case 'draft_c':
		$args['post_status']	= 'draft';
		$args['meta_query']		= array( array(
			'key'		=> 'mlm_is_course',
			'value'		=> 'yes',
			'compare'	=> '=',
		) );
		break;
		
	default:
		$args['post_status']	= array( 'publish', 'pending', 'draft' );
		break;
}

$query		= new WP_Query( $args );
?>

<h3 class="mlm-box-title sm m-0 pt-2"><?php _e( 'All products', 'mlm' ); ?></h3>
<nav class="mlm-sort-items mb-3 p-0 mx-0 text-secondary bold-300 clearfix">
	<a href="<?php echo $products_url; ?>" class="text-dark <?php if( empty( $status ) ) echo 'bold-900'; ?>"><?php _e( 'All', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'publish_p', $products_url ); ?>" class="text-dark <?php if( $status == 'publish_p' ) echo 'bold-900'; ?>"><?php _e( 'Published', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'pending_p', $products_url ); ?>" class="text-dark <?php if( $status == 'pending_p' ) echo 'bold-900'; ?>"><?php _e( 'Pending', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'draft_p', $products_url ); ?>" class="text-dark <?php if( $status == 'draft_p' ) echo 'bold-900'; ?>"><?php _e( 'Draft', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'publish_c', $products_url ); ?>" class="text-dark <?php if( $status == 'publish_c' ) echo 'bold-900'; ?>"><?php _e( 'Published courses', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'pending_c', $products_url ); ?>" class="text-dark <?php if( $status == 'pending_c' ) echo 'bold-900'; ?>"><?php _e( 'Pending courses', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'draft_c', $products_url ); ?>" class="text-dark <?php if( $status == 'draft_c' ) echo 'bold-900'; ?>"><?php _e( 'Draft courses', 'mlm' ); ?></a>
</nav>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_search"><?php _e( 'Search product', 'mlm' ); ?></a> 
	<a href="<?php echo $submit_url; ?>" class=""></a>
	<select class="btn btn-success btn-sm float-left mr-1 my-1 font-13 pt-2 simple" onchange="javascript:location.href=this.value;">
		<option value="" selected="selected"><?php _e( 'Add new product', 'mlm' ); ?></option>
		<option value="<?php echo $submit_url; ?>"><?php _e( 'Add new file', 'mlm' ); ?></option>
		<option value="<?php echo $course_url; ?>"><?php _e( 'Add new course', 'mlm' ); ?></option>
		<option value="<?php echo $physical_url; ?>"><?php _e( 'Add new product', 'mlm' ); ?></option>
	</select>
</div>

<?php if( isset( $_GET['submited'] ) && $_GET['submited'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Product submitted successfully and will publish after moderation.', 'mlm' ); ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php elseif( isset( $_GET['updated'] ) && $_GET['updated'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Product updated successfully and will publish after moderation.', 'mlm' ); ?>
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
					<th class="sm" scope="col"><?php _e( 'Image', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Title', 'mlm' ); ?></th>
					<th class="sm" scope="col"><?php _e( 'Price', 'mlm' ); ?></th>
					<th class="sm" scope="col"><?php _e( 'View', 'mlm' ); ?></th>
					<th class="sm" scope="col"><?php _e( 'Sale', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Tools', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<?php
					$post_id	= get_the_ID();
					$status		= get_post_field( 'post_status', $post_id );
					$is_file	= get_post_meta( $post_id, '_downloadable', true );
					$mlm_reject	= get_post_meta( $post_id, 'mlm_reject', true );
					?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border" alt="post-image">
						</th>
						<td>
							<a target="_blank" href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
						</td>
						<td><?php mlm_product_price(); ?></td>
						<td><?php echo mlm_get_post_views( $post_id ); ?></td>
						<td><?php echo (int)get_post_meta( $post_id, 'total_sales', true ); ?></td>
						<td>
							<?php if( mlm_check_course( $post_id ) ): ?>
								<a href="<?php echo $course_url . 'mid/'. $post_id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'Edit', 'mlm' ); ?></a>
							<?php elseif( $is_file != 'yes' ): ?>
								<a href="<?php echo $physical_url . 'mid/'. $post_id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'Edit', 'mlm' ); ?></a>
							<?php else: ?>
								<a href="<?php echo $submit_url . 'mid/'. $post_id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'Edit', 'mlm' ); ?></a>
							<?php endif; ?>
							<?php if( $status == 'publish' ): ?>
								<a target="_blank" href="<?php the_permalink(); ?>" class="btn btn-sm btn-light py-0 float-right m-1 font-11"><?php _e( 'View', 'mlm' ); ?></a>
							<?php elseif( $status == 'draft' ): ?>
								<a target="_blank" href="<?php echo get_preview_post_link( $post_id ); ?>" class="btn btn-sm btn-warning py-0 float-right m-1 font-11"><?php _e( 'Preview', 'mlm' ); ?></a><span style="color: #ff0000;"><?php _e( 'Draft', 'mlm' ); ?></span>
							<?php else: ?>
								<a target="_blank" href="<?php echo get_preview_post_link( $post_id ); ?>" class="btn btn-sm btn-warning py-0 float-right m-1 font-11"><?php _e( 'Pending', 'mlm' ); ?></a>
							<?php endif; ?>
							<?php if( ! empty( $mlm_reject ) ): ?>
								<button class="btn btn-sm btn-danger py-0 float-right m-1 font-11 disabled" disabled="disabled"><?php _e( 'Moderated', 'mlm' ); ?></button>
							<?php endif; ?>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No products found.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_search" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Search product', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo $products_url; ?>" method="get">
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