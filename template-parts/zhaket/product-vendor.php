<?php
$user_id		= get_the_author_meta( 'ID' );
$verified		= mlmFire()->dashboard->get_account_status( $user_id );
$user_obj		= get_userdata( $user_id );
$user_name		= $user_obj->display_name;
$user_bio		= $user_obj->description;
?>

<div class="product-vendor-widget mb-4 p-3 border rounded clearfix">
	<div class="row no-gutters mx-n1">
		<div class="vendor-col col px-1">
			<div class="row align-items-center no-gutters mx-n1">
				<div class="avatar-col col px-1">
					<?php echo get_avatar( $user_id, 128, NULL , $user_name, array( 'class' => 'd-block rounded-circle bg-white shadow-sm' ) ); ?>
				</div>
				<div class="name-col col px-1">
					<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="vcard author ellipsis text-dark font-16 bold-600 <?php if( $verified ) echo 'verified'; ?>" <?php if( $verified ) echo 'data-toggle="tooltip" data-placement="left" title="" data-original-title="'. __( 'Verified user', 'mlm' ) .'"'; ?>><?php echo $user_name; ?></a>
				</div>
			</div>
		</div>
		<div class="shop-col col px-1">
			<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="d-block store-link" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php _e( 'View shop', 'mlm' ); ?>">
				<svg viewBox="0 0 45.2 42"><g transform="translate(-5210 1374)"><path fill="#E6E6E6" d="M5224-1349c-2.7 0-5 2.2-5 4.9v11.1h10v-11c0-2.7-2.2-5-5-5 .1 0 0 0 0 0z"></path><path fill="#FEA000" d="M5234-1349h12v10h-12z"></path><path fill="#4D4D4D" d="M5246-1338h-12c-.6 0-1-.4-1-1v-10c0-.6.4-1 1-1h12c.6 0 1 .4 1 1v10c0 .6-.4 1-1 1zm-11-2h10v-8h-10v8z"></path><path fill="#4D4D4D" d="M5255.2-1360.9c0-.2 0-.3-.1-.5l-4.7-10.8c-.5-1.1-1.5-1.8-2.7-1.8h-30.4c-1.2 0-2.2.7-2.7 1.8l-4.5 10.8c-.1.1-.1.3-.1.4.1 2.1 1.3 4 3 5v22.9c0 .6.4 1 1 1h37c.6 0 1-.4 1-1v-22.8c1.8-.9 3.1-2.8 3.2-5zm-38.8-10.5c.2-.4.5-.6.9-.6h30.4c.4 0 .7.2.9.6l4.6 10.6c-.2 2.2-2.1 3.9-4.4 3.8-2.4.1-4.3-1.7-4.4-4 0-.5-.5-.9-1-.9s-1 .4-1 1c0 1.1-.5 2.1-1.4 2.9-.8.8-1.9 1.1-3.1 1.1-2.3.1-4.3-1.7-4.4-4 0-.5-.5-.9-1-.9s-1 .4-1 1c0 1.1-.5 2.1-1.4 2.9-.8.8-1.9 1.1-3.1 1.1-2.3.1-4.3-1.7-4.4-4 0-.5-.5-.9-1-.9s-1 .4-1 1c-.1 2.3-2.1 4.1-4.4 4-2.2.1-4.1-1.6-4.4-3.8l4.6-10.9zm11.6 37.4h-8v-10.1c0-1.1.4-2 1.2-2.8.8-.7 1.8-1.1 2.8-1.1h.1c2.2 0 3.9 1.8 3.9 4v10zm22 0h-20v-10c0-3.3-2.6-6-5.9-6h-.1c-1.6 0-3.1.6-4.2 1.7s-1.8 2.6-1.8 4.2v10.1h-3v-21.1c.4.1.9.1 1.4.1 1.6.1 3.2-.5 4.4-1.6.4-.4.7-.8 1-1.2 1.2 1.8 3.2 2.9 5.3 2.8 1.6.1 3.2-.5 4.4-1.6.4-.4.7-.8 1-1.2 1.2 1.8 3.2 2.9 5.3 2.8h.3c1.5 0 3-.6 4.2-1.6.4-.4.7-.8 1-1.2 1.2 1.8 3.2 2.9 5.3 2.8.4 0 .8 0 1.2-.1v21.1z"></path></g></svg>
			</a>
		</div>
	</div>
	<?php if( ! empty( $user_bio ) ): ?>
		<div class="py-3 mb-3 border-bottom text-secondary text-justify font-13">
			<?php echo $user_bio; ?>
		</div>
	<?php endif; ?>
	<div class="user-medals">
		<?php mlmFire()->medal->print_user_medals( $user_id, 'mlm-vendor-medal-nav nav m-0 p-0' ); ?>
	</div>
</div>