<?php


/**
 * WooCommerce support.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_woocommerce_support' ) )
{
	function mlm_woocommerce_support()
	{
		add_theme_support( 'woocommerce' );
	}
	add_action( 'after_setup_theme', 'mlm_woocommerce_support' );
}


/**
 * Customize account page navigation.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_remove_my_account_links' ) )
{
	function mlm_remove_my_account_links( $menu_links )
	{
		unset( $menu_links['edit-account'] );
		unset( $menu_links['customer-logout'] );

		return $menu_links;
	}
	add_filter ( 'woocommerce_account_menu_items', 'mlm_remove_my_account_links' );
}


/**
 * Ajax cart count
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_cart_count_fragments' ) )
{
	function mlm_cart_count_fragments( $fragments )
	{
		$count = WC()->cart->get_cart_contents_count();

		$demo = mlm_selected_demo();

		if( $demo == 'zhaket' )
		{
			$class = ( $count > 0 ) ? 'al' : '';
			$fragments['.app-basket-btn'] = '<button type="button" class="app-basket-btn btn border-0 bg-transparent position-relative '. $class .'"><svg viewBox="0 0 22.8 29.4"><path d="M21.8 6.5h-5.6V4.8c0-2.6-2.1-4.8-4.8-4.8-2.6 0-4.8 2.1-4.8 4.8v1.8H1c-.6 0-1 .4-1 1v17.8c.2 2.2 2 4 4.3 4h14.3c2.2 0 4.1-1.8 4.3-4.1V7.5c-.1-.5-.5-1-1.1-1zM8.6 4.8C8.6 3.3 9.9 2 11.4 2c1.5 0 2.8 1.3 2.8 2.8v1.8H8.6V4.8zm10 22.6H4.5c-1.2.1-2.3-.9-2.4-2.1V8.5h4.6v.9c0 .6.4 1 1 1s1-.4 1-1v-.9h5.6v.9c0 .6.4 1 1 1s1-.4 1-1v-.9h4.6v16.7c-.2 1.2-1.1 2.2-2.3 2.2z"></path></svg></button>';
		}
		else
		{
			$class = ( $count > 0 ) ? '' : 'empty-cart';
			$fragments['.mlm-cart-quantity'] = '<span class="mlm-cart-quantity">
			<span class="quantity rounded '. $class .'">'. $count .'</span>
			<span class="icon icon-cart '. $class .'"></span>
			</span>';
		}



		return $fragments;
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'mlm_cart_count_fragments', 10, 1 );
}


/**
 * Remove the sorting dropdown from Woocommerce
 *
 * @pack WebHow
 */
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );


/**
 * Remove the result count from WooCommerce
 *
 * @pack WebHow
 */
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );


/**
 * Rearrange add to cart button
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 *
 * @pack WebHow
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );


/**
 * Remove unwanted checkout fields
 *
 * @return $fields array
 */
if( ! function_exists( 'mlm_remove_billing_checkout_fields' ) )
{
	function mlm_remove_billing_checkout_fields( $fields )
	{
		if( mlm_cart_has_virtual_product() == true )
		{
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_country']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_state']);
			// unset($fields['billing']['billing_phone']);
		}

		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields' , 'mlm_remove_billing_checkout_fields', 99 );
}


/**
 * Remove Order Notes (fully, including the title) on the WooCommerce Checkout
 */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


/**
 * Check if the cart contains virtual product
 *
 * @return bool
 */
if( ! function_exists( 'mlm_cart_has_virtual_product' ) )
{
	function mlm_cart_has_virtual_product()
	{
		foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item )
		{
			if( ! $cart_item['data']->is_virtual() )
			{
				return false;
			}
		}

		return true;
	}
}


