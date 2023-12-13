<?php
$demo = mlm_selected_demo();
?>

<article id="article-<?php the_ID(); ?>" class="mlm-single-post">
	<?php if( $demo != 'zhaket' ): ?>
		<header class="page-header my-4 p-0 clearfix">
			<?php mlm_breadcrumbs(); ?>
			<?php the_title( '<h1 class="page-title entry-title mlm-box-title m-0">', '</h1>' ); ?>
		</header>
	<?php endif; ?>
	<div class="mlm-widget bg-white p-4 mb-4 clearfix">
		<div class="entry-content text-justify">
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
	</div>
</article>