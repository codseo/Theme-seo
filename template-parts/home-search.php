<div class="mlm-main-search p-3 my-0 mx-auto text-center clearfix">
	<h2 class="d-block my-4 bold-600"><?php echo get_option('mlm_search_title'); ?></h2>
	<form class="mlm-ajax-search position-relative" action="<?php echo esc_url( home_url('/') ); ?>" method="get">
		<div class="input-group m-0 p-0">
			<input type="search" name="s" class="form-control p-3 bg-light border-0" value="<?php echo get_search_query(); ?>" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>" aria-label="<?php _e( 'Search for ...', 'mlm' ); ?>" data-verify="<?php echo wp_create_nonce('mlm_farolmokr'); ?>">
			<div class="input-group-append">
				<button class="btn btn-primary px-3 py-0" type="submit">
					<span class="icon icon-search bold-icon mx-2 d-block"></span>
				</button>
			</div>
		</div>
		<div class="mlm-search-results mlm-widget bg-white position-absolute text-justify m-0 p-0 rounded clearfix"></div>
	</form>
	<div class="row my-4">
		<?php
		for( $i = 1; $i <= 4; $i++ )
		{
			$cat_id		= (int)get_option( 'mlm_cat_' . $i );
			$cat_icon	= get_option( 'mlm_cat_icon_' . $i );
			$obj		= get_term( $cat_id );
			
			if( ! empty( $obj ) && ! is_wp_error( $obj ) )
			{
				//$count	= mlm_get_term_childs_count( $cat_id, 'product_cat', $obj->count );
				$count	= $obj->count;
				?>
				<div class="col-6 col-md-3">
					<a href="<?php echo esc_url( get_term_link( $obj ) ); ?>" class="mlm-cat-item d-block my-2 overflow-hidden clearfix">
						<span class="icon <?php echo $cat_icon; ?> float-right transition"></span>
						<span class="title float-left pr-2"><?php echo $count; ?> <?php echo _nx( 'item', 'items', $count, 'item count', 'mlm' ); ?><i class="c d-block"><?php echo $obj->name; ?></i></span>
					</a>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>