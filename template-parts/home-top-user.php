<?php
$user_id	= mlmFire()->dashboard->top_vendor_of_week();
?>

<?php if( mlm_user_exists( $user_id ) ): ?>
	
	<?php
	$user_name	= mlm_get_user_name( $user_id );
	$verified	= mlmFire()->dashboard->get_account_status( $user_id );
	$query	= new WP_Query( array(
		'post_type'			=> 'product',
		'post_status'		=> 'publish',
		'author'			=> $user_id,
		'posts_per_page'	=> 8,
	) );
	?>
	
	<div class="mlm-top-user-widget mb-4 clearfix">
		<h3 class="mlm-box-title mb-3 py-2"><?php _e( 'Top seller of week', 'mlm' ); ?></h3>
		<div class="mlm-widget mlm-product-vendor-widget bg-white p-3 m-0">
			<div class="row">
				<div class="col-12 col-lg-5">
					<div class="vendor-image">
						<?php echo get_avatar( $user_id, 128, NULL , $user_name, array( 'class' => 'rounded-circle d-block mx-auto' ) ); ?>
					</div>
					<div class="vendor-name text-center mb-1 clearfix">
						<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="d-inline-block text-dark bold-300 <?php if( $verified ) echo 'verified'; ?>" <?php if( $verified ) echo 'data-toggle="tooltip" data-placement="left" title="" data-original-title="'. __( 'Verified user', 'mlm' ) .'"'; ?>><?php echo $user_name; ?></a>
					</div>
					<div class="vendor-text text-justify font-11 text-secondary mb-3">
						<?php
						printf(
							__( 'Best seller of the week is %1$s with %2$s valuable products.', 'mlm' ),
							$user_name,
							count_user_posts( $user_id , 'product' )
						);
						?>
					</div>
				</div>
				<div class="col-12 col-lg-7">
					<?php if( $query->have_posts() ): ?>
						<div class="mlm-vendor-top-slider swiper-container">
							<div class="swiper-wrapper">
								<?php while( $query->have_posts() ): $query->the_post(); ?>
									<div class="swiper-slide">
										<a href="<?php the_permalink(); ?>" class="top-product d-block" title="<?php the_title_attribute(); ?>">
											<img src="<?php mlm_image_url( get_the_ID(), 'thumbnail' ); ?>" class="rounded" alt="<?php the_title_attribute(); ?>">
										</a>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="vendor-link pt-3 mt-3 border-top border-light">
						<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="btn btn-light btn-block rounded-pill"><?php _e( 'View shop', 'mlm' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>