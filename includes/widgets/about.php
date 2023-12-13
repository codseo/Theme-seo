<?php

class MLM_About_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 
			'mlm-about-widget',
			__( '2- About us', 'mlm' ),
			array(
				'classname'   => 'mlm-about-widget-wrapper',
				'description' => __( 'Display site info', 'mlm' ),
			)
		);
	}
	
	public function form( $instance ) 
	{
		$defaults		= array(
			'title'		=> __( 'About us', 'mlm' ),
			'text'		=> '',
			'twitter'	=> '',
			'aparat'	=> '',
			'telegram'	=> '',
			'instagram'	=> '',
			'youtube'	=> '',
			'rss'		=> 'on',
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
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter URL', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php echo esc_attr( $instance['twitter'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'aparat' ); ?>"><?php _e( 'Aparat URL', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'aparat' ); ?>" name="<?php echo $this->get_field_name( 'aparat' ); ?>" value="<?php echo esc_attr( $instance['aparat'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'telegram' ); ?>"><?php _e( 'Telegram URL', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'telegram' ); ?>" name="<?php echo $this->get_field_name( 'telegram' ); ?>" value="<?php echo esc_attr( $instance['telegram'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram URL', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" value="<?php echo esc_attr( $instance['instagram'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube URL', 'mlm' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" value="<?php echo esc_attr( $instance['youtube'] ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['rss'], 'on' ); ?> id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" /> 
			<label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e( 'Display RSS feed icon?', 'mlm' ); ?></label>
		</p>
		
		<?php
	}
	
	
	public function update( $new_instance, $old_instance )
	{
		$instance				= $old_instance;
		$instance['title']		= esc_attr( $new_instance['title'] );
		$instance['text']		= esc_textarea( $new_instance['text'] );
		$instance['twitter']	= esc_url( $new_instance['twitter'] );
		$instance['aparat']		= esc_url( $new_instance['aparat'] );
		$instance['telegram']	= esc_url( $new_instance['telegram'] );
		$instance['instagram']	= esc_url( $new_instance['instagram'] );
		$instance['youtube']	= esc_url( $new_instance['youtube'] );
		$instance['rss']		= esc_attr( $new_instance['rss'] );
		
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
		
		if( ! empty( $instance['text'] ) )
		{
			echo '<p class="text-justify">'. $instance['text'] .'</p>';
		}
		
		echo '<div class="mlm-about-widget">';
		echo '<ul class="mlm-social-nav nav m-0 p-0 justify-content-center">';
		
		if( ! empty( $instance['twitter'] ) )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. $instance['twitter'] .'" class="nav-link text-white icon icon-twitter" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'Twitter', 'mlm' ) .'">';
			echo '<span class="text-hide">Twitter</span>';
			echo '</a>';
			echo '</li>';			
		}
		
		if( ! empty( $instance['aparat'] ) )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. $instance['aparat'] .'" class="nav-link text-white icon icon-aparat-large white" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'Aparat', 'mlm' ) .'">';
			echo '<span class="text-hide">Aparat</span>';
			echo '</a>';
			echo '</li>';			
		}
		
		if( ! empty( $instance['telegram'] ) )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. $instance['telegram'] .'" class="nav-link text-white icon icon-telegram" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'Telegram', 'mlm' ) .'">';
			echo '<span class="text-hide">Telegram</span>';
			echo '</a>';
			echo '</li>';			
		}
		
		if( ! empty( $instance['instagram'] ) )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. $instance['instagram'] .'" class="nav-link text-white icon icon-instagram" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'Instagram', 'mlm' ) .'">';
			echo '<span class="text-hide">Instagram</span>';
			echo '</a>';
			echo '</li>';			
		}
		
		if( ! empty( $instance['youtube'] ) )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. $instance['youtube'] .'" class="nav-link text-white icon icon-youtube" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'Youtube', 'mlm' ) .'">';
			echo '<span class="text-hide">Youtube</span>';
			echo '</a>';
			echo '</li>';			
		}
		
		if( 'on' == $instance['rss'] )
		{
			echo '<li class="nav-item">';
			echo '<a target="_blank" href="'. esc_url( home_url('feed') ) .'" class="nav-link text-white icon icon-rss" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. __( 'RSS', 'mlm' ) .'">';
			echo '<span class="text-hide">RSS</span>';
			echo '</a>';
			echo '</li>';	
		}
		
		echo '</ul>';
		echo '</div>';
		
		echo $after_widget;
	}
}