<?php

if( ! defined( 'ABSPATH' ) )
{
	die( 'You are not allowed to call this page directly !' );
}

$texts = get_option('mlm_mail_texts');
?>

<h1 class="wp-heading-inline"><?php _e( 'Mail texts', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<div class="mlm_alert alert-danger">
	<?php _e( 'Default text will send for empty fields.', 'mlm' ); ?>
</div>

<form name="mlm-mail-texts-form" action="<?php echo admin_url( 'admin.php?page=mlm-mail-settings' ); ?>" method="post">
	<table class="form-table">
		<tr>
			<th><label><?php _e( 'Register', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['register'] ) ? $texts['register'] : '', 'mlm_mail_register', array(
					'textarea_name'	=> 'mlm_mail_register',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
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
				<?php
				wp_editor( isset( $texts['lost_code'] ) ? $texts['lost_code'] : '', 'mlm_mail_lost_code', array(
					'textarea_name'	=> 'mlm_mail_lost_code',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: {CODE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Verify Email', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['verify_code'] ) ? $texts['verify_code'] : '', 'mlm_mail_verify_code', array(
					'textarea_name'	=> 'mlm_mail_verify_code',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}<br />
					<?php _e( 'Verification code', 'mlm' ); ?>: {CODE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Pending product', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['product_moderation'] ) ? $texts['product_moderation'] : '', 'mlm_mail_product_moderation', array(
					'textarea_name'	=> 'mlm_mail_product_moderation',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Pending post', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['post_moderation'] ) ? $texts['post_moderation'] : '', 'mlm_mail_post_moderation', array(
					'textarea_name'	=> 'mlm_mail_post_moderation',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Withdrawal paid', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['withdrawal_paid'] ) ? $texts['withdrawal_paid'] : '', 'mlm_mail_withdrawal_paid', array(
					'textarea_name'	=> 'mlm_mail_withdrawal_paid',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Amount', 'mlm' ); ?>: {AMOUNT}<br />
					<?php _e( 'Description', 'mlm' ); ?>: {DESC}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Withdrawal request submitted', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['withdrawal_request'] ) ? $texts['withdrawal_request'] : '', 'mlm_mail_withdrawal_request', array(
					'textarea_name'	=> 'mlm_mail_withdrawal_request',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Amount', 'mlm' ); ?>: {AMOUNT}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Upgrade request submitted', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['upgrade_request'] ) ? $texts['upgrade_request'] : '', 'mlm_mail_upgrade_request', array(
					'textarea_name'	=> 'mlm_mail_upgrade_request',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Upgrade request submitted', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['upgraded'] ) ? $texts['upgraded'] : '', 'mlm_mail_upgraded', array(
					'textarea_name'	=> 'mlm_mail_upgraded',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New reply for comment', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['comment_replied'] ) ? $texts['comment_replied'] : '', 'mlm_mail_comment_replied', array(
					'textarea_name'	=> 'mlm_mail_comment_replied',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'New comment', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['new_comment'] ) ? $texts['new_comment'] : '', 'mlm_mail_new_comment', array(
					'textarea_name'	=> 'mlm_mail_new_comment',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Product or post name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Ticket submitted', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['new_ticket'] ) ? $texts['new_ticket'] : '', 'mlm_mail_new_ticket', array(
					'textarea_name'	=> 'mlm_mail_new_ticket',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Following vendors new product', 'mlm' ); ?></label></th>
			<td>
				<?php
				wp_editor( isset( $texts['follower_new_product'] ) ? $texts['follower_new_product'] : '', 'mlm_mail_follower_new_product', array(
					'textarea_name'	=> 'mlm_mail_follower_new_product',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
				<p class="description">
					<?php _e( 'Display name', 'mlm' ); ?>: {NAME}<br />
					<?php _e( 'Product name', 'mlm' ); ?>: {TITLE}<br />
					<?php _e( 'Product link', 'mlm' ); ?>: {LINK}<br />
					<?php _e( 'Site title', 'mlm' ); ?>: {SITE}
				</p>
			</td>
		</tr>
		
		
	</table>
	<?php wp_nonce_field( 'mlm_lsadjyfast', 'mlm_security' ); ?>
	<button type="submit" class="mlm-save-btn button button-primary button-large"><?php _e( 'Save changes', 'mlm' ); ?></button>
</form>