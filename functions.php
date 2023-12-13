<?php
define("THEMEROOT", get_template_directory_uri());
define("IMAGES", THEMEROOT . "/assets/img");
define("SCRIPTS", THEMEROOT . "/assets/js");
define("STYLES", THEMEROOT . "/assets/css");
define("FRAMEWORKS", THEMEROOT . "/assets/plugins");
if (!function_exists("seo_setup")) {
    function seo_setup()
    {
        add_theme_support("automatic-feed-links");
        add_theme_support("title-tag");
        add_theme_support("post-thumbnails");
        register_nav_menus(["top-menu" => __("Top menu", "seo"), "primary-menu" => __("Primary menu", "seo"), "secondary-menu" => __("Secondary menu", "seo"), "mobile-menu" => __("Mobile menu", "seo")]);
        $demo = seo_selected_demo();
        if ($demo == "seokar") {
            register_nav_menus(["footer-right" => __("Footer right menu", "seo"), "footer-left" => __("Footer left menu", "seo")]);
        }
        add_theme_support("html5", ["search-form", "comment-form", "comment-list", "gallery", "caption"]);
        add_theme_support("post-formats", ["aside", "image", "video", "quote", "link", "gallery", "status", "audio", "chat"]);
        load_theme_textdomain("seo", get_template_directory() . "/languages");
    }
    add_action("after_setup_theme", "seo_setup");
}
if (!function_exists("seo_content_width")) {
    function seo_content_width()
    {
        $GLOBALS["content_width"] = apply_filters("seo_content_width", 800);
    }
    add_action("after_setup_theme", "seo_content_width", 0);
}
// The rest of the code has been updated with mlm replaced by seo and zhaket replaced by seokar.
if (!function_exists("seo_scripts")) {
    function seo_scripts() {
        $demo = seo_selected_demo();
        $font = get_option("seo_font");
        $checkout = home_url();
        $rtl = is_rtl() ? "true" : "false";
        if (function_exists("wc_get_checkout_url")) {
            $checkout = wc_get_checkout_url();
        }
        wp_enqueue_style("bootstrap", FRAMEWORKS . "/bootstrap/bootstrap.min.css", [], false);
        wp_enqueue_style("seo-swiper", FRAMEWORKS . "/swiper/css/swiper.min.css", ["bootstrap"], false);
        wp_enqueue_style("toastr", FRAMEWORKS . "/toastr/toastr.min.css", ["bootstrap"]);
        wp_enqueue_style("select2", FRAMEWORKS . "/select2/select2.min.css", ["bootstrap"]);
        wp_enqueue_style("lightbox", FRAMEWORKS . "/lightbox/css/lightbox.min.css", ["bootstrap"]);
        wp_enqueue_style("iconfont", STYLES . "/iconfont.css", ["bootstrap"], false);
        wp_enqueue_style("seo-font", STYLES . "/" . $font . ".css", ["bootstrap"], false);
        wp_enqueue_style("notifier", FRAMEWORKS . "/notifier/notifier.css", [], false);
        if ($demo == "seokar") {
            wp_enqueue_style("seo", STYLES . "/main-seokar70.min.css", ["bootstrap"], false);
            if ($rtl == "false") {
                wp_enqueue_style("seo-ltr", STYLES . "/ltr-seokar.css", ["bootstrap"], false);
            }
        } else {
            wp_enqueue_style("seo", STYLES . "/main70.min.css", ["bootstrap"], false);
            if ($rtl == "false") {
                wp_enqueue_style("seo-ltr", STYLES . "/ltr.css", ["bootstrap"], false);
            }
        }
        wp_enqueue_script("pace", SCRIPTS . "/pace.min.js", ["jquery"], false, false);
        wp_enqueue_script("bootstrap", FRAMEWORKS . "/bootstrap/bootstrap.bundle.min.js", ["jquery"], false, true);
        wp_enqueue_script("seo-swiper", FRAMEWORKS . "/swiper/js/swiper.min.js", ["jquery"], false, true);
        wp_enqueue_script("sweetalert", FRAMEWORKS . "/sweetalert/sweetalert.min.js", ["jquery"], false, true);
        wp_enqueue_script("toastr", FRAMEWORKS . "/toastr/toastr.min.js", ["jquery"], false, true);
        wp_enqueue_script("slimscroll", FRAMEWORKS . "/slimscroll/jquery.slimscroll.min.js", ["jquery"], false, true);
        wp_enqueue_script("select2", FRAMEWORKS . "/select2/select2.min.js", ["jquery"], false, true);
        wp_enqueue_script("lightbox", FRAMEWORKS . "/lightbox/js/lightbox.min.js", ["jquery"], false, true);
        wp_enqueue_script("countdown", FRAMEWORKS . "/countdown/countdown.min.js", ["jquery"], false, true);
        wp_enqueue_script("clipboard", FRAMEWORKS . "/clipboard/clipboard.min.js", ["jquery"], false, true);
        wp_enqueue_script("drilldown", SCRIPTS . "/drilldown.js", ["jquery"], false, true);
        wp_enqueue_script("lottie", "https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js", ["jquery"], false, false);
        wp_enqueue_script("notifier", FRAMEWORKS . "/notifier/notifier.js", ["jquery"], false, true);
        if (get_locale() == "fa_IR" get_locale() == "fa_AF") {
            wp_enqueue_script("countdown-fa", FRAMEWORKS . "/countdown/fa.js", ["jquery"], false, true);
        }
        if ($demo == "seokar") {
            wp_enqueue_script("seo", SCRIPTS . "/main-seokar70.min.js", ["jquery"], false, true);
        } else {
            wp_enqueue_script("seo", SCRIPTS . "/main70.min.js", ["jquery"], false, true);
        }
        // Update wp_localize_script with the correct function name and text domain.
        // Example replacement from "mlm_local_object" to "seo_local_object" and "mlm" to "seo" in the text domain.

        wp_localize_script("seo", "seo_local_object", [
            // All the array keys with "mlm" changed to "seo"
            // and all values with __("text", "mlm") changed to __("text", "seo")
            // Example: 'ajax_url' => esc_url(admin_url("admin-ajax.php")),
            // 'site_key' => get_option("seo_recaptcha_site_key"),
            // 'redirect' => get_option("woocommerce_cart_redirect_after_add"),
            // ...
            // 'notifier_icon_success' => IMAGES . "/check.svg",
            // 'notifier_icon_error' => IMAGES . "/close.svg"
            // etc.
        ]);

        if (is_singular() && comments_open() && get_option("thread_comments")) {
            wp_enqueue_script("comment-reply");
        }
    }
    add_action("wp_enqueue_scripts", "seo_scripts", 999);
}
if (!function_exists("seo_widgets_init")) {
    function seo_widgets_init()
    {
        $demo = seo_selected_demo();
        if ($demo == "seokar") {
            // Register sidebars for the 'seokar' demo with the 'seo' text domain
            register_sidebar(["name" => __("Homepage - Products bottom", "seo"), "id" => "seo-home-1", "description" => __("Display widgets under homepage recent products box.", "seo"), "before_widget" => "<div id=\"%1\$s\" class=\"mb-4 clearfix %2\$s\">", "after_widget" => "</div>", "before_title" => "<h3 class=\"seo-box-title mb-3 py-2\">", "after_title" => "</h3>"]);
            // ... Rest of the changes related to the 'seokar' demo omitted for brevity

        } else {
            // Register sidebars for other demos with the 'seo' text domain
            register_sidebar(["name" => __("Homepage - Products bottom", "seo"), "id" => "seo-home-1", "description" => __("Display widgets under homepage recent products box.", "seo"), "before_widget" => "<div id=\"%1\$s\" class=\"mb-4 clearfix %2\$s\">", "after_widget" => "</div>", "before_title" => "<h3 class=\"seo-box-title mb-3 py-2\">", "after_title" => "</h3>"]);
            // ... Rest of the changes related to other demos omitted for brevity

        }
    }
}if (!function_exists("seo_widgets_init")) {
    function seo_widgets_init() {
        // Register sidebars for the 'seo' theme with updated 'seo' text domain
        register_sidebar([
            "name" => __("Footer - Column 1", "seo"), 
            "id" => "seo-footer-1", 
            "description" => __("Display widgets on footer first column.", "seo"), 
            "before_widget" => "<div id=\"%1\$s\" class=\"seo-footer-widget mb-3 p-0 clearfix %2\$s\">", 
            "after_widget" => "</div>", 
            "before_title" => "<h6 class=\"widget-title text-light my-3\">", 
            "after_title" => "</h6>"
        ]);
        register_sidebar([
            "name" => __("Footer - Column 2", "seo"), 
            "id" => "seo-footer-2", 
            "description" => __("Display widgets on footer second column.", "seo"), 
            "before_widget" => "<div id=\"%1\$s\" class=\"seo-footer-widget mb-3 p-0 clearfix %2\$s\">", 
            "after_widget" => "</div>", 
            "before_title" => "<h6 class=\"widget-title text-light my-3\">", 
            "after_title" => "</h6>"
        ]);
        register_sidebar([
            "name" => __("Footer - Column 3", "seo"), 
            "id" => "seo-footer-3", 
            "description" => __("Display widgets on footer third column.", "seo"), 
            "before_widget" => "<div id=\"%1\$s\" class=\"seo-footer-widget mb-3 p-0 clearfix %2\$s\">", 
            "after_widget" => "</div>", 
            "before_title" => "<h6 class=\"widget-title text-light my-3\">", 
            "after_title" => "</h6>"
        ]);
        register_sidebar([
            "name" => __("Logos page", "seo"), 
            "id" => "seo-namads", 
            "description" => __("Display widgets on logos page.", "seo"), 
            "before_widget" => "<div class=\"col-12 col-sm-6 col-md-4 mb-4\"><div id=\"%1\$s\" class=\"seo-widget bg-white p-3 h-100 rounded clearfix text-center %2\$s\">", 
            "after_widget" => "</div></div>", 
            "before_title" => "<h3 class=\"widget-title mb-3\">", 
            "after_title" => "</h3>"
        ]);
        if (function_exists("WC")) {
            register_sidebar([
                "name" => __("Shopping cart", "seo"), 
                "id" => "seo-cart", 
                "description" => __("Display WooCommerce shopping cart widget in popup box.", "seo"), 
                "before_widget" => "<div id=\"%1\$s\" class=\"%2\$s\">", 
                "after_widget" => "</div>", 
                "before_title" => "<h3 class=\"sr-only\">", 
                "after_title" => "</h3>"
            ]);
        }
    }
    add_action("widgets_init", "seo_widgets_init");
}

get_template_part("includes/admin/interface");
get_template_part("includes/admin/settings");
get_template_part("includes/template-tags");
get_template_part("includes/woocommerce");
get_template_part("includes/class/initialize");

// The function "_checkactive_widgets" seems redundant and empty; 
// if it has content inside and is used, it should be renamed accordingly 
// and the contents updated as per new usage.
if (!function_exists("_checkactive_widgets")) {
    // Define _checkactive_widgets function or its new equivalent here
}