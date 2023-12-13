<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

$ticket_id	= isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
$ticket_obj	= mlmfire()->ticket->get_ticket_data( $ticket_id );
?>

<?php if( isset( $ticket_obj->id ) ): ?>

	<?php
	$user_data	= maybe_unserialize( $ticket_obj->user_data );
	$attaches	= isset( $ticket_obj->attaches ) ? maybe_unserialize( $ticket_obj->attaches ) : array();
	?>

	<div class="panel-box">
		<div class="box-title">
			<h4 class="title"><?php echo $ticket_obj->title; ?></h4>
		</div>
		<div class="box-content">
			<div class="mlm-filter-bar clearfix">
				<a href="#" class="page-title-action" onclick="window.history.back()"><?php _e( 'Return', 'mlm' ); ?></a>
				<a href="#mlm-delete-ticket" class="page-title-action red" data-id="<?php echo $ticket_id; ?>" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'Delete ticket', 'mlm' ); ?></a>
			</div>
			<div class="mlm-ticket-content">
				<div class="ticket-header" id="ticket_item_<?php echo $ticket_id; ?>">
					<div class="user">
						<?php echo get_avatar( $ticket_obj->sender_id, 150, '' ); ?>
						<span><?php echo mlm_get_user_name( $ticket_obj->sender_id, __( 'Guest user', 'mlm' ) ); ?></span>
						<?php if( mlm_post_exists( $ticket_obj->post_id ) ): ?>
							<div class="ticket-related-product d-block text-center my-3 clearfix"><?php _e( 'Related product', 'mlm' ); ?>: <a href="<?php echo get_the_permalink( $ticket_obj->post_id ); ?>"><?php echo mlm_get_post_title( $ticket_obj->post_id, '' ); ?></a></div>
						<?php endif; ?>
					</div>
					<div class="meta">
						<div class="item">
							<?php if( ! mlm_user_exists( $ticket_obj->reciver_id ) && isset( $user_data['unit'] ) ): ?>
								<span class="t d-block text-center"><?php _e( 'Department', 'mlm' ); ?></span>
								<span class="v date"><?php echo $user_data['unit']; ?></span>
							<?php else: ?>
								<span class="t d-block text-center"><?php _e( 'Recipient', 'mlm' ); ?></span>
								<span class="v date"><?php echo mlm_get_user_name( $ticket_obj->reciver_id, __( 'Site support', 'mlm' ) ); ?></span>
							<?php endif; ?>
						</div>
						<div class="item">
							<span class="t"><?php _e( 'Status', 'mlm' ); ?></span>
							<span class="v"><?php echo mlmFire()->ticket->ticket_status( $ticket_obj->status ); ?></span>
						</div>
						<div class="item">
							<span class="t"><?php _e( 'Sent at', 'mlm' ); ?></span>
							<span class="v date"><?php echo date_i18n( get_option('date_format'), strtotime( $ticket_obj->date ) ); ?></span>
						</div>
					</div>
					<div class="desc">
						<?php
						$content = apply_filters( 'the_content', $ticket_obj->content );
						echo stripslashes( $content );
						?>
					</div>
					<div class="attach clearfix">
						<?php if( is_array( $attaches ) && count( $attaches ) ): ?>
							<?php foreach( $attaches as $attach ): ?>
								<a href="<?php echo $attach; ?>" target="_blank" class="button button-primary button-small"><?php _e('View attached file'); ?></a>
							<?php endforeach; ?>
						<?php else: ?>
							<p class="no-attach"><?php _e('No files attached.','mlm'); ?></p>
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
						$attaches	= isset( $reply->attaches ) ? maybe_unserialize( $reply->attaches ) : array();
						?>
						<div class="ticket-reply" id="ticket_item_<?php echo $reply->id; ?>">
							<div class="top-bar">
								<div class="user">
									<?php echo get_avatar( $reply->sender_id, 40, '' ); ?>
									<div class="nm">
										<?php echo mlm_get_user_name( $reply->sender_id, __( 'Guest user', 'mlm' ) ); ?>
										<span class="dt"><?php echo date_i18n( get_option('date_format'), strtotime( $reply->date ) ); ?> - <?php echo date_i18n( get_option('time_format'), strtotime( $reply->date ) ); ?></span>
									</div>
								</div>
								<div class="delete">
									<a href="#mlm-delete-ticket" class="page-title-action red" data-id="<?php echo $reply->id; ?>" data-verify="<?php echo $attributes['nonce']; ?>"><?php _e( 'Delete', 'mlm' ); ?></a>
								</div>
							</div>
							<div class="reply-text">
								<?php
								$contentR	= apply_filters( 'the_content', $reply->content );
								echo stripslashes( $contentR );
								?>
							</div>
							<div class="attach clearfix">
								<?php if( is_array( $attaches ) && count( $attaches ) ): ?>
									<?php foreach( $attaches as $attach ): ?>
										<a href="<?php echo $attach; ?>" target="_blank" class="button button-primary button-small"><?php _e('View attached file'); ?></a>
									<?php endforeach; ?>
								<?php else: ?>
									<p class="no-attach"><?php _e('No files attached.','mlm'); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="mlm-reply-wrapper clear clearfix">
		<form id="mlm_reply_ticket_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<table class="form-table">
				<tbody>
					<tr class="mlm-form-wrap">
						<th><label for="mlm_content"><?php _e( 'Reply content', 'mlm' ); ?><label></th>
						<td>
							<?php
							wp_editor( NULL, 'mlm_content', array(
								'textarea_name'	=> 'mlm_content',
								'media_buttons'	=> true,
								'editor_height'	=> 300,
								'teeny'			=> false,
								'quicktags'		=> false
							) );
							?>
						</td>
					</tr>
					<tr>
						<th><label><?php _e( 'Attach images', 'mlm' ); ?></label></th>
						<td id="mlm-attach-ticket-wrap">
							<div class="thumb-box clearfix"></div>
							<button class="button button-secondary" id="mlm-attach-ticket-image"><?php _e('Attach image', 'mlm'); ?></button>
						</td>
					</tr>
					<tr class="mlm-form-wrap">
						<th><label for="mlm_status"><?php _e( 'Status', 'mlm' ); ?><label></th>
						<td>
							<select name="mlm_status" id="mlm_status" class="regular-text">
								<option value="1"><?php _e( 'Open', 'mlm' ); ?></option>
								<option value="2"><?php _e( 'Ongoing', 'mlm' ); ?></option>
								<option value="3" selected><?php _e( 'Replied', 'mlm' ); ?></option>
								<option value="4"><?php _e( 'Closed', 'mlm' ); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="hidden" name="mlm_parent" id="mlm_parent" value="<?php echo $ticket_id; ?>" />
				<input type="hidden" name="mlm_verify" id="mlm_verify" value="<?php echo $attributes['nonce']; ?>" />
				<button type="submit" class="button button-primary" id="mlm_new_ticket_btn"><?php _e( 'Save', 'mlm' ); ?></button>
				<button type="button" class="button button-secondary" id="mlm_change_ticket_status"><?php _e( 'Change status', 'mlm' ); ?></button>
			</p>
		</form>
	</div>

<?php else: ?>

	<h1 class="wp-heading-inline"><?php _e( 'Not found', 'mlm' ); ?></h1>
	<a href="#" class="page-title-action" onclick="window.history.back()"><?php _e( 'Return', 'mlm' ); ?></a>
	<hr class="wp-header-end">
	<div class="clear clearfix" style="margin-bottom:15px;"></div>
	<div class="mlm_alert alert-danger"><?php _e( 'Invalid ticket ID.', 'mlm' ); ?></div>

<?php endif; ?>