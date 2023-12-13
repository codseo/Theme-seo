<?php
$user_id	= get_queried_object_id();
$user_data	= get_userdata( $user_id );
$user_name	= $user_data->display_name;
$user_url	= get_author_posts_url( $user_id );
$section	= isset( $_GET['section'] ) ? esc_attr( $_GET['section'] ) : '';
$paged		= get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
?>
	
<header class="page-header m-0 clearfix">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h2 class="font-28 bold-400 text-white ellipsis my-3">
					<span class="icon icon-notebook"></span>
					<?php echo $user_name; ?>
				</h2>
			</div>
			<div class="col-auto">
				<?php mlm_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</header>
<section id="primary" class="content-area mt-5">
	<main id="app-main-content" class="site-main container">
	
		<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
			<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
		<?php endif; ?>
	
		<?php get_template_part( 'template-parts/vendor', 'box' ); ?>
	
		<?php if( $section == 'products' ): ?>
		
			<?php
			$product_query	= new WP_Query( array(
				'post_type'			=> 'product',
				'post_status'		=> 'publish',
				'author'			=> $user_id,
				'paged'				=> $paged,
			) );
			?>
			<?php if( $product_query->have_posts() ): ?>
				<div class="app-products-archive mb-4 clearfix">
					<div class="row">
						<?php while( $product_query->have_posts() ): $product_query->the_post(); ?>
							<div class="col-12 col-md-4 col-lg-3">
								<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<?php mlm_navigation( $product_query ); ?>
			<?php endif; ?>
		
		<?php elseif( $section == 'posts' ): ?>
		
			<?php
			$post_query	= new WP_Query( array(
				'post_type'			=> 'post',
				'post_status'		=> 'publish',
				'author'			=> $user_id,
				'paged'				=> $paged,
			) );
			?>
			<?php if( $post_query->have_posts() ): ?>
				<div class="mlm-archive mb-4 clearfix">
					<div class="row">
						<?php while( $post_query->have_posts() ): $post_query->the_post(); ?>
							<div class="col-12 col-md-6 col-lg-4">
								<?php get_template_part( 'template-parts/content', 'blog' ); ?>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<?php mlm_navigation( $post_query ); ?>
			<?php endif; ?>
		
		<?php else: ?>
			
			<?php
			$product_query	= new WP_Query( array(
				'post_type'			=> 'product',
				'post_status'		=> 'publish',
				'posts_per_page'	=> 10,
				'author'			=> $user_id,
				//'ignore_sticky_posts'	=> 1,
			) );
			
			$post_query	= new WP_Query( array(
				'post_type'			=> 'post',
				'post_status'		=> 'publish',
				'posts_per_page'	=> 10,
				'author'			=> $user_id,
				//'ignore_sticky_posts'	=> 1,
			) );
			$user_query = new WP_User_Query( array(
				'exclude'				=> array( $user_id ),
				'number'				=> 18,
				//'role__not_in'			=> 'mlm_block',
				'meta_key'				=> 'mlm_balance',
				'orderby'				=> 'meta_value_num',
				'order'					=> 'DESC',
				'has_published_posts'	=> array('product'),
			) );
			?>
			
			<?php if( $product_query->have_posts() ): ?>
				<div class="mlm-products-slider-wrapper app-products-archive mb-4 clearfix">
					<h3 class="mlm-box-title mb-3 py-2 icon icon-video d-block clearfix">
						<?php printf( __( "%s's recent products", 'mlm' ), $user_name ); ?>
						<a href="<?php echo add_query_arg( 'section', 'products', $user_url ); ?>" class="btn btn-light rounded-pill float-left mr-3"><?php _e( 'View More', 'mlm' ); ?></a>
					</h3>
					<div class="mlm-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php while( $product_query->have_posts() ): $product_query->the_post(); ?>
								<div class="swiper-slide" style="overflow:visible">
									<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( $post_query->have_posts() ): ?>
				<div class="mlm-posts-slider-wrapper mlm-archive mb-4 clearfix">
					<h3 class="mlm-box-title mb-3 py-2 icon icon-notebook d-block clearfix">
						<?php printf( __( "%s's recent posts", 'mlm' ), $user_name ); ?>
						<a href="<?php echo add_query_arg( 'section', 'posts', $user_url ); ?>" class="btn btn-light rounded-pill float-left mr-3"><?php _e( 'View More', 'mlm' ); ?></a>
					</h3>
					<div class="mlm-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php while( $post_query->have_posts() ): $post_query->the_post(); ?>
								<div class="swiper-slide">
									<?php get_template_part( 'template-parts/content', 'blog' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $user_query->get_results() ) ): ?>
				<div class="mlm-vendors-slider-wrapper mlm-category-widget mb-4 clearfix">
					<h3 class="mlm-box-title mb-3 py-2 icon icon-global d-block">
						<?php _e( "Shop vendors", 'mlm' ); ?>
					</h3>
					<div class="mlm-vendor-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php foreach ( $user_query->get_results() as $user ): ?>
								<div class="swiper-slide">
									<div class="mlm-category-box bg-white text-center p-2 m-0 rounded transition clearfix">
										<?php echo get_avatar( $user->ID, 80, '' , $user->display_name, array( 'class' => 'item-image rounded-circle d-block mx-auto' ) ); ?>
										<h5 class="item-title my-2 bold-600"><?php echo $user->display_name; ?></h5>
										<a href="<?php echo get_author_posts_url( $user->ID ); ?>" class="btn btn-light py-0 rounded-pill" title="<?php echo $user->display_name; ?>" rel="bookmark"><?php _e( 'View', 'mlm' ); ?></a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
		<?php endif; ?>
	
	</main>
</section>