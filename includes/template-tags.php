<?php


/**
 * Next/Prev link attributes.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_posts_link_attributes' ) )
{
	function mlm_posts_link_attributes()
	{
		return 'class="page-link"';
	}
	add_filter( 'next_posts_link_attributes', 'mlm_posts_link_attributes' );
	add_filter( 'previous_posts_link_attributes', 'mlm_posts_link_attributes' );
}


/**
 * Theme Navigation.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_navigation' ) )
{
	function mlm_navigation( $query = false )
	{
		global $wp_query;
		
		if( $query )
		{
			$temp_query	= $wp_query;
			$wp_query	= NULL;
			$wp_query	= $query;
		}
		
		// Stop execution if there's only 1 page
		if( $wp_query->max_num_pages <= 1 )
		{
			return;
		}
		
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		// Add current page to the array
		if( $paged >= 1 )
		{
			$links[] = $paged;
		}
		
		// Add the pages around the current page to the array
		if( $paged >= 3 )
		{
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if( ( $paged + 2 ) <= $max )
		{
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		
		echo '<nav class="mlm-navigation mx-0 mb-5 p-0 clearfix" aria-label="Navigation">';
		echo '<ul class="pagination justify-content-center flex-row-reverse flex-wrap m-0 p-0">';

		// Previous Post Link
		if( get_previous_posts_link() )
		{
			printf(
				'<li class="page-item">%s</li>',
				get_previous_posts_link(
					__( '<span class="icon icon-arrow-left2 float-left mr-2"></span> Prev', 'mlm' )
				)
			);
		}

		// Link to first page, plus ellipses if necessary
		if( ! in_array( 1, $links ) )
		{
			$class = 1 == $paged ? 'active' : '';
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( 1 ) ), '1' );
			
			if( ! in_array( 2, $links ) )
			{
				echo '<li class="page-item space"><span class="page-link">...</span></li>';
			}
		}

		// Link to current page, plus 2 pages in either direction if necessary
		sort( $links );
		foreach( (array) $links as $link )
		{
			$class = $paged == $link ? 'active' : '';
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $link ) ), $link );
		}

		// Link to last page, plus ellipses if necessary
		if( ! in_array( $max, $links ) )
		{
			if( ! in_array( $max - 1, $links ) )
			{
				echo '<li class="page-item space"><span class="page-link">...</span></li>';
			}

			$class = $paged == $max ? 'active' : '';
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $max ) ), $max );
		}

		// Next Post Link
		if( get_next_posts_link() )
		{
			printf(
				'<li class="page-item">%s</li>',
				get_next_posts_link(
					__( 'Next <span class="icon icon-arrow-right2 float-right ml-2"></span>', 'mlm' )
				)
			);
		}
		
		echo '</ul>';
		echo '</nav>';
		
		if( $query )
		{
			$wp_query	= NULL;
			$wp_query	= $temp_query;
		}
	}
}


/**
 * WP-Admin pagination
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_wp_navigation' ) )
{
	function mlm_wp_navigation( $total_records, $link, $per = 30 )
	{
		if( empty( $total_records ) || empty( $link ) )
		{
			return false;
		}

		$total_pages	= ceil( $total_records / $per );
		$paged			= isset($_GET['paged']) ? urldecode($_GET['paged']) : 1;
		?>
		
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num"><?php printf( __( '%d items', 'mlm' ), $total_records ); ?></span>
				<span class="pagination-links">
				<?php 
					if( $paged > 1 ) {
						echo ' <a class="first-page" href="'.$link.'"><span aria-hidden="true">«</span></a> ';
					}
					
					if( $paged > 1 ) {
						$i = $paged-1;
						echo ' <a class="prev-page" href="'.$link.'&paged='.$i.'"><span aria-hidden="true">‹</span></a> ';
					}
		
					echo ' <span id="table-paging" class="paging-input">'.$paged.' '. __( 'of', 'mlm' ) .' <span class="total-pages">'.$total_pages.'</span></span> ';
					
					if( $paged < $total_pages ) {
						$j = $paged+1;
						echo ' <a class="next-page" href="'.$link.'&paged='.$j.'"><span aria-hidden="true">›</span></a> ';
					}
					
					if( $paged < ( $total_pages-1 ) ) {
						echo ' <a class="last-page" href="'.$link.'&paged='.$total_pages.'"><span aria-hidden="true">»</span></a>';
					}
				?>
				</span>
			</div>
		</div>
		
		<?php
	}
}


/**
 * WPDB pagination
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_db_pagination' ) )
{
	function mlm_db_pagination( $total_records, $url, $per = 15, $paged = '' )
	{
		if( empty( $total_records ) || empty( $url ) )
		{
			return false;
		}

		$total_pages	= ceil( $total_records / $per );
		
		if( empty( $paged ) )
		{
			$paged	= get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		}
		
		if( $total_pages <= 1 )
		{
			return;
		}
		
		// Add current page to the array
		if( $paged >= 1 )
		{
			$links[] = $paged;
		}
		
		// Add the pages around the current page to the array
		if( $paged >= 3 )
		{
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if( ( $paged + 2 ) <= $total_pages )
		{
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		
		echo '<nav class="mlm-navigation mx-0 mb-5 p-0 clearfix" aria-label="Navigation">';
		echo '<ul class="pagination justify-content-center flex-row-reverse flex-wrap m-0 p-0">';
		
		if( $paged > 1 )
		{
			$i = $paged - 1;
			printf( '<li class="page-item"><a href="%s" class="page-link">'. __( '<span class="icon icon-arrow-left2 float-left mr-2"></span> Prev', 'mlm' ) .'</a></li>', add_query_arg( 'paged', $i, $url ) );
		}
		
		if( ! in_array( 1, $links ) )
		{
			$class = 1 == $paged ? 'active' : '';
			
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, esc_url( $url ), '1' );
			
			if( ! in_array( 2, $links ) )
			{
				echo '<li class="page-item space"><span class="page-link">...</span></li>';
			}
		}
		
		sort( $links );
		foreach( (array) $links as $link )
		{
			$class = $paged == $link ? 'active' : '';
			
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, add_query_arg( 'paged', $link, $url ), $link );
		}
		
		if ( ! in_array( $total_pages, $links ) )
		{
			if( ! in_array( $total_pages - 1, $links ) )
			{
				echo '<li class="page-item space"><span class="page-link">...</span></li>';
			}

			$class = $paged == $total_pages ? 'active' : '';
			printf( '<li class="page-item %s"><a class="page-link" href="%s">%s</a></li>', $class, add_query_arg( 'paged', $total_pages, $url ), $total_pages );
		}
		
		if( $paged < $total_pages )
		{
			$j = $paged + 1;
			printf( '<li class="page-item"><a href="%s" class="page-link">'. __( 'Next <span class="icon icon-arrow-right2 float-right ml-2"></span>', 'mlm' ) .'</a></li>', add_query_arg( 'paged', $j, $url ) );
		}
		
		echo '</ul>';
		echo '</nav>';
	}
}


/**
 * Custom breadcrumbs
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_breadcrumbs' ) )
{
	function mlm_breadcrumbs()
	{
		if( is_front_page() || is_home() )
		{
			return;
		}
			
		global $post, $wp_query;
		echo '<nav aria-label="breadcrumb" class="p-0 mx-0 mb-1">';
		echo '<ol class="breadcrumb p-0 m-0 bg-transparent rounded-0">';
		echo '<li class="breadcrumb-item"><a href="'. esc_url( home_url() ) .'" class="text-dark">'. __( 'Home', 'mlm' ) .'</a></li>';
		
		if( is_tax() && ! is_category() && ! is_tag() )
		{
			$post_type			= get_post_type();
			$custom_tax_name	= get_queried_object()->name;
			
			if( $post_type != 'post' )
			{
				$post_type_object	= get_post_type_object( $post_type );
				$post_type_archive	= get_post_type_archive_link( $post_type );
				
				echo '<li class="breadcrumb-item"><a href="'. $post_type_archive .'">'. $post_type_object->labels->name .'</a></li>';
			}
			
			echo '<li class="breadcrumb-item active" aria-current="page">' . $custom_tax_name . '</li>';
		}
		elseif( is_single() )
		{
			$post_type		= get_post_type();
			$category		= get_the_category();
			$cat_display	= '';
			$last_category	= '';
			$cat_id			= 0;
			
			if( $post_type != 'post' )
			{
				$post_type_object	= get_post_type_object( $post_type );
				$post_type_archive	= get_post_type_archive_link( $post_type );
				
				echo '<li class="breadcrumb-item"><a href="'. $post_type_archive .'">'. $post_type_object->labels->name .'</a></li>';
			}
			
			if( ! empty( $category ) )
			{
				$last_category		= @end( array_values( $category ) );
				$get_cat_parents	= @rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
				$cat_parents		= explode( ',',$get_cat_parents );
				
				foreach( $cat_parents as $parents )
				{
					$cat_display .= '<li class="breadcrumb-item">'.$parents.'</li>';
				}
			}
			
			// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
			$custom_taxonomy	= false;
			$taxonomy_exists	= taxonomy_exists( $custom_taxonomy );
			
			if( empty( $last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists )
			{
				$taxonomy_terms	= get_the_terms( $post->ID, $custom_taxonomy );
				$cat_id			= $taxonomy_terms[0]->term_id;
				$cat_nicename	= $taxonomy_terms[0]->slug;
				$cat_link		= get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
				$cat_name		= $taxonomy_terms[0]->name;
			}
			
			if( ! empty( $last_category ) )
			{
				echo $cat_display;
				echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
			}
			elseif( ! empty( $cat_id ) )
			{
				echo '<li class="breadcrumb-item"><a href="'. $cat_link .'">'. $cat_name .'</a></li>';
				echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
			}
			else
			{
				echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
			}			
		}
		elseif( is_category() )
		{
			echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title( '', false ) . '</li>';
		}
		elseif( is_page() )
		{
			if( $post->post_parent )
			{
				$anc	= get_post_ancestors( $post->ID );
				$anc	= array_reverse( $anc );
				
				if( ! isset( $parents ) )
				{
					$parents = null;
				}
				
				foreach( $anc as $ancestor )
				{
					$parents .= '<li class="breadcrumb-item"><a href="'. get_permalink( $ancestor ) .'">'. get_the_title( $ancestor ) .'</a></li>';
				}
				
				echo $parents;
			}
			
			echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
		}
		elseif( is_tag() )
		{
			$term_id	= get_query_var('tag_id');
			$args		= 'include=' . $term_id;
			$terms		= get_terms( 'post_tag', $args );
				
			echo '<li class="breadcrumb-item active" aria-current="page">' . $terms[0]->name . '</li>';
		}
		elseif( is_day() )
		{
			echo '<li class="breadcrumb-item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') .'</a></li>';
			echo '<li class="breadcrumb-item"><a href="'. get_month_link( get_the_time('Y'), get_the_time('m') ) .'">'. get_the_time('M') .'</a></li>';
			echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('j') . '</li>';
		}
		elseif( is_month() )
		{
			echo '<li class="breadcrumb-item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') .'</a></li>';
			echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('M') . '</li>';
		}
		elseif( is_year() )
		{
			echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('Y') . '</li>';
		}
		elseif( is_author() )
		{
			global $author;
			$userdata = get_userdata( $author );
			
			echo '<li class="breadcrumb-item active" aria-current="page">' . $userdata->display_name . '</li>';
		}
		elseif( get_query_var('paged') )
		{
			global $author;
			$userdata = get_userdata( $author );
			
			echo '<li class="breadcrumb-item active" aria-current="page">'. __( 'page', 'mlm' ) .' ' . get_query_var('paged') . '</li>';
		}
		elseif( is_search() )
		{
			echo '<li class="breadcrumb-item active" aria-current="page">'. sprintf( __( "Search results for %s", 'mlm' ), get_search_query() ) .'</li>';
		}
		elseif( is_404() )
		{
			echo '<li class="breadcrumb-item active" aria-current="page">'. __( "404 - Not found", "mlm" ) .'</li>';
		}
		
		echo '</ol>';
		echo '</nav>';
	}
}


/**
 * Set post views.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_set_post_views' ) )
{
	function mlm_set_post_views( $post_id )
	{
		$count	= (int)get_post_meta( $post_id, 'mlm_views', true );
		
		if( $count == 0 )
		{
			update_post_meta( $post_id, 'mlm_views', 1 );
		}
		else
		{
			$count+=1;
			update_post_meta( $post_id, 'mlm_views', $count );
		}
	}
}


/**
 * Get post views.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_get_post_views' ) )
{
	function mlm_get_post_views( $post_id )
	{
		return (int)get_post_meta( $post_id, 'mlm_views', true );
	}
}


/**
 * Count up post views.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_count_up_post_veiws' ) )
{
	function mlm_count_up_post_veiws()
	{
		if( ! is_singular() )
		{
			return;
		}
		
		mlm_set_post_views( get_the_ID() );
	}
	add_action( 'wp_head', 'mlm_count_up_post_veiws' );
}


/**
 * Post excerpt print
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_excerpt' ) )
{
	function mlm_excerpt( $length = NULL, $print = true )
	{
		if( empty( $length ) )
		{
			$length = 280;
		}
		
		$output		= '';
		$length		= $length + 1;
		$excerpt	= get_the_excerpt();
		$excerpt	= strip_tags( $excerpt );
		
		if( empty( $excerpt ) )
		{
			$excerpt	= strip_tags( get_the_content() );
		}
		
		if( mb_strlen( $excerpt ) > $length )
		{
			$subex		= mb_substr( $excerpt, 0, $length - 5 );
			$exwords	= explode( ' ', $subex );
			$excut		= - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			
			if ( $excut < 0 ) {
				$output .= mb_substr( $subex, 0, $excut );
			} else {
				$output .= $subex;
			}
			
			$output .= '[...]';
		}
		else
		{
			$output .= $excerpt;
		}
		
		if( $print )
		{
			echo $output;
		}
		
		return $output;
	}
}


/**
 * Post thumbnail
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_post_thumbnail' ) )
{
	function mlm_post_thumbnail( $size )
	{
		if( has_post_thumbnail() && ! post_password_required() )
		{
			the_post_thumbnail( $size, array( 'class' => 'img-fluid' ) );
		}
		else
		{
			echo '<img class="img-fluid" src="'. IMAGES .'/no-thumbnail.png" alt="'. the_title_attribute('echo=0') .'">';
		}
	}
}


/**
 * Get post image url
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_image_url' ) )
{
	function mlm_image_url( $post_id, $size = 'thumbnail', $print = true )
	{
		$image_src	= get_the_post_thumbnail_url( $post_id, $size );
		
		if( ! $image_src )
		{
			$image_src	= IMAGES . '/no-thumbnail.png';
		}
		
		if( ! $print )
		{
			return $image_src;
		}
		
		echo $image_src;
	}
}


/**
 * HEADER META.
 *
 * @pack WebHow
 */
