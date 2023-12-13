<?php
add_action('init','mlm_theme_options');

if ( ! function_exists( 'mlm_theme_options' ) )
{
	function mlm_theme_options()
	{
		global $tt_options;
		$shortname			= 'mlm_';
		$options			= array();
		$tt_options			= get_option('of_options');

		// ACCESS THE WORDPRESS PAGES VIA AN ARRAY
		$tt_pages			= array();
		$tt_pages_obj		= get_pages('sort_column=post_parent,menu_order');
		foreach( $tt_pages_obj as $tt_page )
		{
			if( isset( $tt_page->ID ) && isset( $tt_page->post_title ) )
			{
				$tt_pages[$tt_page->ID][0]	= $tt_page->ID;
				$tt_pages[$tt_page->ID][1]	= $tt_page->post_title;
			}
		}
		$tt_pages_tmp		= array_unshift( $tt_pages, array( 0, __( 'Select page', 'mlm' ) ) );

		// ACCESS THE WORDPRESS CATEGORIES VIA AN ARRAY
		$tt_categories		= array();
		$tt_categories_obj	= get_categories('hide_empty=0&taxonomy=product_cat');
		foreach( $tt_categories_obj as $tt_cat )
		{
			$tt_categories[$tt_cat->cat_ID][0] = $tt_cat->cat_ID;
			$tt_categories[$tt_cat->cat_ID][1] = $tt_cat->cat_name;
		}
		$categories_tmp		= array_unshift( $tt_categories, array(0, __( 'Select category', 'mlm' ) ) );

		// ACCESS THE WORDPRESS CATEGORIES VIA AN ARRAY
		$bg_categories		= array();
		$bg_categories_obj	= get_categories('hide_empty=0&taxonomy=category');
		foreach( $bg_categories_obj as $bg_cat )
		{
			$bg_categories[$bg_cat->cat_ID][0] = $bg_cat->cat_ID;
			$bg_categories[$bg_cat->cat_ID][1] = $bg_cat->cat_name;
		}
		$categories_tmpb	= array_unshift( $bg_categories, array(0, __( 'Select category', 'mlm' ) ) );

		/*
		 * OPTION PAGE 1 - GENERAL
		 */
		$options[] = array(
			'name'		=> __( '1. General', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Phone', 'mlm' ),
			'desc'		=> __( 'Phone number will be displayed on primary menu.', 'mlm' ),
			'id'		=> $shortname.'phone',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Search box title', 'mlm' ),
			'desc'		=> __( 'Home page search box title', 'mlm' ),
			'id'		=> $shortname.'search_title',
			'std'		=> __( 'MarketMLM, Network marketing professional', 'mlm' ),
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Search box sub title', 'mlm' ),
			'desc'		=> __( 'Home page search box title', 'mlm' ),
			'id'		=> $shortname.'search_subtitle',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Medals page title', 'mlm' ),
			'desc'		=> __( 'User panel medals page title', 'mlm' ),
			'id'		=> $shortname.'medal_title',
			'std'		=> __( 'After getting verified you will be displayed on home page top vendor widget.', 'mlm' ),
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Copyright text', 'mlm' ),
			'desc'		=> __( 'Copyright text will be displayed on site footer.', 'mlm' ),
			'id'		=> $shortname.'copyright',
			'std'		=> __( 'All rights reserved.', 'mlm' ),
			'type'		=> 'textarea'
		);
		$options[] = array(
			'name'		=> __( 'Product purchase benefits', 'mlm' ),
			'desc'		=> __( 'Will be displayed at products single page. write on item per line.', 'mlm' ),
			'id'		=> $shortname.'pros_text',
			'std'		=> '',
			'type'		=> 'textarea'
		);
		$options[] = array(
			'name'		=> __( 'Products count', 'mlm' ),
			'desc'		=> __( 'Home pages product count as a numeric value.', 'mlm' ),
			'id'		=> $shortname.'product_count',
			'std'		=> 6,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Products count', 'mlm' ),
			'desc'		=> __( 'Shop pages product count as a numeric value.', 'mlm' ),
			'id'		=> $shortname.'product_per',
			'std'		=> 12,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Related products type', 'mlm' ),
			'desc'		=> __( 'Select how to display related products', 'mlm' ),
			'id'		=> $shortname.'related_type',
			'std'		=> 'sale',
			'type'		=> 'select',
			'options'	=> array(
				'cat'		=> array( 'cat', __( 'By related categories', 'mlm' ) ),
				'tag'		=> array( 'tag', __( 'By related tags', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'View or Sale count?', 'mlm' ),
			'desc'		=> __( 'Select whether to display products view or sale count.', 'mlm' ),
			'id'		=> $shortname.'download_cnt',
			'std'		=> 'sale',
			'type'		=> 'select',
			'options'	=> array(
				'view'		=> array( 'view', __( 'View count', 'mlm' ) ),
				'sale'		=> array( 'sale', __( 'Sale count', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Forced login', 'mlm' ),
			'desc'		=> __( 'Force user login to display products price.', 'mlm' ),
			'id'		=> $shortname.'login_req',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);

		$options[] = array(
			'name'		=> __( 'Free files direct download', 'mlm' ),
			'desc'		=> __( 'Enable free files direct download tool.', 'mlm' ),
			'id'		=> $shortname.'direct_download',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Recaptcha site key', 'mlm' ),
			'desc'		=> __( 'Google Recaptcha site key', 'mlm' ),
			'id'		=> $shortname.'recaptcha_site_key',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Recaptcha secret key', 'mlm' ),
			'desc'		=> __( 'Google Recaptcha secret key', 'mlm' ),
			'id'		=> $shortname.'recaptcha_secret_key',
			'std'		=> '',
			'type'		=> 'text'
		);

		/*
		 * Option Page 2 - SLIDER
		 */
		$options[] = array(
			'name'		=> __( '2. Slider', 'mlm' ),
			'type'		=> 'heading'
		);
		for( $i = 1; $i <= 8; $i++ )
		{
			$options[] = array(
				'name'		=> sprintf( __( 'Slide %d title', 'mlm' ), $i ),
				'desc'		=> __( 'Enter slide title', 'mlm' ),
				'id'		=> $shortname.'slide_txt_' . $i,
				'std'		=> '',
				'type'		=> 'text'
			);
			$options[] = array(
				'name'		=> sprintf( __( 'Slide %d link', 'mlm' ), $i ),
				'desc'		=> __( 'Enter slide link', 'mlm' ),
				'id'		=> $shortname.'slide_url_' . $i,
				'std'		=> '',
				'type'		=> 'text'
			);
			$options[] = array(
				'name'		=> sprintf( __( 'Slide %d image', 'mlm' ), $i ),
				'desc'		=> __( 'Upload slide image', 'mlm' ),
				'id'		=> $shortname.'slide_img_' . $i,
				'std'		=> '',
				'type'		=> 'upload'
			);
		}

		/*
		 * Option Page 3 - cats
		 */
		$options[] = array(
			'name'		=> __( '3. Categories', 'mlm' ),
			'type'		=> 'heading'
		);
		for( $i = 1; $i <= 6; $i++ )
		{
			$options[] = array(
				'name'		=> sprintf( __( 'Category %d', 'mlm' ), $i ),
				'desc'		=> __( 'Select the related category', 'mlm' ),
				'id'		=> $shortname.'cat_' . $i,
				'std'		=> '',
				'type'		=> 'select',
				'options'	=> $tt_categories
			);
			$options[] = array(
				'name'		=> sprintf( __( 'Category %d icon', 'mlm' ), $i ),
				'desc'		=> __( 'Enter the related category icon code', 'mlm' ),
				'id'		=> $shortname.'cat_icon_' . $i,
				'std'		=> '',
				'type'		=> 'text'
			);
		}

		/*
		 * Option Page 4 - Pages
		 */
		$options[] = array(
			'name'		=> __( '4. Pages', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Login page', 'mlm' ),
			'desc'		=> __( 'Create the related page and select it here.', 'mlm' ),
			'id'		=> $shortname.'login_page',
			'std'		=> 'login',
			'type'		=> 'select',
			'options'	=> $tt_pages
		);
		$options[] = array(
			'name'		=> __( 'Register page', 'mlm' ),
			'desc'		=> __( 'Create the related page and select it here.', 'mlm' ),
			'id'		=> $shortname.'register_page',
			'std'		=> 'register',
			'type'		=> 'select',
			'options'	=> $tt_pages
		);
		$options[] = array(
			'name'		=> __( 'Forgot password page', 'mlm' ),
			'desc'		=> __( 'Create the related page and select it here.', 'mlm' ),
			'id'		=> $shortname.'lost_page',
			'std'		=> 'password-lost',
			'type'		=> 'select',
			'options'	=> $tt_pages
		);
		/*$options[] = array(
			'name'		=> __( 'Reset password page', 'mlm' ),
			'desc'		=> __( 'Create the related page and select it here.', 'mlm' ),
			'id'		=> $shortname.'reset_page',
			'std'		=> 'password-reset',
			'type'		=> 'select',
			'options'	=> $tt_pages
		);*/
		$options[] = array(
			'name'		=> __( 'User panel page', 'mlm' ),
			'desc'		=> __( 'Create the related page and select it here.', 'mlm' ),
			'id'		=> $shortname.'panel_page',
			'std'		=> 'dashboard',
			'type'		=> 'select',
			'options'	=> $tt_pages
		);

		/*
		 * Option Page 5 - SMS Panel
		 */
		$options[] = array(
			'name'		=> __( '5. Notifications', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Site title', 'mlm' ),
			'desc'		=> __( 'Site title on sent emails', 'mlm' ),
			'id'		=> $shortname.'sender_name',
			'std'		=> get_bloginfo('name'),
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Site email', 'mlm' ),
			'desc'		=> __( 'Site email on sent emails', 'mlm' ),
			'id'		=> $shortname.'sender_email',
			'std'		=> 'no-reply@'. mlmFire()->notif->get_site_domain(),
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Admin mobile', 'mlm' ),
			'desc'		=> __( 'Site admin phone number to receive notifications.', 'mlm' ),
			'id'		=> $shortname.'admin_mobile',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'SMS provider', 'mlm' ),
			'desc'		=> __( 'Select the SMS service provider', 'mlm' ),
			'id'		=> $shortname.'sms_panel',
			'std'		=> 'niazpardaz',
			'type'		=> 'select',
			'options'	=> array(
				'niazpardaz'	=> array( 'niazpardaz', __( 'Niazpardaz', 'mlm' ) ),
				'ipanel'		=> array( 'ipanel', __( 'Ippanel', 'mlm' ) ),
				'mellipayamak'	=> array( 'mellipayamak', __( 'Mellipayamak', 'mlm' ) ),
				'kavenegar'		=> array( 'kavenegar', __( 'Kavenegar', 'mlm' ) ),
				'farazsms'		=> array( 'farazsms', __( 'Farazsms', 'mlm' ) ),
				'parsgreen'		=> array( 'parsgreen', __( 'Parsgreen', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'SMS provider username', 'mlm' ),
			'desc'		=> __( 'Enter "API key" for Kavenegar<br />Enter "Signature" for Parsgreen', 'mlm' ),
			'id'		=> $shortname.'sms_user',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'SMS provider password', 'mlm' ),
			'desc'		=> __( 'No need for password for Kavenegar', 'mlm' ),
			'id'		=> $shortname.'sms_pass',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'SMS provider number', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'sms_line',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Use Ippanel patterns', 'mlm' ),
			'desc'		=> __( 'Choose if you want to user Ippanel patterns or not', 'mlm' ),
			'id'		=> $shortname.'sms_pattern',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Products support text', 'mlm' ),
			'desc'		=> __( 'Will be displayed on products single page support tab.', 'mlm' ),
			'id'		=> $shortname.'support_text',
			'std'		=> '',
			'type'		=> 'textarea'
		);

		/*
		 * Option Page 6 - AFFILIATE
		 */
		$options[] = array(
			'name'		=> __( '6. Multi vendor', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Minimum withdrawal amount', 'mlm' ),
			'desc'		=> __( 'Minimum withdrawal amount as a numeric value.', 'mlm' ),
			'id'		=> $shortname.'min_cash',
			'std'		=> '10000',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Site percent', 'mlm' ),
			'desc'		=> __( 'Enter a numeric value between 0 and 100', 'mlm' ),
			'id'		=> $shortname.'rate',
			'std'		=> '20',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Customer gift amount', 'mlm' ),
			'desc'		=> __( 'Enter a numeric value between 0 and 100. For example 10 equals to 10%% of cart total amount.', 'mlm' ),
			'id'		=> $shortname.'customer_rate',
			'std'		=> 15,
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 7 - CODES
		 */
		$options[] = array(
			'name'		=> __( '7. Track scripts', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Header scripts', 'mlm' ),
			'desc'		=> __( 'Will be appended to site head tag.', 'mlm' ),
			'id'		=> $shortname.'header_meta',
			'std'		=> '',
			'type'		=> 'textarea'
		);
		$options[] = array(
			'name'		=> __( 'Footer scripts', 'mlm' ),
			'desc'		=> __( 'Will be append to site footer before body tag end.', 'mlm' ),
			'id'		=> $shortname.'footer_meta',
			'std'		=> '',
			'type'		=> 'textarea'
		);

		/*
		 * OPTION PAGE 8 - SOCIAL
		 */
		$options[] = array(
			'name'		=> __( '8. Social media', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Telegram', 'mlm' ),
			'desc'		=> 'https://t.me/',
			'id'		=> $shortname.'sc_telegram',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Instagram', 'mlm' ),
			'desc'		=> 'http://instagram.com/',
			'id'		=> $shortname.'sc_instagram',
			'std'		=> '',
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 9 - VENDOR
		 */
		$options[] = array(
			'name'		=> __( '9. Vendor', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Disable upgrade as vendor', 'mlm' ),
			'desc'		=> __( 'Disable upgrade as vendor button', 'mlm' ),
			'id'		=> $shortname.'sel_up_disabled',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Custom fields', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'custom_fields',
			'std'		=> 'mlm',
			'type'		=> 'select',
			'options'	=> array(
				'mlm'		=> array( 'mlm', __( 'Default', 'mlm' ) ),
				'custom'	=> array( 'custom', __( 'Custom', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Download host IP', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'ftp_url',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'FTP username', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'ftp_user',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'FTP password', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'ftp_pass',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Download host subdomain URL', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'ftp_link',
			'std'		=> '',
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 10 - STYLE
		 */
		$options[] = array(
			'name'		=> __( '10. Appearance', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Select demo', 'mlm' ),
			'desc'		=> __( 'Select your preferred demo', 'mlm' ),
			'id'		=> $shortname.'demo',
			'std'		=> 'mlm',
			'type'		=> 'select',
			'options'	=> array(
				'mlm'		=> array( 'mlm', __( 'Default', 'mlm' ) ),
				'zhaket'	=> array( 'zhaket', __( 'Zhaket', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Select font', 'mlm' ),
			'desc'		=> __( 'Select your preferred font', 'mlm' ),
			'id'		=> $shortname.'font',
			'std'		=> 'mlm',
			'type'		=> 'select',
			'options'	=> array(
				'iranyekan'	=> array( 'iranyekan', __( 'Iran Yekan', 'mlm' ) ),
				'avini'		=> array( 'avini', __( 'Avini', 'mlm' ) ),
				'opensans'	=> array( 'opensans', __( 'Open Sans', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Site logo', 'mlm' ),
			'desc'		=> __( 'Upload site logo image.', 'mlm' ),
			'id'		=> $shortname.'logo',
			'std'		=> IMAGES . '/logo.png',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Footer logo', 'mlm' ),
			'desc'		=> __( 'Upload site footer logo for Zhaket demo.', 'mlm' ),
			'id'		=> $shortname.'logo_footer',
			'std'		=> '',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Mobile logo', 'mlm' ),
			'desc'		=> __( 'Upload site mobile logo for Zhaket demo.', 'mlm' ),
			'id'		=> $shortname.'mobile_logo',
			'std'		=> '',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Desktop sticky menu', 'mlm' ),
			'desc'		=> __( 'Display desktop menu as a sticky menu', 'mlm' ),
			'id'		=> $shortname.'fixed_menu_lg',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Mobile sticky menu', 'mlm' ),
			'desc'		=> __( 'Display mobile menu as a sticky menu', 'mlm' ),
			'id'		=> $shortname.'fixed_menu',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Desktop sticky add to cart', 'mlm' ),
			'desc'		=> __( 'Display add to cart button as a sticky button on desktop view.', 'mlm' ),
			'id'		=> $shortname.'fixed_btn_lg',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Mobile sticky add to cart', 'mlm' ),
			'desc'		=> __( 'Display add to cart button as a sticky button on mobile view.', 'mlm' ),
			'id'		=> $shortname.'fixed_btn',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Background color', 'mlm' ),
			'desc'		=> __( 'Site background color', 'mlm' ),
			'id'		=> $shortname.'bg_color',
			'std'		=> '#fff',
			'type'		=> 'color'
		);
		$options[] = array(
			'name'		=> __( 'Header background color', 'mlm' ),
			'desc'		=> __( 'Header background color', 'mlm' ),
			'id'		=> $shortname.'bg_header',
			'std'		=> '#fff',
			'type'		=> 'color'
		);
		$options[] = array(
			'name'		=> __( 'Footer background color', 'mlm' ),
			'desc'		=> __( 'Footer and header secondary menu background color', 'mlm' ),
			'id'		=> $shortname.'bg_footer',
			'std'		=> '#343a40',
			'type'		=> 'color'
		);
		$options[] = array(
			'name'		=> __( 'Buttons color', 'mlm' ),
			'desc'		=> __( 'Buttons and elements main color', 'mlm' ),
			'id'		=> $shortname.'main_color',
			'std'		=> '#007bff',
			'type'		=> 'color'
		);

		/*
		 * OPTION PAGE 11 - REFERRAL
		 */
		$options[] = array(
			'name'		=> __( '11. Referral', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Disable upgrade as referrer', 'mlm' ),
			'desc'		=> __( 'Disable upgrade as referrer button', 'mlm' ),
			'id'		=> $shortname.'ref_up_disabled',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Reagent percent step 1', 'mlm' ),
			'desc'		=> __( 'Reagent percent from site share. for example 15 equals to 15%% of site share.', 'mlm' ),
			'id'		=> $shortname.'sub_1',
			'std'		=> 15,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Reagent percent step 2', 'mlm' ),
			'desc'		=> __( 'Reagent percent from site share. for example 15 equals to 15%% of site share.', 'mlm' ),
			'id'		=> $shortname.'sub_2',
			'std'		=> 12,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Reagent percent step 3', 'mlm' ),
			'desc'		=> __( 'Reagent percent from site share. for example 15 equals to 15%% of site share.', 'mlm' ),
			'id'		=> $shortname.'sub_3',
			'std'		=> 9,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Reagent percent step 4', 'mlm' ),
			'desc'		=> __( 'Reagent percent from site share. for example 15 equals to 15%% of site share.', 'mlm' ),
			'id'		=> $shortname.'sub_4',
			'std'		=> 6,
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Reagent percent step 5', 'mlm' ),
			'desc'		=> __( 'Reagent percent from site share. for example 15 equals to 15%% of site share.', 'mlm' ),
			'id'		=> $shortname.'sub_5',
			'std'		=> 3,
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 12 - PANEL
		 */
		$options[] = array(
			'name'		=> __( '12. User panel', 'mlm' ),
			'type'		=> 'heading'
		);
		$after_items		= array();
		$dashboard_items	= mlmFire()->dashboard->get_menu_items();
		$nav_items			= get_terms( array(
			'taxonomy'		=> 'nav_menu',
			'hide_empty'	=> false,
		) );

		if( is_array( $dashboard_items ) && count( $dashboard_items ) )
		{
			foreach( $dashboard_items as $k => $v )
			{
				$options[] = array(
					'name'		=> $v['title'],
					'desc'		=> sprintf( __( 'Display "%s" on user panel menu.', 'mlm' ), $v['title'] ),
					'id'		=> $shortname.'hide_' . $k,
					'std'		=> 'false',
					'type'		=> 'select',
					'options'	=> array(
						'false'		=> array( 'false', __( 'display', 'mlm' ), ),
						'true'		=> array( 'true', __( 'hide', 'mlm' ) ),
					)
				);

				if( is_array( $v['sub'] ) && count( $v['sub'] ) > 0 )
				{
					foreach( $v['sub'] as $x => $z )
					{
						$options[] = array(
							'name'		=> $v['title'] . ' - ' . $z['title'],
							'desc'		=> sprintf( __( 'Display "%s" on user panel menu.', 'mlm' ), $z['title'] ),
							'id'		=> $shortname.'hide_sub_' . $x,
							'std'		=> 'false',
							'type'		=> 'select',
							'options'	=> array(
								'false'		=> array( 'false', __( 'display', 'mlm' ), ),
								'true'		=> array( 'true', __( 'hide', 'mlm' ) ),
							)
						);
					}
				}

				$after_items[$k] = array( $k, $v['title'] );
			}
		}

		if( ! empty( $nav_items ) && ! is_wp_error( $nav_items ) )
		{
			$nav_options	= array(
				''	=> array( '', __( 'None', 'mlm' ) )
			);

			foreach( $nav_items as $nav_item )
			{
				$nav_options[$nav_item->term_id] = array( $nav_item->term_id, $nav_item->name );
			}

			$options[] = array(
				'name'		=> __( 'User panel custom links', 'mlm' ),
				'desc'		=> __( 'Create a custom menu to display on user panel.', 'mlm' ),
				'id'		=> $shortname.'extra_links',
				'std'		=> '',
				'type'		=> 'select',
				'options'	=> $nav_options
			);

			if( is_array( $after_items ) && count( $after_items ) )
			{
				$options[] = array(
					'name'		=> __( 'Custom links position', 'mlm' ),
					'desc'		=> __( 'Display custom links after the selected item.', 'mlm' ),
					'id'		=> $shortname.'extra_links_after',
					'std'		=> 'profile',
					'type'		=> 'select',
					'options'	=> $after_items
				);
			}
		}

		/*
		 * OPTION PAGE 13 - LOGIN
		 */
		$options[] = array(
			'name'		=> __( '13. Login & Register', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Login box image', 'mlm' ),
			'desc'		=> __( 'Login / register popup box image', 'mlm' ),
			'id'		=> $shortname.'popup_img',
			'std'		=> IMAGES . '/login-popup.png',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Remove email', 'mlm' ),
			'desc'		=> __( 'Remove email field on user register form.', 'mlm' ),
			'id'		=> $shortname.'email_disabled',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
				'opt'		=> array( 'opt', __( 'Optional', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Reagent code', 'mlm' ),
			'desc'		=> __( 'Display reagent code on register form.', 'mlm' ),
			'id'		=> $shortname.'code_enabled',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Forced reagent code', 'mlm' ),
			'desc'		=> __( 'Forced reagent code on user register.', 'mlm' ),
			'id'		=> $shortname.'code_required',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Force mobile verification', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'verify_mobile',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Force email verification', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'verify_email',
			'std'		=> 'no',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Verification type', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'verify_type',
			'std'		=> 'all',
			'type'		=> 'select',
			'options'	=> array(
				'all'		=> array( 'all', __( 'After login', 'mlm' ) ),
				'custom'	=> array( 'custom', __( 'Custom link', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Verification custom link', 'mlm' ),
			'desc'		=> __( 'Enter the page link you want to verify user before view', 'mlm' ),
			'id'		=> $shortname.'verify_link',
			'std'		=> '',
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 14 - NOTIFICATION BAR
		 */
		$options[] = array(
			'name'		=> __( '14. Notification bar', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Notification text', 'mlm' ),
			'desc'		=> __( 'Enter notification brief.', 'mlm' ),
			'id'		=> $shortname.'notbar_text',
			'std'		=> '',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Button text', 'mlm' ),
			'desc'		=> __( 'Notification bar button text', 'mlm' ),
			'id'		=> $shortname.'notbar_btn',
			'std'		=> __( 'Details', 'mlm' ),
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Button URL', 'mlm' ),
			'desc'		=> __( 'Link to notification details page', 'mlm' ),
			'id'		=> $shortname.'notbar_url',
			'std'		=> '',
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 15 - MEDALS
		 */
		$options[] = array(
			'name'		=> __( '15. Medals', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Subset income limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_subset_income',
			'std'		=> '20000',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Sale income limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_sale_income',
			'std'		=> '500000',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Referral income limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_ref_income',
			'std'		=> '50000',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid referrals limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_ref',
			'std'		=> '200',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid posts limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_post',
			'std'		=> '5',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid products limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_product',
			'std'		=> '5',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid subsets limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_subset',
			'std'		=> '5',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid purchases limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_purchase',
			'std'		=> '5',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid withdrawal limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_withdraw',
			'std'		=> '5',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid comments limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_valid_comment',
			'std'		=> '10',
			'type'		=> 'text'
		);
		$options[] = array(
			'name'		=> __( 'Valid featued products limit', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'medal_vip_product',
			'std'		=> '1',
			'type'		=> 'text'
		);

		/*
		 * OPTION PAGE 16 - APPLICATION
		 */
		$options[] = array(
			'name'		=> __( '16. Application', 'mlm' ),
			'type'		=> 'heading'
		);
		$options[] = array(
			'name'		=> __( 'Application color', 'mlm' ),
			'desc'		=> __( 'Application main color', 'mlm' ),
			'id'		=> $shortname.'app_color',
			'std'		=> '',
			'type'		=> 'color'
		);
		$options[] = array(
			'name'		=> __( 'Home banner', 'mlm' ),
			'desc'		=> __( 'Application home page banner', 'mlm' ),
			'id'		=> $shortname.'app_banner',
			'std'		=> '',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Home shop category', 'mlm' ),
			'desc'		=> __( 'Select the related category', 'mlm' ),
			'id'		=> $shortname.'app_product_cat',
			'std'		=> '',
			'type'		=> 'select',
			'options'	=> $tt_categories
		);
		$options[] = array(
			'name'		=> __( 'Home blog category', 'mlm' ),
			'desc'		=> __( 'Select the related category', 'mlm' ),
			'id'		=> $shortname.'app_blog_cat',
			'std'		=> '',
			'type'		=> 'select',
			'options'	=> $bg_categories
		);
		$options[] = array(
			'name'		=> __( 'Top banner', 'mlm' ),
			'desc'		=> __( 'Application pages top banner', 'mlm' ),
			'id'		=> $shortname.'app_top_banner',
			'std'		=> '',
			'type'		=> 'upload'
		);
		$options[] = array(
			'name'		=> __( 'Display statistics box', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'app_stats_box',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Display upgrade to referrer link?', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'app_upgrade_ref',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Display upgrade to seller link?', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'app_upgrade_sell',
			'std'		=> 'yes',
			'type'		=> 'select',
			'options'	=> array(
				'yes'		=> array( 'yes', __( 'Yes', 'mlm' ) ),
				'no'		=> array( 'no', __( 'No', 'mlm' ) ),
			)
		);
		$options[] = array(
			'name'		=> __( 'Is app RTL or LTR?', 'mlm' ),
			'desc'		=> '',
			'id'		=> $shortname.'app_rtl',
			'std'		=> 'rtl',
			'type'		=> 'select',
			'options'	=> array(
				'rtl'		=> array( 'rtl', __( 'RTL', 'mlm' ) ),
				'ltr'		=> array( 'ltr', __( 'LTR', 'mlm' ) ),
			)
		);

		/*
		 * UPDATE OPTIONS.
		 */
		update_option( 'of_template', $options );
		update_option( 'of_shortname', $shortname );
	}
}