<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}
?>

<h1 class="wp-heading-inline"><?php _e( 'Referral links', 'mlm' ); ?></h1>
<hr class="wp-header-end">
<div class="mlm-filter-bar-wrapper clearfix">
	<form id="mlm_referral_search" action="<?php echo esc_url( admin_url('admin.php') ); ?>" method="get">
		<?php wp_dropdown_users( $attributes['args'] ); ?>
		<input type="hidden" name="page" value="mlm-referral">
		<input type="submit" class="button button-primary" value="<?php _e( 'Filter', 'mlm' ); ?>">
	</form>
</div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="100"><i class="dashicons dashicons-rss"></i> <?php _e( 'No.', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-businessman"></i> <?php _e( 'Referrer', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-site"></i> <?php _e( 'Source', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-links"></i> <?php _e( 'Landing', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-admin-users"></i> <?php _e( 'User', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-location-alt"></i> <?php _e( 'IP', 'mlm' ); ?></th>
				<th width="110"><i class="dashicons dashicons-calendar"></i> <?php _e( 'Date', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $entry ): ?>
				<?php
				$id				= $entry->id;
				$ref_user_id	= $entry->ref_user_id; // Link owner
				$user_id		= $entry->user_id; // Clicker
				$user_ip		= $entry->user_ip;
				$user_url		= $entry->user_url;
				$user_host		= empty( $entry->user_host ) ? __( 'direct link', 'mlm' ) : $entry->user_host;
				$invalid		= $entry->invalid;
				$purchase		= $entry->purchase;
				$date			= $entry->date;
				$readable_url	= urldecode( $user_url );
				$user_name		= mlm_get_user_name( $ref_user_id, __( 'Unknown', 'mlm' ) );
				$profile		= mlm_get_user_link( $ref_user_id );
				$clicker_name	= mlm_get_user_name( $user_id, __( 'Guest', 'mlm' ) );
				$clicker_url	= mlm_get_user_link( $user_id );

				if( $invalid )
				{
					$class = 'invalid';
				}
				else
				{
					$class = 'valid';
				}
				?>
				<tr class="<?php echo $class; ?>">
					<th scope="row">
						<strong>#<?php echo $id; ?></strong>
					</th>
					<td>
						<?php if( ! empty( $profile ) ): ?>
							<a href="<?php echo $profile; ?>">
								<?php echo $user_name; ?>
							</a>
						<?php else: ?>
							<?php echo $user_name; ?>
						<?php endif; ?>
					</td>
					<td><?php echo $user_host; ?></td>
					<td>
						<a target="_blank" href="<?php echo $user_url; ?>">
							<?php echo $readable_url; ?>
						</a>
					</td>
					<td>
						<?php if( ! empty( $clicker_url ) ): ?>
							<a href="<?php echo $clicker_url; ?>">
								<?php echo $clicker_name; ?>
							</a>
						<?php else: ?>
							<?php echo $clicker_name; ?>
						<?php endif; ?>
					</td>
					<td><?php echo $user_ip; ?></td>
					<td>
						<?php echo date_i18n( 'j F Y', strtotime( $date ) ); ?><br />
						<?php echo date_i18n( 'H:i', strtotime( $date ) ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>