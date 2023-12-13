<?php
$locations	= get_nav_menu_locations();

if( isset($locations['top-menu']) )
{
	$menu = get_term( $locations['top-menu'], 'nav_menu' );
	
	if( $items = wp_get_nav_menu_items( $menu->name ) )
	{
		echo '<ul class="mlm-top-nav nav nav-fill p-0 m-0">';
		
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
			
			array_push( $new_classes, 'nav-link', 'py-3', 'px-1', 'transition' );
			
			$classList	= implode( " ", $new_classes );
			
			echo "<li class='nav-item'>";
			echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
			echo '</li>';
		}
		
		echo '</ul>';
	}
}