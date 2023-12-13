<?php

class MLM_Featured_Product_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-featured-product-widget',
			__( '7- Featured product', 'mlm' ),
			array(
				'classname'   => 'mlm-featured-product-widget',
				'description' => __( 'Display a featured product on home page', 'mlm' ),
			) 
		);
	}
	
	public function form( $instance ) 
	{
		$defaults			= array(
			'product_id'	=> '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'product_id' ); ?>"><?php _e( 'Product ID', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'product_id' ); ?>" name="<?php echo $this->get_field_name( 'product_id' ); ?>" value="<?php echo esc_attr( $instance['product_id'] ); ?>">
		</p>
		
		<?php
	}
	
	
	public function update( $new_instance, $old_instance )
	{
		$instance				= $old_instance;
		$instance['product_id']	= absint( $new_instance['product_id'] );
		
		return $instance;
	}
	
	
	public function widget( $args, $instance )
	{
		extract( $args );
		
		$post_id	= $instance['product_id'];
		
		if( ! mlm_post_exists( $post_id ) )
		{
			return;
		}
		
		$image_one		= get_post_meta( $post_id, 'mlm_image_one', true );
		$image_two		= get_post_meta( $post_id, 'mlm_image_two', true );
		$image_one		= empty( $image_one ) ? IMAGES . '/no-thumbnail.png' : $image_one;
		$image_two		= empty( $image_two ) ? IMAGES . '/no-thumbnail.png' : $image_two;
		$average		= mlmFire()->rating->get_average( $post_id );
		$total_count	= mlmFire()->rating->total_count( $post_id );
		$download_cnt	= get_option('mlm_download_cnt');
		$price			= mlm_get_product_price( $post_id );
		$product		= wc_get_product( $post_id );
		$percentage		= mlm_product_has_off( $post_id );
		
		if( $download_cnt == 'view' )
		{
			$total_sales	= mlm_get_post_views( $post_id );
		}
		else
		{
			$total_sales	= (int)get_post_meta( $post_id, 'total_sales', true );
		}
		
		echo $before_widget;
		?>
		
		<div class="app-product-slide-widget mb-5 pt-5 clearfix">
			<div class="container">
				<div class="row no-gutters mx-n2 mx-lg-n5">
					<div class="col-12 col-lg-6 px-2 px-lg-5 align-self-end">
						<div class="slide-image position-relative overflow-hidden clearfix mb-4 mb-lg-0">
							<div class="slide-item float-left">
								<img src="<?php echo $image_one; ?>" class="w-100" alt="slide-image">
							</div>
							<div class="slide-item float-left">
								<img src="<?php echo $image_two; ?>" class="w-100" alt="slide-image">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-6 px-2 px-lg-5 align-self-center">
						<div class="slide-title mb-3">
							<h1 class="font-20 bold-600 text-white m-0">
								<?php echo get_the_title( $post_id ); ?>
							</h1>
						</div>
						<div class="slide-rating lg mb-4 mlm-rating-box">
							<div class="row align-items-center">
								<div class="text-col col">
									<span class="font-12 bold-400 text-white m-0">
										<?php _e( 'Customers rating', 'mlm' ); ?>: <?php echo $average; ?> <?php _e( 'out', 'mlm' ); ?> <?php echo $total_count; ?> <?php echo _nx( 'vote', 'votes', $total_count, 'votes count', 'mlm' ); ?>
									</span>
								</div>
								<div class="star-col col">
									<div class="stars-group lg np clearfix">
										<input type="radio" class="d-none" id="rate-star-5" name="rating" value="5" <?php checked( 5, absint( $average ) ); ?>>
										<label class="full <?php if($average >= 5) echo 'checked'; ?>" for="rate-star-5" title="<?php _e( 'Amazing', 'mlm' ); ?>"></label>
										
										<input type="radio" class="d-none" id="rate-star-4" name="rating" value="4" <?php checked( 4, absint( $average ) ); ?>>
										<label class="full <?php if($average >= 4) echo 'checked'; ?>" for="rate-star-4" title="<?php _e( 'Good', 'mlm' ); ?>"></label>
										
										<input type="radio" class="d-none" id="rate-star-3" name="rating" value="3" <?php checked( 3, absint( $average ) ); ?>>
										<label class="full <?php if($average >= 3) echo 'checked'; ?>" for="rate-star-3" title="<?php _e( 'Average', 'mlm' ); ?>"></label>
										
										<input type="radio" class="d-none" id="rate-star-2" name="rating" value="2" <?php checked( 2, absint( $average ) ); ?>>
										<label class="full <?php if($average >= 2) echo 'checked'; ?>" for="rate-star-2" title="<?php _e( 'Not good', 'mlm' ); ?>"></label>
										
										<input type="radio" class="d-none" id="rate-star-1" name="rating" value="1" <?php checked( 1, absint( $average ) ); ?>>
										<label class="full <?php if($average >= 1) echo 'checked'; ?>" for="rate-star-1" title="<?php _e( 'So bad', 'mlm' ); ?>"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="slide-price position-relative text-center mb-4">
							<svg viewBox="0 0 462 103.7"><g id="Group_5101" transform="translate(-11249.401 1012)"><path class="st0" d="M11709.9-971.2h1.4v-36.9c0-2.2-1.8-4-4-4h-454c-2.2 0-4 1.8-4 4v36.8h1c5.9.2 10.7 5.1 10.7 11s-4.7 10.8-10.7 11h-1v36.9c0 2.2 1.8 4 4 4h454c2.2 0 4-1.8 4-4v-37l-1.1.1h-.4c-6.1 0-11-4.9-11-11 .1-5.9 5.1-10.9 11.1-10.9zm-13 11c0 7 5.6 12.7 12.5 13v34.9c0 1.1-.9 2-2 2h-454c-1.1 0-2-.9-2-2v-34.9c6.5-.7 11.7-6.3 11.6-12.9 0-6.7-5.1-12.3-11.6-12.9v-34.9c0-1.1.9-2 2-2h454c1.1 0 2 .9 2 2v34.8c-6.9.2-12.5 5.9-12.5 12.9z"></path><path class="st0" d="M11479.9-927.7c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .6-.4 1-1 1zm0-18.1c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .5-.4 1-1 1zm0-18.1c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .5-.4 1-1 1zm0-18.2c-.6 0-1-.4-1-1v-9.1c0-.6.4-1 1-1s1 .4 1 1v9.1c0 .6-.4 1-1 1z"></path></g></svg>
							<div class="row align-items-center">
								<div class="col-6">
									<div class="item-sales text-white">
										<svg viewBox="0 0 34 43.3"><path class="st0" d="M24 7.3v-.4c0-1.9-.8-3.6-2.1-4.9C20.6.7 18.8 0 17 0c-1.9 0-3.6.7-4.9 2-1.3 1.3-2 3-2.1 4.9v.4H0v30.1c0 1.6.6 3.1 1.8 4.2 1.1 1.1 2.6 1.7 4.2 1.7H28.2c3.3 0 5.9-2.7 5.9-6v-30H24zm-12-.4c0-1.3.5-2.6 1.5-3.5.9-.9 2.1-1.4 3.5-1.4 1.3 0 2.6.5 3.5 1.4 1 .9 1.5 2.2 1.5 3.5v.4H12v-.4zm20 30.4c0 2.2-1.7 4-3.9 4l-.1 1v-1H6c-1.1 0-2.1-.4-2.8-1.1-.8-.8-1.2-1.8-1.2-2.9v-2h30v2zm-30-4v-3h30v3H2zm0-5v-19h8v5c0 .6.4 1 1 1s1-.4 1-1v-5h10v5c0 .6.4 1 1 1s1-.4 1-1v-5h8v19H2z"></path></svg>
										<?php if( $percentage ): ?>
											<span class="item-value ellipsis font-28 bold-600 text-light">
												<?php echo $percentage .'%'; ?>
											</span>
											<span class="item-label ellipsis font-12">
												<?php _e( 'off', 'mlm' ); ?>
											</span>
										<?php else: ?>
											<span class="item-value ellipsis font-28 bold-600 text-light">
												<?php echo $total_sales; ?>
											</span>
											<span class="item-label ellipsis font-12">
												<?php if( $download_cnt == 'view' ): ?>
													<?php echo _nx( 'view', 'views', $total_sales, 'view count', 'mlm' ); ?>
												<?php elseif( mlm_check_course( $post_id ) ): ?>
													<?php echo _nx( 'student', 'students', $total_sales, 'students count', 'mlm' ); ?>
												<?php elseif( $product->is_downloadable() ): ?>
													<?php echo _nx( 'download', 'downloads', $total_sales, 'download count', 'mlm' ); ?>
												<?php else: ?>
													<?php echo _nx( 'delivery', 'deliveries', $total_sales, 'delivery count', 'mlm' ); ?>
												<?php endif; ?>
											</span>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-6">
									<div class="item-price text-white">
										<?php if( $price == 0 ): ?>
											<span class="item-value ellipsis font-20 bold-600"><?php _e( 'Free', 'mlm' ); ?></span>
										<?php else: ?>
											<span class="item-value ellipsis font-28 bold-600"><?php echo $product->get_price_html(); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="slide-links mb-4">
							<a href="<?php echo get_the_permalink( $post_id ); ?>" class="btn btn-block btn-demo p-3 font-15">
								<span class="icon icon-eye"></span> <?php _e( 'View product', 'mlm' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		echo $after_widget;
	}
}