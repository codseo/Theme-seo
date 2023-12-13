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
$submit_url	= trailingslashit( mlm_page_url('panel') ) . 'section/tickets-new/';
$nonce		= wp_create_nonce( 'mlm_ticket_setul' );
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$status		= isset( $_GET['status'] ) ? absint( $_GET['status'] ) : '';

if( is_numeric( $status ) )
{
	$result		= mlmFire()->db->query_rows(
		"SELECT * FROM {TABLE} WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d ) AND status = %d ORDER BY id DESC LIMIT %d, %d",
		array( 0, $user_id, $user_id, $status, $start, $per ),
		'ticket'
	);

	$count_rows	= mlmFire()->db->count_query_rows(
		"SELECT COUNT(id) FROM {TABLE} WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d ) AND status = %d",
		array( 0, $user_id, $user_id, $status ),
		'ticket'
	);
}
else
{
	$result		= mlmFire()->db->query_rows(
		"SELECT * FROM {TABLE} WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d ) ORDER BY id DESC LIMIT %d, %d",
		array( 0, $user_id, $user_id, $start, $per ),
		'ticket'
	);

	$count_rows	= mlmFire()->db->count_query_rows(
		"SELECT COUNT(id) FROM {TABLE} WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d )",
		array( 0, $user_id, $user_id ),
		'ticket'
	);
}
?>

<h3 class="mlm-box-title sm m-0 pt-2"><?php _e( 'Tickets', 'mlm' ); ?></h3>
<nav class="mlm-sort-items mb-3 p-0 mx-0 text-secondary bold-300 clearfix">
	<a href="<?php echo $ticket_url; ?>" class="text-dark <?php if( empty( $status ) ) echo 'bold-900'; ?>"><?php _e( 'All', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 1, $ticket_url ); ?>" class="text-dark <?php if( $status == 1 ) echo 'bold-900'; ?>"><?php _e( 'Open', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 2, $ticket_url ); ?>" class="text-dark <?php if( $status == 2 ) echo 'bold-900'; ?>"><?php _e( 'Ongoing', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 3, $ticket_url ); ?>" class="text-dark <?php if( $status == 3 ) echo 'bold-900'; ?>"><?php _e( 'Replied', 'mlm' ); ?></a>
	<i class="d-inline-block divider px-1">/</i>
	<a href="<?php echo add_query_arg( 'status', 4, $ticket_url ); ?>" class="text-dark <?php if( $status == 4 ) echo 'bold-900'; ?>"><?php _e( 'Closed', 'mlm' ); ?></a>
</nav>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="<?php echo $submit_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'New ticket', 'mlm' ); ?></a>
</div>

<?php if( ! empty( $result ) ): ?>
	<div class="table-responsive">
        <?php
        $ticket_array = array();
        foreach( $result as $ticket )
        {
            $last_change	= mlmFire()->ticket->get_last_change( $ticket->id, $ticket->date );
            $ticket_array[$last_change] = $ticket;
        }
        krsort($ticket_array);
        ?>
		<table class="mlm-table mlm-ticket-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="lg" scope="col"><?php _e( 'Title', 'mlm' ); ?></th>
					<th class="sm" scope="col"><?php _e( 'Status', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Last change', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $ticket_array as $ticket ): ?>
					<?php
					$last_change	= mlmFire()->ticket->get_last_change( $ticket->id, $ticket->date );
					?>
					<tr>
						<th scope="row">
							<a class="title" href="<?php echo $ticket_url . 'mid/'. $ticket->id . '/verify/'.$nonce; ?>"><?php echo $ticket->title; ?></a>
							<div class="details mt-1 clearfix">
								<span class="contact ml-3 d-inline-block">
									<i class="icon-user d-inline-block"></i> 
									<?php echo mlm_get_user_name( $ticket->sender_id, __( 'Guest user', 'mlm' ) ); ?>
								</span>
								<span class="date d-inline-block">
									<i class="icon-calendar d-inline-block"></i>  
									<?php echo date_i18n( 'j F Y', strtotime( $ticket->date ) ); ?>
								</span>
							</div>
						</th>
						<td>
                            <?php echo $last_change; ?>
							<div class="mb-1"><?php echo mlmFire()->ticket->ticket_status( $ticket->status ); ?></div>
							<a href="<?php echo $ticket_url . 'mid/'. $ticket->id . '/verify/'.$nonce; ?>" class="btn btn-sm btn-light btn-block py-0 font-11 rounded-pill"><?php _e( 'View', 'mlm' ); ?></a>
						</td>
						<td><?php echo human_time_diff( strtotime( $last_change ), current_time('timestamp') ) . ' ' . __( 'ago', 'mlm' ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php mlm_db_pagination( $count_rows, $ticket_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>
	<div class="alert alert-warning"><?php _e( 'No tickets submitted yet.', 'mlm' ); ?></div>
<?php endif; ?>