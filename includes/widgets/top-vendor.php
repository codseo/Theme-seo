<?php

class MLM_Top_Vendor_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'mlm-top-vendor-widget',
			__( '8- Best vendor', 'mlm' ),
			array(
				'classname'   => 'mlm-top-vendor-widget',
				'description' => __( 'Display best vendor widget on home page.', 'mlm' ),
			)
		);
	}

	public function form( $instance )
	{
		$defaults			= array(
			'user_id'	=> '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'user_id' ); ?>"><?php _e( 'Select user', 'mlm' ); ?></label>
			<?php
				wp_dropdown_users( array(
					'show_option_all'         => 0, // string
					'show_option_none'        => __( 'Best seller of the week', 'mlm' ), // string
					'hide_if_only_one_author' => 0, // string
					'selected'                => esc_attr( $instance['user_id'] ),
					'include_selected'        => 1,
					'class'        			  => 'widefat',
					'name'                    => $this->get_field_name( 'user_id' ),
					'id'	                  => $this->get_field_id( 'user_id' ),
				) );
			?>
		</p>

		<?php
	}


	public function update( $new_instance, $old_instance )
	{
		$instance				= $old_instance;
		$instance['user_id']	= absint( $new_instance['user_id'] );

		return $instance;
	}


	public function widget( $args, $instance )
	{
		extract( $args );

		$user_id	= $instance['user_id'];

		if( empty( $user_id ) )
		{
			$user_id	= mlmFire()->dashboard->top_vendor_of_week();
		}

		if( ! mlm_user_exists( $user_id ) )
		{
			return;
		}

		$user_obj		= get_userdata( $user_id );
		$user_name		= $user_obj->display_name;
		$user_bio		= $user_obj->description;
		$verified		= mlmFire()->dashboard->get_account_status( $user_id );
		$query			= new WP_Query( array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'author'			=> $user_id,
			'posts_per_page'	=> 8,
		) );

		echo $before_widget;
		?>

		<div class="app-top-vendor-widget mb-5 py-5 clearfix">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-4 col-xl-5">
						<div class="row align-items-center no-gutters mx-n1">
							<div class="avatar-col col px-1">
								<?php echo get_avatar( $user_id, 64, NULL , $user_name, array( 'class' => 'avatar d-block rounded-circle bg-white shadow-sm' ) ); ?>
							</div>
							<div class="name-col col px-1">
								<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="ellipsis text-white font-16 bold-600"><?php echo $user_name; ?></a>
								<span class="ellipsis text-light font-14 bold-400">
									<?php
									$posts_count	= count_user_posts( $user_id , 'product' );
									printf(
										_nx( '%d product with best quality.', '%d products with best quality.', $posts_count, 'view count', 'mlm' ),
										$posts_count
									);
									?>
								</span>
							</div>
						</div>
						<div class="py-3 mb-3 text-light text-justify font-13">
							<?php echo $user_bio; ?>
						</div>
						<div class="user-medals mb-3">
							<?php mlmFire()->medal->print_user_medals( $user_id, 'mlm-vendor-medal-nav mlm-user-medal-nav nav m-0 p-0' ); ?>
						</div>
						<div class="vendor-link mb-3">
							<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="btn btn-light px-sm-5 py-sm-2">
								<span class="icon icon-eye"></span>
								<?php _e( 'View shop', 'mlm' ); ?>
							</a>
						</div>
					</div>
					<?php if( $query->have_posts() ): ?>
						<div class="col-12 col-lg-8 col-xl-7">
							<div class="top-vendor-slider swiper-container">
								<div class="swiper-wrapper">
									<?php while( $query->have_posts() ): $query->the_post(); ?>
										<div class="swiper-slide">
											<?php get_template_part( 'template-parts/zhaket/content', 'top-product' ); ?>
										</div>
									<?php endwhile; wp_reset_postdata(); ?>
								</div>
								<div class="swiper-pagination"></div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php
		echo $after_widget;
	}
}