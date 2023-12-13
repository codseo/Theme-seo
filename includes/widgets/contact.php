<?php

class MLM_Contact_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-contact-widget',
			__( '1- Contact us', 'mlm' ),
			array(
				'classname'   => 'mlm-contact-widget-wrapper',
				'description' => __( 'Display site contact info.', 'mlm' ),
			)
		);
	}
	
	public function form( $instance ) 
	{
		$defaults		= array(
			'title'		=> __( 'Contact Us', 'mlm' ),
			'text'		=> '',
			'phone'		=> '',
			'site'		=> '',
			'email'		=> '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text', 'mlm' ); ?>:</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo esc_attr( $instance['phone'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'site' ); ?>"><?php _e( 'Site domain', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'site' ); ?>" name="<?php echo $this->get_field_name( 'site' ); ?>" value="<?php echo esc_attr( $instance['site'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email address', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo esc_attr( $instance['email'] ); ?>">
		</p>
		
		<?php
	}
	
	
	public function update( $new_instance, $old_instance )
	{
		$instance			= $old_instance;
		$instance['title']	= esc_attr( $new_instance['title'] );
		$instance['text']	= esc_textarea( $new_instance['text'] );
		$instance['phone']	= esc_attr( $new_instance['phone'] );
		$instance['site']	= esc_attr( $new_instance['site'] );
		$instance['email']	= esc_attr( $new_instance['email'] );
		
		return $instance;
	}
	
	
	public function widget( $args, $instance )
	{
		extract( $args );
		
		$title		= apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		
		if( $title )
		{
			echo $before_title . $title . $after_title;
		}
		
		echo '<div class="mlm-contact-widget">';
		
		if( ! empty( $instance['text'] ) )
		{
			echo '<p class="text-justify">'. $instance['text'] .'</p>';
		}
		
		if( ! empty( $instance['phone'] ) )
		{
			echo '<p class="icon icon-phone ltr my-2">'. $instance['phone'] .'</p>';
		}
		
		if( ! empty( $instance['site'] ) )
		{
			echo '<p class="icon icon-link ltr my-2">'. $instance['site'] .'</p>';
		}
		
		if( ! empty( $instance['email'] ) )
		{
			echo '<p class="icon icon-earth ltr my-2">'. $instance['email'] .'</p>';
		}
		
		echo '</div>';
		
		echo $after_widget;
	}
}