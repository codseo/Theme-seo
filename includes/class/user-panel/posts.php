<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id	= get_current_user_id();
$nonce		= wp_create_nonce( 'mlm_edit_jibella' );
$posts_url	= trailingslashit( mlm_page_url('panel') ) . 'section/posts-all/';
$submit_url	= trailingslashit( mlm_page_url('panel') ) . 'section/posts-new/';
$search		= isset( $_GET['search'] ) ? esc_attr( $_GET['search'] ): '';
$query		= new WP_Query( array(
	'post_type' 		=> 'post',
	'author'			=> $user_id,
	'post_status'		=> array( 'publish', 'pending' ),
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
	's'					=> $search
) );
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'All posts', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_search"><?php _e( 'Search post', 'mlm' ); ?></a> 
	<a href="<?php echo $submit_url; ?>" class="btn btn-success btn-sm float-left mr-1 my-1"><?php _e( 'Add new post', 'mlm' ); ?></a> 
</div>

<?php if( isset( $_GET['submited'] ) && $_GET['submited'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Post submitted successfully and will publish after moderation.', 'mlm' ); ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php elseif( isset( $_GET['updated'] ) && $_GET['updated'] == 'OK' ): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php _e( 'Post updated successfully and will publish after moderation.', 'mlm' ); ?>
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
					<th class="sm" scope="col"><?php _e( 'Views', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Tools', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<?php
					$post_id	= get_the_ID();
					$status		= get_post_field( 'post_status', $post_id );
					?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border post-image" alt="post-image">
						</th>
						<td>
							<a target="_blank" href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
						</td>
						<td><?php echo mlm_get_post_views( $post_id ); ?></td>
						<td>
							<a href="<?php echo $submit_url . 'mid/'. $post_id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-secondary py-0 float-right m-1 font-11"><?php _e( 'edit', 'mlm' ); ?></a>
							<?php if( $status == 'publish' ): ?>
								<a target="_blank" href="<?php the_permalink(); ?>" class="btn btn-sm btn-light py-0 float-right m-1 font-11"><?php _e( 'view', 'mlm' ); ?></a>
							<?php else: ?>
								<a href="#" class="btn btn-sm btn-warning py-0 float-right m-1 font-11 disabled" disabled="disabled"><?php _e( 'pending', 'mlm' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No posts found.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_search" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Search post', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo $posts_url; ?>" method="get">
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