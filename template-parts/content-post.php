<div class="mlm-product-sm bg-white text-center p-0 mb-2 rounded transition clearfix">
	<a href="<?php the_permalink(); ?>" class="d-block" title="<?php the_title_attribute(); ?>" rel="bookmark">
		<div class="item-image rounded-top oveflow-hidden" style="background-image: url(<?php mlm_image_url( get_the_ID(), 'thumbnail' ); ?>);">
			<img src="<?php mlm_image_url( get_the_ID(), 'thumbnail' ); ?>" class="d-none" alt="<?php the_title_attribute(); ?>">
		</div>
		<h4 class="item-title m-2 bold-600"><?php the_title(); ?></h4>
	</a>
</div>