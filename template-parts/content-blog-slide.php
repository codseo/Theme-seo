<article class="mlm-blog media bg-white p-0 mb-3 rounded-lg overflow-hidden transition clearfix">
	<a href="<?php the_permalink(); ?>" class="media-img" title="<?php the_title_attribute(); ?>" rel="bookmark">
		<img src="<?php mlm_image_url( get_the_ID(), 'thumbnail' ); ?>" class="item-image" alt="<?php the_title_attribute(); ?>">
	</a>
	<div class="media-body align-self-center">
		<h4 class="item-title font-14 bold-600 mx-3 mt-3 mb-2 p-0 overflow-hidden">
			<a href="<?php the_permalink(); ?>" class="text-dark" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h4>
		<p class="item-excerpt overflow-hidden text-justify font-12 bold-300 mx-3 mb-3 p-0 text-secondary"><?php mlm_excerpt( 200 ); ?></p>
	</div>
</article>