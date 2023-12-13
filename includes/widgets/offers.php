<?php

class MLM_Offers_Slider_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-offers-slider-widget',
			__( '6- Featured offers', 'mlm' ),
			array(
				'classname'		=> 'mlm-offers-slider-wrapper-widget',
				'description'	=> __( 'Displat featured offers slider.', 'mlm' ),
			) 
		);
	}
	
	public function form( $instance ) 
	{
		$defaults			= array(
			'title'		=> __( 'Featured offers', 'mlm' ),
			'count'		=> 3,
			'orderby'	=> 'date',
			'order'		=> 'DESC',
			'taxonomy'	=> '',
		);
		
		$orderby_options	= array(
			'ID'				=> __( 'ID', 'mlm' ),
			'title'				=> __( 'Title', 'mlm' ),
			'date'				=> __( 'Date', 'mlm' ),
			'modified'			=> __( 'Update date', 'mlm' ),
			'rand'				=> __( 'Random', 'mlm' ),
		);

		$order_options		= array(
			'DESC'		=> __( 'Descending', 'mlm' ),
			'ASC'		=> __( 'Ascending', 'mlm' ),
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'count' ); ?>"><?php _e( 'Count', 'mlm' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" size="3" value="<?php echo absint( $instance['count'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'orderby' ); ?>"><?php _e( 'Order by', 'mlm' ); ?>:</label>
			<select class='widefat'  id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach( $orderby_options as $value => $name ) : ?>
					<option <?php selected( esc_attr( $instance['orderby'] ) , $value ) ?> value='<?php echo $value ?>'><?php echo $name ?></option>
				<?php endforeach ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'order' ); ?>"><?php _e( 'Order', 'mlm' ); ?>: </label>
			<select class='widefat'  id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<?php foreach( $order_options as $value => $name ) : ?>
					<option <?php selected( esc_attr( $instance['order'] ), $value ) ?> value='<?php echo $value ?>'><?php echo $name ?></option>
				<?php endforeach ?>
			</select>
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
		$instance['count']		= absint( $new_instance['count'] );
		$instance['orderby']	= esc_attr( $new_instance['orderby'] );
		$instance['order']		= esc_attr( $new_instance['order'] );
		$instance['taxonomy']	= absint( $new_instance['taxonomy'] );
		
		return $instance;
	}
	
	
	public function widget( $args, $instance )
	{
		extract( $args );
		
		$title	= apply_filters( 'widget_title', $instance['title'] );
		$args	= array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'orderby'				=> $instance['orderby'],
			'order'					=> $instance['order'],
			'posts_per_page'		=> $instance['count'],
			'ignore_sticky_posts'	=> 1,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array(
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			)
		);
		
		if( ! empty( $instance['taxonomy'] ) )
		{
			$args['tax_query'] = array( array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'term_id',
				'terms'		=> array( $instance['taxonomy'] )
			) );
		}
		
		$query	= new WP_Query( $args );
		$demo	= mlm_selected_demo();
		
		if( $query->have_posts() )
		{
			if( $demo == 'zhaket' )
			{
				echo '<div class="container">';
			}
			
			echo $before_widget;
			
			if( $title )
			{
				echo $before_title . $title . $after_title;
			}
			
			echo '<div class="mlm-offers-slider-wrapper">';
			echo '<div class="mlm-offers-box bg-white clearfix">';
				echo '<div class="mlm-offers-slider swiper-container">';
					echo '<div class="swiper-wrapper">';
						while( $query->have_posts() ): $query->the_post();							
							echo '<div class="swiper-slide">';
								get_template_part( 'template-parts/content', 'offer' );
							echo '</div>';
						endwhile; wp_reset_postdata();
					echo '</div>';
					echo '<div class="swiper-button-next"><span class="icon"></span></div>';
					echo '<div class="swiper-button-prev"><span class="icon"></span></div>';
				echo '</div>';
			echo '</div>';
			echo '</div>';
			
			echo $after_widget;
			
			if( $demo == 'zhaket' )
			{
				echo '</div>';
			}
		}
	}
}