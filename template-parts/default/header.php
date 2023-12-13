<?php
$site_logo		= get_option( 'mlm_logo' );
$notbar_text	= get_option('mlm_notbar_text');
$notbar_btn		= get_option('mlm_notbar_btn');
$notbar_url		= get_option('mlm_notbar_url');
$hide_notif		= isset( $_COOKIE['mlm_hide_notif'] ) ? true : false;
?>

<div id="mlm-fixed-menu-holder"></div>
<header id="header" class="mlm-header bg-white mb-4 clearfix">

	<?php if( ! empty( $notbar_text ) && ! $hide_notif ): ?>
		<div class="app-notification py-1 overflow-hidden clearfix">
			<div class="container h-100">
				<div class="row align-items-center h-100 no-gutters mx-n2">
					<div class="close-col col px-2">
						<button type="button" class="close-notification-btn btn btn-light py-1 text-secondary">
							<span class="font-28 d-block bold-600">×</span>
						</button>
					</div>
					<div class="text-col col px-2">
						<div class="text-center text-white font-14 bold-600 clearfix">
							<?php if( ! empty( $notbar_url ) ): ?>
								<a href="<?php echo $notbar_url; ?>" class="text-white font-14 bold-600">
									<?php echo $notbar_text; ?>
								</a>
							<?php else: ?>
								<?php echo $notbar_text; ?>
							<?php endif; ?>
						</div>
					</div>
					<?php if( ! empty( $notbar_url ) ): ?>
						<div class="action-col col px-2 d-none d-md-flex">
							<a href="<?php echo $notbar_url; ?>" class="btn btn-light btn-block ellipsis py-1 font-14 bold-600 text-secondary">
								<?php echo $notbar_btn; ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/navigation', 'secondary' ); ?>
	<div class="container">
		<div class="mlm-top-row p-0 m-0">
			<div class="row justify-content-between">
				<?php if( is_user_logged_in() ): ?>
					<div class="col-12 col-sm-6 col-md-auto">
				<?php else: ?>
					<div class="col-6 col-md-auto">
				<?php endif; ?>
					<form class="mlm-ajax-search position-relative my-2" action="<?php echo esc_url( home_url('/') ); ?>" method="get">
						<div class="input-group m-0 p-0">
							<input type="search" name="s" class="form-control py-1" value="<?php echo get_search_query(); ?>" placeholder="<?php _e( "Search for ...", "mlm" ); ?>" aria-label="<?php _e( "Search for ...", "mlm" ); ?>" data-verify="<?php echo wp_create_nonce('mlm_farolmokr'); ?>">
							<div class="input-group-append">
								<button class="btn btn-primary py-0" type="submit">
									<span class="icon icon-search bold-icon"></span>
								</button>
							</div>
						</div>
						<div class="mlm-search-results mlm-widget bg-white position-absolute text-justify m-0 p-0 rounded clearfix"></div>
					</form>
				</div>
				<?php if( is_user_logged_in() ): ?>
					<div class="col-12 col-sm-6 col-md-auto">
				<?php else: ?>
					<div class="col-6 col-md-auto">
				<?php endif; ?>
					<?php if( is_user_logged_in() ): ?>
						<?php
						$user_id	= get_current_user_id();
						$balance	= mlmFire()->wallet->get_balance( $user_id );
						$announce	= mlmFire()->announce->check_user_announce( $user_id );
                        $current_url = $_SERVER['REQUEST_URI'];

						if($announce && !strpos($current_url, 'announce')) {
                            ?>

                            <div class="modal fade" id="announce_modal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-vertical-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><span class="icon icon-bell"></span><?php _e('New notification', 'mlm'); ?></h5>
                                            <button type="button" class="close mr-auto ml-0" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>section/announce/" class="notification-btn my-2 d-block position-relative px-1">
                                              <div id="announce-bell-animation"></div>
                                                <div class="annonace-modal-note mt-3">
                                                    <?php _e('Click to see the notification', 'mlm'); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <script type="text/javascript">
                          var animation = bodymovin.loadAnimation({
                            // animationData: { /* ... */ },
                            container: document.getElementById('announce-bell-animation'), // required
                            path: '<?php echo SCRIPTS. '/2099-new-notification-bell.json'; ?>' , // required
                            renderer: 'svg', // required
                            loop: true, // optional
                            autoplay: true, // optional
                            name: "Announce Bell Animation", // optional
                          });
                            jQuery(document).ready(function($){
                                    $("#announce_modal").modal();
                            });
                        </script>
                            <?php

                        }

						?>
						<div class="row no-gutters">
							<div class="col">
								<a href="<?php echo trailingslashit( mlm_page_url('panel') ); ?>section/announce/" class="notification-btn my-2 d-block position-relative px-1 <?php if( $announce ) echo 'new-note'; ?>">
									<span class="d-block icon icon-bell"></span>
								</a>
							</div>
							<div class="col pr-1">
								<a href="<?php echo mlm_page_url('panel'); ?>" class="btn btn-primary btn-block rounded-pill py-1 my-2 ellipsis"><?php _e( "Dashboard", "mlm" ); ?> <span class="d-none d-lg-inline-block"> - <?php _e( "Balance:", "mlm" ); ?> <?php echo mlm_filter( $balance ); ?></span></a>
							</div>
							<div class="col pr-1 d-none d-lg-flex">
								<a href="<?php echo mlm_wc_logut_url(); ?>" class="btn btn-danger btn-block rounded-pill py-1 my-2 ellipsis"><?php _e( "Sign out", "mlm" ); ?></a>
							</div>
						</div>
					<?php else: ?>
						<button type="button" class="btn btn-primary rounded-pill py-1 my-2 ellipsis float-left" data-toggle="modal" data-target="#mlm-login-register-popup"><?php _e( "Login / Register", "mlm" ); ?></button>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="mlm-logo-row py-2 m-0">
			<div class="row justify-content-between align-items-center">
				<div class="col-12 col-lg-auto text-center">
					<h1 class="mlm-title mx-0 my-2 text-center">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mlm-logo text-dark" title="<?php bloginfo( 'name' ); ?>" rel="home">
							<?php if( ! empty( $site_logo ) ): ?>
								<img src="<?php echo esc_url( $site_logo ); ?>" class="mx-auto" alt="<?php bloginfo( 'name' ); ?>">
								<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
							<?php else: ?>
								<?php bloginfo( 'name' ); ?>
							<?php endif; ?>
						</a>
					</h1>
				</div>
				<div class="col-12 col-lg-auto flex-fill">
					<?php get_template_part( 'template-parts/navigation', 'top' ); ?>
				</div>
			</div>
		</div>
		<?php get_template_part( 'template-parts/navigation', 'main' ); ?>
	</div>
</header>

<?php get_template_part( 'template-parts/navigation', 'mobile' ); ?>

<section id="primary" class="content-area container">
	<div class="row">