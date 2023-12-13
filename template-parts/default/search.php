<main id="main" class="site-main col-12">

	<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
		<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
	<?php endif; ?>

	<form name="mlm-advanced-search-form" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
		<div class="row">

			<div class="app-search-page-wrapper col-lg-4 col-xl-3">
				<div class="slimscroll h-100">
					<?php get_template_part( 'template-parts/default/sidebar', 'search' ); ?>
				</div>
			</div>

			<div class="col-12 col-lg-8 col-xl-9">

				<?php $order = get_query_var( 'mlm_order', 'new' ); ?>
				<div class="mlm-search-page-header mb-3 clearfix">
					<div class="row no-gutters mx-n2 align-items-center">
						<div class="filter-col col px-2 d-lg-none">
							<button id="app-toggle-search-menu" type="button" class="app-search-filter-btn btn btn-light">
								<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
							</button>
						</div>
						<div class="input-col col col-lg-12 px-2">
							<div class="search-input-group input-group m-0 rounded-pill">
								<input name="s" type="text" class="form-control border-0 rounded-pill" value="<?php echo get_search_query(); ?>" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>" />
								<div class="input-group-append">
									<button type="submit" class="search-btn btn border-0 bg-transparent">
										<svg viewBox="-4.615 -5.948 39.083 39.417"><path stroke="#4D4D4D" stroke-width="1" d="M33.207 30.77L25.6 23c-.064-.065-.143-.104-.218-.148 2.669-2.955 4.31-6.856 4.31-11.143 0-9.189-7.476-16.665-16.665-16.665S-3.638 2.52-3.638 11.709s7.476 16.665 16.665 16.665c4.221 0 8.067-1.59 11.007-4.186.042.072.076.148.137.211l7.607 7.77a.998.998 0 0 0 1.414.016 1.002 1.002 0 0 0 .015-1.415zm-20.18-4.397c-8.086 0-14.665-6.578-14.665-14.665S4.94-2.956 13.027-2.956c8.086 0 14.665 6.579 14.665 14.665s-6.579 14.664-14.665 14.664z"></path></svg>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row mt-2 mb-0 align-items-center">
						<label class="col-sm-auto col-form-label"><?php _e( 'Sort by:', 'mlm' ); ?></label>
						<div class="col-sm-auto">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="mlm_order" id="mlm_order_sale" value="sale" <?php checked( $order, 'sale' ); ?>>
								<label class="form-check-label" for="mlm_order_sale"><?php _e( 'Best selling', 'mlm' ); ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="mlm_order" id="mlm_order_new" value="new" <?php checked( $order, 'new' ); ?>>
								<label class="form-check-label" for="mlm_order_new"><?php _e( 'Newest', 'mlm' ); ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="mlm_order" id="mlm_order_low" value="low" <?php checked( $order, 'low' ); ?>>
								<label class="form-check-label" for="mlm_order_low"><?php _e( 'Lowest price', 'mlm' ); ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="mlm_order" id="mlm_order_high" value="high" <?php checked( $order, 'high' ); ?>>
								<label class="form-check-label" for="mlm_order_high"><?php _e( 'Highest price', 'mlm' ); ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="mlm_order" id="mlm_order_update" value="update" <?php checked( $order, 'update' ); ?>>
								<label class="form-check-label" for="mlm_order_update"><?php _e( 'Last update', 'mlm' ); ?></label>
							</div>
						</div>
					</div>
				</div>

				<?php if( have_posts() ): ?>
					<div class="mlm-archive mb-4 clearfix">
						<div class="row">
							<?php while( have_posts() ): the_post(); ?>
								<div class="col-12 col-md-6">
									<?php get_template_part( 'template-parts/content', 'blog' ); ?>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
					<?php mlm_navigation(); ?>
				<?php else: ?>
					<div class="alert alert-warning"><?php _e( 'Nothing found', 'mlm' ); ?></div>
				<?php endif; ?>
			</div>

		</div>
	</form>

	<?php if( is_active_sidebar( 'mlm-archive-bottom' ) ): ?>
		<?php dynamic_sidebar( 'mlm-archive-bottom' ); ?>
	<?php endif; ?>

</main>