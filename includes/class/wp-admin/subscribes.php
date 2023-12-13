<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

$nonce = wp_create_nonce( 'mlm_subscribe_lex' );
?>

<h1 class="wp-heading-inline"><?php _e( 'VIP plans', 'mlm' ); ?></h1>
<hr class="wp-header-end">
<div class="mlm-filter-bar-wrapper clearfix">
	<form id="mlm_subscribes_search" action="<?php echo esc_url( admin_url('admin.php') ); ?>" method="get">
		<?php wp_dropdown_users( $attributes['args'] ); ?>
		<input type="hidden" name="page" value="mlm-subscribes">
		<input type="submit" class="button button-primary" value="<?php _e( 'Filter', 'mlm' ); ?>">
	</form>
</div>
<div class="clear clearfix" style="margin-bottom:15px;"></div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="120"><i class="dashicons dashicons-rss"></i> <?php _e( 'No.', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-users"></i> <?php _e( 'User', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-menu"></i> <?php _e( 'Plan', 'mlm' ); ?></th>
				<th width="150"><i class="dashicons dashicons-calendar"></i> <?php _e( 'Date', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-backup"></i> <?php _e( 'Status', 'mlm' ); ?></th>
				<th width="100"><?php _e( 'Validity', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $plan ): ?>
				<?php
				$profile	= mlm_get_user_link( $plan->user_id );
				$plan_data	= maybe_unserialize( $plan->plan_data );
				$valid_text	= ( $plan->valid == 1 ) ? __( 'valid', 'mlm' ) : __( 'invalid', 'mlm' );
				?>
				<tr class="mlm-status-<?php echo $plan->status; ?>">
					<th scope="row">
						<strong>#<?php echo $plan->id; ?></strong>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo admin_url( 'admin.php?page=mlm-subscribes&id='.$plan->id.'&verify='.$nonce ); ?>"><?php _e( 'edit', 'mlm' ); ?></a>
							</span>
						</div>
					</th>
					<td>
						<?php if( ! empty( $profile ) ): ?>
							<a href="<?php echo $profile; ?>">
								<?php echo mlm_get_user_name( $plan->user_id ); ?>
							</a>
						<?php else: ?>
							<?php echo mlm_get_user_name( $plan->user_id ); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if( is_array( $plan_data ) && count( $plan_data ) > 0 ): ?>
							<span class="name"><?php echo $plan_data['name']; ?></span>
						<?php else: ?>
							<span class="not-found"><?php _e( 'Plan data not found!', 'mlm' ); ?></span>
						<?php endif; ?>
						<?php if( $plan->type == 3 ): ?>
							<br /><small><i><?php _e( 'Site admin created the plan.', 'mlm' ); ?></i></small>
						<?php endif; ?>
					</td>
					<td>
						<?php echo date_i18n( get_option('date_format'), strtotime( $plan->date ) ); ?><br />
						<?php echo date_i18n( get_option('time_format'), strtotime( $plan->date ) ); ?>
					</td>
					<td><?php echo mlmFire()->plan->get_subscription_status( $plan->status ); ?></td>
					<td><?php echo $valid_text; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>