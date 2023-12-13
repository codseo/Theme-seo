<?php
$phone		= get_option('mlm_phone');
$locations	= get_nav_menu_locations();

echo '<nav class="mlm-main-nav nav align-items-center m-0 px-0 py-2 position-relative">';

if( isset($locations['primary-menu']) )
{
	$menu = get_term( $locations['primary-menu'], 'nav_menu' );
	
	if( $items = wp_get_nav_menu_items( $menu->name ) )
	{
		foreach( $items as $item )
		{
			// if the current item is not a top level item, skip it
			if( $item->menu_item_parent != 0 )
			{
				continue;
			}
			
			$level2		= '';
			$classList	= implode( " ", $item->classes );

			foreach( $items as $lvl2 )
			{
				if( $lvl2->menu_item_parent != $item->ID )
				{
					continue;
				}
				
				$level3		= '';
				$lvl2Class	= implode( " ", $lvl2->classes );
				
				foreach( $items as $lvl3 )
				{
					if( $lvl3->menu_item_parent != $lvl2->ID )
					{
						continue;
					}
					
					$lvl3Class	= implode( " ", $lvl3->classes );
					
					$level3 .= "<li class=''>";
					$level3 .= "<a class=\"{$lvl3Class}\" href=\"{$lvl3->url}\">{$lvl3->title}</a>";
					$level3 .= '</li>';
				}
				
				if( ! empty( $level3 ) )
				{
					$level2 .= "<li class='menu-item-has-children'>";
					$level2 .= "<a class=\"{$lvl2Class}\" href=\"{$lvl2->url}\">{$lvl2->title}</a>";
					$level2 .= '<ul class="sub-menu">' . $level3 . '</ul>';
					$level2 .= '</li>';
				}
				else
				{
					$level2 .= "<li class=''>";
					$level2 .= "<a class=\"{$lvl2Class}\" href=\"{$lvl2->url}\">{$lvl2->title}</a>";
					$level2 .= '</li>';
				}
			}
			
			if( ! empty( $level2 ) )
			{
				if( in_array( 'megamenu', $item->classes ) )
				{
					echo "<li class='nav-item d-none d-lg-flex menu-item-has-children this-is-mega-menu'>";
				}
				else
				{
					echo "<li class='nav-item d-none d-lg-flex menu-item-has-children'>";
				}
				
				echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
				echo '<ul class="sub-menu">' . $level2 . '</ul>';
				echo '</li>';
			}
			else
			{
				echo "<li class='nav-item d-none d-lg-flex'>";
				echo "<a class=\"{$classList}\" href=\"{$item->url}\">{$item->title}</a>";
				echo '</li>';
			}
		}
	}
}

echo '<li class="nav-item d-lg-none">';
echo '<div class="toggle-btn">';
echo '<button id="mlm-toggle-mobile-menu" class="toggle-quru toggle-daha"><span>toggle menu</span></button>';
echo '</div>';
echo '</li>';

if( ! empty( $phone ) )
{
	echo '<li class="nav-item mr-auto">';
	echo '<a href="tel:'. $phone .'" class="icon icon-phone px-1 clearfix ltr">'. $phone .'</a>';
	echo '</li>';
}
	
if( function_exists('WC') )
{
	$count = WC()->cart->get_cart_contents_count();
	$class = ( $count > 0 ) ? '' : 'empty-cart';
	
	if( ! empty( $phone ) )
	{
		echo '<li class="nav-item">';
	}
	else
	{
		echo '<li class="nav-item mr-auto">';
	}
	
	echo '<a class="mlm-cart-btn px-1 d-block" href="#" data-toggle="modal" data-target="#mlm-cart-modal">';
	echo '<span class="mlm-cart-quantity">';
	echo '<span class="quantity rounded '. $class .'">'. $count .'</span>';
	echo '<span class="icon icon-cart '. $class .'"></span>';
	echo '</span>';
	echo '</a>';
	echo '</li>';
}

echo '</nav>';