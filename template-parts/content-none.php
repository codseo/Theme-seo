<div class="not-found-page page-404 mb-4 clearfix">
	<div class="row align-items-center">
		<div class="col-12 col-lg-6">
		
			<?php if( is_search() ): ?>
			
				<h2 class="title-404"><?php _e( 'Nothing found', 'mlm' ); ?></h2>
				<p class="text-justify font-14">
					<?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mlm' ); ?>
				</p>
			<?php else: ?>
				
				<h2 class="title-404"><?php _e( 'OOPS!', 'mlm' ); ?></h2>
				<p class="text-justify font-14">
					<?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mlm' ); ?>
				</p>

			<?php endif; ?>
			
			<p>
				<a href="#" onclick="history.back(-1)" class="btn btn-secondary"><?php _e( 'Go back', 'mlm' ); ?></a> 
				<a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary"><?php _e( 'Go to homepage', 'mlm' ); ?></a> 
			</p>
			
		</div>
		<div class="col-12 col-lg-6">
			<svg viewBox="0 0 837 1045" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
					<path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" class="path-1" stroke="#007FB2" stroke-width="6" sketch:type="MSShapeGroup"></path>
					<path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" class="path-2" stroke="#EF4A5B" stroke-width="6" sketch:type="MSShapeGroup"></path>
					<path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" class="path-3" stroke="#795D9C" stroke-width="6" sketch:type="MSShapeGroup"></path>
					<path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" class="path-4" stroke="#F2773F" stroke-width="6" sketch:type="MSShapeGroup"></path>
					<path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" class="path-5" stroke="#36B455" stroke-width="6" sketch:type="MSShapeGroup"></path>
				</g>
			</svg>
		</div>
	</div>
</div>