<?php

if( ! defined( 'ABSPATH' ) )
{
	die( 'You are not allowed to call this page directly !' );
}

$patterns = get_option('mlm_sms_patterns');
?>

<h1 class="wp-heading-inline"><?php _e( 'SMS patterns', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<div class="mlm_alert alert-danger">
	<?php _e( 'Enter the related pattern code you registered on your own sms provider panel.', 'mlm' ); ?>
</div>

<form name="mlm-sms-patterns-form" action="<?php echo admin_url( 'admin.php?page=mlm-sms-patterns' ); ?>" method="post">
	<table class="form-table">
		<tr>
			<th><label><?php _e( 'Register', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][register]" class="regular-text" value="<?php echo isset( $patterns['register'] ) ? $patterns['register'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'User Name', 'mlm' ); ?>: %user%<br />
					<?php _e( 'Password', 'mlm' ); ?>: %password%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Forgot password', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][lost_code]" class="regular-text" value="<?php echo isset( $patterns['lost_code'] ) ? $patterns['lost_code'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: %code%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Verify mobile', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][verify_code]" class="regular-text" value="<?php echo isset( $patterns['verify_code'] ) ? $patterns['verify_code'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: %code%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Product verified', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][product_published]" class="regular-text" value="<?php echo isset( $patterns['product_published'] ) ? $patterns['product_published'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: %title%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Product rejected', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][product_rejected]" class="regular-text" value="<?php echo isset( $patterns['product_rejected'] ) ? $patterns['product_rejected'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: %title%<br />
					<?php _e( 'Reject reason', 'mlm' ); ?>: %reason%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Post verified', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][post_published]" class="regular-text" value="<?php echo isset( $patterns['post_published'] ) ? $patterns['post_published'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Post name', 'mlm' ); ?>: %title%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Post rejected', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][post_rejected]" class="regular-text" value="<?php echo isset( $patterns['post_rejected'] ) ? $patterns['post_rejected'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Post name', 'mlm' ); ?>: %title%<br />
					<?php _e( 'Reject reason', 'mlm' ); ?>: %reason%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Ticket reply', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][ticket_replied]" class="regular-text" value="<?php echo isset( $patterns['ticket_replied'] ) ? $patterns['ticket_replied'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Ticket number', 'mlm' ); ?>: %id%<br />
					<?php _e( 'Sender name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New ticket', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][new_ticket]" class="regular-text" value="<?php echo isset( $patterns['new_ticket'] ) ? $patterns['new_ticket'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Sender name', 'mlm' ); ?>: %sender%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Ticket number', 'mlm' ); ?>: %id%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Change password', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][password_changed]" class="regular-text" value="<?php echo isset( $patterns['password_changed'] ) ? $patterns['password_changed'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Password', 'mlm' ); ?>: %password%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New purchase', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][new_purchase]" class="regular-text" value="<?php echo isset( $patterns['new_purchase'] ) ? $patterns['new_purchase'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Order number', 'mlm' ); ?>: %order%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New sale', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][new_sale]" class="regular-text" value="<?php echo isset( $patterns['new_sale'] ) ? $patterns['new_sale'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: %title%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%<br />
					<?php _e( 'Order number', 'mlm' ); ?>: %order%<br />
					<?php _e( 'Order total', 'mlm' ); ?>: %total%<br />
					<?php _e( 'Customer name', 'mlm' ); ?>: %customer%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan activated', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][plan_activated]" class="regular-text" value="<?php echo isset( $patterns['plan_activated'] ) ? $patterns['plan_activated'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: %plan%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 20 days', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][plan_expire_in_20]" class="regular-text" value="<?php echo isset( $patterns['plan_expire_in_20'] ) ? $patterns['plan_expire_in_20'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: %plan%<br />
					<?php _e( 'Time left', 'mlm' ); ?>: %time%<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: %link%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 10 days', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][plan_expire_in_10]" class="regular-text" value="<?php echo isset( $patterns['plan_expire_in_10'] ) ? $patterns['plan_expire_in_10'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: %plan%<br />
					<?php _e( 'Time left', 'mlm' ); ?>: %time%<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: %link%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 1 day', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][plan_expire_in_1]" class="regular-text" value="<?php echo isset( $patterns['plan_expire_in_1'] ) ? $patterns['plan_expire_in_1'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Plan ID', 'mlm' ); ?>: %plan%<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: %link%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Upgrade account', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][upgraded]" class="regular-text" value="<?php echo isset( $patterns['upgraded'] ) ? $patterns['upgraded'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New reply for comment', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][comment_replied]" class="regular-text" value="<?php echo isset( $patterns['comment_replied'] ) ? $patterns['comment_replied'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New comment', 'mlm' ); ?></label></th>
			<td>
				<input type="text" name="mlm_pattern[i][new_comment]" class="regular-text" value="<?php echo isset( $patterns['new_comment'] ) ? $patterns['new_comment'] : ''; ?>" />
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: %name%<br />
					<?php _e( 'Product or post name', 'mlm' ); ?>: %title%<br />
					<?php _e( 'Site title', 'mlm' ); ?>: %site%
				</p>
			</td>
		</tr>
	</table>
	<?php wp_nonce_field( 'mlm_lsadjyfast', 'mlm_security' ); ?>
	<button type="submit" class="mlm-save-btn button button-primary button-large"><?php _e( 'Save changes', 'mlm' ); ?></button>
</form>