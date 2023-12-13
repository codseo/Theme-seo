<?php while( have_posts() ): the_post(); ?>
	
	<div itemscope itemtype="http://schema.org/Product">
		<?php get_template_part( 'template-parts/zhaket/product', 'slide' ); ?>
		
		<?php
		global $product;
		$post_id		= get_the_ID();
		$category_list	= get_the_term_list( $post_id, 'product_cat', '', ' ', '' );
		$tag_list		= get_the_term_list( $post_id, 'product_tag', '', ' ', '' );
		$course_video	= get_post_meta( $post_id, 'mlm_course_video', true );
		?>

		<section id="primary" class="content-area">
			<main id="app-main-content" class="site-main">
			
				<div class="app-product-tabs mb-5 p-0">
					<div class="container">
						<ul class="nav nav-tabs m-0 p-0 border-0 bg-transparent">
							<li class="nav-item m-0">
								<a class="nav-link transition font-14 bold-600 active" id="mlm-product-tab1" data-toggle="tab" href="#mlm-product-nav1" role="tab" aria-controls="mlm-product-nav1" aria-selected="true">
									<span class="icon icon-notebook"></span> <?php _e( 'Product details', 'mlm' ); ?>
								</a>
							</li>
							<li class="nav-item m-0">
								<a class="nav-link transition font-14 bold-600" id="mlm-product-tab3" data-toggle="tab" href="#mlm-product-nav3" role="tab" aria-controls="mlm-product-nav3" aria-selected="false">
									<span class="icon icon-chat"></span> <?php _e( 'Comments', 'mlm' ); ?>
								</a>
							</li>
							<li class="nav-item m-0">
								<a class="nav-link transition font-14 bold-600" id="mlm-product-tab4" data-toggle="tab" href="#mlm-product-nav4" role="tab" aria-controls="mlm-product-nav4" aria-selected="false">
									<span class="icon icon-megaphone"></span> <?php _e( 'Product support', 'mlm' ); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
				
				<?php if( ! $product->is_in_stock() ): ?>
				
					<?php get_template_part( 'template-parts/zhaket/product', 'recommend' ); ?>
				
				<?php endif; ?>
				
				<div class="container">
					<div class="single-product-rows row mlm-single-product">
						<div class="single-product-content-col col-12 col-lg-auto">
							
							<div class="mlm-product-tab-content tab-content clearfix">
								<div class="tab-pane fade show active" id="mlm-product-nav1" role="tabpanel" aria-labelledby="mlm-product-tab1">
									<?php if( mlm_check_course( $post_id ) && ! empty( $course_video ) ): ?>
										<figure class="entry-thumbnail p-0 mb-3 clearfix">
											<?php echo htmlspecialchars_decode( $course_video ); ?>
										</figure>
									<?php endif; ?>
									<div class="entry-content text-justify mb-3" itemprop="description">
										<?php
										the_content();
										
										wp_link_pages( array(
											'before'		=> '<div class="page-links">',
											'after'			=> '</div>',
											'link_before'	=> '<span>',
											'link_after'	=> '</span>',
											'pagelink'		=> '<span class="screen-reader-text">page </span>%',
											'separator'		=> '<span class="screen-reader-text">, </span>',
										) );
										?>
									</div>
									<footer class="entry-footer clearfix">
										<?php get_template_part( 'template-parts/product', 'attributes' ); ?>
										<?php get_template_part( 'template-parts/course', 'articles' ); ?>
									</footer>
									<div class="border-top py-2 my-3">
										<div class="row justify-content-between align-items-center">
											<div class="col-auto">
												<?php get_template_part( 'template-parts/zhaket/product', 'bookmark' ); ?>
											</div>
											<div class="col-auto">
												<?php get_template_part( 'template-parts/zhaket/content', 'share' ); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="mlm-product-nav3" role="tabpanel" aria-labelledby="mlm-product-tab3">
									<?php if( comments_open() || get_comments_number() ): ?>
										<?php comments_template(); ?>
									<?php endif; ?>
								</div>
								
								<div class="tab-pane fade" id="mlm-product-nav4" role="tabpanel" aria-labelledby="mlm-product-tab4">
									<?php get_template_part( 'template-parts/product', 'support' ); ?>
								</div>
							</div>
							
						</div>
						<div class="single-product-sidebar-col col-12 col-lg-auto">
							
							<div class="product-sidebar clearfix">
								<?php get_template_part('template-parts/zhaket/product', 'purchase'); ?>
								<?php get_template_part( 'template-parts/zhaket/product', 'rating' ); ?>
								<?php get_template_part( 'template-parts/zhaket/product', 'meta' ); ?>
								<?php get_template_part( 'template-parts/zhaket/product', 'gallery' ); ?>
								<?php get_template_part( 'template-parts/zhaket/product', 'vendor' ); ?>
								<?php get_template_part( 'template-parts/zhaket/course', 'teacher' ); ?>
								<?php get_template_part( 'template-parts/zhaket/course', 'status' ); ?>
								<?php get_template_part( 'template-parts/product', 'download-history' ); ?>
								<?php if( $tag_list ): ?>
									<div class="product-tags-widget mb-4 clearfix">
										<div class="mlm-post-tags kapali position-relative m-0 pb-5 bold-300 clearfix">
											<?php echo $tag_list; ?>
											<a href="#mlm-toggle-tags" class="btn btn-secondary py-0 px-3 float-left font-10"><?php _e( 'See all', 'mlm' ); ?></a>
										</div>
									</div>
								<?php endif; ?>
								<div class="shortlink my-2 clearfix">
									<button class="btn btn-light btn-block mlm-clipboard" data-clipboard-text="<?php echo wp_get_shortlink(); ?>">
										<?php _e( 'Copy shortcode', 'mlm' ); ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				
				</div>
				
				<?php if( $product->is_in_stock() ): ?>
				
					<?php get_template_part( 'template-parts/zhaket/related', 'vendor-products' ); ?>
				
					<?php get_template_part( 'template-parts/zhaket/related', 'vendor-courses' ); ?>
				
					<?php get_template_part( 'template-parts/zhaket/related', 'products' ); ?>
				
				<?php endif; ?>
				
			</main>
		</section>
	
	</div>
	
	<?php get_template_part( 'template-parts/zhaket/product', 'fixed-btn' ); ?>
	
<?php endwhile; ?>