if ( ! function_exists( 'mlm_header_meta' ) )
{
	function mlm_header_meta()
	{
		$demo			= mlm_selected_demo();
		$header_meta	= get_option('mlm_header_meta');
		$popup_img		= get_option('mlm_popup_img');
		$bg_color		= get_option('mlm_bg_color');
		$bg_footer		= get_option('mlm_bg_footer');
		$bg_header		= get_option('mlm_bg_header');
		$main_color		= get_option('mlm_main_color');
		
		echo '<style type="text/css">';
		
		if( ! empty( $popup_img ) )
		{
			echo '#mlm-login-register-popup .mlm-popup-login-cover:after {background-image: url('. $popup_img .') !important;}';
		}
		
		if( ! empty( $bg_color ) )
		{
			echo 'body {background-color: '. $bg_color .';}';
		}
		
		if( ! empty( $bg_header ) )
		{
			echo '.mlm-header {background-color: '. $bg_header .'!important;}';
		}
		
		if( ! empty( $bg_footer ) )
		{
			echo '.mlm-footer, .app-footer {background-color: '. $bg_footer .'!important;}';
		}
		
		if( ! empty( $main_color ) )
		{
			echo '
			.app-header-menu .cat-link:hover .icon,
			.app-mobile-menu .cat-nav .cat-link.active .icon,
			.app-mobile-menu .cat-nav .cat-link:hover .icon,
			.ui-datepicker a:hover,
			.ui-datepicker td:hover a,
			.dropdown-item:hover,
			.dropdown-item:focus,
			.mlm-stats-box .stat-item .count,
			.mlm-single-product .mlm-product-meta .icon,
			.mlm-single-post .mlm-post-cat-box a:hover,
			.mlm-single-product .mlm-product-cat-box a:hover,
			.mlm-vendor-box .vendor-stats .stat-item .icon,
			.mlm-panel-wrapper .mlm-user-meta .icon,
			.mlm-panel-wrapper .woocommerce-table a:hover,
			.mlm-panel-wrapper .woocommerce-orders-table a:hover,
			.mlm-panel-wrapper .mlm-table .title:hover,
			.woocommerce-MyAccount-navigation ul a:hover,
			.woocommerce form.cart a.added_to_cart,
			.woocommerce table.variations a.reset_variations,
			.mlm-progress-bar .step-item .txt,
			.app-products-archive .archive-item .item-price,
			.app-fixed-popup-box .item-price,
			.app-panel-content .total-stats-widget .stat-item .v,
			.text-primary {
				color: '. $main_color .'!important;
			}
			.app-notification,
			.app-mobile-menu .app-left-mobile-menu,
			.btn-mlm,
			.button:not(.btn),
			button[type="submit"]:not(.btn),
			input[type="submit"]:not(.btn),
			.btn-mlm:hover,
			.button:not(.btn):hover,
			button[type="submit"]:not(.btn):hover,
			input[type="submit"]:not(.btn):hover,
			.btn-mlm:focus,
			.button:not(.btn):focus,
			button[type="submit"]:not(.btn):focus,
			input[type="submit"]:not(.btn):focus,
			.ui-datepicker .ui-datepicker-current-day,
			.tooltip > .tooltip-inner,
			.mlm-header .mlm-main-nav a.mlm-cart-btn .quantity,
			.swiper-button-prev,
			.swiper-button-next,
			.swiper-pagination-bullet-active,
			.mlm-box-title:not(.icon):after,
			.mlm-footer-widget .widget-title:not(.icon):after,
			.mlm-navigation .page-item.active .page-link,
			.mlm-product-nav .nav-pills .nav-link.active,
			.mlm-single-post .mlm-post-tags a:not(.btn):hover,
			.mlm-single-post .mlm-post-tags a:not(.btn):focus,
			.mlm-single-product .mlm-post-tags a:not(.btn):hover,
			.mlm-single-product .mlm-post-tags a:not(.btn):focus,
			.mlm-user-panel-widget .panel-nav .acik > a,
			.mlm-table thead,
			.mlm-orders-wrapper table thead,
			.mlm-ticket-content .ticket-reply .top-bar,
			.mlm-bookmark-table .mlm-tool:hover,
			.mlm-countdown .countdown-section:hover .countdown-amount,
			.mlm-widget .widget-title:not(.icon):after,
			.woocommerce .widget_shopping_cart .cart_list li a.remove,
			.woocommerce.widget_shopping_cart .cart_list li a.remove,
			.woocommerce-MyAccount-navigation ul .is-active a,
			.woocommerce table.shop_table thead,
			.woocommerce table.variations a.reset_variations:hover,
			.mlm-progress-bar .step-item:before,
			.mlm-progress-bar .step-item .num,
			.btn-primary,
			.btn-primary:hover,
			.btn-primary:focus,
			.app-products-archive .box-title .title .icon,
			.app-product-slide .slide-links .btn-demo,
			.app-product-slide-widget .slide-links .btn-demo,
			.app-header-menu .app-notification-btn.al:before,
			.app-header-menu .app-basket-btn.al:before,
			.app-header-menu .register-btn,
			.bg-primary {
				background-color: '. $main_color .'!important;
			}
			.btn-mlm:hover,
			.button:not(.btn):hover,
			button[type="submit"]:not(.btn):hover,
			input[type="submit"]:not(.btn):hover,
			.btn-mlm:focus,
			.button:not(.btn):focus,
			button[type="submit"]:not(.btn):focus,
			input[type="submit"]:not(.btn):focus,
			.btn-primary,
			.btn-primary:hover,
			.btn-primary:focus {
				opacity: 0.9!important;
				box-shadow: none!important;
			}
			.btn-mlm,
			.button:not(.btn),
			button[type="submit"]:not(.btn),
			input[type="submit"]:not(.btn),
			.btn-mlm:hover,
			.button:not(.btn):hover,
			button[type="submit"]:not(.btn):hover,
			input[type="submit"]:not(.btn):hover,
			blockquote,
			.mlm-header .mlm-top-nav .nav-link:hover,
			.mlm-header .mlm-top-nav .nav-link:focus,
			.mlm-main-search .mlm-cat-item:hover .icon,
			.mlm-archive .mlm-product:hover,
			.mlm-archive .mlm-product-sm:hover,
			.mlm-category-widget .mlm-category-box:hover,
			.mlm-panel-wrapper .mlm-archive .mlm-product-sm:hover,
			.mlm-panel-wrapper .mlm-category-widget .mlm-category-box:hover,
			.woocommerce table.variations a.reset_variations,
			.woocommerce ul.order_details li,
			.btn-primary,
			.btn-primary:hover,
			.btn-primary:focus,
			.border-primary,
			.app-panel-content .dashboard-menu .panel-nav .acik > a,
			.app-fixed-header {
				border-color: '. $main_color .'!important;
			}
			.bs-tooltip-right .arrow::before,
			.bs-tooltip-auto[x-placement^="right"] .arrow::before {
				border-right-color: '. $main_color .'!important;
			}
			.bs-tooltip-left .arrow::before,
			.bs-tooltip-auto[x-placement^="left"] .arrow::before {
				border-left-color: '. $main_color .'!important;
			}
			.bs-tooltip-top .arrow::before,
			.bs-tooltip-auto[x-placement^="top"] .arrow::before {
				border-top-color: '. $main_color .'!important;
			}
			.bs-tooltip-bottom .arrow::before,
			.bs-tooltip-auto[x-placement^="bottom"] .arrow::before {
				border-bottom-color: '. $main_color .'!important;
			}
			.mlm-header .mlm-main-nav a.mlm-cart-btn .quantity:after {
				border-top-color: '. $main_color .'!important;
			}
			';
			
			if( $demo == 'zhaket' )
			{
				echo '
				.page-header {
					background: '. $main_color .'!important;
				}
				.app-product-tabs .nav .nav-link:hover,
				.app-product-tabs .nav .nav-link.active {
					color: '. $main_color .'!important;
					box-shadow: 0px -4px 0px 0px '. $main_color .'!important;
				}
				';
			}
			else
			{
				echo '
				.woocommerce #respond input#submit.alt,
				.woocommerce a.button.alt,
				.woocommerce button.button.alt,
				.woocommerce input.button.alt,
				.woocommerce #respond input#submit, 
				.woocommerce a.button,
				.woocommerce button.button, 
				.woocommerce input.button,
				.woocommerce #respond input#submit.alt.disabled,
				.woocommerce #respond input#submit.alt.disabled:hover,
				.woocommerce #respond input#submit.alt:disabled,
				.woocommerce #respond input#submit.alt:disabled:hover,
				.woocommerce #respond input#submit.alt:disabled[disabled],
				.woocommerce #respond input#submit.alt:disabled[disabled]:hover,
				.woocommerce a.button.alt.disabled,
				.woocommerce a.button.alt.disabled:hover,
				.woocommerce a.button.alt:disabled,
				.woocommerce a.button.alt:disabled:hover,
				.woocommerce a.button.alt:disabled[disabled],
				.woocommerce a.button.alt:disabled[disabled]:hover,
				.woocommerce button.button.alt.disabled,
				.woocommerce button.button.alt.disabled:hover,
				.woocommerce button.button.alt:disabled,
				.woocommerce button.button.alt:disabled:hover,
				.woocommerce button.button.alt:disabled[disabled],
				.woocommerce button.button.alt:disabled[disabled]:hover,
				.woocommerce input.button.alt.disabled,
				.woocommerce input.button.alt.disabled:hover,
				.woocommerce input.button.alt:disabled,
				.woocommerce input.button.alt:disabled:hover,
				.woocommerce input.button.alt:disabled[disabled],
				.woocommerce input.button.alt:disabled[disabled]:hover {
					background-color: '. $main_color .'!important;
				}
				.woocommerce #respond input#submit.alt,
				.woocommerce a.button.alt,
				.woocommerce button.button.alt,
				.woocommerce input.button.alt,
				.woocommerce #respond input#submit, 
				.woocommerce a.button,
				.woocommerce button.button, 
				.woocommerce input.button,
				.woocommerce form.cart a.added_to_cart {
					border-color: '. $main_color .'!important;
				}
				';
			}
		}
		
		echo '</style>';
		
		if( ! empty( $header_meta ) )
		{
			echo stripslashes( htmlspecialchars_decode( $header_meta, ENT_QUOTES ) );
		}
	}
	add_action( 'wp_head', 'mlm_header_meta' );
}


