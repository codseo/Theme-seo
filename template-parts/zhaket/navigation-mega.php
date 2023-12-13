<?php
$locations	= get_nav_menu_locations();

if( isset($locations['primary-menu']) )
{
	$menu = get_term( $locations['primary-menu'], 'nav_menu' );
	
	if( $items = wp_get_nav_menu_items( $menu->name ) )
	{
		echo '<ul class="mega-nav nav align-items-center justify-content-center p-0 m-0">';
		
		foreach( $items as $item )
		{
			// if the current item is not a top level item, skip it
			if( $item->menu_item_parent != 0 )
			{
				continue;
			}
			
			$level2		= '';
			$classList	= implode( " ", $item->classes );
			$classList	= $classList . ' nav-link text-white';
			
			foreach( $items as $lvl2 )
			{
				if( $lvl2->menu_item_parent != $item->ID )
				{
					continue;
				}
				
				$lvl2Class	= implode( " ", $lvl2->classes );
				$lvl2Class	= $lvl2Class . ' nav-link transition';
				$level2 .= "<li class='col-3'>";
				$level2 .= "<a class=\"{$lvl2Class}\" href=\"{$lvl2->url}\">{$lvl2->title}</a>";
				$level2 .= '</li>';
			}
			
			if( ! empty( $level2 ) )
			{
				echo "<li class='nav-item px-2 transition menu-item-has-children'>";
				echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
				echo '<div class="sub-menu m-0 p-4 position-absolute bg-white transition"><ul class="my-0 p-0 row">' . $level2 . '</ul></div>';
				echo '</li>';
			}
			else
			{
				echo "<li class='nav-item px-2 transition'>";
				echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
				echo '</li>';
			}
		}
		
		echo '</ul>';
	}
}