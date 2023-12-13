<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

$nonce = wp_create_nonce( 'mlm_with_vaks' );
?>

<h1 class="wp-heading-inline"><?php _e( 'Withdrawals', 'mlm' ); ?></h1>
<hr class="wp-header-end">
<div class="mlm-filter-bar-wrapper clearfix">
	<form id="mlm_wallet_search" action="<?php echo esc_url( admin_url('admin.php') ); ?>" method="get">
		<?php wp_dropdown_users( $attributes['args'] ); ?>
		<input type="hidden" name="page" value="mlm-withdrawals">
		<input type="submit" class="button button-primary" value="<?php _e( 'Filter', 'mlm' ); ?>">
	</form>
</div>
<div class="clear clearfix" style="margin-bottom:15px;"></div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="100"><i class="dashicons dashicons-rss"></i> <?php _e( 'ID', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-users"></i> <?php _e( 'User', 'mlm' ); ?></th>
				<th width="150"><i class="dashicons dashicons-tag"></i> <?php _e( 'Amount', 'mlm' ); ?></th>
				<th width="150"><i class="dashicons dashicons-tags"></i> <?php _e( 'Balance', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-backup"></i> <?php _e( 'Status', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-menu"></i> <?php _e( 'Description', 'mlm' ); ?></th>
				<th width="110"><i class="dashicons dashicons-calendar"></i> <?php _e( 'Date', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $trans ): ?>
				<?php
				$profile		= mlm_get_user_link( $trans->user_id );
				?>
				<tr class="mlm-status-<?php echo $trans->status; ?>">
					<th scope="row">
						<strong>#<?php echo $trans->id; ?></strong>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo admin_url( 'admin.php?page=mlm-withdrawals&id='.$trans->id.'&verify='.$nonce ); ?>"><?php _e( 'Edit', 'mlm' ); ?></a>
							</span>
						</div>
					</th>
					<td>
						<?php if( ! empty( $profile ) ): ?>
							<a href="<?php echo $profile; ?>">
								<?php echo mlm_get_user_name( $trans->user_id ); ?>
							</a>
						<?php else: ?>
							<?php echo mlm_get_user_name( $trans->user_id ); ?>
						<?php endif; ?>
					</td>
					<td><?php echo mlm_filter( $trans->amount ); ?></td>
					<td><?php echo mlm_filter( $trans->balance ); ?></td>
					<td><?php echo mlmFire()->wallet->get_status_text( $trans->status ); ?></td>
					<td><?php echo $trans->description; ?></td>
					<td>
						<?php echo date_i18n( get_option('date_format'), strtotime( $trans->date ) ); ?><br />
						<?php echo date_i18n( get_option('time_format'), strtotime( $trans->date ) ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>