/**
 * FOOTER SCRIPTS.
 *
 * @pack WebHow
 */
if ( ! function_exists( 'mlm_footer_scripts' ) )
{
	function mlm_footer_scripts()
	{
		$footer_meta	= get_option('mlm_footer_meta');
		$fixed_menu		= get_option('mlm_fixed_menu');
		$fixed_menu_lg	= get_option('mlm_fixed_menu_lg');
		$fixed_btn_lg	= get_option('mlm_fixed_btn_lg');
		
		if( $fixed_menu == 'yes' || $fixed_menu_lg == 'yes' )
		{
			?>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				function mlm_get_width() {
					return Math.max(
						document.body.scrollWidth,
						document.documentElement.scrollWidth,
						document.body.offsetWidth,
						document.documentElement.offsetWidth,
						document.documentElement.clientWidth
					);
				}
				
				if( $('.mlm-header .mlm-main-nav').length ) {
					var navpos = $('.mlm-header .mlm-main-nav').offset(),
					pagewidth = mlm_get_width(),
					navHeight = $('.mlm-header .mlm-main-nav').height();
					
					<?php if( $fixed_menu == 'yes' && $fixed_menu_lg == 'yes' ): ?>
						$(window).bind('scroll', function() {
							if ( $(window).scrollTop() > navpos.top ) {
								$('#header.mlm-header').css( 'padding-top', navHeight + 'px' );
								$('.mlm-header .mlm-main-nav').addClass('fixed-menu');
							} else {
								$('#header.mlm-header').css( 'padding-top', '0px' );
								$('.mlm-header .mlm-main-nav').removeClass('fixed-menu');
							}
						});
					<?php elseif( $fixed_menu == 'yes' ): ?>
						$(window).bind('scroll', function() {
							if ( $(window).scrollTop() > navpos.top && pagewidth <= 750 ) {
								$('#header.mlm-header').css( 'padding-top', navHeight + 'px' );
								$('.mlm-header .mlm-main-nav').addClass('fixed-menu');
							} else {
								$('#header.mlm-header').css( 'padding-top', '0px' );
								$('.mlm-header .mlm-main-nav').removeClass('fixed-menu');
							}
						});
					<?php elseif( $fixed_menu_lg == 'yes' ): ?>
						$(window).bind('scroll', function() {
							if ( $(window).scrollTop() > navpos.top && pagewidth > 750 ) {
								$('#header.mlm-header').css( 'padding-top', navHeight + 'px' );
								$('.mlm-header .mlm-main-nav').addClass('fixed-menu');
							} else {
								$('#header.mlm-header').css( 'padding-top', '0px' );
								$('.mlm-header .mlm-main-nav').removeClass('fixed-menu');
							}
						});
					<?php endif; ?>
				}
			});
			</script>
			<?php
		}
		
		if( $fixed_btn_lg == 'yes' && is_singular('product') )
		{
			?>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				if( $('.mlm-purchase-product-widget .mlm-purchase-btn').length ) {
					var btnpos = $('.mlm-purchase-product-widget .mlm-purchase-btn').offset();
					$(window).bind('scroll', function() {
						if ( $(window).scrollTop() > btnpos.top ) {
							$('.mlm-product-fixed-widget').addClass('d-md-block');
							$('.mlm-product-fixed-widget').removeClass('d-md-none');
						} else {
							$('.mlm-product-fixed-widget').addClass('d-md-none');
							$('.mlm-product-fixed-widget').removeClass('d-md-block');
						}
					});
				}
			});
			</script>
			<?php
		}
		
		if( ! empty( $footer_meta ) )
		{
			echo stripslashes( htmlspecialchars_decode( $footer_meta, ENT_QUOTES ) );
		}
	}
	add_action( 'wp_footer', 'mlm_footer_scripts' );
}


