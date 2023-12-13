<?php
$all_posts		= wp_count_posts('post');
$all_products	= wp_count_posts('product');
$all_users		= count_users();
$all_comments	= wp_count_comments();
?>

<div class="mlm-stats-box py-3 mb-4 clearfix">
	<div class="row">
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-basket d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo isset( $all_products->publish ) ? $all_products->publish : 0; ?></span>
				<span class="text text-light d-block"><?php _e( 'products for sale', 'mlm' ); ?></span>
			</div>
		</div>
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-heart1 d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo mlmFire()->wallet->get_total_sales_count(); ?></span>
				<span class="text text-light d-block"><?php _e( 'happy customers', 'mlm' ); ?></span>
			</div>
		</div>
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-lightbulb d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo isset( $all_posts->publish ) ? $all_posts->publish : 0; ?></span>
				<span class="text text-light d-block"><?php _e( 'articles and news', 'mlm' ); ?></span>
			</div>
		</div>
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-tools-2 d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo mlmFire()->ticket->count_all_tickets(); ?></span>
				<span class="text text-light d-block"><?php _e( 'support requests', 'mlm' ); ?></span>
			</div>
		</div>
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-aperture d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo isset( $all_users['total_users'] ) ? $all_users['total_users'] : 0; ?></span>
				<span class="text text-light d-block"><?php _e( 'active users count', 'mlm' ); ?></span>
			</div>
		</div>
		<div class="col-6 col-md-4 col-lg-2">
			<div class="stat-item text-center my-2 clearfix">
				<span class="icon icon-chat d-block"></span>
				<span class="count d-block my-1 bold-600"><?php echo isset( $all_comments->approved ) ? $all_comments->approved : 0; ?></span>
				<span class="text text-light d-block"><?php _e( 'comments received', 'mlm' ); ?></span>
			</div>
		</div>
	</div>
</div>