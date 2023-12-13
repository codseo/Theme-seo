<?php
$locations	= get_nav_menu_locations();

if( isset($locations['secondary-menu']) )
{
	$menu = get_term( $locations['secondary-menu'], 'nav_menu' );
	
	if( $items = wp_get_nav_menu_items( $menu->name ) )
	{
		echo '<div class="mlm-footer bg-dark py-2 d-none d-lg-block clearfix">';
		echo '<div class="container">';
		echo '<ul class="mlm-secondary-nav nav p-0 m-0">';
		
		foreach( $items as $item )
		{
			// if the current item is not a top level item, skip it
			if( $item->menu_item_parent != 0 )
			{
				continue;
			}
			
			if( isset( $item->classes ) && is_array( $item->classes ) )
			{
				$new_classes	= $item->classes;
			}
			else
			{
				$new_classes	= array();
			}
			
			array_push( $new_classes, 'nav-link', 'transition', 'py-0', 'px-2', 'text-light', 'font-12' );
			
			$classList	= implode( " ", $new_classes );
			
			echo "<li class='nav-item'>";
			echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>';
		echo '</div>';
	}
}