/**
 * Create a nav menu with very basic markup.
 *
 * @author Thomas Scholz http://toscho.de
 * @version 1.0
 */
class MLM_Nav_Menu_Walker extends Walker_Nav_Menu
{
	/**
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() )
	{
		$output .= '<ul class="drilldown-sub"><li class="drilldown-back">'. __( '<a href="#" class="bg-light text-dark icon icon-arrow-right2" rel="nofollow">Return</a>', 'mlm' ) .'</li>';
	}
}


/**
 * REMOVE URL FROM COMMENTS.
 *
 * @pack WebHow
 */
if( ! function_exists('mlm_remove_comments_url') )
{
	function mlm_remove_comments_url( $fields )
	{
		if( isset( $fields['url'] ) )
		{
			unset( $fields['url'] );
		}
		
		return $fields;
	}
	add_filter( 'comment_form_default_fields', 'mlm_remove_comments_url' );
}


/**
 * REMOVE PAGES FROM SEARCH
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_remove_pages_from_search' ) )
{
	function mlm_remove_pages_from_search()
	{
		global $wp_post_types;
		$wp_post_types['page']->exclude_from_search	= true;
	}
	add_action( 'init', 'mlm_remove_pages_from_search' );
}


/**
 * Register custom query vars
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
if( ! function_exists( 'mlm_register_query_vars' ) )
{
	function mlm_register_query_vars( $vars )
	{
		$vars[] = 'mlm_order';
		$vars[] = 'mlm_min_price';
		$vars[] = 'mlm_max_price';
		$vars[] = 'mlm_category';
		$vars[] = 'mlm_tag';
		$vars[] = 'mlm_medal';
		$vars[] = 'mlm_vendor';
		
		return $vars;
	}
	add_filter( 'query_vars', 'mlm_register_query_vars' );
}


/**
 * Hook global wp_query.
 *
 * @pack WebHow
 */
if ( ! function_exists( 'mlm_hook_wp_query' ) )
{
	function mlm_hook_wp_query( $query )
	{
		if( is_admin() )
		{
			return $query;
		}
		
		$mlm_order		= get_query_var( 'mlm_order' );
		
		if( $query->is_main_query() && is_author() )
		{
			$query->set( 'post_type', array( 'post', 'product' ) );
		}
		
		if( $query->is_main_query() && ! empty( $mlm_order ) && ! is_singular() )
		{
			if( $mlm_order == 'old' )
			{
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'ASC' );
			}
			elseif( $mlm_order == 'new' )
			{
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
			}
			elseif( $mlm_order == 'update' )
			{
				$query->set( 'orderby', 'modified' );
				$query->set( 'order', 'DESC' );
			}
			elseif( $mlm_order == 'view' )
			{
				$query->set( 'meta_key', 'mlm_views' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'DESC' );
			}
			elseif( $mlm_order == 'sale' )
			{
				$query->set( 'meta_key', 'total_sales' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'DESC' );
			}
			elseif( $mlm_order == 'low' )
			{
				$query->set( 'meta_key', '_price' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'ASC' );
			}
			elseif( $mlm_order == 'high' )
			{
				$query->set( 'meta_key', '_price' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'DESC' );
			}
		}
		
		if( ! is_singular() && function_exists('is_shop') && is_shop() && $query->is_main_query() && $mlm_order == 'sale' )
		{
			$meta_query		= (array)$query->get( 'meta_query' );
			$meta_query[]	= array(
				'key'		=> '_price',
				'value'		=> 0,
				'compare'	=> '>',
			);
			
			if( count( $meta_query ) > 0 )
			{
				$meta_query['relation']	= 'AND';
			}
			
			$query->set( 'meta_query', $meta_query );
		}
		
		if( $query->is_main_query() && is_search() )
		{
			$vendor			= get_query_var( 'mlm_vendor', '' );
			$min_price		= get_query_var( 'mlm_min_price', '' );
			$max_price		= get_query_var( 'mlm_max_price', '' );
			$category		= get_query_var( 'mlm_category', array() );
			$tag			= get_query_var( 'mlm_tag', array() );
			$medals			= get_query_var( 'mlm_medal', array() );
			$tax_query		= array();
			$meta_query		= array();
			
			$query->set( 'post_type', 'product' );
			
			if( ! empty( $vendor ) )
			{
				$query->set( 'author', $vendor );
			}
			
			if( is_array( $category ) && count( $category ) > 0 )
			{
				$tax_query[] = array(
					'taxonomy'			=> 'product_cat',
					'field'				=> 'term_id',
					'terms'				=> $category,
				);
			}
			
			if( is_array( $tag ) && count( $tag ) > 0 )
			{
				$tax_query[] = array(
					'taxonomy'			=> 'product_tag',
					'field'				=> 'term_id',
					'terms'				=> $tag,
				);
			}
			
			if( count( $tax_query ) > 0 )
			{
				$tax_query['relation']			= 'AND';
				$query->tax_query->queries[]	= $tax_query;
				$query->query_vars['tax_query']	= $query->tax_query->queries;
			}
			
			if( ! empty( $min_price ) )
			{
				$meta_query[] = array(
					'key'		=> '_price',
					'value'		=> $min_price,
					'compare'	=> '>=',
				);
			}
			
			if( ! empty( $max_price ) )
			{
				$meta_query[] = array(
					'key'		=> '_price',
					'value'		=> $max_price,
					'compare'	=> '<=',
				);
			}
			
			if( is_array( $medals ) && count( $medals ) > 0 )
			{
				foreach( $medals as $medal )
				{
					$meta_query[] = array(
						'key'		=> 'mlm_medal_'.$medal,
						'value'		=> '1'
					);
				}
			}
			
			if( count( $meta_query ) > 1 )
			{
				$meta_query['relation'] = 'AND';
			}
			
			if( count( $meta_query ) > 0 )
			{
				$query->set( 'meta_query', $meta_query );
			}
		}
		
		return $query;
	}
	add_filter( 'pre_get_posts', 'mlm_hook_wp_query' );
}

