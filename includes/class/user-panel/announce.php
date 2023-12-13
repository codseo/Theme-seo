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
$nonce			= wp_create_nonce( 'mlm_skgasgyhdh' );
$meta_query		= mlmFire()->announce->announce_meta_query( $user_id );
$user_announces	= mlmFire()->announce->get_user_announces( $user_id );
$announce_url	= trailingslashit( mlm_page_url('panel') ) . 'section/announce/';
$status			= isset( $_GET['status'] ) ? esc_attr( $_GET['status'] ): '';
$args			= array(
	'post_type' 		=> 'mlm-announce',
	'post_status' 		=> 'publish',
	'posts_per_page'	=> 10,
	'paged'				=> $attributes['page'],
);

if( $meta_query )
{
	$args['meta_query'] = $meta_query;
}

switch( $status )
{
	case 'seen':
		
		if( $user_announces )
		{
			$args['post__in'] = $user_announces;
		}
		else
		{
			$args['post__in'] = array( 0 );
		}
		
		break;
		
	case 'unseen':
		
		if( $user_announces )
		{
			$args['post__not_in'] = $user_announces;
		}
		else
		{
			$args['post__not_in'] = array( 0 );
		}
		
		break;
}

$query		= new WP_Query( $args );
?>

<h3 class="mlm-box-title sm m-0 pt-2"><?php _e( 'Announces', 'mlm' ); ?></h3>
<nav class="mlm-sort-items mb-3 p-0 mx-0 text-secondary bold-300 clearfix">
	<a href="<?php echo $announce_url; ?>" class="text-dark <?php if( empty( $status ) ) echo 'bold-900'; ?>"><?php _e( 'All', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'seen', $announce_url ); ?>" class="text-dark <?php if( $status == 'seen' ) echo 'bold-900'; ?>"><?php _e( 'Seen', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 'unseen', $announce_url ); ?>" class="text-dark <?php if( $status == 'unseen' ) echo 'bold-900'; ?>"><?php _e( 'Unread', 'mlm' ); ?></a>
</nav>
	
<?php if( $query->have_posts() ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-announce-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="lg" scope="col"><?php _e( 'Title', 'mlm' ); ?></th>
					<th class="sm" scope="col"><?php _e( 'Date', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<tr>
						<td>
							<a href="<?php echo $announce_url . 'mid/'. get_the_ID() . '/verify/'.$nonce; ?>" class="title"><?php the_title(); ?></a> 
							<?php if( ! $user_announces || ( is_array( $user_announces ) && ! in_array( get_the_ID(), $user_announces ) ) ) echo '<span class="badge badge-warning">'. __( 'Unread', 'mlm' ) .'</span>'; ?>
						</td>
						<td><?php echo date_i18n( 'Y/m/d', get_the_time( 'U' ) ); ?></td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>