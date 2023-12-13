<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! current_user_can( 'moderate_comments' ) )
{
	wp_die('You are not allowed here !');
}
?>

<h1 class="wp-heading-inline"><?php _e( 'Dashboard', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<?php if( isset( $attributes['withdraw'] ) && ! empty( $attributes['withdraw'] ) ): ?>
	<div class="lian_alert alert-danger">
		<?php echo $attributes['withdraw']; ?> <?php _e( 'pending withdrawals', 'mlm' ); ?>
		<a href="<?php echo admin_url('admin.php?page=mlm-withdrawals'); ?>" class="button button-primary"><?php _e( 'Check', 'mlm' ); ?></a>
	</div>
<?php endif; ?>

<?php if( isset( $attributes['ticket'] ) && ! empty( $attributes['ticket'] ) ): ?>
	<div class="lian_alert alert-danger">
		<?php echo $attributes['ticket']; ?> <?php _e( 'open tickets', 'mlm' ); ?>
		<a href="<?php echo admin_url('admin.php?mlm_status=5&page=mlm-tickets'); ?>" class="button button-primary"><?php _e( 'Check', 'mlm' ); ?></a>
	</div>
<?php endif; ?>