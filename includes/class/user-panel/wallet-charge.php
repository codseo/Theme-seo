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
$wallet_url	= trailingslashit( mlm_page_url('panel') ) . 'section/wallet/';
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$results	= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE user_id = %d AND ( type = %d || type = %d ) ORDER BY id DESC LIMIT %d, %d",
	array( $user_id, 6, 7, $start, $per ),
	'wallet'
);

$count_rows	= mlmFire()->db->count_query_rows(
	"SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND ( type = %d || type = %d )",
	array( $user_id, 6, 7 ),
	'wallet'
);
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Wallet charge & discharges', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_charge"><?php _e( 'Charge wallet', 'mlm' ); ?></a> 
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
								<button class="btn btn-sm btn-warning py-0 disabled" disabled="disabled" style="line-height: 16px;"><?php echo mlmFire()->wallet->get_status_text( $trans->status ); ?></button>
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
	
	<?php mlm_db_pagination( $count_rows, $wallet_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_charge" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Charge wallet', 'mlm' ); ?></h5>
				<button type="button" class="close ml-0 mr-auto" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="mlm_increase_form" action="<?php echo $wallet_url; ?>" method="post">
					<div class="form-group">
						<label for="mlm_amount"><?php _e( 'Amount', 'mlm' ); ?> <i class="required">*</i></label>
						<div class="input-group">
							<input type="number" name="mlm_amount" id="mlm_amount" class="form-control ltr" >
							<div class="input-group-append">
								<span class="input-group-text font-12"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
							</div>
						</div>
					</div>
					<div class="clearfix">
						<?php wp_nonce_field( 'mlm_ioytdagud', 'mlm_security' ); ?>
						<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Online payment', 'mlm' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php if( isset( $_GET['forced'] ) && $_GET['forced'] == 'charge' ): ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#mlm_charge').modal({ show: true });
		});
	</script>
<?php endif; ?>