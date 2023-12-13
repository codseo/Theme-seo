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
$withdrawal_url	= trailingslashit( mlm_page_url('panel') ) . 'section/withdrawals/';
$balance		= mlmFire()->wallet->get_balance( $user_id );
$min			= mlmFire()->wallet->min_withdraw_amount();
$per			= 20;
$start			= intval( ($attributes['page'] - 1) * $per );
$results		= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE user_id = %d AND type = %d ORDER BY id DESC LIMIT %d, %d",
	array( $user_id, 5, $start, $per ),
	'wallet'
);

$count_rows		= mlmFire()->db->count_query_rows(
	"SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type = %d",
	array( $user_id, 5 ),
	'wallet'
);

$pending		= mlmFire()->db->query_rows(
	"SELECT id FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d LIMIT %d",
	array( $user_id, 5, 1, 1 ),
	'wallet',
	true
);
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Withdrawals', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_withdraw"><?php _e( 'Withdrawal request', 'mlm' ); ?></a> 
</div>

<div class="alert alert-secondary">
	<p class="m-0"><?php _e( 'your balance', 'mlm' ); ?>: <?php echo mlm_filter( $balance ); ?></p>
	<?php if( $min > $balance ): ?>
		<p class="m-0"><?php _e( 'Minimum amount to withdraw', 'mlm' ); ?>: <?php echo mlm_filter( $min ); ?></p>
	<?php endif; ?>
</div>
				
<?php if( ! empty( $results ) ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-wallet-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'No.', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Type', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Amount', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Description', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Date', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $results as $trans ): ?>
					<tr class="<?php echo mlmFire()->wallet->get_type_class( $trans->type ); ?>">
						<th scope="row"><strong>#<?php echo $trans->id; ?></strong></th>
						<td><?php echo mlmFire()->wallet->get_type_text( $trans->type ); ?></td>
						<td><?php echo mlm_filter( $trans->amount ); ?></td>
						<td>
							<?php echo $trans->description; ?> 
							<?php if( $trans->status != 2 && $trans->status != 4 && $trans->status != 3 ): ?>
								<button class="btn btn-sm btn-warning py-0 font-10 disabled" disabled="disabled" style="line-height: 16px;"><?php echo mlmFire()->wallet->get_status_text( $trans->status ); ?></button>
							<?php endif; ?>
						</td>
						<td>
							<?php echo date_i18n( get_option('date_format'), strtotime( $trans->date ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_db_pagination( $count_rows, $withdrawal_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_withdraw" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Withdrawal request', 'mlm' ); ?></h5>
				<button type="button" class="close ml-0 mr-auto" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php if( $balance >= $min && $balance > 0 ): ?>
					<?php if( ! empty( $pending ) ): ?>
						<div class="alert alert-danger"><?php _e( 'Please wait until your last active withdraw request moderation.', 'mlm' ); ?></div>
					<?php else: ?>
						<?php
						$mlm_card	= get_user_meta( $user_id, 'mlm_card', true );
						$mlm_sheba	= get_user_meta( $user_id, 'mlm_sheba', true );
						$mlm_owner	= get_user_meta( $user_id, 'mlm_owner', true );
						?>
						<form id="mlm_withdraw_form" action="<?php echo $withdrawal_url; ?>" method="post">
							<div class="form-row">
								<div class="form-group col-12 col-sm-6">
									<label for="mlm_amount"><?php _e( 'Amount', 'mlm' ); ?> <i class="text-danger">*</i></label>
									<div class="input-group">
										<input type="number" name="mlm_amount" id="mlm_amount" value="<?php echo $balance; ?>" class="form-control ltr" min="<?php echo $min; ?>" max="<?php echo $balance; ?>">
										<div class="input-group-append">
											<span class="input-group-text font-12"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
										</div>
									</div>
								</div>
								<div class="form-group col-12 col-sm-6">
									<label for="mlm_card"><?php _e( 'Card number', 'mlm' ); ?> <i class="text-danger">*</i></label>
									<input type="text" name="mlm_card" id="mlm_card" class="form-control ltr" value="<?php echo $mlm_card; ?>" placeholder="<?php _e( 'Your card number', 'mlm' ); ?>">
								</div>
								<div class="form-group col-12 col-sm-6">
									<label for="mlm_sheba"><?php _e( 'Sheba code', 'mlm' ); ?> <i class="text-danger">*</i></label>
									<input type="text" name="mlm_sheba" id="mlm_sheba" class="form-control ltr" value="<?php echo $mlm_sheba; ?>" placeholder="<?php _e( 'Your sheba code', 'mlm' ); ?>">
								</div>
								<div class="form-group col-12 col-sm-6">
									<label for="mlm_owner"><?php _e( 'Card owner', 'mlm' ); ?> <i class="text-danger">*</i></label>
									<input type="text" name="mlm_owner" id="mlm_owner" class="form-control" value="<?php echo $mlm_owner; ?>" placeholder="<?php _e( 'Enter the card owner name', 'mlm' ); ?>">
								</div>
							</div>
							<div class="clearfix">
								<?php wp_nonce_field( 'mlm_jaharfetim', 'mlm_security' ); ?>
								<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Submit request', 'mlm' ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				<?php else: ?>
					<div class="alert alert-danger"><?php _e( 'Your balance is not enough to submit a withdrawal request.', 'mlm' ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>