/**
 * Products per page
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_products_per_page' ) )
{
	function mlm_products_per_page( $cols )
	{
		return (int)get_option('mlm_product_per');
	}
	add_filter( 'loop_shop_per_page', 'mlm_products_per_page', 20 );
}


/**
 * Enable product author
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_product_author_support' ) )
{
	function mlm_product_author_support()
	{
		add_post_type_support( 'product', 'author' );
	}
	add_action( 'init', 'mlm_product_author_support' );
}


/**
 * WC logout URL
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_wc_logut_url' ) )
{
	function mlm_wc_logut_url()
	{
		if( function_exists('wc_logout_url') )
		{
			return esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) );
		}

		return wp_logout_url();
	}
}


/**
 * Download tables image column.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_custom_downloads_table_columns' ) )
{
	function mlm_custom_downloads_table_columns( $columns )
	{
		$image_column	= array(
			'product-image' => __( 'Image', 'mlm' )
		);

		return array_merge( $image_column, $columns );
	}
	add_action( 'woocommerce_account_downloads_columns', 'mlm_custom_downloads_table_columns', 99, 1 );
}


/**
 * Download tables image column value.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_downloads_table_image_column' ) )
{
	function mlm_downloads_table_image_column( $download )
	{
		?>
		<img width="64" height="64" src="<?php mlm_image_url( $download['product_id'], 'thumbnail' ); ?>" class="d-block rounded border post-image" alt="post-image">
		<?php
	}
	add_action( 'woocommerce_account_downloads_column_product-image', 'mlm_downloads_table_image_column' );
}


/**
 * Change products default sorting.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_change_wc_default_sorting' ) )
{
	function mlm_change_wc_default_sorting( $args )
	{
		$args['order']		= 'DESC';
		$args['orderby']	= 'date';

		return $args;
	}
	add_filter( 'woocommerce_get_catalog_ordering_args', 'mlm_change_wc_default_sorting', 99 );
}


/**
 * Print product price
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_product_price' ) )
{
	function mlm_product_price( $post_id = false )
	{
		if( ! function_exists( 'wc_get_product' ) )
		{
			return;
		}

		if( ! $post_id )
		{
			$post_id	= get_the_ID();
		}

		$productObj	= wc_get_product( $post_id );

		/*
		$productObj->get_regular_price();
		$productObj->get_sale_price();
		$productObj->get_price();
		$productObj->get_price_html();
		*/

		if( $productObj->get_price() == 0 )
		{
			_e( 'Free', 'mlm' );
		}
		else
		{
			echo mlm_filter( $productObj->get_price() );
		}
	}
}


/**
 * Get product price
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_get_product_price' ) )
{
	function mlm_get_product_price( $post_id )
	{
		if( ! function_exists( 'wc_get_product' ) || ! mlm_post_exists( $post_id ) )
		{
			return 0;
		}

		$productObj	= wc_get_product( $post_id );

		return is_object( $productObj ) ? intval( $productObj->get_price() ) : 0;
	}
}


/**
 * Print product off
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_product_has_off' ) )
{
	function mlm_product_has_off( $post_id = false )
	{
		if( ! function_exists( 'wc_get_product' ) )
		{
			return 0;
		}

		if( ! $post_id )
		{
			$post_id	= get_the_ID();
		}

		$productObj		= wc_get_product( $post_id );
		$sale_price		= $productObj->get_sale_price();
		$regular_price	= $productObj->get_regular_price();
		$percentage		= 0;

		if( $sale_price >= 0 && $regular_price > 0 && $productObj->is_on_sale() )
		{
			$percentage	= round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
		}

		return $percentage;
	}
}


/**
 * Filter price.
 *
 * @pack WebHow.
 */
if( ! function_exists('mlm_filter') )
{
	function mlm_filter( $price )
	{
		// Return if price is empty.
		if( empty( $price ) && $price != 0 )
		{
			return false;
		}

		// do filter.
		if( function_exists( 'wc_price' ) )
		{
			$price	= wc_price( $price );
		}

		return strip_tags( $price );
	}
}


