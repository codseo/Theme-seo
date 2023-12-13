<?php
$user_id		= get_queried_object_id();
$verified		= mlmFire()->dashboard->get_account_status( $user_id );
$user_obj		= get_userdata( $user_id );
$user_name		= $user_obj->display_name;
$user_bio		= $user_obj->description;
$mlm_state		= get_user_meta( $user_id, 'mlm_state', true );
$mlm_twitter	= get_user_meta( $user_id, 'mlm_twitter', true );
$mlm_aparat		= get_user_meta( $user_id, 'mlm_aparat', true );
$mlm_telegram	= get_user_meta( $user_id, 'mlm_telegram', true );
$mlm_instagram	= get_user_meta( $user_id, 'mlm_instagram', true );
$mlm_youtube	= get_user_meta( $user_id, 'mlm_youtube', true );
$mlm_cover		= get_user_meta( $user_id, 'mlm_cover', true );
$posts_cnt		= count_user_posts( $user_id , 'post' );
$products_cnt	= count_user_posts( $user_id , 'product' );
?>

<div class="mlm-vendor-box mlm-widget mlm-product-vendor-widget bg-white rounded p-0 mb-4 clearfix">
	<div class="vendor-cover position-relative rounded-top" <?php if( $mlm_cover ) echo 'style="background-image: url('. $mlm_cover .');"'; ?>>
		<ul class="mlm-vendor-social-nav nav m-0 p-0 justify-content-center">
			<?php if( ! empty( $mlm_twitter ) ): ?>
				<li class="nav-item">
					<a target="_blank" href="<?php echo esc_url( $mlm_twitter ); ?>" class="nav-link text-white py-1 px-2 icon icon-twitter" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Twitter', 'mlm' ); ?>">
						<span class="text-hide">Twitter</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if( ! empty( $mlm_aparat ) ): ?>
				<li class="nav-item">
					<a target="_blank" href="<?php echo esc_url( $mlm_aparat ); ?>" class="nav-link text-white py-1 px-2 icon icon-aparat white" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Aparat', 'mlm' ); ?>">
						<span class="text-hide">Aparat</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if( ! empty( $mlm_telegram ) ): ?>
				<li class="nav-item">
					<a target="_blank" href="<?php echo esc_url( $mlm_telegram ); ?>" class="nav-link text-white py-1 px-2 icon icon-telegram" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Telegram', 'mlm' ); ?>">
						<span class="text-hide">Telegram</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if( ! empty( $mlm_instagram ) ): ?>
				<li class="nav-item">
					<a target="_blank" href="<?php echo esc_url( $mlm_instagram ); ?>" class="nav-link text-white py-1 px-2 icon icon-instagram" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Instagram', 'mlm' ); ?>">
						<span class="text-hide">Instagram</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if( ! empty( $mlm_youtube ) ): ?>
				<li class="nav-item">
					<a target="_blank" href="<?php echo esc_url( $mlm_youtube ); ?>" class="nav-link text-white py-1 px-2 icon icon-youtube" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Youtube', 'mlm' ); ?>">
						<span class="text-hide">Youtube</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="vendor-image position-relative">
		<?php echo get_avatar( $user_id, 128, NULL , $user_name, array( 'class' => 'rounded-circle d-block mx-auto' ) ); ?>
	</div>
	<div class="p-3 clearfix">
		<div class="vendor-name text-center mb-2 clearfix">
			<span class="d-inline-block text-dark bold-300 <?php if( $verified ) echo 'verified'; ?>" <?php if( $verified ) echo 'data-toggle="tooltip" data-placement="left" title="" data-original-title="'. __( 'Verified user', 'mlm' ) .'"'; ?>><?php echo $user_name; ?></span>
		</div>
		<div class="vendor-follow mb-3 text-center clearfix">
			<?php mlmFire()->follow->print_follow_button( $user_id ); ?>
		</div>
		<div class="mlm-vendor-bio text-justify text-secondary mb-3 clearfix">
			<div class="meta reg-date icon icon-lock1 mb-2">
				<span class="t"><?php _e( 'Register date', 'mlm' ); ?>:</span>
				<span class="v"><?php echo human_time_diff( strtotime( $user_obj->user_registered ), current_time('timestamp') ); ?> <?php _e( 'ago', 'mlm' ); ?></span>
			</div>
			<div class="meta state icon icon-flag1 mb-2">
				<span class="t"><?php _e( 'State', 'mlm' ); ?>:</span>
				<span class="v"><?php echo $mlm_state; ?></span>
			</div>
			<?php if( ! empty( $user_bio ) ) echo $user_bio ?>
		</div>
		<div class="vendor-stats mb-3 pt-3 border-top border-light clearfix">
			<div class="row">
				<div class="col-6 col-lg-3">
					<div class="stat-item text-center my-2 clearfix">
						<span class="icon icon-lightbulb d-block"></span>
						<span class="count d-block my-1 bold-600"><?php echo $posts_cnt; ?></span>
						<span class="text font-10 d-block"><?php _e( 'Online posts count', 'mlm' ); ?></span>
					</div>
				</div>
				<div class="col-6 col-lg-3">
					<div class="stat-item text-center my-2 clearfix">
						<span class="icon icon-basket d-block"></span>
						<span class="count d-block my-1 bold-600"><?php echo $products_cnt; ?></span>
						<span class="text font-10 d-block"><?php _e( 'Online products count', 'mlm' ); ?></span>
					</div>
				</div>
				<div class="col-6 col-lg-3">
					<div class="stat-item text-center my-2 clearfix">
						<span class="icon icon-linegraph d-block"></span>
						<span class="count d-block my-1 bold-600"><?php echo mlmFire()->wallet->get_user_sales_count( $user_id ); ?></span>
						<span class="text font-10 d-block"><?php _e( 'Direct sales count', 'mlm' ); ?></span>
					</div>
				</div>
				<div class="col-6 col-lg-3">
					<div class="stat-item text-center my-2 clearfix">
						<span class="icon icon-puzzle d-block"></span>
						<span class="count d-block my-1 bold-600"><?php echo mlmFire()->follow->count_followers( $user_id ); ?></span>
						<span class="text font-10 d-block"><?php _e( 'Followers', 'mlm' ); ?></span>
					</div>
					<?php /*
					<div class="stat-item text-center my-2 clearfix">
						<span class="icon icon-puzzle d-block"></span>
						<span class="count d-block my-1 bold-600"><?php echo mlmFire()->referral->get_refs_count( $user_id ); ?></span>
						<span class="text font-10 d-block"><?php _e( 'Valid clicks count', 'mlm' ); ?></span>
					</div>
					*/ ?>
				</div>
			</div>
		</div>
		<?php mlmFire()->medal->print_user_medals( $user_id, 'mlm-vendor-medal-nav nav m-0 px-0 pb-0 pt-3 justify-content-center border-top border-light' ); ?>
	</div>
</div>