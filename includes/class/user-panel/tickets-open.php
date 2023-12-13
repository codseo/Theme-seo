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
$ticket_url	= trailingslashit( mlm_page_url('panel') ) . 'section/tickets-all/';
$ticket_id	= $attributes['mid'];
$ticket		= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE id = %d AND parent_id = %d AND ( sender_id = %d OR reciver_id = %d ) LIMIT %d",
	array( $ticket_id, 0, $user_id, $user_id, 1 ),
	'ticket',
	true
);
?>

<?php if( $ticket ): ?>

	<?php
	$post_id		= absint( $ticket->post_id );
	$sender_id		= absint( $ticket->sender_id );
	$reciver_id		= absint( $ticket->reciver_id );
	$title			= esc_attr( $ticket->title );
	$content		= apply_filters( 'the_content', $ticket->content );
	$status			= absint( $ticket->status );
	$date			= esc_attr( $ticket->date );
	$user_data		= maybe_unserialize( $ticket->user_data );
	$attaches		= isset( $ticket->attaches ) ? maybe_unserialize( $ticket->attaches ) : array();
	$sender_name	= mlm_get_user_name( $sender_id, __( 'Guest user', 'mlm' ) );
	?>
	<h3 class="mlm-box-title sm mb-2 py-2"><?php echo $title; ?></h3>
	<div class="mlm-filter-bar mb-3 p-0 clearfix">
		<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" onclick="window.history.back()"><?php _e( 'Return', 'mlm' ); ?></a>
		<a href="#" class="btn btn-success btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_new_ticket"><?php _e( 'Reply ticket', 'mlm' ); ?></a>
	</div>
	<div class="mlm-ticket-content">
		<div class="ticket-header mb-4">
			<div class="mlm-user-widget mb-3">
				<div class="user-avatar text-center">
					<?php echo get_avatar( $sender_id, 140, '' , $sender_name, array( 'class' => 'transition' ) ); ?>
				</div>
				<h4 class="user-name text-center p-0 m-0"><?php echo $sender_name; ?></h4>
				<?php if( mlm_post_exists( $post_id ) ): ?>
					<div class="ticket-related-product my-3 text-center clearfix">
						<?php _e( 'Related product', 'mlm' ); ?>: <a href="<?php echo get_the_permalink( $post_id ); ?>" class="text-success"><?php echo mlm_get_post_title( $post_id, '' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
			<div class="meta mb-3 p-2 clearfix">
				<div class="row">
					<div class="col-4">
						<?php if( ! mlm_user_exists( $reciver_id ) && isset( $user_data['unit'] ) ): ?>
							<span class="t d-block text-center"><?php _e( 'Department', 'mlm' ); ?></span>
							<span class="v d-block text-center date"><?php echo $user_data['unit']; ?></span>
						<?php else: ?>
							<span class="t d-block text-center"><?php _e( 'Recipient', 'mlm' ); ?></span>
							<span class="v d-block text-center date"><?php echo mlm_get_user_name( $reciver_id, __( 'Site support', 'mlm' ) ); ?></span>
						<?php endif; ?>
					</div>
					<div class="col-4">
						<span class="t d-block text-center"><?php _e( 'Status', 'mlm' ); ?></span>
						<span class="v d-block text-center"><?php echo mlmFire()->ticket->ticket_status( $status ); ?></span>
					</div>
					<div class="col-4">
						<span class="t d-block text-center"><?php _e( 'Sent at', 'mlm' ); ?></span>
						<span class="v d-block text-center date"><?php echo date_i18n( 'j F Y', strtotime( $date ) ); ?></span>
					</div>
				</div>
			</div>
			<div class="desc text-justify">
				<?php echo stripslashes( $content ); ?>
			</div>
			<div class="attach p-2 bg-light border clearfix">
				<?php if( is_array( $attaches ) && count( $attaches ) ): ?>
					<?php foreach( $attaches as $attach ): ?>
						<a href="<?php echo $attach; ?>" target="_blank" class="btn btn-secondary py-0 px-1 ml-2 my-2"><?php _e('View attached file'); ?></a>
					<?php endforeach; ?>
				<?php else: ?>
					<span class="no-attach"><?php _e('No files attached.','mlm'); ?></span>
				<?php endif; ?>
			</div>
		</div>

		<?php
		$replies	= mlmFire()->db->query_rows(
			"SELECT * FROM {TABLE} WHERE parent_id = %d ORDER BY id ASC",
			array( $ticket_id ),
			'ticket'
		);
		?>

		<?php if( ! empty( $replies ) ): ?>
			<?php foreach( $replies as $reply ): ?>
				<?php
				$senderId	= absint( $reply->sender_id );
				$contentR	= apply_filters( 'the_content', $reply->content );
				$dateR		= esc_attr( $reply->date );
				$senderName	= mlm_get_user_name( $senderId, __( 'Guest user', 'mlm' ) );
				$attaches	= isset( $reply->attaches ) ? maybe_unserialize( $reply->attaches ) : array();
				?>
				<div class="ticket-reply mb-4">
					<div class="top-bar py-1 px-3 rounded clearfix">
						<div class="user float-right">
							<?php echo get_avatar( $senderId, 40, '' , $senderName ); ?>
							<span class="text-white"><?php echo $senderName; ?></span>
						</div>
						<div class="date float-left">
							<span class="text-white"><?php echo date_i18n( get_option('date_format'), strtotime( $dateR ) ); ?> - <?php echo date_i18n( get_option('time_format'), strtotime( $dateR ) ); ?></span>
						</div>
					</div>
					<div class="reply-text p-2 text-justify">
						<?php echo stripslashes( $contentR ); ?>
					</div>
					<div class="attach p-2 bg-light border clearfix">
						<?php if( is_array( $attaches ) && count( $attaches ) ): ?>
							<?php foreach( $attaches as $attach ): ?>
								<a href="<?php echo $attach; ?>" target="_blank" class="btn btn-secondary py-0 px-1 ml-2 my-2"><?php _e('View attached file'); ?></a>
							<?php endforeach; ?>
						<?php else: ?>
							<span class="no-attach"><?php _e('No files attached.','mlm'); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div class="modal fade" id="mlm_new_ticket" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?php _e( 'Reply ticket', 'mlm' ); ?></h5>
					<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="mlm_reply_ticket_form" action="<?php echo $ticket_url; ?>" method="post">
						<div class="form-group">
							<?php
							wp_editor( NULL, 'mlm_content', array(
								'textarea_name'	=> 'mlm_content',
								'media_buttons'	=> true,
								'editor_height'	=> 300,
								'teeny'			=> true,
								'quicktags'		=> false
							) );
							?>
						</div>
						<?php if( current_user_can( 'upload_files' ) ): ?>
							<div class="mlm-attach-field-wrap form-group state-1 state-2 gzl">
								<div class="ticket-attaches-placeholder clearfix">
									<span class="placeholder"><?php _e('Ticket attaches', 'mlm'); ?></span>
								</div>
								<div class="mlm-attach-upload-holder">
									<input type="file" class="upload-toggle" data-verify="<?php echo wp_create_nonce('mlm_asdkugfas'); ?>">
									<button class="btn btn-secondary btn-block" type="button"><?php _e( 'Attach file', 'mlm' ); ?></button>
								</div>
								<div class="mlm-attach-upload-progress">
									<div class="progress">
										<div class="progress-bar bg-success" aria-valuemin="0" aria-valuemax="100" width="0%"></div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if( current_user_can('read_private_pages') ): ?>
							<div class="form-group">
								<label for="mlm_status"><?php _e( 'Status', 'mlm' ); ?> <i class="text-danger">*</i></label>
								<select name="mlm_status" id="mlm_status" class="form-control">
									<option value="1"><?php _e( 'Open', 'mlm' ); ?></option>
									<option value="2"><?php _e( 'Ongoing', 'mlm' ); ?></option>
									<option value="3" selected><?php _e( 'Replied', 'mlm' ); ?></option>
									<option value="4"><?php _e( 'Closed', 'mlm' ); ?></option>
								</select>
							</div>
						<?php else: ?>
							<input type="hidden" name="mlm_status" id="mlm_status" value="1">
						<?php endif; ?>
						<div class="form-group">
							<input type="hidden" name="mlm_parent" id="mlm_parent" value="<?php echo $ticket_id; ?>">
							<?php wp_nonce_field( 'mlm_ticket_repqpa', 'mlm_security' ); ?>
							<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Send', 'mlm' ); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>

	<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Ticket details', 'mlm' ); ?></h3>
	<div class="alert alert-danger"><?php _e( 'Ticket ID is invalid.', 'mlm' ); ?></div>

<?php endif; ?>