/**
 * Check if user can download product for free.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_is_product_free' ) )
{
	function mlm_is_product_free( $post_id )
	{
		$price		= mlm_get_product_price( $post_id );
		$download	= get_option('mlm_direct_download');

		if( $price == 0 && $download == 'yes' )
		{
			return true;
		}

		return false;
	}
}


/**
 * Checkout steps.
 *
 * @pack WebHow.
 */
if( ! function_exists('mlm_checkout_steps') )
{
	function mlm_checkout_steps( $step = 1 )
	{
		$output	= '<div class="mlm-progress-bar position-relative mb-4 text-center clearfix">';
		$output	.= '<div class="row no-gutters">';
		$output	.= ( $step == 1 ) ? '<div class="step-item col-4 active">' : '<div class="step-item col-4">';
		$output	.= '<span class="txt ellipsis mb-2 font-14">'. __( 'Shopping Cart', 'mlm' ) .'</span>';
		$output	.= '<span class="num d-block position-relative mx-auto my-0 rounded-circle"></span>';
		$output	.= '</div>';
		$output	.= ( $step == 2 ) ? '<div class="step-item col-4 active">' : '<div class="step-item col-4">';
		$output	.= '<span class="txt ellipsis mb-2 font-14">'. __( 'Confirm & Payment', 'mlm' ) .'</span>';
		$output	.= '<span class="num d-block position-relative mx-auto my-0 rounded-circle"></span>';
		$output	.= '</div>';
		$output	.= ( $step == 3 ) ? '<div class="step-item col-4 active">' : '<div class="step-item col-4">';
		$output	.= '<span class="txt ellipsis mb-2 font-14">'. __( 'Access to Product', 'mlm' ) .'</span>';
		$output	.= '<span class="num d-block position-relative mx-auto my-0 rounded-circle"></span>';
		$output	.= '</div>';
		$output	.= '</div>';
		$output	.= '</div>';

		return $output;
	}
}


/**
 * Print checkout progress steps.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_print_checkout_steps' ) )
{
	function mlm_print_checkout_steps()
	{
		if( is_cart() )
		{
			echo mlm_checkout_steps( 1 );
		}
		elseif( is_checkout() )
		{
			echo mlm_checkout_steps( 2 );
		}
	}
	add_action( 'woocommerce_check_cart_items', 'mlm_print_checkout_steps' );
}


/**
 * Print order received progress step.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_print_received_steps' ) )
{
	function mlm_print_received_steps( $order_id )
	{
		echo mlm_checkout_steps( 3 );

		return $order_id;
	}
	add_filter( 'woocommerce_thankyou_order_id', 'mlm_print_received_steps', 10, 1 );
}


/**
 * Remove default email header and footer.
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_remove_email_template' ) && function_exists('WC') )
{
	function mlm_remove_email_template()
	{
		remove_action( 'woocommerce_email_header', array( WC()->mailer(), 'email_header' ) );
		remove_action( 'woocommerce_email_footer', array( WC()->mailer(), 'email_footer' ) );
	}
	add_action( 'init', 'mlm_remove_email_template' );
}


/**
 * Custom email header
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_custom_email_header' ) )
{
	function mlm_custom_email_header( $email_heading, $email )
	{
		$site_title			= get_bloginfo('name');
		$site_logo			= get_option( 'mlm_logo' );
		$template			= mlm_get_template( 'class/wp-admin/wc-email-header' );

		if( empty( $site_logo ) )
		{
			$site_logo	= IMAGES .'/mail-template/logo.png';
		}

		// HTML mail content.
		$template	= str_replace( '{site_logo}', esc_url( $site_logo ), $template );
		$template	= str_replace( '{site_title}', $site_title, $template );
		$template	= str_replace( '{mail_subject}', $email_heading, $template );

		echo $template;
	}
	add_action( 'woocommerce_email_header', 'mlm_custom_email_header', 10, 2 );
}


/**
 * Custom email footer
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_custom_email_footer' ) )
{
	function mlm_custom_email_footer()
	{
		$telegram			= get_option('mlm_sc_telegram');
		$instagram			= get_option('mlm_sc_instagram');
		$site_domain		= mlmFire()->notif->get_site_domain();
		$template			= mlm_get_template( 'class/wp-admin/wc-email-footer' );

		// HTML mail content.
		$template	= str_replace( '{telegram}', $telegram, $template );
		$template	= str_replace( '{instagram}', $instagram, $template );
		$template	= str_replace( '{site_domain}', $site_domain, $template );

		echo $template;
	}
	add_action( 'woocommerce_email_footer', 'mlm_custom_email_footer' );
}


/**
 * Print purchase button
 *
 * @pack WebHow
 */
