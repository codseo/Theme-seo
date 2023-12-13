<?php
$site_logo		= get_option( 'mlm_logo' );
$mobile_logo	= get_option( 'mlm_mobile_logo' );
$panel_id		= get_option( 'mlm_panel_page' );
?>

<div class="app-header-menu clearfix">
	<div class="container">
		<div class="header-nav nav align-items-center p-0 mx-0 my-2">
			<li class="nav-item d-lg-none">
				<div class="toggle-btn">
					<button id="mlm-toggle-mobile-menu" class="toggle-quru toggle-daha">
						<span>toggle menu</span>
					</button>
				</div>
			</li>
			<?php if( ! empty( $mobile_logo ) ): ?>
				<li class="nav-item ml-auto hinv d-lg-none">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo d-block" title="<?php bloginfo( 'name' ); ?>" rel="home">
						<img src="<?php echo esc_url( $mobile_logo ); ?>" class="img-fluid w-auto h-100" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				</li>
			<?php endif; ?>
			<?php if( ! empty( $site_logo ) ): ?>
				<li class="nav-item ml-auto hinv d-none d-lg-flex">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo d-block" title="<?php bloginfo( 'name' ); ?>" rel="home">
						<img src="<?php echo esc_url( $site_logo ); ?>" class="img-fluid w-auto h-100" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				</li>
			<?php endif; ?>
			<?php
			for( $i = 1; $i <= 6; $i++ )
			{
				$cat_id		= (int)get_option( 'mlm_cat_' . $i );
				$cat_icon	= get_option( 'mlm_cat_icon_' . $i );
				$obj		= get_term( $cat_id );
				
				if( ! empty( $obj ) && ! is_wp_error( $obj ) )
				{
					?>
					<li class="nav-item d-none d-lg-flex hinv">
						<a href="<?php echo esc_url( get_term_link( $obj ) ); ?>" class="cat-link d-block" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $obj->name; ?>">
							<span class="icon <?php echo $cat_icon; ?> transition"></span>
							<span class="sr-only"><?php echo $obj->name; ?></span>
						</a>
					</li>
					<?php
				}
			}
			?>
			<?php if( ! empty( $panel_id ) && is_page( $panel_id ) ): ?>
				<?php
				$user_id	= get_current_user_id();
				$announce	= mlmFire()->announce->check_user_announce( $user_id );
				?>
				<li class="nav-item py-2 mr-auto position-relative z4">
					<a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>section/announce/" class="app-notification-btn btn border-0 bg-transparent <?php if( $announce ) echo 'al'; ?>">
						<svg viewBox="0 0 26 30"><path d="M23.7 19.7h-20v-6.2c0-2.6 1.1-5.1 3-7 1.9-1.8 4.4-2.8 7-2.8h.1c5.5 0 9.9 4.5 9.9 9.9v6.1zm-18-2h16v-4.1c0-4.4-3.5-7.9-7.9-7.9h-.1c-2.1 0-4.1.8-5.6 2.3-1.5 1.5-2.4 3.4-2.4 5.6v4.1z"></path><path d="M13.4 5.7c-.6 0-1-.4-1-1V1c0-.6.4-1 1-1s1 .4 1 1v3.7c0 .6-.4 1-1 1zM13.3 30c-2.5 0-4.6-2-4.6-4.6v-.2h2c0 1.5 1.2 2.7 2.7 2.7h.1c1.5 0 2.6-1.2 2.6-2.7h2c0 2.6-2 4.7-4.6 4.8h-.2c.1 0 .1 0 0 0z"></path><path d="M22 25.8H4c-2.2 0-4-1.8-4-4s1.8-4 4-4h18c2.2 0 4 1.8 4 4s-1.8 4-4 4zm-18-6c-1.1 0-2 .9-2 2s.9 2 2 2h18c1.1 0 2-.9 2-2s-.9-2-2-2H4z"></path></svg>
					</a>
				</li>
			<?php else: ?>
				<li class="nav-item py-2 mr-auto position-relative z4">
					<button type="button" class="app-search-btn btn border-0 bg-transparent">
						<svg viewBox="-4.615 -5.948 39.083 39.417"><path stroke="#4D4D4D" stroke-width="1" d="M33.207 30.77L25.6 23c-.064-.065-.143-.104-.218-.148 2.669-2.955 4.31-6.856 4.31-11.143 0-9.189-7.476-16.665-16.665-16.665S-3.638 2.52-3.638 11.709s7.476 16.665 16.665 16.665c4.221 0 8.067-1.59 11.007-4.186.042.072.076.148.137.211l7.607 7.77a.998.998 0 0 0 1.414.016 1.002 1.002 0 0 0 .015-1.415zm-20.18-4.397c-8.086 0-14.665-6.578-14.665-14.665S4.94-2.956 13.027-2.956c8.086 0 14.665 6.579 14.665 14.665s-6.579 14.664-14.665 14.664z"></path></svg>
					</button>
				</li>
			<?php endif; ?>
			<?php if( function_exists('WC') ): ?>
				<?php
				$count = WC()->cart->get_cart_contents_count();
				?>
				<li class="nav-item py-2 position-relative z4">
					<button type="button" class="app-basket-btn btn border-0 bg-transparent position-relative <?php if( $count > 0 ) echo 'al'; ?>">
						<svg viewBox="0 0 22.8 29.4"><path d="M21.8 6.5h-5.6V4.8c0-2.6-2.1-4.8-4.8-4.8-2.6 0-4.8 2.1-4.8 4.8v1.8H1c-.6 0-1 .4-1 1v17.8c.2 2.2 2 4 4.3 4h14.3c2.2 0 4.1-1.8 4.3-4.1V7.5c-.1-.5-.5-1-1.1-1zM8.6 4.8C8.6 3.3 9.9 2 11.4 2c1.5 0 2.8 1.3 2.8 2.8v1.8H8.6V4.8zm10 22.6H4.5c-1.2.1-2.3-.9-2.4-2.1V8.5h4.6v.9c0 .6.4 1 1 1s1-.4 1-1v-.9h5.6v.9c0 .6.4 1 1 1s1-.4 1-1v-.9h4.6v16.7c-.2 1.2-1.1 2.2-2.3 2.2z"></path></svg>
					</button>
				</li>
			<?php endif; ?>
			<?php if( is_user_logged_in() ): ?>
				<?php
				$user_id	= get_current_user_id();
				$balance	= mlmFire()->wallet->get_balance( $user_id );
				$user_name	= mlm_get_user_name( $user_id );
				?>
				<li class="nav-item py-2 position-relative z4">
					<div class="user-tools mr-2 dropdown clearfix">
						<button class="app-user-btn btn p-0 border-0 bg-transparent dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo get_avatar( $user_id, 48, NULL , $user_name, array( 'class' => 'rounded-circle bg-white shadow-sm' ) ); ?>
						</button>
						<div class="dropdown-menu">
							<a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>" class="dropdown-item ellipsis font-13 bold-600 py-2 px-3 transition">
								<?php echo $user_name; ?><br />
								<?php _e( 'Balance:', 'mlm' ); ?> <?php echo mlm_filter( $balance ); ?>
							</a>
							<div class="dropdown-divider"></div>
							<a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>section/tickets-new/" class="dropdown-item ellipsis font-13 bold-600 py-2 px-3 transition">
								<?php _e( 'New ticket', 'mlm' ); ?>
							</a>
							<div class="dropdown-divider"></div>
							<a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>section/profile/" class="dropdown-item ellipsis font-13 bold-600 py-2 px-3 transition">
								<?php _e( 'Edit profile', 'mlm' ); ?>
							</a>
							<div class="dropdown-divider"></div>
							<a href="<?php echo mlm_wc_logut_url(); ?>" class="dropdown-item ellipsis font-13 bold-600 py-2 px-3 transition">
								<svg viewBox="0 0 15 16.658"><g transform="translate(6705.098 -722.842)" fill="none" stroke="#E85D4A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"><path d="M-6697.943 723.842h6.068c.46.03.808.426.779.886V737.606a.834.834 0 0 1-.77.893l-.008.001h-6.068"></path><g transform="translate(-6704.098 726.895)"><path d="M3.697 8.211l-3.7-4.105L3.697 0M.452 4.105h8.476"></path></g></g></svg>
								<?php _e( 'Sign out', 'mlm' ); ?>
							</a>
						</div>
					</div>
				</li>
			<?php else: ?>
				<li class="nav-item py-2 position-relative z4">
					<a href="<?php echo mlm_page_url('login'); ?>" class="btn login-btn font-15 transition rounded">
						<?php _e( 'Login', 'mlm' ); ?>
					</a>
				</li>
				<li class="nav-item py-2 position-relative z4">
					<a href="<?php echo mlm_page_url('register'); ?>" class="btn register-btn font-15 transition rounded">
						<?php _e( 'Register', 'mlm' ); ?>
					</a>
				</li>
			<?php endif; ?>
		</div>
	</div>
</div>