<?php
$sale_query		= new WP_Query( array(
	'post_type'				=> 'product',
	'post_status'			=> 'publish',
	'orderby'				=> 'meta_value_num',
	'order'					=> 'DESC',
	'meta_key'				=> 'total_sales',
	'posts_per_page'		=> (int)get_option('mlm_product_count'),
	'meta_query'			=> array( array(
		'key'		=> '_price',
		'value'		=> 0,
		'compare'	=> '>',
	) ),
) );

$new_query		= new WP_Query( array(
	'post_type'				=> 'product',
	'post_status'			=> 'publish',
	'orderby'				=> 'date',
	'order'					=> 'DESC',
	'posts_per_page'		=> (int)get_option('mlm_product_count'),
) );

$update_query	= new WP_Query( array(
	'post_type'				=> 'product',
	'post_status'			=> 'publish',
	'orderby'				=> 'modified',
	'order'					=> 'DESC',
	'posts_per_page'		=> (int)get_option('mlm_product_count'),
) );
?>

<div class="app-home-tabs mb-5 clearfix">
	<div class="mlm-container">
		<div class="mb-4 text-center clearfix">
			<div class="d-inline-block">
				<ul class="app-tabs-nav nav nav-tabs m-0 p-0 justify-content-center" role="tablist">
					<li class="nav-item m-0 position-relative">
						<a class="nav-link font-13 bold-600 rounded-0 border-0 active" id="app-sale-tab" data-toggle="tab" href="#app-sale" role="tab" aria-controls="app-sale" aria-selected="true">
							<?php _e( 'Best selling', 'mlm' ); ?>
						</a>
					</li>
					<li class="nav-item m-0 position-relative">
						<a class="nav-link font-13 bold-600 rounded-0 border-0" id="app-recent-tab" data-toggle="tab" href="#app-recent" role="tab" aria-controls="app-recent" aria-selected="false">
							<?php _e( 'Most recent', 'mlm' ); ?>
						</a>
					</li>
					<li class="nav-item m-0 position-relative">
						<a class="nav-link font-13 bold-600 rounded-0 border-0" id="app-update-tab" data-toggle="tab" href="#app-update" role="tab" aria-controls="app-update" aria-selected="false">
							<?php _e( 'Just updated', 'mlm' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="tab-content m-0 p-0">
			<div class="tab-pane fade show active" id="app-sale" role="tabpanel" aria-labelledby="app-sale-tab">
				<?php if( $sale_query->have_posts() ): ?>
					<div class="app-mini-products clearfix">
						<div class="row align-items-center justify-content-center no-gutters">
							<?php while( $sale_query->have_posts() ): $sale_query->the_post(); ?>
								<div class="product-col col-auto">
									<?php get_template_part( 'template-parts/zhaket/content', 'thumb' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
					<?php if( function_exists( 'wc_get_page_id' ) ): ?>
						<?php
						$url	= get_permalink( wc_get_page_id( 'shop' ) );
						$url	= add_query_arg( 'mlm_order', 'sale', $url );
						?>
						<div class="text-center mt-4">
							<a href="<?php echo $url; ?>" class="btn btn-grey btn-lg no-shadow font-14 bold-600 py-2 px-4">
								<?php _e( 'See all', 'mlm' ); ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="tab-pane fade" id="app-recent" role="tabpanel" aria-labelledby="app-recent-tab">
				<?php if( $new_query->have_posts() ): ?>
					<div class="app-mini-products clearfix">
						<div class="row align-items-center justify-content-center no-gutters">
							<?php while( $new_query->have_posts() ): $new_query->the_post(); ?>
								<div class="product-col col-auto">
									<?php get_template_part( 'template-parts/zhaket/content', 'thumb' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
					<?php if( function_exists( 'wc_get_page_id' ) ): ?>
						<?php
						$url	= get_permalink( wc_get_page_id( 'shop' ) );
						$url	= add_query_arg( 'mlm_order', 'new', $url );
						?>
						<div class="text-center mt-4">
							<a href="<?php echo $url; ?>" class="btn btn-grey btn-lg no-shadow font-14 bold-600 py-2 px-4">
								<?php _e( 'See all', 'mlm' ); ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="tab-pane fade" id="app-update" role="tabpanel" aria-labelledby="app-update-tab">
				<?php if( $update_query->have_posts() ): ?>
					<div class="app-mini-products clearfix">
						<div class="row align-items-center justify-content-center no-gutters">
							<?php while( $update_query->have_posts() ): $update_query->the_post(); ?>
								<div class="product-col col-auto">
									<?php get_template_part( 'template-parts/zhaket/content', 'thumb' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
					<?php if( function_exists( 'wc_get_page_id' ) ): ?>
						<?php
						$url	= get_permalink( wc_get_page_id( 'shop' ) );
						$url	= add_query_arg( 'mlm_order', 'update', $url );
						?>
						<div class="text-center mt-4">
							<a href="<?php echo $url; ?>" class="btn btn-grey btn-lg no-shadow font-14 bold-600 py-2 px-4">
								<?php _e( 'See all', 'mlm' ); ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="app-fixed-popup-box d-none d-md-block p-0 m-0 bg-white transition overflow-hidden clearfix">
		<div class="item-image position-relative overflow-hidden clearfix">
			<img src="<?php echo IMAGES . '/no-thumbnail.png'; ?>" class="position-absolute" alt="Image">
			<div class="item-avatar position-absolute clearfix">
				<div class="row align-items-center no-gutters mx-n1">
					<div class="avatar-col col px-1">
						<img src="<?php echo IMAGES . '/no-thumbnail.png'; ?>" class="avatar d-block rounded-circle" alt="vendor" />
					</div>
					<div class="name-col col px-1">
						<span class="item-vendor ellipsis text-white font-15 bold-600"></span>
						<span class="item-bio ellipsis text-white font-12 bold-400"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="item-title px-3 my-3 font-16 bold-600 text-secondary">
			
		</div>
		<div class="item-text px-3 pb-4 font-12 bold-400 text-justify text-grey">
			
		</div>
		<div class="row no-gutters mx-n1 py-3 px-1 text-center">
			<div class="col px-1">
				<div class="item-sale ellipsis font-15 bold-600 text-secondary">
					<?php _e( '0 sales', 'mlm' ); ?>
				</div>
			</div>
			<div class="col px-1">
				<div class="item-rate ellipsis font-15 bold-600 text-secondary">
					0
				</div>
			</div>
			<div class="col px-1">
				<div class="item-price ellipsis font-15 bold-600 text-warning">
					<span class="v">0</span> 
					<?php if( function_exists('get_woocommerce_currency_symbol') ): ?>
						<span class="text-grey font-12"><?php echo get_woocommerce_currency_symbol(); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>