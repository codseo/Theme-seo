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
$user_data		= get_userdata( $user_id );
$user_login		= $user_data->user_login;
$comment_url	= trailingslashit( mlm_page_url('panel') ) . 'section/comments-all/';
$paged			= $attributes['page'];
$per_page		= 15;
$offset			= ( $paged - 1 ) * $per_page;
$comments_query	= new WP_Comment_Query;
$posts_array	= array();

$all_query	= new WP_Query( array(
	'post_type' 		=> array( 'post', 'product' ),
	'post_status'		=> array( 'publish', 'pending' ),
	'author'			=> $user_id,
	'posts_per_page'	=> 200
) );

if( $all_query->have_posts() )
{
	while( $all_query->have_posts() )
	{
		$all_query->the_post();
		array_push( $posts_array, get_the_ID() );
	}
	
	wp_reset_postdata();
}

if( count( $posts_array ) < 1 )
{
	$comments	= '';
}
else
{
	$comments	= $comments_query->query( array(
		'post__in'	=> $posts_array,
		'number'	=> $per_page,
		'offset'	=> $offset,
	) );
	$pagination	= $comments_query->query( array(
		'post__in'	=> $posts_array
	) );
}
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'All comments', 'mlm' ); ?></h3>
		
<?php if( empty( $comments ) ): ?>

	<div class="alert alert-warning"><?php _e( 'No comments found for your posts nor products.', 'mlm' ); ?></div>
	
<?php else: ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-comments-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="md" scope="col"><?php _e( 'Author', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Comment', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $comments as $comment ): ?>
					<?php
					$comment_ID				= isset($comment->comment_ID) ? $comment->comment_ID : '';
					$comment_post_ID		= isset($comment->comment_post_ID) ? $comment->comment_post_ID : '';
					$comment_author			= isset($comment->comment_author) ? $comment->comment_author : '';
					$comment_author_email	= isset($comment->comment_author_email) ? $comment->comment_author_email : '';
					$comment_author_url		= isset($comment->comment_author_url) ? $comment->comment_author_url : '';
					$comment_date			= isset($comment->comment_date) ? $comment->comment_date : '';
					$comment_content		= isset($comment->comment_content) ? $comment->comment_content : '';
					$comment_approved		= isset($comment->comment_approved) ? $comment->comment_approved : '';
					$class					= ( $comment_approved ) ? 'approved' : 'table-warning';
					?>
					<tr id="mlm-review-<?php echo $comment_ID; ?>" class="<?php echo $class; ?>">
						<th class="col-author" scope="row">
							<div class="avatar"><?php echo get_avatar( $comment_author_email, 32 ); ?></div>
							<div class="name font-13"><?php echo $comment_author; ?></div>
							<div class="email font-12 text-secondary"><?php echo $comment_author_email; ?></div>
						</th>
						<td class="col-content">
							<div class="clearfix">
								<div class="font-11 text-secondary">
									<?php echo date_i18n( get_option( 'date_format' ), strtotime( $comment_date ) ); ?>
								</div>
								<div class="text-justify font-12 clearfix"><?php echo $comment_content; ?></div>
								<?php if( $comment_approved ): ?>
									<a class="btn btn-light btn-sm py-0 font-10 mt-2" href="<?php echo get_comment_link( $comment_ID ) ?>"><?php _e( 'View comment', 'mlm' ); ?></a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_db_pagination( count( $pagination ), $comment_url, $per_page, $paged ); ?>

<?php endif; ?>