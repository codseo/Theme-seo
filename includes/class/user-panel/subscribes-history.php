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
$plans_url	= trailingslashit( mlm_page_url('panel') ) . 'section/subscribe-history/';
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$results	= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE user_id = %d ORDER BY id DESC LIMIT %d, %d",
	array( $user_id, $start, $per ),
	'subscribe'
);

$count_rows	= mlmFire()->db->count_query_rows(
	"SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d",
	array( $user_id ),
	'subscribe'
);
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Plans purchase history', 'mlm' ); ?></h3>

<?php if( ! empty( $results ) ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-subscribe-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'No.', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Plan', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Date', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Status', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $results as $subscribe ): ?>
					<?php
					$plan_data	= maybe_unserialize( $subscribe->plan_data );
					?>
					<tr class="table-<?php echo mlmFire()->plan->get_subscription_status_class( $subscribe->status ); ?>">
						<th scope="row"><strong>#<?php echo $subscribe->id; ?></strong></th>
						<td>
							<?php if( is_array( $plan_data ) && count( $plan_data ) > 0 ): ?>
								<span class="name"><?php echo $plan_data['name']; ?></span>
							<?php else: ?>
								<span class="not-found"><?php _e( 'Plan data not found!', 'mlm' ); ?></span>
							<?php endif; ?>
						</td>
						<td><?php echo date_i18n( 'j F Y', strtotime( $subscribe->date ) ); ?></td>
						<td><?php echo mlmFire()->plan->get_subscription_status( $subscribe->status ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_db_pagination( $count_rows, $plans_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'You have purchased no plans yet.', 'mlm' ); ?></div>
	
<?php endif; ?>