<?php if( is_active_sidebar( 'mlm-cart' ) ): ?>
    <div class="app-cart-popup position-fixed p-0 m-0 transition bg-white clearfix hide">
        <div class="cart-header border-bottom p-3 clearfix">
            <div class="row align-items-center no-gutters mx-n2">
                <div class="title-col col px-2">
                    <span class="ellipsis text-secondary font-16 bold-600"><?php _e( 'You picked these products', 'mlm' ); ?></span>
                </div>
                <div class="btn-col col px-2">
                    <button type="button" class="app-close-cart-btn btn py-3 px-4 no-shadow">
                        <span class="font-32 d-block bold-600">×</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="cart-body p-3">
            <div class="h-100 slimscroll">
                <?php dynamic_sidebar( 'mlm-cart' ); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$notbar_text	= get_option('mlm_notbar_text');
$notbar_btn		= get_option('mlm_notbar_btn');
$notbar_url		= get_option('mlm_notbar_url');
$hide_notif		= isset( $_COOKIE['mlm_hide_notif'] ) ? true : false;
?>

    <header id="header" class="app-fixed-header position-fixed p-0 m-0 bg-white transition clearfix <?php if( is_front_page() ) echo 'home-header home-page'; ?>">

        <?php if( ! empty( $notbar_text ) && ! $hide_notif ): ?>
            <div class="app-notification py-1 overflow-hidden clearfix">
                <div class="mlm-container h-100">
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

        <?php get_template_part( 'template-parts/zhaket/navigation', 'header' ); ?>

    </header>


<?php
if(is_user_logged_in())
{
    $user_id	= get_current_user_id();
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
}
?>