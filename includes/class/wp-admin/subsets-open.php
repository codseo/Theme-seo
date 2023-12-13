<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

if( ! mlm_user_exists( $attributes['id'] ) )
{
	wp_die( __( 'User ID is invalid.', 'mlm' ) );
}
?>

<h1 class="wp-heading-inline">
	<?php printf( __( '%s subsets', 'mlm' ), mlm_get_user_name( $attributes['id'] ) ); ?>
	<a href="javascript:history.go(-1)" class="page-title-action"><?php _e( 'Return', 'mlm' ); ?></a>
</h1>
<hr class="wp-header-end">
<div class="clear clearfix" style="margin-bottom:15px;"></div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="70"><?php _e( 'Image', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-businessman"></i> <?php _e( 'Name', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-sticky"></i> <?php _e( 'Ref count', 'mlm' ); ?></th>
				<th width="120"><i class="dashicons dashicons-groups"></i> <?php _e( 'Subsets', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-admin-site-alt3"></i> <?php _e( 'Ref code', 'mlm' ); ?></th>
				<th width="140"><i class="dashicons dashicons-portfolio"></i> <?php _e( 'Balance', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $item ): ?>
				<tr class="mlm-user-<?php echo $item->user_id; ?>">
					<th scope="row"><?php echo get_avatar( $item->user_id, 48 ); ?></th>
					<td><?php echo mlm_get_user_name( $item->user_id ); ?></td>
					<td><?php echo mlmFire()->referral->get_refs_count( $item->user_id ); ?></td>
					<td>
						<?php echo mlmFire()->network->get_subs_count( $item->user_id ); ?>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo admin_url( 'admin.php?page=mlm-network&mlm_user='. $item->user_id ); ?>"><?php _e( 'View', 'mlm' ); ?></a>
							</span>
						</div>
					</td>
					<td><?php echo mlmFire()->referral->generate_ref_code( $item->user_id ); ?></td>
					<td><?php echo mlm_filter( mlmFire()->wallet->get_balance( $item->user_id ) ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>