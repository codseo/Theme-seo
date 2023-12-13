<?php
$user_id		= get_the_author_meta( 'ID' );
$verified		= mlmFire()->dashboard->get_account_status( $user_id );
$user_obj		= get_userdata( $user_id );
$user_name		= $user_obj->display_name;
$user_bio		= $user_obj->description;
$reg_date		= $user_obj->user_registered;
$mlm_twitter	= get_user_meta( $user_id, 'mlm_twitter', true );
$mlm_aparat		= get_user_meta( $user_id, 'mlm_aparat', true );
$mlm_telegram	= get_user_meta( $user_id, 'mlm_telegram', true );
$mlm_instagram	= get_user_meta( $user_id, 'mlm_instagram', true );
$mlm_youtube	= get_user_meta( $user_id, 'mlm_youtube', true );
?>

<div class="mlm-product-vendor-widget mb-4 clearfix">
	<h3 class="mlm-box-title icon icon-profile-male sm mb-2"><?php _e( 'Seller', 'mlm' ); ?></h3>
	<div class="vendor-image mb-2 clearfix">
		<?php echo get_avatar( $user_id, 128, NULL , $user_name, array( 'class' => 'rounded-circle d-block mx-auto' ) ); ?>
	</div>
	<div class="vendor-name text-center mb-1 clearfix">
		<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="vcard author d-inline-block text-dark bold-300 <?php if( $verified ) echo 'verified'; ?>" <?php if( $verified ) echo 'data-toggle="tooltip" data-placement="left" title="" data-original-title="'. __( 'Verified user', 'mlm' ) .'"'; ?>><?php echo $user_name; ?></a>
	</div>
	<div class="vendor-follow mb-3 text-center clearfix">
		<?php mlmFire()->follow->print_follow_button( $user_id ); ?>
	</div>
	<ul class="mlm-vendor-social-nav nav mx-0 mb-2 p-0 justify-content-center">
		<?php if( ! empty( $mlm_twitter ) ): ?>
			<li class="nav-item">
				<a target="_blank" href="<?php echo esc_url( $mlm_twitter ); ?>" class="nav-link text-dark py-1 px-2 icon icon-twitter" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Twitter', 'mlm' ); ?>">
					<span class="text-hide">Twitter</span>
				</a>
			</li>
		<?php endif; ?>
		<?php if( ! empty( $mlm_aparat ) ): ?>
			<li class="nav-item">
				<a target="_blank" href="<?php echo esc_url( $mlm_aparat ); ?>" class="nav-link text-dark py-1 px-2 icon icon-aparat" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Aparat', 'mlm' ); ?>">
					<span class="text-hide">Aparat</span>
				</a>
			</li>
		<?php endif; ?>
		<?php if( ! empty( $mlm_telegram ) ): ?>
			<li class="nav-item">
				<a target="_blank" href="<?php echo esc_url( $mlm_telegram ); ?>" class="nav-link text-dark py-1 px-2 icon icon-telegram" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Telegram', 'mlm' ); ?>">
					<span class="text-hide">Telegram</span>
				</a>
			</li>
		<?php endif; ?>
		<?php if( ! empty( $mlm_instagram ) ): ?>
			<li class="nav-item">
				<a target="_blank" href="<?php echo esc_url( $mlm_instagram ); ?>" class="nav-link text-dark py-1 px-2 icon icon-instagram" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Instagram', 'mlm' ); ?>">
					<span class="text-hide">Instagram</span>
				</a>
			</li>
		<?php endif; ?>
		<?php if( ! empty( $mlm_youtube ) ): ?>
			<li class="nav-item">
				<a target="_blank" href="<?php echo esc_url( $mlm_youtube ); ?>" class="nav-link text-dark py-1 px-2 icon icon-youtube" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php _e( 'Youtube', 'mlm' ); ?>">
					<span class="text-hide">Youtube</span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
	<?php if( ! empty( $user_bio ) ): ?>
		<div class="mlm-vendor-bio text-justify text-secondary mb-2 clearfix">
			<?php echo $user_bio; ?>
		</div>
	<?php endif; ?>
	<?php mlmFire()->medal->print_user_medals( $user_id, 'mlm-vendor-medal-nav nav mx-0 mb-2 p-0 justify-content-center' ); ?>
	<div class="vendor-link clearfix">
		<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="btn btn-light btn-block"><?php _e( 'View shop', 'mlm' ); ?></a>
	</div>
	<div class="shortlink my-2 clearfix">
		<button class="btn btn-light btn-block mlm-clipboard" data-clipboard-text="<?php echo wp_get_shortlink(); ?>">
			<?php _e( 'Copy shortlink', 'mlm' ); ?>
		</button>
	</div>
</div>