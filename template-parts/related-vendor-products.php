<?php
global $post;
$vendor_name	= mlm_get_user_name( $post->post_author );
$related_query	= new WP_Query( array(
	'post_type'			=> 'product',
	'post_status'		=> 'publish',
	'author'			=> $post->post_author,
	'post__not_in'		=> array( $post->ID ),
	'posts_per_page'	=> 20,
	'meta_query'		=> array(
		array(
			'key'		=> 'mlm_is_course',
			'value'		=> 'yes',
			'compare'	=> '!=',
		)
	),
) );
?>

<?php if( $related_query->have_posts() ): ?>
	<div class="mlm-products-slider-wrapper mlm-archive mb-4 clearfix">
		<h3 class="mlm-box-title mb-3 py-2"><?php printf( __( "%s's other products", 'mlm' ), $vendor_name ); ?></h3>
		<div class="mlm-vendor-products-slider swiper-container">
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