<?php
$locations	= get_nav_menu_locations();
?>

<div class="app-mobile-menu clearfix">
	<div class="app-right-mobile-menu position-fixed overflow-hidden transition bg-white">
		<div class="h-100">
			<div class="border-bottom clearfix">
				<button type="button" class="app-close-mobile-btn btn btn-block py-4 no-shadow">
					<span class="font-32 d-block bold-600">×</span>
				</button>
			</div>
			<ul class="cat-nav d-block m-0 p-0 h-100 slimscroll">
				<?php
				for( $i = 1; $i <= 6; $i++ )
				{
					$cat_id		= (int)get_option( 'mlm_cat_' . $i );
					$cat_icon	= get_option( 'mlm_cat_icon_' . $i );
					$obj		= get_term( $cat_id );
					
					if( ! empty( $obj ) && ! is_wp_error( $obj ) )
					{
						?>
						<li class="d-block m-0 py-3 px-1 border-bottom">
							<a href="<?php echo esc_url( get_term_link( $obj ) ); ?>" class="cat-link text-center d-block py-2">
								<span class="icon <?php echo $cat_icon; ?> transition"></span>
								<span class="text ellipsis font-12 text-secondary"><?php echo $obj->name; ?></span>
							</a>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div class="app-left-mobile-menu position-fixed overflow-hidden transition">
		<div class="h-100">
			<div class="search-form-wrap border-bottom border-orange clearfix">
				<form class="mlm-ajax-search position-relative m-0" action="<?php echo esc_url( home_url('/') ); ?>" method="get">
					<div class="search-input-group input-group m-0">
						<input name="s" type="text" class="form-control border-0 no-shadow bg-transparent" value="<?php echo get_search_query(); ?>" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>" data-verify="<?php echo wp_create_nonce('mlm_farolmokr'); ?>" />
						<div class="input-group-append">
							<button type="submit" class="search-btn btn border-0 bg-transparent no-shadow">
								<svg viewBox="-4.615 -5.948 39.083 39.417"><path stroke="#fff" stroke-width="1" d="M33.207 30.77L25.6 23c-.064-.065-.143-.104-.218-.148 2.669-2.955 4.31-6.856 4.31-11.143 0-9.189-7.476-16.665-16.665-16.665S-3.638 2.52-3.638 11.709s7.476 16.665 16.665 16.665c4.221 0 8.067-1.59 11.007-4.186.042.072.076.148.137.211l7.607 7.77a.998.998 0 0 0 1.414.016 1.002 1.002 0 0 0 .015-1.415zm-20.18-4.397c-8.086 0-14.665-6.578-14.665-14.665S4.94-2.956 13.027-2.956c8.086 0 14.665 6.579 14.665 14.665s-6.579 14.664-14.665 14.664z"></path></svg>
							</button>
						</div>
					</div>
					<div class="mlm-search-results mlm-widget bg-white position-absolute text-justify m-0 p-0 rounded clearfix"></div>
				</form>
			</div>
			<?php
			if( isset($locations['mobile-menu']) )
			{
				$menu = get_term( $locations['mobile-menu'], 'nav_menu' );
				
				if( $items = wp_get_nav_menu_items( $menu->name ) )
				{
					echo '<ul class="main-nav d-block m-0 p-0 h-100 slimscroll">';
					
					foreach( $items as $item )
					{
						// if the current item is not a top level item, skip it
						if( $item->menu_item_parent != 0 )
						{
							continue;
						}
						
						$level2		= '';
						$classList	= implode( " ", $item->classes );
						$classList	= $classList . ' nav-link d-block text-white py-4 px-3 font-15';
						
						foreach( $items as $lvl2 )
						{
							if( $lvl2->menu_item_parent != $item->ID )
							{
								continue;
							}
							
							$lvl2Class	= implode( " ", $lvl2->classes );
							$lvl2Class	= $lvl2Class . ' nav-link d-block text-white py-2';
							$level2 .= "<li class='d-block m-0 p-0'>";
							$level2 .= "<a class=\"{$lvl2Class}\" href=\"{$lvl2->url}\">{$lvl2->title}</a>";
							$level2 .= '</li>';
						}
						
						if( ! empty( $level2 ) )
						{
							echo "<li class='d-block m-0 p-0 menu-item-has-children'>";
							echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
							echo '<ul class="sub-menu m-0 py-3 px-0">' . $level2 . '</ul>';
							echo '</li>';
						}
						else
						{
							echo "<li class='d-block m-0 p-0'>";
							echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
							echo '</li>';
						}
					}
					
					echo '</ul>';
				}
			}
			?>
		</div>
	</div>
</div>