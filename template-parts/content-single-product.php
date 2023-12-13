<?php
$post_id		= get_the_ID();
$category_list	= get_the_term_list( $post_id, 'product_cat', '', ' ', '' );
$tag_list		= get_the_term_list( $post_id, 'product_tag', '', ' ', '' );
$course_video	= get_post_meta( $post_id, 'mlm_course_video', true );
?>

<article id="article-<?php the_ID(); ?>" class="mlm-single-product">
	<div itemscope itemtype="http://schema.org/Product">
		<header class="page-header my-4 p-0 clearfix">
			<?php mlm_breadcrumbs(); ?>
			<?php the_title( '<h1 class="page-title entry-title mlm-box-title m-0" itemprop="name">', '</h1>' ); ?>
		</header>
		<div class="mlm-widget bg-white p-4 mb-4 clearfix">
			<div class="row">
				<div class="col-12 col-lg-8">
					<nav class="mlm-product-nav p-0 mx-0 mb-3">
						<div class="nav nav-pills nav-fill m-0 p-0" id="mlm-product-tabs" role="tablist">
							<a class="nav-item nav-link mx-1 my-0 active" id="mlm-product-tab1" data-toggle="tab" href="#mlm-product-nav1" role="tab" aria-controls="mlm-product-nav1" aria-selected="true">
								<span class="icon icon-notebook"></span><?php _e( 'Product details', 'mlm' ); ?>
							</a>
							<a class="nav-item nav-link mx-1 my-0" id="mlm-product-tab2" data-toggle="tab" href="#mlm-product-nav2" role="tab" aria-controls="mlm-product-nav2" aria-selected="false">
								<span class="icon icon-flag1"></span><?php _e( 'Purchase tips', 'mlm' ); ?>
							</a>
							<a class="nav-item nav-link mx-1 my-0" id="mlm-product-tab3" data-toggle="tab" href="#mlm-product-nav3" role="tab" aria-controls="mlm-product-nav3" aria-selected="false">
								<span class="icon icon-chat"></span><?php _e( 'Comments', 'mlm' ); ?>
							</a>
							<a class="nav-item nav-link mx-1 my-0" id="mlm-product-tab4" data-toggle="tab" href="#mlm-product-nav4" role="tab" aria-controls="mlm-product-nav4" aria-selected="false">
								<span class="icon icon-megaphone"></span><?php _e( 'Product support', 'mlm' ); ?>
							</a>
						</div>
					</nav>
					<div class="mlm-product-tab-content tab-content clearfix">
						
						<div class="tab-pane fade show active" id="mlm-product-nav1" role="tabpanel" aria-labelledby="mlm-product-tab1">
							<figure class="entry-thumbnail p-0 mb-3 clearfix" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
								<?php if( mlm_check_course( $post_id ) && ! empty( $course_video ) ): ?>
									<?php echo htmlspecialchars_decode( $course_video ); ?>
								<?php elseif( has_post_thumbnail() && ! post_password_required() ): ?>
									<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid w-100 mb-2', 'itemprop' => 'contentUrl' ) ); ?>
								<?php endif; ?>
								<div class="row no-gutters">
									<div class="col-auto bg-light rounded py-1 px-3 my-1">
										<?php _e( 'Product id', 'mlm' ); ?>: <span class="bold-600"><?php echo $post_id; ?></span>
									</div>
									<?php get_template_part( 'template-parts/content', 'share' ); ?>
								</div>
							</figure>
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
								<?php get_template_part( 'template-parts/product', 'meta' ); ?>
								<?php get_template_part( 'template-parts/course', 'articles' ); ?>
							</footer>
						</div>
						
						<div class="tab-pane fade" id="mlm-product-nav2" role="tabpanel" aria-labelledby="mlm-product-tab2">
							<?php get_template_part( 'template-parts/product', 'help' ); ?>
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
				<div class="col-12 col-lg-4">
					<?php  get_template_part( 'template-parts/product', 'purchase' ); ?>
					<?php get_template_part( 'template-parts/product', 'bookmark' ); ?>
					<?php get_template_part( 'template-parts/product', 'medals' ); ?>
					<?php get_template_part( 'template-parts/product', 'gallery' ); ?>
					<?php get_template_part('template-parts/product', 'download-history'); ?>
					<?php get_template_part( 'template-parts/product', 'vendor' ); ?>
					<?php get_template_part( 'template-parts/course', 'teacher' ); ?>
					<?php get_template_part( 'template-parts/course', 'status' ); ?>
					<?php get_sidebar('single'); ?>
				</div>
			</div>
		</div>
		<div class="mlm-widget bg-white p-4 mb-4 clearfix">
			<?php if( $category_list ): ?>
				<div class="mlm-product-cat-box mb-4 p-0 clearfix">
					<h3 class="mlm-box-title icon icon-briefcase1 sm mb-2"><?php _e( 'Categories', 'mlm' ); ?></h3>
					<div class="mlm-categories m-0 p-0">
						<?php echo $category_list; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if( $tag_list ): ?>
				<div class="mlm-product-tags-box mb-4 p-0 clearfix">
					<h3 class="mlm-box-title icon icon-pricetags sm mb-2"><?php _e( 'Tags', 'mlm' ); ?></h3>
					<div class="mlm-post-tags kapali position-relative m-0 pb-5 bold-300 clearfix">
						<?php echo $tag_list; ?>
						<a href="#mlm-toggle-tags" class="btn btn-secondary py-0 px-3 float-left"><?php _e( 'See all', 'mlm' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php get_template_part( 'template-parts/product', 'fixed-btn' ); ?>
</article>