<div class="app-search-popup position-fixed p-2 p-sm-3 p-md-4 m-0 transition bg-white clearfix hide">
	<div class="container">
		<button type="button" class="app-close-search-btn btn py-3 px-4">
			<span class="font-32 d-block bold-600">×</span>
		</button>
		<div class="row justify-content-center">
			<div class="col-12 col-md-10 col-lg-8">
				<form class="mlm-ajax-search position-relative m-0" action="<?php echo esc_url( home_url('/') ); ?>" method="get">
					<div class="search-input-group input-group my-4 mx-0 rounded">
						<input name="s" type="text" class="form-control border-0" value="<?php echo get_search_query(); ?>" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>" data-verify="<?php echo wp_create_nonce('mlm_farolmokr'); ?>" />
						<div class="input-group-append">
							<button type="submit" class="search-btn btn border-0 bg-transparent">
								<svg viewBox="-4.615 -5.948 39.083 39.417"><path stroke="#4D4D4D" stroke-width="1" d="M33.207 30.77L25.6 23c-.064-.065-.143-.104-.218-.148 2.669-2.955 4.31-6.856 4.31-11.143 0-9.189-7.476-16.665-16.665-16.665S-3.638 2.52-3.638 11.709s7.476 16.665 16.665 16.665c4.221 0 8.067-1.59 11.007-4.186.042.072.076.148.137.211l7.607 7.77a.998.998 0 0 0 1.414.016 1.002 1.002 0 0 0 .015-1.415zm-20.18-4.397c-8.086 0-14.665-6.578-14.665-14.665S4.94-2.956 13.027-2.956c8.086 0 14.665 6.579 14.665 14.665s-6.579 14.664-14.665 14.664z"></path></svg>
							</button>
						</div>
					</div>
					<div class="mlm-search-results mlm-widget bg-white position-absolute text-justify m-0 p-0 rounded clearfix"></div>
				</form>
			</div>
		</div>
	</div>
</div>

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

<?php get_template_part( 'template-parts/zhaket/navigation', 'mobile' ); ?>

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

	<div class="app-mega-menu position-relative d-none d-lg-block clearfix">
		<div class="container">
			<?php get_template_part( 'template-parts/zhaket/navigation', 'mega' ); ?>
		</div>
	</div>
	
	<?php if( is_singular('product') ): ?>
		<?php while( have_posts() ): the_post(); ?>
			<?php global $product; ?>
			<?php if( ! $product->is_in_stock() ): ?>
				<div class="mlm-unavailable-product-alert d-block bg-danger text-white d-block text-center p-3">
					<?php _e( 'Product unavailable', 'mlm' ); ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif; ?>
</header>