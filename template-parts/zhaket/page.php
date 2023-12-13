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
	<div class="container">
		
		<?php if( is_active_sidebar( 'mlm-archive-top' ) ): ?>
			<?php dynamic_sidebar( 'mlm-archive-top' ); ?>
		<?php endif; ?>
	
		<?php if( is_active_sidebar( 'mlm-page-sidebar' ) ): ?>
			<div class="row">
				<div class="col-12 col-lg-9">
		<?php endif; ?>
		
					<main id="app-main-content" class="site-main">
						<?php
						while( have_posts() ): the_post();
							
							get_template_part( 'template-parts/content', 'page' );
							
							if( comments_open() || get_comments_number() )
							{
								comments_template();
							}
							
						endwhile;
						?>
					
					</main>
					
		<?php if( is_active_sidebar( 'mlm-page-sidebar' ) ): ?>
				</div>
				<div class="col-12 col-lg-3">
					<aside id="sidebar">
						<?php dynamic_sidebar( 'mlm-page-sidebar' ); ?>
					</aside>
				</div>
			</div>
		<?php endif; ?>
		
	</div>
</section>