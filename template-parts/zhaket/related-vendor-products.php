<?php
global $post;
$vendor_name	= mlm_get_user_name( $post->post_author );
$related_query	= new WP_Query( array(
	'post_type'			=> 'product',
	'post_status'		=> 'publish',
	'author'			=> $post->post_author,
	'post__not_in'		=> array( $post->ID ),
	'posts_per_page'	=> 4,
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
	<div class="app-products-archive mb-5 clearfix">
		<div class="container">
			<div class="box-title mb-4 clearfix">
				<h3 class="title position-relative font-18 text-secondary bold-600 my-3">
					<span class="position-absolute icon icon-ribbon"></span>
					<?php printf( __( "%s's other products", 'mlm' ), $vendor_name ); ?>
					<span class="ellipsis bold-300 font-14">Other Products</span>
				</h3>
			</div>
			<div class="row">
				<?php while( $related_query->have_posts() ): $related_query->the_post(); ?>
					<div class="col-12 col-sm-6 col-lg-3">
						<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
					</div>
				<?php endwhile; wp_reset_query(); ?>
			</div>				
		</div>
	</div>
<?php endif; ?>