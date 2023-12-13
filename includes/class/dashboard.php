<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Dashboard
{
    public function __construct()
    {
        add_filter("init", [$this, "panel_rewrite_rule"]);
        add_action("template_include", [$this, "dashboard_template"]);
        add_action("wp_enqueue_scripts", [$this, "panel_scripts_enqueue"], 998);
        add_filter("get_avatar_url", [$this, "custom_avatar"], 10, 3);
        add_action("delete_attachment", [$this, "disable_delete_media"], 11, 1);
        add_filter("add_to_cart_text", [$this, "custom_add_to_cart_text"]);
        add_filter("woocommerce_product_single_add_to_cart_text", [$this, "custom_add_to_cart_text"]);
        add_filter("wsl_render_auth_widget_alter_provider_icon_markup", [$this, "social_login_icons"], 10, 3);
        add_action("wp_authenticate_user", [$this, "validate_login_captcha"], 10, 2);
        add_action("comment_form", [$this, "comments_form_captcha_field"]);
        add_action("pre_comment_on_post", [$this, "validate_comments_captcha"]);
    }
    public function panel_rewrite_rule()
    {
        global $wp_rewrite;
        $wp_rewrite->author_base = "user";
        $wp_rewrite->author_structure = "/" . $wp_rewrite->author_base . "/%author%";
        add_rewrite_endpoint("section", EP_PAGES);
    }
    public function dashboard_template($template)
    {
        $new_template = "";
        $demo = mlm_selected_demo();
        $panel_page_id = get_option("mlm_panel_page");
        $login_page_id = get_option("mlm_login_page");
        $register_page_id = get_option("mlm_register_page");
        $lost_page_id = get_option("mlm_lost_page");
        $reset_page_id = get_option("mlm_reset_page");
        if (mlm_post_exists($panel_page_id) && is_page($panel_page_id) || function_exists("is_account_page") && is_account_page()) {
            $new_template = locate_template(["layouts/panel.php"]);
            if ("" != $new_template) {
                return $new_template;
            }
        }
        if ($demo == "zhaket" && !empty($login_page_id) && is_page($login_page_id) || $demo == "zhaket" && !empty($register_page_id) && is_page($register_page_id) || $demo == "zhaket" && !empty($lost_page_id) && is_page($lost_page_id) || $demo == "zhaket" && !empty($reset_page_id) && is_page($reset_page_id)) {
            $new_template = locate_template(["layouts/login-register.php"]);
            if ("" != $new_template) {
                return $new_template;
            }
        }
        return $template;
    }
    public function panel_scripts_enqueue()
    {
        $id = get_option("mlm_panel_page");
        $site_key = get_option("mlm_recaptcha_site_key");
        if (is_user_logged_in() && !empty($id) && is_page($id)) {
            wp_enqueue_media();
            wp_enqueue_script("jquery-ui-core");
            wp_enqueue_script("datepicker", FRAMEWORKS . "/datepicker/date.js", ["jquery-ui-core"]);
            wp_enqueue_script("calendar-fa", FRAMEWORKS . "/datepicker/calendar.js", ["jquery-ui-core"]);
            wp_enqueue_script("datepicker-fa", FRAMEWORKS . "/datepicker/fa.js", ["jquery-ui-core"]);
            wp_enqueue_script("mlm-chart", FRAMEWORKS . "/chartjs/Chart.min.js", ["jquery"], false, true);
        }
        if (!empty($site_key) && !is_user_logged_in()) {
            wp_enqueue_script("pace", "https://www.google.com/recaptcha/api.js?render=" . $site_key, [], false, false);
        }
    }
    public function disable_delete_media($post_id)
    {
        if (!current_user_can("moderate_comments")) {
            exit("You cannot delete media.");
        }
    }
    public function custom_add_to_cart_text()
    {
        global $product;
        if (mlm_check_course($product->get_id())) {
            return __("Participate in course", "mlm");
        }
        return __("Add to cart", "mlm");
    }
    public function custom_avatar($url, $id_or_email, $args)
    {
        $user_id = 0;
        $email = "";
        $default = esc_url(IMAGES . "/avatar.svg");
        if (is_object($id_or_email)) {
            if (isset($id_or_email->comment_author_email)) {
                $email = $id_or_email->comment_author_email;
            } else {
                return $default;
            }
        }
        if (is_email($email) && !email_exists($email)) {
            return $default;
        }
        if (is_string($id_or_email) && is_email($id_or_email)) {
            $email = $id_or_email;
        }
        if (is_email($email)) {
            $user_obj = get_user_by("email", $email);
            $user_id = isset($user_obj->ID) ? $user_obj->ID : 0;
        } else {
            if (is_numeric($id_or_email)) {
                $user_id = $id_or_email;
            }
        }
        if (!mlm_user_exists($user_id)) {
            return $default;
        }
        $mlm_avatar = get_user_meta($user_id, "mlm_avatar", true);
        if (empty($mlm_avatar)) {
            return $default;
        }
        return esc_url($mlm_avatar);
    }
    public function social_login_icons($provider_id, $provider_name, $authenticate_url)
    {
        if ($provider_name == "Google") {
            $provider_name = __("Google", "mlm");
        }
        echo "\t\t\t<a\r\n\t\t\t   rel           = \"nofollow\"\r\n\t\t\t   href          = \"";
        echo $authenticate_url;
        echo "\"\r\n\t\t\t   data-provider = \"";
        echo $provider_id;
        echo "\"\r\n\t\t\t   class         = \"wp-social-login-provider wp-social-login-provider-";
        echo strtolower($provider_id);
        echo "\"\r\n\t\t\t >\r\n\t\t\t\t<span>\r\n\t\t\t\t\t<svg aria-hidden=\"true\" class=\"svg-icon native iconGoogle\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\"><path d=\"M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z\" fill=\"#4285F4\"></path><path d=\"M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 0 1-7.18-2.54H1.83v2.07A8 8 0 0 0 8.98 17z\" fill=\"#34A853\"></path><path d=\"M4.5 10.52a4.8 4.8 0 0 1 0-3.04V5.41H1.83a8 8 0 0 0 0 7.18l2.67-2.07z\" fill=\"#FBBC05\"></path><path d=\"M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 1.83 5.4L4.5 7.49a4.77 4.77 0 0 1 4.48-3.3z\" fill=\"#EA4335\"></path></svg>\r\n\t\t\t\t\t";
        _e("Sign in with Google", "mlm");
        echo "\t\t\t\t</span>\r\n\t\t\t</a>\r\n\t\t";
    }
    public function validate_login_captcha($user, $password)
    {
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        if (!$this->verify_recaptcha($captcha)) {
            return new WP_Error("invalid_captcha", __("You couldn't pass the captcha test. please reload the page and try again.", "mlm"));
        }
        return $user;
    }
    public function comments_form_captcha_field($post_id)
    {
        if (!is_user_logged_in()) {
            echo "<input type=\"hidden\" name=\"mlm_recaptcha\" data-reason=\"comment\" value=\"\">";
        }
    }
    public function validate_comments_captcha()
    {
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        if (!is_user_logged_in() && !$this->verify_recaptcha($captcha)) {
            wp_die(__("You couldn't pass the captcha test. please reload the page and try again.", "mlm"));
        }
    }
    public function verify_recaptcha($token)
    {
        $secret_key = get_option("mlm_recaptcha_secret_key");
        if (empty($secret_key)) {
            return true;
        }
        if (empty($token)) {
            return false;
        }
        $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", ["body" => ["secret" => $secret_key, "response" => $token]]);
        $success = false;
        if ($response && is_array($response)) {
            $decoded_response = json_decode($response["body"]);
            $success = $decoded_response->success;
        }
        return $success;
    }
    public function get_vars()
    {
        $all = get_query_var("section");
        $section = "free";
        $mid = "";
        $verify = "";
        $page = 1;
        if (!empty($all)) {
            $parts = explode("/", $all);
            $section = $parts[0];
            foreach ($parts as $k => $v) {
                if ($v == "mid") {
                    $mid = isset($parts[$k + 1]) ? $parts[$k + 1] : "";
                } else {
                    if ($v == "verify") {
                        $verify = isset($parts[$k + 1]) ? $parts[$k + 1] : "";
                    } else {
                        if ($v == "page") {
                            $page = isset($parts[$k + 1]) ? $parts[$k + 1] : "";
                        }
                    }
                }
            }
        }
        if (function_exists("is_account_page") && is_account_page()) {
            $section = "orders";
        }
        return ["section" => $section, "mid" => $mid, "verify" => $verify, "page" => $page];
    }
    public function get_menu_items()
    {
        $user_id = get_current_user_id();
        $panel_url = trailingslashit(mlm_page_url("panel"));
        if (function_exists("wc_get_account_endpoint_url")) {
            $orders_url = wc_get_account_endpoint_url(get_option("woocommerce_myaccount_orders_endpoint", "orders"));
            $downloads_url = wc_get_account_endpoint_url(get_option("woocommerce_myaccount_downloads_endpoint", "downloads"));
            $address_url = wc_get_account_endpoint_url(get_option("woocommerce_myaccount_edit_address_endpoint", "edit-address"));
            $bookmarks_url = trailingslashit(mlm_page_url("panel")) . "section/bookmarks/";
        } else {
            $orders_url = "";
            $downloads_url = "";
            $address_url = "";
            $bookmarks_url = "";
        }
        $items = ["free" => ["title" => __("Dashboard", "mlm"), "icon" => "icon-speedometer", "link" => $panel_url, "sub" => "", "visible" => false, "type" => "all"], "orders" => ["title" => __("Orders & Purchases", "mlm"), "icon" => "icon-map1", "link" => "#", "sub" => ["my-orders" => ["title" => __("My orders", "mlm"), "icon" => "icon-arrow-left2", "link" => $orders_url], "my-downloads" => ["title" => __("My downloads", "mlm"), "icon" => "icon-arrow-left2", "link" => $downloads_url], "my-address" => ["title" => __("My address", "mlm"), "icon" => "icon-arrow-left2", "link" => $address_url], "bookmarks" => ["title" => __("Bookmarks", "mlm"), "icon" => "icon-arrow-left2", "link" => $bookmarks_url]], "visible" => false, "type" => "customer"], "courses" => ["title" => __("Courses", "mlm"), "icon" => "icon-video", "link" => $panel_url . "section/courses/", "sub" => "", "visible" => false, "type" => "customer"], "subscribe" => ["title" => __("VIP plan", "mlm"), "icon" => "icon-key1", "link" => "#", "sub" => ["subscribes" => ["title" => __("VIP plan", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/subscribes/"], "all-subscribes" => ["title" => __("Plans history", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/all-subscribes/"]], "visible" => false, "type" => "customer"], "products" => ["title" => __("Products", "mlm"), "icon" => "icon-basket", "link" => "#", "bubble" => $this->get_pending_posts($user_id, "product"), "sub" => ["products-all" => ["title" => __("All products", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/products-all/"], "products-new" => ["title" => __("Add new file", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/products-new/"], "course-new" => ["title" => __("Add new course", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/course-new/"], "physical-new" => ["title" => __("Add new product", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/physical-new/"], "coupons" => ["title" => __("Coupons", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/coupons/"], "coupons-new" => ["title" => __("Add new coupon", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/coupons-new/"], "bookmarks" => ["title" => __("Bookmarks", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/bookmarks/"]], "visible" => false, "type" => "vendor"], "posts" => ["title" => __("Posts & Articles", "mlm"), "icon" => "icon-lightbulb", "link" => "#", "bubble" => $this->get_pending_posts($user_id, "post"), "sub" => ["posts-all" => ["title" => __("All posts", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/posts-all/"], "posts-new" => ["title" => __("Add new post", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/posts-new/"]], "visible" => false, "type" => "vendor"], "comments" => ["title" => __("Comments", "mlm"), "icon" => "icon-chat", "link" => $panel_url . "section/comments-all/", "sub" => "", "visible" => false, "type" => "vendor"], "financial" => ["title" => __("Financial", "mlm"), "icon" => "icon-linegraph", "link" => "#", "sub" => ["purchases" => ["title" => __("Purchases", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/purchases/"], "sales" => ["title" => __("Sale transactions", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/sales/"], "course-sales" => ["title" => __("Course transactions", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/course-sales/"], "referrals" => ["title" => __("Referral transactions", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/referrals/"], "withdrawals" => ["title" => __("Withdrawals", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/withdrawals/"], "wallet" => ["title" => __("Wallet transactions", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/wallet/"]], "visible" => false, "type" => "all"], "referral" => ["title" => __("Referral", "mlm"), "icon" => "icon-presentation", "link" => "#", "sub" => ["links" => ["title" => __("Referral links", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/links/"], "refers" => ["title" => __("Referrals", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/refers/"], "subsets" => ["title" => __("Subsets", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/subsets/"]], "visible" => false, "type" => "vendor"], "tickets" => ["title" => __("Tickets", "mlm"), "icon" => "icon-tools-2", "link" => "#", "bubble" => mlmFire()->ticket->count_open_tickets($user_id), "sub" => ["tickets-all" => ["title" => __("All tickets", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/tickets-all/"], "tickets-new" => ["title" => __("Add new ticket", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/tickets-new/"]], "visible" => false, "type" => "customer"], "announce" => ["title" => __("Announces", "mlm"), "icon" => "icon-megaphone", "link" => $panel_url . "section/announce/", "bubble" => mlmFire()->announce->check_user_announce($user_id), "sub" => "", "visible" => false, "type" => "customer"], "profile" => ["title" => __("Profile", "mlm"), "icon" => "icon-profile-male", "link" => "#", "sub" => ["profile" => ["title" => __("Profile info", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/profile/"], "medals" => ["title" => __("Blue badge & medals", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/medals/"], "change-password" => ["title" => __("Change password", "mlm"), "icon" => "icon-arrow-left2", "link" => $panel_url . "section/change-password/"], "shop" => ["title" => __("View profile", "mlm"), "icon" => "icon-arrow-left2", "link" => esc_url(get_author_posts_url($user_id))]], "visible" => false, "type" => "customer"], "logout" => ["title" => __("Sign out", "mlm"), "icon" => "icon-lock1", "link" => mlm_wc_logut_url(), "sub" => "", "visible" => false, "type" => "customer"]];
        if (!current_user_can("read_private_pages")) {
            $items["orders"]["sub"][] = $items["products"]["sub"]["bookmarks"];
            unset($items["products"]);
        }
        if (!current_user_can("unfiltered_html")) {
            unset($items["referral"]);
            unset($items["comments"]);
            unset($items["financial"]["sub"]["sales"]);
            unset($items["financial"]["sub"]["referrals"]);
            unset($items["financial"]["sub"]["withdrawals"]);
            unset($items["financial"]["sub"]["course-sales"]);
        }
        return $items;
    }
    public function print_avatar_box()
    {
        $user_id = get_current_user_id();
        $user_name = mlm_get_user_name($user_id);
        $verified = mlmFire()->dashboard->get_account_status($user_id);
        $percent = mlmFire()->dashboard->get_account_percent($user_id);
        $balance = mlmFire()->wallet->get_balance($user_id);
        $ref_upgrade = get_option("mlm_ref_up_disabled");
        $sel_upgrade = get_option("mlm_sel_up_disabled");
        if ($verified) {
            $text = __("Blue badge received", "mlm");
        } else {
            $text = sprintf(__("%d%% till blue badge", "mlm"), $percent);
        }
        echo "\r\n\t\t<div class=\"panel-top mlm-product-vendor-widget p-3 m-0 clearfix\">\r\n\t\t\t<div class=\"vendor-image mb-2 clearfix\">\r\n\t\t\t\t";
        echo get_avatar($user_id, 128, NULL, $user_name, ["class" => "rounded-circle d-block bg-white mx-auto"]);
        echo "\t\t\t</div>\r\n\t\t\t<div class=\"vendor-name text-center mb-2 clearfix\">\r\n\t\t\t\t<span class=\"d-inline-block text-secondary bold-300 ";
        if ($verified) {
            echo "verified";
        }
        echo "\">";
        echo $user_name;
        echo "</span>\r\n\t\t\t</div>\r\n\t\t\t";
        if ($this->is_menu_item_visible("subscribe")) {
            echo "\t\t\t\t<div class=\"vendor-plan text-center mb-1 clearfix\">\r\n\t\t\t\t\t<a href=\"";
            echo trailingslashit(mlm_page_url("panel")) . "section/subscribes/";
            echo "\" class=\"btn btn-primary py-0 font-12\">\r\n\t\t\t\t\t\t";
            echo mlmFire()->plan->get_user_role($user_id);
            echo "\t\t\t\t\t</a>\r\n\t\t\t\t</div>\r\n\t\t\t";
        }
        echo "\t\t\t<div class=\"vendor-balance text-center clearfix\">\r\n\t\t\t\t<a href=\"";
        echo trailingslashit(mlm_page_url("panel")) . "section/withdrawals/";
        echo "\" class=\"btn btn-light py-0 font-12\">\r\n\t\t\t\t\t";
        _e("Your balance", "mlm");
        echo ": ";
        echo mlm_filter($balance);
        echo "\t\t\t\t</a>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t\t<div class=\"progress bg-light m-0 border-0 rounded-0\">\r\n\t\t\t<div class=\"progress-bar\" role=\"progressbar\" style=\"width: ";
        echo $percent;
        echo "%\" aria-valuenow=\"";
        echo $percent;
        echo "\" aria-valuemin=\"0\" aria-valuemax=\"100\">";
        echo $text;
        echo "</div>\r\n\t\t</div>\r\n\t\t<div class=\"mlm-call-to-action px-2 clearfix\">\r\n\t\t\t";
        if (!current_user_can("read_private_pages") && $sel_upgrade != "yes") {
            echo "\t\t\t\t<a href=\"";
            echo trailingslashit(mlm_page_url("panel")) . "section/upgrade/mid/1";
            echo "\" class=\"btn btn-light btn-block rounded-pill my-2\">";
            _e("Upgrade to vendor", "mlm");
            echo "</a>\r\n\t\t\t";
        }
        echo "\t\t\t";
        if (!current_user_can("unfiltered_html") && $ref_upgrade != "yes") {
            echo "\t\t\t\t<a href=\"";
            echo trailingslashit(mlm_page_url("panel")) . "section/upgrade/mid/2";
            echo "\" class=\"btn btn-light btn-block rounded-pill my-2\">";
            _e("Upgrade to referrer", "mlm");
            echo "</a>\r\n\t\t\t";
        }
        echo "\t\t</div>\r\n\r\n\t\t";
    }
    public function print_social_icons()
    {
        $telegram = get_option("mlm_sc_telegram");
        $instagram = get_option("mlm_sc_instagram");
        echo "\r\n\t\t<div class=\"mlm-panel-social-icons row align-items-center no-gutters px-3 my-2\">\r\n\t\t\t";
        if (!empty($telegram)) {
            echo "\t\t\t\t<div class=\"col px-1\">\r\n\t\t\t\t\t<a href=\"";
            echo $telegram;
            echo "\" class=\"d-block ellipsis rounded transition text-white px-2 py-1 icon icon-telegram\" title=\"Telegram\">\r\n\t\t\t\t\t\t";
            _e("Telegram", "mlm");
            echo "\t\t\t\t\t</a>\r\n\t\t\t\t</div>\r\n\t\t\t";
        }
        echo "\t\t\t";
        if (!empty($instagram)) {
            echo "\t\t\t\t<div class=\"col px-1\">\r\n\t\t\t\t\t<a href=\"";
            echo $instagram;
            echo "\" class=\"d-block ellipsis rounded transition text-white px-2 py-1 icon icon-instagram\" title=\"Instagram\">\r\n\t\t\t\t\t\t";
            _e("Instagram", "mlm");
            echo "\t\t\t\t\t</a>\r\n\t\t\t\t</div>\r\n\t\t\t";
        }
        echo "\t\t</div>\r\n\r\n\t\t";
    }
    public function print_side_menu($q_args)
    {
        $items = $this->add_custom_menu_items($this->get_menu_items());
        echo "<ul class=\"panel-nav m-0 p-1\">";
        foreach ($items as $key => $value) {
            if ($this->is_menu_item_visible($key) || $value["visible"]) {
                $li_class = "";
                if (isset($value["sub"]) && is_array($value["sub"])) {
                    $li_class .= " multi";
                    if (array_key_exists($q_args["section"], $value["sub"])) {
                        $li_class .= " acik";
                    }
                }
                if ($key == $q_args["section"] && !$value["visible"]) {
                    $li_class .= " acik";
                }
                echo "<li class=\"d-block mx-0 mt-0 mb-1 p-0" . $li_class . "\">";
                echo "<a href=\"" . $value["link"] . "\" class=\"d-block p-2 rounded transition icon " . $value["icon"] . "\">" . $value["title"];
                if (isset($value["bubble"]) && 0 < $value["bubble"]) {
                    echo "<span class=\"cnt mr-2 py-0 px-2 text-white rounded-pill font-12\">" . $value["bubble"] . "</span>";
                }
                echo "</a>";
                if (isset($value["sub"]) && is_array($value["sub"])) {
                    echo "<ul class=\"children m-0 p-0\">";
                    foreach ($value["sub"] as $k => $v) {
                        if ($this->is_sub_menu_item_visible($k)) {
                            echo "<li class=\"d-block m-0 p-0\">";
                            echo "<a href=\"" . $v["link"] . "\" class=\"d-block p-2 transition icon " . $v["icon"] . "\">" . $v["title"] . "</a>";
                            echo "</li>";
                        }
                    }
                    echo "</ul>";
                }
                echo "</li>";
            }
        }
        echo "</ul>";
    }
    public function print_mobile_menu()
    {
        $items = $this->add_custom_menu_items($this->get_menu_items());
        echo "<div class=\"mlm-mobile-nav clearfix\"><div class=\"mlm-drilldown position-relative m-0 p-0\"><div class=\"drilldown-container\"><ul class=\"drilldown-root sliding visible\">";
        foreach ($items as $key => $value) {
            if ($this->is_menu_item_visible($key) || $value["visible"]) {
                $li_class = "";
                if (isset($value["sub"]) && is_array($value["sub"])) {
                    $li_class .= "menu-item-has-children";
                }
                echo "<li class=\"" . $li_class . "\">";
                echo "<a href=\"" . $value["link"] . "\" class=\"icon " . $value["icon"] . "\">" . $value["title"];
                if (isset($value["bubble"]) && 0 < $value["bubble"]) {
                    echo "<span class=\"cnt mr-2 py-0 px-2 text-white rounded-pill font-12\">" . $value["bubble"] . "</span>";
                }
                echo "</a>";
                if (isset($value["sub"]) && is_array($value["sub"])) {
                    echo "<ul class=\"drilldown-sub\"><li class=\"drilldown-back\">";
                    echo "<a href=\"#\" class=\"bg-light text-dark icon icon-arrow-right2\">" . __("Return", "mlm") . "</a>";
                    echo "</li>";
                    foreach ($value["sub"] as $k => $v) {
                        if ($this->is_sub_menu_item_visible($k)) {
                            echo "<li>";
                            echo "<a href=\"" . $v["link"] . "\" class=\"icon " . $v["icon"] . "\">" . $v["title"] . "</a>";
                            echo "</li>";
                        }
                    }
                    echo "</ul>";
                }
                echo "</li>";
            }
        }
        echo "</ul></div></div></div>";
    }
    public function print_zhaket_menu($q_args)
    {
        $flag = true;
        $items = $this->add_custom_menu_items($this->get_menu_items());
        if (!current_user_can("unfiltered_html")) {
            $flag = false;
        }
        if (current_user_can("read_private_pages")) {
            $tab_title = __("Vendor", "mlm");
        } else {
            if (current_user_can("unfiltered_html")) {
                $tab_title = __("Referrer", "mlm");
            } else {
                $tab_title = __("Become a vendor", "mlm");
            }
        }
        echo "<ul class=\"nav nav-tabs nav-fill mb-4 mx-0 mt-0 p-0 border-0\" role=\"tablist\"><li class=\"nav-item m-0\">";
        echo "<a class=\"nav-link font-16 py-3 active\" id=\"customer-tab\" data-toggle=\"tab\" href=\"#customer\" role=\"tab\" aria-controls=\"customer\" aria-selected=\"true\">" . __("Customer", "mlm") . "</a>";
        echo "</li><li class=\"nav-item m-0\">";
        echo "<a class=\"nav-link font-16 py-3\" id=\"vendor-tab\" data-toggle=\"tab\" href=\"#vendor\" role=\"tab\" aria-controls=\"vendor\" aria-selected=\"false\">" . $tab_title . "</a>";
        echo "</li></ul><div class=\"tab-content\"><div class=\"tab-pane fade show active\" id=\"customer\" role=\"tabpanel\" aria-labelledby=\"customer-tab\"><ul class=\"panel-nav m-0 p-0\">";
        foreach ($items as $key => $value) {
            if ($this->is_menu_item_visible($key) || $value["visible"]) {
                if (!($flag && $value["type"] == "all" || $value["type"] == "vendor")) {
                    $li_class = "";
                    if (isset($value["sub"]) && is_array($value["sub"])) {
                        $li_class .= " multi";
                        if (array_key_exists($q_args["section"], $value["sub"])) {
                            $li_class .= " acik";
                        }
                    }
                    if ($key == $q_args["section"] && !$value["visible"]) {
                        $li_class .= " acik";
                    }
                    echo "<li class=\"d-block mx-0 mt-0 mb-1 p-0" . $li_class . "\">";
                    echo "<a href=\"" . $value["link"] . "\" class=\"d-block p-2 rounded transition icon " . $value["icon"] . "\">" . $value["title"];
                    if (isset($value["bubble"]) && 0 < $value["bubble"]) {
                        echo "<span class=\"cnt mr-2 py-0 px-2 text-white rounded-pill font-12\">" . $value["bubble"] . "</span>";
                    }
                    echo "</a>";
                    if (isset($value["sub"]) && is_array($value["sub"])) {
                        echo "<ul class=\"children m-0 p-0\">";
                        foreach ($value["sub"] as $k => $v) {
                            if ($this->is_sub_menu_item_visible($k)) {
                                echo "<li class=\"d-block m-0 p-0\">";
                                echo "<a href=\"" . $v["link"] . "\" class=\"d-block p-2 transition icon " . $v["icon"] . "\">" . $v["title"] . "</a>";
                                echo "</li>";
                            }
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                }
            }
        }
        echo "</ul></div><div class=\"tab-pane fade\" id=\"vendor\" role=\"tabpanel\" aria-labelledby=\"vendor-tab\">";
        if ($flag) {
            echo "<ul class=\"panel-nav m-0 p-0\">";
            foreach ($items as $key => $value) {
                if ($this->is_menu_item_visible($key) || $value["visible"]) {
                    if ($value["type"] != "customer") {
                        $li_class = "";
                        if (isset($value["sub"]) && is_array($value["sub"])) {
                            $li_class .= " multi";
                            if (array_key_exists($q_args["section"], $value["sub"])) {
                                $li_class .= " acik";
                            }
                        }
                        if ($key == $q_args["section"] && !$value["visible"]) {
                            $li_class .= " acik";
                        }
                        echo "<li class=\"d-block mx-0 mt-0 mb-1 p-0" . $li_class . "\">";
                        echo "<a href=\"" . $value["link"] . "\" class=\"d-block p-2 rounded transition icon " . $value["icon"] . "\">" . $value["title"];
                        if (isset($value["bubble"]) && 0 < $value["bubble"]) {
                            echo "<span class=\"cnt mr-2 py-0 px-2 text-white rounded-pill font-12\">" . $value["bubble"] . "</span>";
                        }
                        echo "</a>";
                        if (isset($value["sub"]) && is_array($value["sub"])) {
                            echo "<ul class=\"children m-0 p-0\">";
                            foreach ($value["sub"] as $k => $v) {
                                if ($this->is_sub_menu_item_visible($k)) {
                                    echo "<li class=\"d-block m-0 p-0\">";
                                    echo "<a href=\"" . $v["link"] . "\" class=\"d-block p-2 transition icon " . $v["icon"] . "\">" . $v["title"] . "</a>";
                                    echo "</li>";
                                }
                            }
                            echo "</ul>";
                        }
                        echo "</li>";
                    }
                }
            }
            echo "</ul>";
        }
        $ref_upgrade = get_option("mlm_ref_up_disabled");
        $sel_upgrade = get_option("mlm_sel_up_disabled");
        echo "<div class=\"p-2\">";
        if (!current_user_can("read_private_pages") && $sel_upgrade != "yes") {
            echo "<a href=\"" . trailingslashit(mlm_page_url("panel")) . "section/upgrade/mid/1\" class=\"btn btn-dark btn-block my-2\">" . __("Upgrade to vendor", "mlm") . "</a>";
        }
        if (!current_user_can("unfiltered_html") && $ref_upgrade != "yes") {
            echo "<a href=\"" . trailingslashit(mlm_page_url("panel")) . "section/upgrade/mid/2\" class=\"btn btn-dark btn-block my-2\">" . __("Upgrade to referrer", "mlm") . "</a>";
        }
        echo "</div></div></div>";
    }
    public function get_active_section($q_args)
    {
        $panel_page_id = get_option("mlm_panel_page");
        if ($q_args["section"] == "profile" && $this->is_sub_menu_item_visible("profile")) {
            echo mlm_get_template("class/user-panel/profile", $q_args);
        } else {
            if ($q_args["section"] == "change-password" && $this->is_sub_menu_item_visible("change-password")) {
                echo mlm_get_template("class/user-panel/password-change", $q_args);
            } else {
                if ($q_args["section"] == "medals" && $this->is_sub_menu_item_visible("medals")) {
                    echo mlm_get_template("class/user-panel/medals", $q_args);
                } else {
                    if ($q_args["section"] == "subscribes" && !empty($q_args["mid"]) && $this->is_sub_menu_item_visible("subscribes")) {
                        echo mlm_get_template("class/user-panel/subscribes-list", $q_args);
                    } else {
                        if ($q_args["section"] == "subscribes" && $this->is_sub_menu_item_visible("subscribes")) {
                            echo mlm_get_template("class/user-panel/subscribes", $q_args);
                        } else {
                            if ($q_args["section"] == "all-subscribes" && $this->is_sub_menu_item_visible("all-subscribes")) {
                                echo mlm_get_template("class/user-panel/subscribes-history", $q_args);
                            } else {
                                if ($q_args["section"] == "products-all" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("products-all")) {
                                    echo mlm_get_template("class/user-panel/products", $q_args);
                                } else {
                                    if ($q_args["section"] == "products-new" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("products-new")) {
                                        echo mlm_get_template("class/user-panel/products-add", $q_args);
                                    } else {
                                        if ($q_args["section"] == "physical-new" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("physical-new")) {
                                            echo mlm_get_template("class/user-panel/physical-add", $q_args);
                                        } else {
                                            if ($q_args["section"] == "course-new" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("course-new")) {
                                                if (isset($q_args["page"]) && absint($q_args["page"]) == 2) {
                                                    $query = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE post_id = %d AND parent_id = %d ORDER BY priority ASC", [$q_args["mid"], 0], "course");
                                                    $q_args["query"] = $query;
                                                    echo mlm_get_template("class/user-panel/course-add-articles", $q_args);
                                                } else {
                                                    echo mlm_get_template("class/user-panel/course-add", $q_args);
                                                }
                                            } else {
                                                if ($q_args["section"] == "coupons" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("coupons")) {
                                                    echo mlm_get_template("class/user-panel/coupons", $q_args);
                                                } else {
                                                    if ($q_args["section"] == "coupons-new" && current_user_can("read_private_pages") && $this->is_sub_menu_item_visible("coupons-new")) {
                                                        echo mlm_get_template("class/user-panel/coupons-add", $q_args);
                                                    } else {
                                                        if ($q_args["section"] == "posts-all" && $this->is_sub_menu_item_visible("posts-all")) {
                                                            echo mlm_get_template("class/user-panel/posts", $q_args);
                                                        } else {
                                                            if ($q_args["section"] == "posts-new" && $this->is_sub_menu_item_visible("posts-new")) {
                                                                echo mlm_get_template("class/user-panel/posts-add", $q_args);
                                                            } else {
                                                                if ($q_args["section"] == "comments-all" && current_user_can("unfiltered_html") && $this->is_menu_item_visible("comments")) {
                                                                    echo mlm_get_template("class/user-panel/comments", $q_args);
                                                                } else {
                                                                    if ($q_args["section"] == "tickets-all" && $this->is_sub_menu_item_visible("tickets-all")) {
                                                                        if (wp_verify_nonce($q_args["verify"], "mlm_ticket_setul")) {
                                                                            echo mlm_get_template("class/user-panel/tickets-open", $q_args);
                                                                        } else {
                                                                            echo mlm_get_template("class/user-panel/tickets", $q_args);
                                                                        }
                                                                    } else {
                                                                        if ($q_args["section"] == "tickets-new" && $this->is_sub_menu_item_visible("tickets-new")) {
                                                                            echo mlm_get_template("class/user-panel/tickets-add", $q_args);
                                                                        } else {
                                                                            if ($q_args["section"] == "bookmarks" && $this->is_sub_menu_item_visible("bookmarks")) {
                                                                                echo mlm_get_template("class/user-panel/bookmarks", $q_args);
                                                                            } else {
                                                                                if ($q_args["section"] == "links" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("links")) {
                                                                                    echo mlm_get_template("class/user-panel/links", $q_args);
                                                                                } else {
                                                                                    if ($q_args["section"] == "refers" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("refers")) {
                                                                                        echo mlm_get_template("class/user-panel/refers", $q_args);
                                                                                    } else {
                                                                                        if ($q_args["section"] == "subsets" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("subsets")) {
                                                                                            echo mlm_get_template("class/user-panel/subsets", $q_args);
                                                                                        } else {
                                                                                            if ($q_args["section"] == "purchases" && $this->is_sub_menu_item_visible("purchases")) {
                                                                                                echo mlm_get_template("class/user-panel/wallet-purchases", $q_args);
                                                                                            } else {
                                                                                                if ($q_args["section"] == "sales" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("sales")) {
                                                                                                    echo mlm_get_template("class/user-panel/wallet-sales", $q_args);
                                                                                                } else {
                                                                                                    if ($q_args["section"] == "course-sales" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("course-sales")) {
                                                                                                        echo mlm_get_template("class/user-panel/wallet-course", $q_args);
                                                                                                    } else {
                                                                                                        if ($q_args["section"] == "referrals" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("referrals")) {
                                                                                                            echo mlm_get_template("class/user-panel/wallet-refs", $q_args);
                                                                                                        } else {
                                                                                                            if ($q_args["section"] == "wallet" && $this->is_sub_menu_item_visible("wallet")) {
                                                                                                                echo mlm_get_template("class/user-panel/wallet-charge", $q_args);
                                                                                                            } else {
                                                                                                                if ($q_args["section"] == "withdrawals" && current_user_can("unfiltered_html") && $this->is_sub_menu_item_visible("withdrawals")) {
                                                                                                                    echo mlm_get_template("class/user-panel/withdrawal", $q_args);
                                                                                                                } else {
                                                                                                                    if ($q_args["section"] == "upgrade" && !current_user_can("read_private_pages")) {
                                                                                                                        echo mlm_get_template("class/user-panel/upgrade", $q_args);
                                                                                                                    } else {
                                                                                                                        if ($q_args["section"] == "courses" && $this->is_menu_item_visible("courses")) {
                                                                                                                            echo mlm_get_template("class/user-panel/courses", $q_args);
                                                                                                                        } else {
                                                                                                                            if ($q_args["section"] == "announce" && $this->is_menu_item_visible("announce")) {
                                                                                                                                if (wp_verify_nonce($q_args["verify"], "mlm_skgasgyhdh")) {
                                                                                                                                    echo mlm_get_template("class/user-panel/announce-open", $q_args);
                                                                                                                                } else {
                                                                                                                                    echo mlm_get_template("class/user-panel/announce", $q_args);
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                if (is_page($panel_page_id) && $this->is_menu_item_visible("free")) {
                                                                                                                                    echo mlm_get_template("class/user-panel/dashboard", $q_args);
                                                                                                                                } else {
                                                                                                                                    global $wp_query;
                                                                                                                                    $orders = get_option("woocommerce_myaccount_orders_endpoint", "orders");
                                                                                                                                    $downloads = get_option("woocommerce_myaccount_downloads_endpoint", "downloads");
                                                                                                                                    $address = get_option("woocommerce_myaccount_edit_address_endpoint", "edit-address");
                                                                                                                                    if (isset($wp_query->query_vars[$orders])) {
                                                                                                                                        echo "<h3 class=\"mlm-box-title sm mb-3 py-2\">" . __("My orders", "mlm") . "</h3>";
                                                                                                                                    } else {
                                                                                                                                        if (isset($wp_query->query_vars[$downloads])) {
                                                                                                                                            echo "<h3 class=\"mlm-box-title sm mb-3 py-2\">" . __("My downloads", "mlm") . "</h3>";
                                                                                                                                        } else {
                                                                                                                                            if (isset($wp_query->query_vars[$address])) {
                                                                                                                                                echo "<h3 class=\"mlm-box-title sm mb-3 py-2\">" . __("My addresses", "mlm") . "</h3>";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    echo do_shortcode("[woocommerce_my_account]");
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function top_vendor_of_week()
    {
        $current_time = current_time("mysql");
        $week_array = get_weekstartend($current_time, 6);
        $start_time = date("Y-m-d H:i:s", $week_array["start"]);
        $end_time = date("Y-m-d H:i:s", $week_array["end"]);
        $vendor = mlmFire()->db->query_rows("SELECT user_id, COUNT(user_id) as cnt FROM {TABLE} WHERE type = %d AND status = %d AND (date BETWEEN %s AND %s) GROUP BY user_id ORDER BY cnt DESC LIMIT %d", [1, 2, $start_time, $end_time, 1], "wallet", true);
        return isset($vendor->user_id) ? $vendor->user_id : 0;
    }
    public function get_top_vendors($count = 10)
    {
        return mlmFire()->db->query_rows("SELECT user_id, COUNT(user_id) as cnt FROM {TABLE} WHERE type = %d AND status = %d GROUP BY user_id ORDER BY cnt DESC LIMIT %d", [1, 2, $count], "wallet");
    }
    public function get_top_referrers($count = 10)
    {
        return mlmFire()->db->query_rows("SELECT ref_user_id, COUNT(ref_user_id) as cnt FROM {TABLE} WHERE invalid = %d GROUP BY ref_user_id ORDER BY cnt DESC LIMIT %d", [0, $count], "referral");
    }
    public function get_recent_transactions($user_id, $count = 10)
    {
        return mlmFire()->db->query_rows("SELECT id, amount, type FROM {TABLE} WHERE user_id = %d AND ( status = %d OR status = %d ) ORDER BY id DESC LIMIT %d", [$user_id, 2, 4, $count], "wallet");
    }
    public function get_profile_status($user_id, $force = false)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        if (user_can($user_id, "moderate_comments")) {
            return 100;
        }
        if (!$force) {
            return (int) get_user_meta($user_id, "mlm_profile_fill", true);
        }
        $fill = 0;
        $user_obj = get_userdata($user_id);
        $avatar = get_user_meta($user_id, "mlm_avatar", true);
        $cover = get_user_meta($user_id, "mlm_cover", true);
        $mobile = get_user_meta($user_id, "mlm_mobile", true);
        $state = get_user_meta($user_id, "mlm_state", true);
        $twitter = get_user_meta($user_id, "mlm_twitter", true);
        $aparat = get_user_meta($user_id, "mlm_aparat", true);
        $telegram = get_user_meta($user_id, "mlm_telegram", true);
        $instagram = get_user_meta($user_id, "mlm_instagram", true);
        $youtube = get_user_meta($user_id, "mlm_youtube", true);
        if (!empty($user_obj->first_name)) {
            $fill += 1;
        }
        if (!empty($user_obj->last_name)) {
            $fill += 1;
        }
        if (!empty($user_obj->user_email)) {
            $fill += 1;
        }
        if (!empty($user_obj->description)) {
            $fill += 1;
        }
        if (!empty($avatar)) {
            $fill += 1;
        }
        if (!empty($cover)) {
            $fill += 1;
        }
        if (!empty($mobile)) {
            $fill += 1;
        }
        if (!empty($state)) {
            $fill += 1;
        }
        if (!empty($twitter)) {
            $fill += 1;
        }
        if (!empty($aparat)) {
            $fill += 1;
        }
        if (!empty($telegram)) {
            $fill += 1;
        }
        if (!empty($instagram)) {
            $fill += 1;
        }
        if (!empty($youtube)) {
            $fill += 1;
        }
        $percent = floor($fill / 13 * 100);
        update_user_meta($user_id, "mlm_profile_fill", $percent);
        return $percent;
    }
    public function get_account_percent($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        if (user_can($user_id, "moderate_comments")) {
            return 100;
        }
        $user_medals = mlmFire()->medal->get_user_medals($user_id);
        return floor(count($user_medals) / 14 * 100);
    }
    public function get_account_status($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        if (user_can($user_id, "moderate_comments")) {
            return true;
        }
        $blue_badge = get_user_meta($user_id, "mlm_blue_badge", true);
        if ($blue_badge == "yes") {
            return true;
        }
        $status = get_user_meta($user_id, "mlm_verified", true);
        return empty($status) ? false : true;
    }
    public function get_user_chart_data($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $start_time = strtotime("-30 days");
        $step = 86400;
        $query_rows = mlmFire()->db->query_rows("SELECT amount, type, date FROM {TABLE} WHERE user_id = %d AND ( status = %d || status = %d ) AND date >= %s ORDER BY id ASC", [$user_id, 2, 4, date("Y-m-d H:i:s", $start_time)], "wallet");
        if (empty($query_rows)) {
            return false;
        }
        $chart_data = [];
        foreach ($query_rows as $item) {
            $item_time = strtotime($item->date);
            $current = $start_time;
            for ($i = 1; $i <= 31; $i++) {
                $next = $start_time + $step * $i;
                if (!isset($chart_data[$current]["time"])) {
                    $chart_data[$current]["time"] = $current;
                }
                if (!isset($chart_data[$current]["in"])) {
                    $chart_data[$current]["in"] = 0;
                }
                if (!isset($chart_data[$current]["out"])) {
                    $chart_data[$current]["out"] = 0;
                }
                if ($current <= $item_time && $item_time < $next) {
                    $type = mlmFire()->wallet->get_type_class($item->type);
                    if ($type == "success") {
                        $chart_data[$current]["in"] = $chart_data[$current]["in"] + $item->amount;
                    } else {
                        $chart_data[$current]["out"] = $chart_data[$current]["out"] + $item->amount;
                    }
                }
                $current = $next;
            }
        }
        return $chart_data;
    }
    public function get_pending_posts($user_id, $post_type = "post")
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM " . $wpdb->posts . " WHERE post_type = %s AND post_status = %s AND post_author = %d", $post_type, "pending", $user_id));
    }
    public function get_request_post_id($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->posts . " WHERE post_type = %s AND post_status != %s AND post_author = %d LIMIT %d", "mlm-requests", "trash", $user_id, 1));
    }
    public function is_menu_item_visible($key)
    {
        if (empty($key)) {
            return false;
        }
        $value = get_option("mlm_hide_" . $key);
        if ($value == "true") {
            return false;
        }
        return true;
    }
    public function is_sub_menu_item_visible($key)
    {
        if (empty($key)) {
            return false;
        }
        $value = get_option("mlm_hide_sub_" . $key);
        if ($value == "true") {
            return false;
        }
        return true;
    }
    public function add_custom_menu_items($items)
    {
        $menu_id = get_option("mlm_extra_links");
        $after = get_option("mlm_extra_links_after");
        if (empty($menu_id)) {
            return $items;
        }
        if ($menu_items = wp_get_nav_menu_items($menu_id)) {
            $new_items = [];
            foreach ($menu_items as $menu_item) {
                if ($menu_item->menu_item_parent == 0) {
                    if (is_array($menu_item->classes) && 0 < count($menu_item->classes)) {
                        $classes = implode(" ", $menu_item->classes);
                    } else {
                        $classes = "icon-expand";
                    }
                    $new = [$menu_item->ID => ["title" => $menu_item->title, "icon" => $classes, "link" => $menu_item->url, "sub" => "", "visible" => true, "type" => "all"]];
                    $items = $this->array_insert_after($items, $after, $new);
                }
            }
        }
        return $items;
    }
    public function array_insert_after($array, $key, $new)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        $pos = false === $index ? count($array) : $index + 1;
        $result = array_merge(array_slice($array, 0, $pos), $new, array_slice($array, $pos));
        return $result;
    }
    public function is_email_disabled()
    {
        $value = get_option("mlm_email_disabled");
        if ($value == "yes") {
            return true;
        }
        return false;
    }
    public function is_email_required()
    {
        $value = get_option("mlm_email_disabled");
        if ($value == "no") {
            return true;
        }
        return false;
    }
    public function is_code_enabled()
    {
        $value = get_option("mlm_code_enabled");
        if ($value == "yes") {
            return true;
        }
        return false;
    }
    public function is_code_required()
    {
        $value = get_option("mlm_code_required");
        if ($value == "yes") {
            return true;
        }
        return false;
    }
    public function custom_fields($saved_fields = [])
    {
        $custom_fields = mlmFire()->wp_admin->get_fields();
        if (!$custom_fields) {
            return NULL;
        }
        foreach ($custom_fields as $k => $v) {
            $req = $v["req"] == "yes" ? " <i class=\"text-danger\">*</i>" : "";
            $val = isset($saved_fields[$v["id"]]) ? $saved_fields[$v["id"]] : "";
            echo "<div class=\"form-group col-12 col-md-6\">";
            echo "<label>" . $v["text"] . $req . "</label>";
            echo "<input type=\"text\" name=\"mlm_custom[i][" . $v["id"] . "]\" class=\"form-control\" placeholder=\"" . $v["place"] . "\" value=\"" . $val . "\" />";
            echo "</div>";
        }
    }
    public function check_required_fields($input_fields)
    {
        $custom_fields = mlmFire()->wp_admin->get_fields();
        if (!$custom_fields) {
            return false;
        }
        foreach ($custom_fields as $k => $v) {
            if ($v["req"] == "yes" && (!isset($input_fields[$v["id"]]) || empty($input_fields[$v["id"]]))) {
                return true;
            }
        }
        return false;
    }
}

?>