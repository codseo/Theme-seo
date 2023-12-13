<?php
$query	= new WP_Query( array(
	'post_type'				=> 'product',
	'post_status'			=> 'publish',
	'posts_per_page'		=> (int)get_option('mlm_product_count'),
	//'ignore_sticky_posts'	=> 1,
) );
?>

<?php if( $query->have_posts() && function_exists( 'wc_get_page_id' ) ): ?>

	<div class="mlm-recent-products-wrapper mb-4 clearfix">
		<h3 class="mlm-box-title mb-3 py-2">
			<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="text-dark"><?php _e( 'New products', 'mlm' ); ?></a>
		</h3>
		<div class="mlm-archive mb-4 clearfix">
			<div class="row">
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<div class="col-12 col-md-6 col-lg-4">
						<?php get_template_part( 'template-parts/content', 'product' ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>

<?php endif; ?>