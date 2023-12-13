<?php

class MLM_Top_Products_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-top-products-widget',
			__( '9- Top products', 'mlm' ),
			array(
				'classname'   => 'mlm-top-products-widget',
				'description' => __( 'Display products slider widget.', 'mlm' ),
			) 
		);
	}
	
	public function form( $instance ) 
	{
		$defaults			= array(
			'title'		=> __( 'Top sale products', 'mlm' ),
			'en_title'	=> 'Most popular products',
			'icon'		=> 'icon-ribbon',
			'taxonomy'	=> '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'en_title' ); ?>"><?php _e( 'Secondary title', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'en_title' ); ?>" name="<?php echo $this->get_field_name( 'en_title' ); ?>" value="<?php echo esc_attr( $instance['en_title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Icon code', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" value="<?php echo esc_attr( $instance['icon'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'taxonomy' ); ?>"><?php _e( 'Category', 'mlm' ); ?></label>
			<?php
				wp_dropdown_categories( array(
					'show_option_all'	 => __( 'All categories', 'mlm' ),
					'show_count'         => 1,
					'hide_empty'         => 0,
					'selected'           => esc_attr( $instance['taxonomy'] ),
					'hierarchical'       => 1,
					'name'               => $this->get_field_name( 'taxonomy' ),
					'id'                 => $this->get_field_id( 'taxonomy' ),
					'class'              => 'widefat',
					'taxonomy'           => 'product_cat',
					'value_field'	     => 'term_id',
				) );
			?>
		</p>
		
		<?php
	}
	
	
	public function update( $new_instance, $old_instance )
	{
		$instance				= $old_instance;
		$instance['title']		= esc_attr( $new_instance['title'] );
		$instance['en_title']	= esc_attr( $new_instance['en_title'] );
		$instance['icon']		= esc_attr( $new_instance['icon'] );
		$instance['taxonomy']	= absint( $new_instance['taxonomy'] );
		
		return $instance;
	}
	
	
	public function widget( $args, $instance )
	{
		if( ! function_exists('wc_get_page_id') )
		{
			return;
		}
		
		extract( $args );
		
		$random			= rand( 100, 999 );
		$title			= apply_filters( 'widget_title', $instance['title'] );
		$archive_url	= get_permalink( wc_get_page_id( 'shop' ) );
		$new_args		= array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'orderby'				=> 'date',
			'order'					=> 'DESC',
			'posts_per_page'		=> 4,
			'meta_key'				=> 'total_sales',
			'ignore_sticky_posts'	=> 1,
		);
		$top_args		= array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'orderby'				=> 'meta_value_num',
			'order'					=> 'DESC',
			'posts_per_page'		=> 4,
			'meta_key'				=> 'total_sales',
			'ignore_sticky_posts'	=> 1,
		);
		
		if( ! empty( $instance['taxonomy'] ) )
		{
			$term_link	= get_term_link( $instance['taxonomy'] );
			
			if( ! is_wp_error( $term_link ) )
			{
				$archive_url	= $term_link;
			}
			
			$new_args['tax_query'] = array( array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'term_id',
				'terms'		=> array( $instance['taxonomy'] )
			) );
			$top_args['tax_query'] = array( array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'term_id',
				'terms'		=> array( $instance['taxonomy'] )
			) );
		}
		
		$new_query	= new WP_Query( $new_args );
		$top_query	= new WP_Query( $top_args );
		
		if( $new_query->have_posts() )
		{
			echo $before_widget;
			?>
			
			<div class="app-products-archive mb-5 clearfix">
				<div class="container">
					<div class="box-title mb-4 clearfix">
						<div class="row align-items-center justify-content-between">
							<div class="col-auto">
								<h3 class="title position-relative font-18 text-secondary bold-600 my-3">
									<span class="position-absolute icon <?php echo $instance['icon']; ?>"></span>
									<?php echo $title; ?>
									<?php if( ! empty( $instance['en_title'] ) ): ?>
										<span class="ellipsis bold-300 font-14"><?php echo $instance['en_title']; ?></span>
									<?php endif; ?>
								</h3>
							</div>
							<div class="col-auto">
								<div class="nav m-0 p-0">
									<a class="nav-link font-12 bold-500 py-0 px-2 active" id="archive-tab1<?php echo $random; ?>" data-toggle="pill" href="#archive-tab1<?php echo $random; ?>-content" role="tab" aria-controls="archive-tab1<?php echo $random; ?>-content" aria-selected="true">
										<?php _e( 'Most recent', 'mlm' ); ?>
									</a>
									<a class="nav-link font-12 bold-500 py-0 px-2" id="archive-tab2<?php echo $random; ?>" data-toggle="pill" href="#archive-tab2<?php echo $random; ?>-content" role="tab" aria-controls="archive-tab2<?php echo $random; ?>-content" aria-selected="false">
										<?php _e( 'Best sale', 'mlm' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="archive-tab1<?php echo $random; ?>-content" role="tabpanel" aria-labelledby="archive-tab1<?php echo $random; ?>">
							
							<div class="row">
								<?php while( $new_query->have_posts() ): $new_query->the_post(); ?>
									<div class="col-12 col-sm-6 col-lg-3">
										<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
						
						</div>
						<div class="tab-pane fade" id="archive-tab2<?php echo $random; ?>-content" role="tabpanel" aria-labelledby="archive-tab2<?php echo $random; ?>-content">
							
							<div class="row">
								<?php while( $top_query->have_posts() ): $top_query->the_post(); ?>
									<div class="col-12 col-sm-6 col-lg-3">
										<?php get_template_part( 'template-parts/zhaket/content', 'product' ); ?>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
							
						</div>
					</div>
					<div class="text-center mt-4">
						<a href="<?php echo $archive_url; ?>" class="btn btn-grey btn-lg no-shadow font-14 bold-600 py-2 px-4">
							<?php _e( 'See All', 'mlm' ); ?>
						</a>
					</div>
				</div>
			</div>
			
			<?php
			echo $after_widget;
		}
	}
}