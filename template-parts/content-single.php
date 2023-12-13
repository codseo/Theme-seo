<?php
$category_list	= get_the_category_list( ' ' );
$tag_list		= get_the_tag_list( '', ' ', '' );
$post_id		= get_the_ID();
$demo			= mlm_selected_demo();
?>

<article id="article-<?php the_ID(); ?>" class="mlm-single-post">
	<?php if( $demo != 'zhaket' ): ?>
		<header class="page-header my-4 p-0 clearfix">
			<?php mlm_breadcrumbs(); ?>
			<?php the_title( '<h1 class="page-title entry-title mlm-box-title m-0">', '</h1>' ); ?>
		</header>
	<?php endif; ?>
	<div class="mlm-widget bg-white p-4 mb-4 clearfix">
		<figure class="entry-thumbnail p-0 mb-3 clearfix">
			<?php if( has_post_thumbnail() && ! post_password_required() ): ?>
				<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid w-100 mb-2', 'itemprop' => 'image' ) ); ?>
			<?php endif; ?>
			<div class="row no-gutters">
				<div class="col-auto">
					<div class="d-block bg-light rounded py-1 px-3 my-1">
						<?php _e( 'Post id', 'mlm' ); ?>: <span class="bold-600"><?php echo $post_id; ?></span>
					</div>
				</div>
				<div class="col-auto px-2">
					<div class="d-block bg-light rounded py-1 px-3 my-1">
						<?php _e( 'Views', 'mlm' ); ?>: <span class="bold-600"><?php echo mlm_get_post_views( $post_id ); ?></span>
					</div>
				</div>
				<?php get_template_part( 'template-parts/content', 'share' ); ?>
			</div>
		</figure>
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
			<?php /* get_template_part( 'template-parts/content', 'rating' ); */ ?>
		</div>
	</div>
	<footer class="entry-footer mlm-widget bg-white p-4 mb-4 clearfix">
		<?php if( $category_list ): ?>
			<div class="mlm-post-cat-box mb-4 p-0 clearfix">
				<h3 class="mlm-box-title icon icon-briefcase1 sm mb-2">
					<?php _e( 'Categories', 'mlm' ); ?>
				</h3>
				<div class="mlm-categories m-0 p-0">
					<?php echo $category_list; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if( $tag_list ): ?>
			<div class="mlm-post-tags-box mb-4 p-0 clearfix">
				<h3 class="mlm-box-title icon icon-pricetags sm mb-2">
					<?php _e( 'Tags', 'mlm' ); ?>
				</h3>
				<div class="mlm-post-tags kapali position-relative m-0 pb-5 bold-300 clearfix">
					<?php echo $tag_list; ?>
					<a href="#mlm-toggle-tags" class="btn btn-secondary py-0 px-3 float-left"><?php _e( 'See all', 'mlm' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</footer>
	
	<?php get_template_part( 'template-parts/post', 'author' ); ?>
</article>