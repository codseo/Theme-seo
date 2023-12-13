<?php
$post_id		= get_the_ID();
$user_point		= mlmFire()->rating->get_user_rating( $post_id );
$post_avg		= mlmFire()->rating->get_average( $post_id );
$total_count	= mlmFire()->rating->total_count( $post_id );
?>
<div class="mlm-rating-box border-top border-light text-secondary clearfix" data-id="<?php echo $post_id; ?>" data-verify="<?php echo wp_create_nonce('mlm_askgfazop'); ?>">
	<div class="row justify-content-between">
		<div class="col-auto py-2">
			<div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
				<div itemprop="name" class="sr-only"><?php the_title_attribute(); ?></div>
				<span itemprop="ratingValue"><?php echo absint( $post_avg ); ?></span> <?php _e( 'out', 'mlm' ); ?> <span itemprop="ratingCount"><?php echo $total_count; ?></span> <?php echo _nx( 'vote', 'votes', $total_count, 'votes count', 'mlm' ); ?>
				<meta itemprop="bestRating" content="5">
				<meta itemprop="worstRating" content="1">
				<div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/CreativeWork">
					<div itemprop="name" class="sr-only"><?php the_title_attribute(); ?></div>
				</div>
			</div>
		</div>
		<div class="col-auto py-2">
			<div class="stars-group clearfix">
				<input type="radio" class="d-none" id="rate-star-5" name="rating" value="5" <?php checked( 5, $user_point ); ?>>
				<label class="full" for="rate-star-5" title="<?php _e( 'Amazing', 'mlm' ); ?>"></label>
				
				<input type="radio" class="d-none" id="rate-star-4" name="rating" value="4" <?php checked( 4, $user_point ); ?>>
				<label class="full" for="rate-star-4" title="<?php _e( 'Good', 'mlm' ); ?>"></label>
				
				<input type="radio" class="d-none" id="rate-star-3" name="rating" value="3" <?php checked( 3, $user_point ); ?>>
				<label class="full" for="rate-star-3" title="<?php _e( 'Average', 'mlm' ); ?>"></label>
				
				<input type="radio" class="d-none" id="rate-star-2" name="rating" value="2" <?php checked( 2, $user_point ); ?>>
				<label class="full" for="rate-star-2" title="<?php _e( 'Not good', 'mlm' ); ?>"></label>
				
				<input type="radio" class="d-none" id="rate-star-1" name="rating" value="1" <?php checked( 1, $user_point ); ?>>
				<label class="full" for="rate-star-1" title="<?php _e( 'So bad', 'mlm' ); ?>"></label>
			</div>
		</div>
	</div>
</div>