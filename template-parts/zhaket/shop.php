<header class="page-header m-0 clearfix">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto col-md-9">
				<h2 class="font-28 bold-400 text-white ellipsis my-3">
					<span class="icon icon-notebook"></span>
					<?php the_archive_title(); ?>
				</h2>
				<?php
				if( function_exists('is_shop') && ! is_shop() )
				{
					the_archive_description( '<div class="taxonomy-description font-12 text-justify text-light mt-n2 mb-2">', '</div>' );
				}
				?>
			</div>
			<div class="col-auto col-md-3">
				<div class="float-md-left">
					<?php mlm_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>
</header>

<section id="primary" class="content-area">
	<div class="container">
		
		<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
			<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
		<?php endif; ?>
	
		<?php if( is_active_sidebar( 'mlm-shop-sidebar' ) ): ?>
			<div class="row">
				<div class="col-12 col-lg-9">
		<?php endif; ?>
		
					<main id="app-main-content" class="site-main">
						<?php if( have_posts() ): ?>
							<div class="app-products-archive my-5 clearfix">
								<div class="row">
									<?php while( have_posts() ): the_post(); ?>
										<?php if( is_active_sidebar( 'mlm-shop-sidebar' ) ): ?>
											<div class="col-12 col-sm-6 col-xl-4">
										<?php else: ?>
											<div class="col-12 col-sm-6 col-lg-3">
										<?php endif; ?>
											<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
							<?php mlm_navigation(); ?>
						<?php else: ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php endif; ?>
					</main>
					
		<?php if( is_active_sidebar( 'mlm-shop-sidebar' ) ): ?>
				</div>
				<div class="col-12 col-lg-3">
					<aside id="sidebar">
						<?php dynamic_sidebar( 'mlm-shop-sidebar' ); ?>
					</aside>
				</div>
			</div>
		<?php endif; ?>
	
	</div>
</section>