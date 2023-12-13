<?php

class MLM_Categories_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-category-widget',
			__( '4- Categories list', 'mlm' ),
			array(
				'classname'   => 'mlm-category-widget-wrapper',
				'description' => __( 'Display a list of categories', 'mlm' ),
			) 
		);
	}
	
	public function form( $instance ) 
	{
		$defaults			= array(
			'title'			=> __( 'Products Category', 'mlm' ),
			'orderby'		=> 'name',
			'order'			=> 'DESC',
			'hide_empty'	=> '',
		);
		
		$orderby_options	= array(
			'id'				=> __( 'ID', 'mlm' ),
			'name'				=> __( 'Title', 'mlm' ),
			'count'				=> __( 'Count', 'mlm' ),
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
			<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], 'on' ); ?> id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" /> 
			<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty categories?', 'mlm' ); ?></label>
		</p>
		
		<?php
	}
	
	
	public function update( $new_instance, $old_instance )
	{
		$instance				= $old_instance;
		$instance['title']		= esc_attr( $new_instance['title'] );
		$instance['orderby']	= esc_attr( $new_instance['orderby'] );
		$instance['order']		= esc_attr( $new_instance['order'] );
		$instance['hide_empty']	= esc_attr( $new_instance['hide_empty'] );
		
		return $instance;
	}
	
	
	public function widget( $args, $instance )
	{
		extract( $args );
		
		$step	= 0;
		$title	= apply_filters( 'widget_title', $instance['title'] );
		$args	= array(
			'taxonomy'		=> 'product_cat',
			'hide_empty'	=> ( isset( $instance['hide_empty'] ) && $instance['hide_empty'] == 'on' ) ? true : false,
			'orderby'		=> $instance['orderby'],
			'order'			=> $instance['order'],
		);
		
		$all_terms	= get_terms( $args );
		$demo		= mlm_selected_demo();
		
		if( ! empty( $all_terms ) && ! is_wp_error( $all_terms ) )
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
			
			echo '<div class="mlm-category-widget">';
			echo '<div class="row no-gutters justify-content-center mx-n2">';
			
			foreach( $all_terms as $term )
			{
				$step++;
				$class		= ( $step > 10 ) ? ' d-none' : '';
				$image_id	= get_term_meta( $term->term_id, 'thumbnail_id', true );
				$image		= wp_get_attachment_url( $image_id );
				
				if( ! $image )
				{
					$image	= IMAGES . '/avatar.svg';
				}
				
				echo '<div class="col-auto'. $class .'">';
				echo '<div class="mlm-category-box bg-white text-center p-2 m-2 rounded transition clearfix">';
				echo '<img width="80" height="80" src="'. esc_url( $image ) .'" class="item-image rounded-circle d-block mx-auto" alt="'. $term->name .'">';
				echo '<h5 class="item-title my-2 bold-600">'. $term->name .'</h5>';
				echo '<a href="'. esc_url( get_term_link( $term ) ) .'" class="btn btn-light py-0 rounded-pill" title="'. $term->name .'" rel="bookmark">'. __( 'View', 'mlm' ) .'</a>';
				echo '</div>';
				echo '</div>';
			}
			
			echo '</div>';
			echo '<div class="mt-3 text-center clearfix">';
			echo '<a href="#mlm-toggle-category-btn" class="btn btn-primary rounded-pill px-4">'. __( 'See More', 'mlm' ) .'</a>';
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