/**
 * Next/Prev link attributes.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_selected_demo' ) )
{
	function mlm_selected_demo()
	{
		$demo	= get_option('mlm_demo');
		
		return $demo;
	}
}


/**
 * Add namespace for media:image element used below.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_rss_image_add' ) )
{
	function mlm_rss_image_add()
	{
		echo 'xmlns:media="http://search.yahoo.com/mrss/"';
	}
	add_filter( 'rss2_ns', 'mlm_rss_image_add' );
}


/**
 * insert the image object into the RSS item (see MB-191).
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_rss_image_content' ) )
{
	function mlm_rss_image_content()
	{
		global $post;
		
		if( has_post_thumbnail($post->ID) )
		{
			$thumbnail_ID	= get_post_thumbnail_id($post->ID);
			$thumbnail		= wp_get_attachment_image_src($thumbnail_ID, 'medium');
			
			if( is_array($thumbnail) )
			{
				echo '<media:content medium="image" url="' . $thumbnail[0] . '" width="' . $thumbnail[1] . '" height="' . $thumbnail[2] . '" />';
			}
		}
	}
	add_action('rss2_item', 'mlm_rss_image_content' );
}


/**
 * Load widgets.
 */
get_template_part( 'includes/widgets/contact' );
get_template_part( 'includes/widgets/about' );
get_template_part( 'includes/widgets/products' );
get_template_part( 'includes/widgets/categories' );
get_template_part( 'includes/widgets/posts' );
get_template_part( 'includes/widgets/offers' );
get_template_part( 'includes/widgets/featured-product' );
get_template_part( 'includes/widgets/top-vendor' );
get_template_part( 'includes/widgets/top-products' );


/**
 * Register widgets.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_register_featured_widgets' ) )
{
	function mlm_register_featured_widgets()
	{
		$demo = mlm_selected_demo();
		
		register_widget( 'MLM_Contact_Widget' );
		register_widget( 'MLM_About_Widget' );
		register_widget( 'MLM_Products_Carousel_Widget' );
		register_widget( 'MLM_Categories_Widget' );
		register_widget( 'MLM_Posts_Carousel_Widget' );
		register_widget( 'MLM_Offers_Slider_Widget' );
		
		if( $demo == 'zhaket' )
		{
			register_widget( 'MLM_Featured_Product_Widget' );
			register_widget( 'MLM_Top_Vendor_Widget' );
			register_widget( 'MLM_Top_Products_Widget' );
		}
	}
	add_action( 'widgets_init', 'mlm_register_featured_widgets' );
}