if( ! function_exists( 'mlm_add_to_cart_btn' ) )
{
	function mlm_add_to_cart_btn( $post_id, $class = '', $single = false, $price = false, $multiple = false )
	{
		$attr			= array();
		$extra			= '';
		$download_popup	= false;
		$multiple_active= false;
		$login_req		= get_option('mlm_login_req');
		$product		= wc_get_product( $post_id );
		$publish_time	= get_post_meta( $post_id, 'mlm_file_publish', true );
		$demo			= mlm_selected_demo();

		if( is_user_logged_in() )
		{
			$user_id	= get_current_user_id();
			$user_obj	= get_userdata( $user_id );
			$user_email	= $user_obj->user_email;
			$access		= mlmFire()->plan->check_user_access( $post_id, $user_id );
			$purchased	= false;

			if( function_exists('wc_customer_bought_product') && wc_customer_bought_product( $user_email, $user_id, $post_id ) )
			{
				$purchased	= true;
			}

			if( $product->is_type('external') )
			{
				$url			= $product->get_product_url();
				$attr['target']	= '_blank';
				$text			= $product->get_button_text();
			}
			elseif( $product->is_on_backorder() && ! empty( $publish_time ) )
			{
				$url				= false;
				$class				.= ' disabled';
				$attr['disabled']	= 'disabled';

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Coming soon', 'mlm' );
				}
				else
				{
					$text	= __( 'Currently unavailable', 'mlm' );
				}
			}
			elseif( ! $product->is_in_stock() )
			{
				$url				= false;
				$class				.= ' disabled';
				$attr['disabled']	= 'disabled';

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Completed capacity', 'mlm' );
				}
				else
				{
					$text	= __( 'Unsaleable', 'mlm' );
				}
			}
			elseif( mlm_check_course( $post_id ) && ( $access || $purchased ) )
			{
				if( $single )
				{
					$url	= '#mlm-scroll-to-course';
				}
				else
				{
					$url	= get_the_permalink() . '#mlm-scroll-to-course';
				}

				$text	= __( 'You registered already', 'mlm' );
			}
			elseif(	$product->is_downloadable() && ( mlm_is_product_free( $post_id ) || $access || $purchased ) )
			{
				if( $single )
				{
					$downloads	= $product->get_downloads();
					$text		= __( 'Direct download', 'mlm' );

					if( count( $downloads ) > 1 )
					{
						$download_popup			= true;
						$url					= '#';
						$attr['data-toggle']	= 'modal';
						$attr['data-target']	= '#mlm_subscribe_download';
					}
					else
					{
						$url	= '#';

						foreach( $downloads as $key => $value )
						{
							$url	= home_url('?download_file='.$post_id.'&key='.$key.'&subscribe=1' );
						}
					}
				}
				else
				{
					$url	= get_the_permalink();
					$text	= __( 'See details', 'mlm' );
				}
			}
			else
			{
				$url				= '#mlm-add-to-cart';
				$attr['data-id']	= $post_id;

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Participate', 'mlm' );
				}
				else
				{
					$text	= __( 'Add to cart', 'mlm' );
				}

				if( $price )
				{
					$text .= ' - '. $product->get_price_html();
				}

				if( $single && $multiple )
				{
					$multiple_active = true;
				}
			}
		}
		elseif( $login_req == 'no' )
		{
			if( $product->is_type('external') )
			{
				$url			= $product->get_product_url();
				$attr['target']	= '_blank';
				$text			= $product->get_button_text();
			}
			elseif( $product->is_on_backorder() && ! empty( $publish_time ) )
			{
				$url				= false;
				$class				.= ' disabled';
				$attr['disabled']	= 'disabled';

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Coming soon', 'mlm' );
				}
				else
				{
					$text	= __( 'Currently unavailable', 'mlm' );
				}
			}
			elseif( ! $product->is_in_stock() )
			{
				$url				= false;
				$class				.= ' disabled';
				$attr['disabled']	= 'disabled';

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Completed capacity', 'mlm' );
				}
				else
				{
					$text	= __( 'Unsaleable', 'mlm' );
				}
			}
			elseif( mlm_is_product_free( $post_id ) && $product->is_downloadable() )
			{
				if( $single )
				{
					$downloads	= $product->get_downloads();
					$text		= __( 'Direct download', 'mlm' );

					if( count( $downloads ) > 1 )
					{
						$download_popup			= true;
						$url					= '#';
						$attr['data-toggle']	= 'modal';
						$attr['data-target']	= '#mlm_subscribe_download';
					}
					else
					{
						$url	= '#';

						foreach( $downloads as $key => $value )
						{
							$url	= home_url('?download_file='.$post_id.'&key='.$key.'&subscribe=1' );
						}
					}
				}
				else
				{
					$url	= get_the_permalink();
					$text	= __( 'See details', 'mlm' );
				}
			}
			else
			{
				$url				= '#mlm-add-to-cart';
				$attr['data-id']	= $post_id;

				if( mlm_check_course( $post_id ) )
				{
					$text	= __( 'Participate', 'mlm' );
				}
				else
				{
					$text	= __( 'Add to cart', 'mlm' );
				}

				if( $price )
				{
					$text .= ' - '. $product->get_price_html();
				}

				if( $single && $multiple )
				{
					$multiple_active = true;
				}
			}
		}
		else
		{
			$url	= '#';
			$class	.= ' mlm-login-error';
			$attr['data-toggle'] = 'modal';
			$attr['data-target'] = '#mlm-login-register-popup';

			if( mlm_check_course( $post_id ) )
			{
				$text	= __( 'Participate', 'mlm' );
			}
			else
			{
				$text	= ( $product->is_downloadable() ) ? __( 'Direct download', 'mlm' ) : __( 'Purchase', 'mlm' );
			}
		}

		if( $multiple_active /*&& $demo != 'zhaket'*/ && ! $product->is_type('external') )
		{
			woocommerce_template_single_add_to_cart();

			return;
		}

		if( count( $attr ) )
		{
			foreach( $attr as $k => $v )
			{
				$extra .= ' '.$k.'="'.$v.'"';
			}
		}

		if( $url )
		{
                echo '<a href="'. $url .'" class="'. $class .'" '.$extra.'>'. $text .'</a>';
		}
		else
		{
                echo '<button type="button" class="'. $class .'" '.$extra.'>'. $text .'</button>';
		}

		if( $download_popup )
		{
			echo '<div class="modal fade" id="mlm_subscribe_download" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title">'. __( 'Download box', 'mlm' ) .'</h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">';
				foreach( $downloads as $key => $value )
				{
					$dl_link	= home_url('?download_file='.$post_id.'&key='.$key.'&subscribe=1' );

					echo '<a target="_blank" href="'. $dl_link .'" class="btn btn-secondary btn-block">'. $value['name'] .'</a>';
				}
			echo '</div>
				</div>
				</div>
				</div>
			';
		}
	}
}