<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly.');
}
?>

<h1 class="wp-heading-inline"><?php _e( 'New ticket', 'mlm' ); ?></h1>
<a href="<?php echo esc_url( admin_url( 'admin.php?page=mlm-tickets' ) ); ?>" class="page-title-action"><?php _e( 'Return', 'mlm' ); ?></a>
<hr class="wp-header-end">

<form id="mlm_new_ticket_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_user"><?php _e( 'Select user', 'mlm' ); ?><label></th>
				<td>
					<?php mlmFire()->ticket->select_recipient(); ?>
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_title"><?php _e( 'Subject', 'mlm' ); ?><label></th>
				<td>
					<input type="text" name="mlm_title" id="mlm_title" class="regular-text">
				</td>
			</tr>
			<tr class="mlm-form-wrap">
				<th><label for="mlm_content"><?php _e( 'Content', 'mlm' ); ?><label></th>
				<td>
					<?php
					wp_editor( NULL, 'mlm_content', array(
						'textarea_name'	=> 'mlm_content',
						'media_buttons'	=> true,
						'editor_height'	=> 300,
						'teeny'			=> false,
						'quicktags'		=> false
					) );
					?>
				</td>
			</tr>
			<tr>
				<th><label><?php _e( 'Attach images', 'mlm' ); ?></label></th>
				<td id="mlm-attach-ticket-wrap">
					<div class="thumb-box clearfix"></div>
					<button class="button button-secondary" id="mlm-attach-ticket-image"><?php _e('Attach image', 'mlm'); ?></button>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field( 'mlm_ticket_fsaz', 'mlm_security' ); ?>
		<button type="submit" class="button button-primary" id="mlm_new_ticket_btn"><?php _e( 'Save', 'mlm' ); ?></button>
	</p>
</form>