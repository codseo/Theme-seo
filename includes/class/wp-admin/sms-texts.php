<?php

if( ! defined( 'ABSPATH' ) )
{
	die( 'You are not allowed to call this page directly !' );
}

$texts = get_option('mlm_sms_texts');
?>

<h1 class="wp-heading-inline"><?php _e( 'SMS texts', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<div class="mlm_alert alert-danger">
	<?php _e( 'Default text will send for empty fields.', 'mlm' ); ?>
</div>

<form name="mlm-sms-texts-form" action="<?php echo admin_url( 'admin.php?page=mlm-sms-settings' ); ?>" method="post">
	<table class="form-table">
		<tr>
			<th><label><?php _e( 'Register', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][register]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['register'] ) ? $texts['register'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'User Name', 'mlm' ); ?>: {USERNAME}<br />
					<?php _e( 'Password', 'mlm' ); ?>: {PASSWORD}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Forgot password', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][lost_code]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['lost_code'] ) ? $texts['lost_code'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: {CODE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Verify mobile', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][verify_code]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['verify_code'] ) ? $texts['verify_code'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: {CODE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Product verified', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][product_published]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['product_published'] ) ? $texts['product_published'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: {TITLE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Product rejected', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][product_rejected]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['product_rejected'] ) ? $texts['product_rejected'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Reject reason', 'mlm' ); ?>: {REASON}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Post verified', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][post_published]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['post_published'] ) ? $texts['post_published'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Post name', 'mlm' ); ?>: {TITLE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Post rejected', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][post_rejected]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['post_rejected'] ) ? $texts['post_rejected'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Post name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Reject reason', 'mlm' ); ?>: {REASON}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Ticket reply', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][ticket_replied]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['ticket_replied'] ) ? $texts['ticket_replied'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Ticket number', 'mlm' ); ?>: {ID}<br />
					<?php _e( 'Sender name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New ticket', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][new_ticket]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['new_ticket'] ) ? $texts['new_ticket'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Sender name', 'mlm' ); ?>: {SENDER}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Ticket number', 'mlm' ); ?>: {ID}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Change password', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][password_changed]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['password_changed'] ) ? $texts['password_changed'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Password', 'mlm' ); ?>: {PASSWORD}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New purchase', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][new_purchase]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['new_purchase'] ) ? $texts['new_purchase'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Order number', 'mlm' ); ?>: {ORDER}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New sale', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][new_sale]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['new_sale'] ) ? $texts['new_sale'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Product name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Order number', 'mlm' ); ?>: {ORDER}<br />
					<?php _e( 'Order total', 'mlm' ); ?>: {TOTAL}<br />
					<?php _e( 'Customer name', 'mlm' ); ?>: {CUSTOMER}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan activated', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][plan_activated]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['plan_activated'] ) ? $texts['plan_activated'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: {PLAN}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 20 days', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][plan_expire_in_20]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['plan_expire_in_20'] ) ? $texts['plan_expire_in_20'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: {PLAN}<br />
					<?php _e( 'Time left', 'mlm' ); ?>: {TIME}<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: {LINK}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 10 days', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][plan_expire_in_10]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['plan_expire_in_10'] ) ? $texts['plan_expire_in_10'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Plan name', 'mlm' ); ?>: {PLAN}<br />
					<?php _e( 'Time left', 'mlm' ); ?>: {TIME}<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: {LINK}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Plan will end in 1 day', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][plan_expire_in_1]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['plan_expire_in_1'] ) ? $texts['plan_expire_in_1'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Plan ID', 'mlm' ); ?>: {PLAN}<br />
					<?php _e( 'Renew link', 'mlm' ); ?>: {LINK}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Upgrade account', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][upgraded]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['upgraded'] ) ? $texts['upgraded'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New reply for comment', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][comment_replied]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['comment_replied'] ) ? $texts['comment_replied'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New comment', 'mlm' ); ?></label></th>
			<td>
				<textarea name="mlm_sms[i][new_comment]" class="regular-text" cols="10" rows="5"><?php echo isset( $texts['new_comment'] ) ? $texts['new_comment'] : ''; ?></textarea>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Product or post name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
	</table>
	<?php wp_nonce_field( 'mlm_lsadjyfast', 'mlm_security' ); ?>
	<button type="submit" class="mlm-save-btn button button-primary button-large"><?php _e( 'Save changes', 'mlm' ); ?></button>
</form>