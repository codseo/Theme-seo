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

<h1 class="wp-heading-inline"><?php _e( 'Subsets', 'mlm' ); ?></h1>
<hr class="wp-header-end">
<div class="clear clearfix" style="margin-bottom:15px;"></div>

<?php if( ! empty( $attributes['query'] ) ): ?>

	<table class="mlm-table wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th width="70"><?php _e( 'Image', 'mlm' ); ?></th>
				<th><i class="dashicons dashicons-businessman"></i> <?php _e( 'Name', 'mlm' ); ?></th>
				<th width="180"><i class="dashicons dashicons-paperclip"></i> <?php _e( 'Email', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-sticky"></i> <?php _e( 'Ref count', 'mlm' ); ?></th>
				<th width="120"><i class="dashicons dashicons-groups"></i> <?php _e( 'Subsets', 'mlm' ); ?></th>
				<th width="100"><i class="dashicons dashicons-admin-site-alt3"></i> <?php _e( 'Ref code', 'mlm' ); ?></th>
				<th width="140"><i class="dashicons dashicons-portfolio"></i> <?php _e( 'Balance', 'mlm' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach( $attributes['query'] as $user ): ?>
				<tr class="mlm-user-<?php echo $user->ID; ?>">
					<th scope="row"><?php echo get_avatar( $user->ID, 48, '' , $user->display_name ); ?></th>
					<td><?php echo $user->display_name; ?></td>
					<td><?php echo $user->user_email; ?></td>
					<td><?php echo mlmFire()->referral->get_refs_count( $user->ID ); ?></td>
					<td>
						<?php echo mlmFire()->network->get_subs_count( $user->ID ); ?>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo admin_url( 'admin.php?page=mlm-network&mlm_user='. $user->ID ); ?>"><?php _e( 'View', 'mlm' ); ?></a>
							</span>
						</div>
					</td>
					<td><?php echo mlmFire()->referral->generate_ref_code( $user->ID ); ?></td>
					<td><?php echo mlm_filter( mlmFire()->wallet->get_balance( $user->ID ) ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else: ?>

	<div class="mlm_alert alert-danger"><?php _e( 'No items found.', 'mlm' ); ?></div>
	
<?php endif; ?>