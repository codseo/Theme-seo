<?php
global $post;
$related_type	= get_option('mlm_related_type');
$taxonomy		= ( $related_type == 'cat' ) ? 'category' : 'post_tag';
$tag_ids		= mlm_category_list( $post->ID, 'post_tag', false );
$related_query	= new WP_Query( array(
	'post_type'			=> 'post',
	'post_status'		=> 'publish',
	'post__not_in'		=> array( $post->ID ),
	'posts_per_page'	=> 10,
	'tax_query'			=> array( array(
		'taxonomy'  => $taxonomy,
		'terms'     => $tag_ids,
		'operator'  => 'IN'
	) ),
) );
?>

<?php if( $related_query->have_posts() ): ?>
	<div class="mlm-products-slider-wrapper mlm-archive mb-4 clearfix">
		<h3 class="mlm-box-title mb-3 py-2"><?php _e( 'Related posts', 'mlm' ); ?></h3>
		<div class="mlm-products-slider swiper-container">
			<div class="swiper-wrapper">
				<?php while( $related_query->have_posts() ): $related_query->the_post(); ?>
					<div class="swiper-slide">
						<?php get_template_part( 'template-parts/content', 'post' ); ?>
					</div>
				<?php endwhile; wp_reset_query(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>