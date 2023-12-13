<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

check_admin_referer( 'mlm_with_vaks', 'verify' );

if( empty( $attributes['id'] ) || ! isset( $attributes['query']->id ) )
{
	wp_die( __( 'Transaction ID is invalid.', 'mlm' ) );
}

if( isset( $_POST['verify'] ) && wp_verify_nonce( $_POST['verify'], 'mlm_with_vaks' ) )
{
	$mlm_status	= isset( $_POST['mlm_status'] ) ? absint( $_POST['mlm_status'] ) : 1;
	$mlm_desc	= isset( $_POST['mlm_desc'] ) ? esc_attr( $_POST['mlm_desc'] ) : '';
	
	$result		= mlmFire()->db->wallet_update( $attributes['id'], array(
		'status'		=> $mlm_status,
		'description'	=> $mlm_desc,
		'notes'			=> array(
			'card'	=> get_user_meta( $attributes['query']->user_id, 'mlm_card', true ),
			'sheba'	=> get_user_meta( $attributes['query']->user_id, 'mlm_sheba', true ),
			'owner'	=> get_user_meta( $attributes['query']->user_id, 'mlm_owner', true )
		)
	) );
	
	// NOTIFICATION
	if( $result && $mlm_status == 4 && ! empty( $mlm_desc ) )
	{
		mlmFire()->notif->send_user_mail( $attributes['query']->user_id, 'withdrawal_paid', array(
			'amount'	=> $attributes['query']->amount,
			'desc'		=> $mlm_desc
		) );
	}
		
	echo '<div class="notice notice-success is-dismissible">
		<p>'. __( 'Transaction updated.', 'mlm' ) .'</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Close</span></button>
	</div>';
}

$id				= $attributes['query']->id;
$user_id		= $attributes['query']->user_id;
$post_id		= $attributes['query']->post_id;
$order_id		= $attributes['query']->order_id;
$amount			= $attributes['query']->amount;
$balance		= $attributes['query']->balance;
$type			= $attributes['query']->type;
$status			= $attributes['query']->status;
$description	= $attributes['query']->description;
$date			= $attributes['query']->date;
$mlm_status		= isset( $_POST['mlm_status'] ) ? absint( $_POST['mlm_status'] ) : $status;
$mlm_desc		= isset( $_POST['mlm_desc'] ) ? esc_attr( $_POST['mlm_desc'] ) : $description;
$profile		= mlm_get_user_link( $user_id );
$mlm_card		= get_user_meta( $user_id, 'mlm_card', true );
$mlm_sheba		= get_user_meta( $user_id, 'mlm_sheba', true );
$mlm_owner		= get_user_meta( $user_id, 'mlm_owner', true );
?>

<h1 class="wp-heading-inline">
	<?php _e( 'Edit withdrawal', 'mlm' ); ?>
	<a href="javascript:history.go(-1)" class="page-title-action"><?php _e( 'Return', 'mlm' ); ?></a>
</h1>
<hr class="wp-header-end">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'ID', 'mlm' ); ?><label></th>
				<td class="less-padding"><strong>#<?php echo $id; ?></strong></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'User', 'mlm' ); ?><label></th>
				<td class="less-padding">
					<?php if( ! empty( $profile ) ): ?>
						<a href="<?php echo $profile; ?>"><?php echo mlm_get_user_name( $user_id ); ?></a>
					<?php else: ?>
						<?php echo mlm_get_user_name( $user_id ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Amount', 'mlm' ); ?><label></th>
				<td class="less-padding"><?php echo mlm_filter( $amount ); ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Date', 'mlm' ); ?><label></th>
				<td class="less-padding"><?php echo date_i18n( get_option('date_format'), strtotime( $date ) ); ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Card number', 'mlm' ); ?><label></th>
				<td class="less-padding"><?php echo $mlm_card; ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Sheba code', 'mlm' ); ?><label></th>
				<td class="less-padding"><?php echo $mlm_sheba; ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Card owner', 'mlm' ); ?><label></th>
				<td class="less-padding"><?php echo $mlm_owner; ?></td>
			</tr>				
			<tr class="mlm-form-wrap">
				<th><label for="mlm_status"><?php _e( 'Status', 'mlm' ); ?><label></th>
				<td>
					<select name="mlm_status" class="regular-text">
						<option value="1" <?php selected( $mlm_status, 1 ); ?>><?php _e( 'Pending', 'mlm' ); ?></option>
						<option value="4" <?php selected( $mlm_status, 4 ); ?>><?php _e( 'Paid', 'mlm' ); ?></option>
					</select>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_desc"><?php _e( 'Description', 'mlm' ); ?><label></th>
				<td>
					<input type="text" name="mlm_desc" id="mlm_desc" class="regular-text" value="<?php echo $mlm_desc; ?>" required >
					<p class="description"><?php _e( 'Bank payment track number.', 'mlm' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php wp_nonce_field( 'mlm_with_vaks', 'verify' ); ?>
	<button type="submit" class="button button-primary button-large"><?php _e( 'Update', 'mlm' ); ?></button>
</form>