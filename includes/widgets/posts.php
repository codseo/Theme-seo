<?php

class MLM_Posts_Carousel_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-blog-posts-widget',
			__( '5- Blog', 'mlm' ),
			array(
				'classname'   => 'mlm-blog-posts-wrapper-widget',
				'description' => __( 'Display blog posts widget.', 'mlm' ),
			) 
		);
	}
	
	public function form( $instance ) 
	{
		$defaults			= array(
			'title'		=> __( 'Blog', 'mlm' ),
			'count'		=> 5,
			'orderby'	=> 'date',
			'order'		=> 'DESC',
			'taxonomy'	=> '',
		);
		
		$orderby_options	= array(
			'ID'				=> __( 'ID', 'mlm' ),
			'title'				=> __( 'Title', 'mlm' ),
			'meta_value_num'	=> __( 'Most viewed', 'mlm' ),
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
					'taxonomy'           => 'category',
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
			'post_type'				=> 'post',
			'post_status'			=> 'publish',
			'orderby'				=> $instance['orderby'],
			'order'					=> $instance['order'],
			'posts_per_page'		=> $instance['count'],
			'meta_key'				=> 'mlm_views',
			'ignore_sticky_posts'	=> 1,
		);
		
		if( ! empty( $instance['taxonomy'] ) )
		{
			$args['tax_query'] = array( array(
				'taxonomy'	=> 'category',
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
			
			echo '<div class="mlm-blog-posts-wrapper mlm-archive">';
			echo '<div class="row">';
				while( $query->have_posts() ): $query->the_post();
					echo '<div class="col-12 col-lg-6">';
					get_template_part( 'template-parts/content', 'blog-slide' );
					echo '</div>';
				endwhile; wp_reset_postdata();
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