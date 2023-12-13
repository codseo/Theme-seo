<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'manage_options' ) )
{
	wp_die('You are not allowed here !');
}

check_admin_referer( 'mlm_subscribe_lex', 'verify' );

if( empty( $attributes['id'] ) || ! isset( $attributes['query']->id ) )
{
	wp_die( __( 'Plan ID is invalid.', 'mlm' ) );
}

$plan_data		= maybe_unserialize( $attributes['query']->plan_data );
$mlm_status		= isset( $_POST['mlm_status'] ) ? absint( $_POST['mlm_status'] ) : $attributes['query']->status;
$mlm_order		= isset( $_POST['mlm_order'] ) ? absint( $_POST['mlm_order'] ) : $attributes['query']->order_id;
$mlm_expire		= isset( $_POST['mlm_expire'] ) ? esc_attr( $_POST['mlm_expire'] ) : '';

if( isset( $_POST['verify'] ) && wp_verify_nonce( $_POST['verify'], 'mlm_subscribe_lex' ) )
{
	$expire_flag	= false;
	
	if( ! empty( $mlm_expire ) )
	{
		list( $syear, $smonth, $sday ) = explode( '-', $mlm_expire );
		$timestamp		= mlm_jmktime( '23', '59', '59', $smonth, $sday, $syear );
		$mlm_expire		= date( 'Y-m-d H:i:s', $timestamp );
		
		if( $timestamp > time() )
		{
			$expire_flag	= true;
		}
	}
	
	$result			= mlmFire()->db->subscribe_update( $attributes['id'], array(
		'status'	=> $mlm_status,
		'order_id'	=> $mlm_order,
		'expire'	=> $mlm_expire
	) );
	
	if( $mlm_status == 1 && $expire_flag )
	{
		mlmFire()->plan->set_user_active_plan( $attributes['id'], $attributes['query']->user_id, $plan_data['id'] );
	}
	elseif( $mlm_status != 1 || ! $expire_flag )
	{
		mlmFire()->plan->delete_user_active_plan( $attributes['query']->user_id, $attributes['id'] );
	}
	
	echo '<div class="notice notice-success is-dismissible">
		<p>'. __( 'Plan details updated.', 'mlm' ) .'</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Close</span></button>
	</div>';
}

if( empty( $mlm_expire ) )
{
	$mlm_expire	= $attributes['query']->expire;
}

$id				= $attributes['query']->id;
$user_id		= $attributes['query']->user_id;
$type			= $attributes['query']->type;
$valid			= $attributes['query']->valid;
$user_data		= maybe_unserialize( $attributes['query']->user_data );
$date			= $attributes['query']->date;
$expire			= $attributes['query']->expire;
$valid_text		= ( $valid == 1 ) ? __( 'valid', 'mlm' ) : __( 'invalid', 'mlm' );
$profile		= mlm_get_user_link( $user_id );
?>

<h1 class="wp-heading-inline">
	<?php _e( 'Edit subscription', 'mlm' ); ?> 
	<a href="javascript:history.go(-1)" class="page-title-action"><?php _e( 'Return', 'mlm' ); ?></a>
</h1>
<hr class="wp-header-end">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'ID', 'mlm' ); ?></label></th>
				<td class="less-padding"><strong>#<?php echo $id; ?></strong></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'User', 'mlm' ); ?></label></th>
				<td class="less-padding">
					<?php if( ! empty( $profile ) ): ?>
						<a href="<?php echo $profile; ?>"><?php echo mlm_get_user_name( $user_id ); ?></a>
					<?php else: ?>
						<?php echo mlm_get_user_name( $user_id ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Plan', 'mlm' ); ?></label></th>
				<td class="less-padding">
					<?php if( is_array( $plan_data ) && count( $plan_data ) > 0 ): ?>
						<span class="name"><?php echo $plan_data['name']; ?></span>
					<?php else: ?>
						<span class="not-found"><?php _e( 'Plan data not found!', 'mlm' ); ?></span>
					<?php endif; ?>
					<?php if( $type == 3 ): ?>
						<br /><small><i><?php _e( 'Site admin created the plan.', 'mlm' ); ?></i></small>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Validity', 'mlm' ); ?></label></th>
				<td class="less-padding"><?php echo $valid_text; ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th class="less-padding"><label><?php _e( 'Purchase date', 'mlm' ); ?></label></th>
				<td class="less-padding"><?php echo date_i18n( get_option('date_format'), strtotime( $date ) ); ?></td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_expire"><?php _e( 'Expire date', 'mlm' ); ?></label></th>
				<td>
					<input type="text" name="mlm_expire" id="mlm_expire" class="regular-text mlm-datepicker" value="<?php echo date_i18n( get_option('date_format'), strtotime( $mlm_expire ) ); ?>">
				</td>
			</tr>			
			<tr class="mlm-form-wrap">
				<th><label for="mlm_status"><?php _e( 'Payment status', 'mlm' ); ?></label></th>
				<td>
					<select name="mlm_status" class="regular-text">
						<option value="0" <?php selected( $mlm_status, 0 ); ?>><?php echo mlmFire()->plan->get_subscription_status( 0 ); ?></option>
						<option value="1" <?php selected( $mlm_status, 1 ); ?>><?php echo mlmFire()->plan->get_subscription_status( 1 ); ?></option>
						<option value="2" <?php selected( $mlm_status, 2 ); ?>><?php echo mlmFire()->plan->get_subscription_status( 2 ); ?></option>
					</select>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_order"><?php _e( 'Payment number', 'mlm' ); ?></label></th>
				<td>
					<input type="number" name="mlm_order" id="mlm_order" class="regular-text" value="<?php echo $mlm_order; ?>" min="0" >
				</td>
			</tr>
		</tbody>
	</table>
	<?php wp_nonce_field( 'mlm_subscribe_lex', 'verify' ); ?>
	<button type="submit" class="button button-primary button-large"><?php _e( 'Update', 'mlm' ); ?></button>
</form>