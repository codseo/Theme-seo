<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'moderate_comments' ) )
{
	wp_die('You are not allowed here !');
}

$nonce		= wp_create_nonce('mlm_ygrftafdew');
$all_types	= mlmFire()->wallet->get_types();

unset( $all_types[5] );
?>

<h1 class="wp-heading-inline"><?php _e( 'Transactions', 'mlm' ); ?></h1>
<hr class="wp-header-end">
<div class="mlm-filter-bar-wrapper clearfix">
	<div class="clearfix">
		<form id="mlm_wallet_search" action="<?php echo esc_url( admin_url('admin.php') ); ?>" method="get">
			<div class="ucdebir clearfix">
				<label for="mlm_user"><?php _e( 'User', 'mlm' ); ?></label>
				<?php wp_dropdown_users( $attributes['args'] ); ?>
			</div>
			<div class="ucdebir clearfix">
				<label for="mlm_type"><?php _e( 'Type', 'mlm' ); ?></label>
				<select name="mlm_type" id="mlm_type" class="regular-text">
					<option value=""><?php _e( 'All', 'mlm' ); ?></option>
					<?php foreach( $all_types as $k => $v ): ?>
						<option value="<?php echo $k; ?>" <?php selected( $attributes['type'], $k ); ?>><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="ucdebir clearfix">
				<input type="hidden" name="page" value="mlm-wallet">
				<input type="submit" class="button button-primary" value="<?php _e( 'Filter', 'mlm' ); ?>">
			</div>
		</form>
	</div>
</div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="100"><i class="dashicons dashicons-rss"></i> <?php _e( 'ID', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-users"></i> <?php _e( 'User', 'mlm' ); ?></th>
				<th width="150"><i class="dashicons dashicons-tag"></i> <?php _e( 'Amount', 'mlm' ); ?></th>
				<th width="150"><i class="dashicons dashicons-tags"></i> <?php _e( 'Balance', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-category"></i> <?php _e( 'Type', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-menu"></i> <?php _e( 'Description', 'mlm' ); ?></th>
				<th width="110"><i class="dashicons dashicons-calendar"></i> <?php _e( 'Date', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $trans ): ?>
				<?php
				$profile		= mlm_get_user_link( $trans->user_id );
				?>
				<tr id="trans_item_<?php echo $trans->id; ?>" class="mlm-status-<?php echo $trans->status; ?>">
					<th scope="row">
						<strong>#<?php echo $trans->id; ?></strong>
						<?php if( $trans->order_id && $trans->order_id > 0 ): ?>
							<div class="row-actions">
								<span class="edit">
									<a href="<?php echo esc_url( admin_url( 'post.php?post='.$trans->order_id.'&action=edit' ) ); ?>"><?php _e( 'Details', 'mlm' ); ?></a> | 
								</span>
								<span class="trash">
									<a href="#mlm-delete-transaction" class="mlm-delete-transaction" data-id="<?php echo $trans->id; ?>" data-verify="<?php echo $nonce; ?>"><?php _e( 'Delete', 'mlm' ); ?></a>
								</span>
							</div>
						<?php endif; ?>
					</th>
					<td>
						<?php if( ! empty( $profile ) ): ?>
							<a href="<?php echo $profile; ?>">
								<?php echo mlm_get_user_name( $trans->user_id ); ?>
							</a>
						<?php else: ?>
							<?php echo mlm_get_user_name( $trans->user_id, __( 'Site', 'mlm' ) ); ?>
						<?php endif; ?>
					</td>
					<td><?php echo mlm_filter( $trans->amount ); ?></td>
					<td><?php echo mlm_filter( $trans->balance ); ?></td>
					<td><?php echo mlmFire()->wallet->get_type_text( $trans->type ); ?></td>
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