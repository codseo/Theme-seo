<header class="page-header m-0 clearfix">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h2 class="font-28 bold-400 text-white ellipsis my-3">
					<span class="icon icon-notebook"></span>
					<?php the_title(); ?>
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
	
		<?php while( have_posts() ): the_post(); ?>
		
			<article id="article-<?php the_ID(); ?>" class="mlm-single-post">
				
				<?php if( is_active_sidebar( 'mlm-namads' ) ): ?>
					<aside id="mlm-site-licenses" class="row justify-content-center">
						<?php dynamic_sidebar( 'mlm-namads' ); ?>
					</aside>
				<?php endif; ?>
				
			</article>
			
		<?php endwhile; ?>
	
	</main>
</section>