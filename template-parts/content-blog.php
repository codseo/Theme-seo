<?php
$post_id	= get_the_ID();
$user_id	= get_the_author_meta( 'ID' );
$verified	= mlmFire()->dashboard->get_account_status( $user_id );
$user_name	= get_the_author();
$views_cnt	= mlm_get_post_views( $post_id );
?>

<article class="mlm-product bg-white p-0 mb-3 rounded transition clearfix">
	<header class="item-header position-relative rounded-top overflow-hidden transition">
		<a href="<?php the_permalink(); ?>" class="d-block" title="<?php the_title_attribute(); ?>" rel="bookmark">
			<img src="<?php mlm_image_url( $post_id, 'medium' ); ?>" class="position-absolute" alt="<?php the_title_attribute(); ?>">
		</a>
	</header>
	<h4 class="item-title p-3 m-0">
		<a href="<?php the_permalink(); ?>" class="" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
	</h4>
	<footer class="item-footer px-3 m-0 clearfix">
		<div class="row">
			<div class="col-6">
				<span class="item-meta my-1 view icon icon-target1 float-right">
					<?php
						printf(
							_nx(
								'%1$s view',
								'%1$s views',
								$views_cnt,
								'views title',
								'mlm'
							),
							$views_cnt
						);
					?>
				</span>
			</div>
			<div class="col-6">
				<span class="item-meta my-1 date icon icon-clock1 float-left">
					<?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __( 'ago', 'mlm' ); ?>
				</span>
			</div>
		</div>
		<div class="row align-items-center">
			<div class="col-6 border-top">
				<a href="<?php echo esc_url( get_author_posts_url( $user_id ) ); ?>" class="item-vendor float-right my-2 <?php if( $verified ) echo 'verified'; ?>">
					<?php echo get_avatar( $user_id, 32, NULL , $user_name, array( 'class' => 'rounded-circle float-right ml-2' ) ); ?>
					<?php echo $user_name; ?>
				</a>
			</div>
			<div class="col-6 border-top">
				<a href="<?php the_permalink(); ?>" class="item-open btn btn-primary btn-block rounded-pill my-2 py-0"><?php _e( 'Continue reading', 'mlm' ); ?></a>
			</div>
		</div>
	</footer>
</article>