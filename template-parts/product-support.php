<?php
$post_id		= get_the_ID();
$user_id		= get_current_user_id();
$support_text	= get_option('mlm_support_text');

if( is_user_logged_in() )
{
	$user_obj	= get_userdata( $user_id );
	$user_email	= $user_obj->user_email;
}
?>
<?php if( ! empty( $support_text ) ): ?>
<div class="mlm-support-text-box mb-4 clearfix">
	<h3 class="mlm-box-title sm mb-2"><?php _e( 'About product support', 'mlm' ); ?></h3>
	<p class="text-justify"><?php echo $support_text; ?></p>
</div>
<?php endif; ?>
<div class="mlm-support-ways-box mb-4 clearfix">
	<h3 class="mlm-box-title sm mb-2"><?php _e( 'Support methods', 'mlm' ); ?></h3>
	
	<?php if( ! is_user_logged_in() ): ?>
									
		<div class="alert alert-warning m-0 clearfix">
			<?php _e( 'You have to login your account to submit a new ticket.', 'mlm' ); ?>
			<button type="button" class="btn btn-secondary float-left mr-2 py-0" data-toggle="modal" data-target="#mlm-login-register-popup"><?php _e( 'Login', 'mlm' ); ?></button>
		</div>
	
	<?php elseif( function_exists('wc_customer_bought_product') && wc_customer_bought_product( $user_email, $user_id, $post_id ) ): ?>
	
		<div class="row">
			<div class="col-6 col-md-3">
				<a href="<?php echo trailingslashit( mlm_page_url('panel') ).'section/tickets-new/'; ?>" class="mlm-product-meta d-block bg-light text-dark p-3 mb-4 text-center transition clearfix">
					<span class="icon icon-bubbles2 d-block"></span>
					<span class="v d-block bold-600"><?php _e( 'Ticket', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-3">
				<button type="button" class="mlm-product-meta d-block w-100 bg-light text-dark p-3 mb-4 text-center transition clearfix disabled" tabindex="-1" aria-disabled="true" disabled>
					<span class="icon icon-envelop d-block"></span>
					<span class="v d-block bold-600"><?php _e( 'Email', 'mlm' ); ?></span>
				</button>
			</div>
			<div class="col-6 col-md-3">
				<button type="button" class="mlm-product-meta d-block w-100 bg-light text-dark p-3 mb-4 text-center transition clearfix disabled" tabindex="-1" aria-disabled="true" disabled>
					<span class="icon icon-telegram d-block"></span>
					<span class="v d-block bold-600"><?php _e( 'Telegram', 'mlm' ); ?></span>
				</button>
			</div>
			<div class="col-6 col-md-3">
				<button type="button" class="mlm-product-meta d-block w-100 bg-light text-dark p-3 mb-4 text-center transition clearfix disabled" tabindex="-1" aria-disabled="true" disabled>
					<span class="icon icon-phone d-block"></span>
					<span class="v d-block bold-600"><?php _e( 'Phone', 'mlm' ); ?></span>
				</button>
			</div>
		</div>
		<div class="alert alert-warning m-0">
			<?php _e( 'Click the support method you want to contact the product seller.', 'mlm' ); ?>
		</div>
		
	<?php else: ?>
		
		<div class="alert alert-warning m-0 clearfix">
			<?php _e( 'You have to purchase the product first. Then you can access the product support.', 'mlm' ); ?>
		</div>
	
	<?php endif; ?>
</div>