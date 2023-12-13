<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id		= get_current_user_id();
$plans_url		= trailingslashit( mlm_page_url('panel') ) . 'section/subscribes/';
$all_plans		= mlmFire()->plan->get_plans( 0, true );
$current_plans	= mlmFire()->plan->get_user_active_plan( $user_id );
$nonce			= wp_create_nonce('mlm_zoxolunsaw');
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'VIP plans', 'mlm' ); ?></h3>

<?php if( is_array( $all_plans ) && count( $all_plans ) > 0 ): ?>
	
	<div class="mlm-plans-wrapper clearfix">
		<div class="row">
			
			<?php foreach( $all_plans as $plan ): ?>
				
				<div class="col-12 col-md-6">
					<div class="plan-item mlm-widget bg-white rounded border mb-4 p-3 clearfix">
						<h5 class="mlm-box-title sm mb-3 py-2 icon icon-key1 d-block clearfix">
							<?php echo $plan['name']; ?>
							<a href="<?php echo $plans_url; ?>mid/<?php echo $plan['id']; ?>/" class="btn btn-light rounded-pill float-left mr-2 font-10 py-0"><?php _e( 'List products', 'mlm' ); ?></a>
						</h5>
						<p class="text-secondary text-justify font-12"><?php echo $plan['text']; ?></p>
						<p class="text-center text-primary font-14 bold-600">
							<?php printf( __( '%1$s days VIP member for only %2$s', 'mlm' ), $plan['time'], mlm_filter( $plan['price'] ) ); ?>
						</p>
						<?php if( is_array( $current_plans ) && in_array( $plan['id'], $current_plans ) ): ?>
							<button type="button" class="btn btn-success btn-block rounded-pill disabled"><?php _e( 'Already purchased', 'mlm' ); ?></button>
						<?php else: ?>
							<a href="#mlm-purchase-plan" class="btn btn-outline-primary btn-block rounded-pill" data-id="<?php echo $plan['id']; ?>" data-verify="<?php echo $nonce; ?>"><?php _e( 'Purchase plan', 'mlm' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				
			<?php endforeach; ?>
			
		</div>
	</div>
		
<?php else: ?>
	
	<div class="alert alert-warning"><?php _e( 'Site administration activated no plans for now.', 'mlm' ); ?></div>
	
<?php endif; ?>