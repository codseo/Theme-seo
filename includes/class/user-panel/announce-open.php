<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$post_id		= $attributes['mid'];
$user_id		= get_current_user_id();
$user_access	= mlmFire()->announce->check_user_access( $user_id, $post_id );
$announce_url	= trailingslashit( mlm_page_url('panel') ) . 'section/announce/';
?>

<?php if( ! $user_access || ! wp_verify_nonce( $attributes['verify'], 'mlm_skgasgyhdh' ) ): ?>

	<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Announces', 'mlm' ); ?></h3>

	<div class="mlm-filter-bar mb-3 p-0 clearfix">
		<a href="<?php echo $announce_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All announces', 'mlm' ); ?></a> 
	</div>

	<div class="alert alert-danger"><?php _e( 'You are not allowed here.', 'mlm' ); ?></div>
	
<?php else: ?>
	
	<h3 class="mlm-box-title sm mb-2 py-2"><?php echo get_the_title( $post_id ); ?></h3>

	<div class="mlm-filter-bar mb-3 p-0 clearfix">
		<a href="<?php echo $announce_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'All announces', 'mlm' ); ?></a> 
	</div>
	
	<div class="content text-justify px-3">
		<?php
		$post_content = get_post_field( 'post_content', $post_id );
		echo apply_filters( 'the_content', $post_content );
		?>
	</div>
	
	<?php mlmFire()->announce->announce_seen_by_user( $user_id, $post_id ); ?>
	
<?php endif; ?>