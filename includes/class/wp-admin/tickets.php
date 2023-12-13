<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}
?>

<h1 class="wp-heading-inline"><?php _e( 'Tickets', 'mlm' ); ?></h1>
<a href="<?php echo esc_url( admin_url( 'admin.php?page=mlm-new-ticket' ) ); ?>" class="page-title-action"><?php _e( 'New ticket', 'mlm' ); ?></a>
<hr class="wp-header-end">
<div class="mlm-filter-bar-wrapper clearfix">
	<div class="clearfix">
		<form id="mlm_tickets_search" action="<?php echo esc_url( admin_url('admin.php') ); ?>" method="get">
			<div class="ucdebir clearfix">
				<label for="mlm_user"><?php _e( 'User', 'mlm' ); ?></label>
				<?php wp_dropdown_users( $attributes['args'] ); ?>
			</div>
			<div class="ucdebir clearfix">
				<label for="mlm_status"><?php _e( 'Status', 'mlm' ); ?></label>
				<select name="mlm_status" id="mlm_status" class="regular-text">
					<option value=""><?php _e( 'All', 'mlm' ); ?></option>
					<option value="1" <?php selected( $attributes['status'], 1 ); ?>><?php _e( 'Open', 'mlm' ); ?></option>
					<option value="2" <?php selected( $attributes['status'], 2 ); ?>><?php _e( 'Ongoing', 'mlm' ); ?></option>
					<option value="3" <?php selected( $attributes['status'], 3 ); ?>><?php _e( 'Replied', 'mlm' ); ?></option>
					<option value="4" <?php selected( $attributes['status'], 4 ); ?>><?php _e( 'Closed', 'mlm' ); ?></option>
				</select>
			</div>
			<div class="ucdebir clearfix">
				<input type="hidden" name="page" value="mlm-tickets" />
				<input type="submit" class="button button-primary" value="<?php _e( 'Filter', 'mlm' ); ?>">
			</div>
		</form>
	</div>
</div>

<?php if( ! empty( $attributes['query'] ) ): ?>
	<table class="mlm-table table widefat striped">
		<thead>
			<tr>
				<th><?php _e( 'Title', 'mlm' ); ?> <i class="dashicons dashicons-paperclip"></i></th>
				<th><?php _e( 'Recipient', 'mlm' ); ?> <i class="dashicons dashicons-admin-users"></i></th>
				<th style="width:100px"><?php _e( 'Status', 'mlm' ); ?> <i class="dashicons dashicons-unlock"></i></th>
				<th style="width:100px"><?php _e( 'Last change', 'mlm' ); ?> <i class="dashicons dashicons-hammer"></i></th>
			</tr>
		</thead>
		<tbody id="the-list">
        <?php
        $ticket_array = array();
        foreach( $attributes['query'] as $ticket )
        {
            $last_change	= mlmFire()->ticket->get_last_change( $ticket->id, $ticket->date );
            $ticket_array[$last_change] = $ticket;
        }
        krsort($ticket_array);

        ?>
			<?php foreach( $ticket_array as $ticket ): ?>
				
				<?php
				$user_data		= maybe_unserialize( $ticket->user_data );
				$last_change	= mlmFire()->ticket->get_last_change( $ticket->id, $ticket->date );
				$ticket_url		= admin_url( 'admin.php?page=mlm-tickets&id='.$ticket->id.'&verify='.$attributes['nonce'] );
				?>
				<tr>
					<td>
						<a href="<?php echo esc_url( $ticket_url ); ?>"><?php echo $ticket->title; ?></a>
						<div class="details">
							<span class="contact">
								<?php echo mlm_get_user_name( $ticket->sender_id, __( 'Guest user', 'mlm' ) ); ?>
							</span> - 
							<span class="date">
								<?php echo date_i18n( get_option('date_format'), strtotime( $ticket->date ) ); ?>
							</span>
						</div>
					</td>
					<td>
						<?php if( ! mlm_user_exists( $ticket->reciver_id ) && isset( $user_data['unit'] ) ): ?>
							<?php echo $user_data['unit']; ?>
						<?php else: ?>
							<?php echo mlm_get_user_name( $ticket->reciver_id, __( 'Site support', 'mlm' ) ); ?>
						<?php endif; ?>
					</td>
					<td><?php echo mlmFire()->ticket->ticket_status( $ticket->status ); ?></td>
					<td><?php echo human_time_diff( strtotime( $last_change ), current_time('timestamp') ); ?> <?php _e( 'ago', 'mlm' ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
<?php endif; ?>