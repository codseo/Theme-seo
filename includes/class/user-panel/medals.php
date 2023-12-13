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
$user_name		= mlm_get_user_name( $user_id );
$verified		= mlmFire()->dashboard->get_account_status( $user_id );
$percent		= mlmFire()->dashboard->get_account_percent( $user_id );
$all_medals		= mlmFire()->medal->get_medals();
$user_medals	= mlmFire()->medal->get_user_medals( $user_id );

if( $verified )
{
	$text	= __( 'Blue badge received', 'mlm' );
}
else
{
	$text	= sprintf( __( '%d%% till blue badge', 'mlm' ), $percent );
}
?>

<h3 class="mlm-box-title sm mb-0 py-2"><?php _e( 'Blue badge progress status', 'mlm' ); ?></h3>
<p class="text-justify font-12 mb-4 text-secondary"><?php echo get_option('mlm_medal_title'); ?></p>

<div class="mlm-user-panel-widget p-0 mb-4 clearfix">
	<div class="mlm-product-vendor-widget bg-light rounded p-3 m-0 clearfix">
		<div class="vendor-image mb-2 clearfix">
			<?php echo get_avatar( $user_id, 128, NULL , $user_name, array( 'class' => 'rounded-circle d-block bg-white mx-auto' ) ); ?>
		</div>
		<div class="vendor-name text-center mb-3 clearfix">
			<span class="d-inline-block text-secondary bold-300 <?php if( $verified ) echo 'verified'; ?>"><?php echo $user_name; ?></span>
		</div>
	</div>
	<div class="progress bg-light m-0 border-0 rounded-0">
		<div class="progress-bar" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $text; ?></div>
	</div>
</div>

<div class="mlm-user-medals-widget row no-gutters">
	<?php foreach( $all_medals as $medal ): ?>
		
		<?php
		if( $medal == 'account-ok' )
		{
			continue;
		}
		
		$minimum	= mlmFire()->medal->min_available_amount( $medal );
		$title		= mlmFire()->medal->get_medal_title( $medal, $minimum );
		$percent	= mlmFire()->medal->get_medal_status( $user_id, $medal, true );
		?>
		<div class="col-12 col-md-6 px-2">
			<div class="mlm-user-medal-item border border-light rounded p-3 mb-3 font-12 text-secondary clearfix">
				<?php echo $title; ?>
				<?php if( isset( $user_medals[$medal] ) ): ?>
					<span class="badge badge-success float-left font-10 px-2 py-1 mr-2 my-1"><?php _e( 'Done', 'mlm' ); ?></span>
				<?php else: ?>
					<span class="badge badge-warning float-left font-10 px-2 py-1 mr-2 my-1"><?php _e( 'Pending', 'mlm' ); ?></span>
				<?php endif; ?>
				<div class="progress bg-light mt-3 mb-n3 mx-n3 border-0 rounded">
					<div class="progress-bar" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>