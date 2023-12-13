<?php
/*
 * @
 */

add_action("init", "Zhkt_Guard_MarketMLM_init");
class Zhkt_Guard_MarketMLM_SDK
{
    private $name = NULL;
    private $slug = NULL;
    private $parent_slug = NULL;
    private $text_domain = NULL;
    private static $option_name = NULL;
    private $product_token = NULL;
    public static $api_url = "http://guard.zhaket.com/api/";
    private static $instance = NULL;
    public function __construct($settings)
    {
        $defaults = ["name" => "", "slug" => "zhk_guard_register", "parent_slug" => "options-general.php", "text_domain" => "", "product_token" => "", "option_name" => "zhk_guard_register_settings"];
        foreach ($settings as $key => $setting) {
            if (array_key_exists($key, $defaults) && !empty($setting)) {
                $defaults[$key] = $setting;
            }
        }
        $this->name = $defaults["name"];
        $this->slug = $defaults["slug"];
        $this->parent_slug = $defaults["parent_slug"];
        $this->text_domain = $defaults["text_domain"];
        self::$option_name = $defaults["option_name"];
        $this->product_token = $defaults["product_token"];
        add_action("admin_menu", [$this, "admin_menu"]);
        add_action("wp_ajax_" . $this->slug, [$this, "wp_starter"]);
        add_action("wp_ajax_" . $this->slug . "_revalidate", [$this, "revalidate_starter"]);
        add_action("init", [$this, "schedule_programs"]);
        add_action($this->slug . "_daily_validator", [$this, "daily_event"]);
        add_action("admin_notices", [$this, "admin_notice"]);
    }
    public function admin_menu()
    {
        add_submenu_page($this->parent_slug, __("Activate license", "mlm"), __("Activate license", "mlm"), "manage_options", $this->slug, [$this, "menu_content"]);
    }
    public function menu_content()
    {
        $option = get_option(self::$option_name);
        $now = json_decode(get_option($option));
        $starter = isset($now->starter) && !empty($now->starter) ? base64_decode($now->starter) : "";
        if (isset($_GET["debugger"]) && !empty($_GET["debugger"]) && $_GET["debugger"] === "show") {
            $data_show = $option;
        } else {
            $data_show = "";
        }
        echo "        <style>\r\n            form.register_version_form,\r\n            .current_license {\r\n                width: 30%;\r\n                background: #fff;\r\n                margin: 0 auto;\r\n                padding: 20px 30px;\r\n            }\r\n            form.register_version_form  .license_key {\r\n                padding: 5px 10px;\r\n                width: calc( 100% - 100px );\r\n            }\r\n\r\n            form.register_version_form button {\r\n                width: 80px;\r\n                text-align: center;\r\n            }\r\n\r\n            form.register_version_form .result,\r\n            .current_license .check_result {\r\n                width: 100%;\r\n                padding: 30px 0 15px;\r\n                text-align: center;\r\n                display: none;\r\n            }\r\n            .current_license .check_result {\r\n                padding: 20px 0;\r\n                float: right;\r\n                width: 100%;\r\n            }\r\n            form.register_version_form .result .spinner,\r\n            .current_license .check_result .spinner {\r\n                width: auto;\r\n                background-position: right center;\r\n                padding-right: 30px;\r\n                margin: 0;\r\n                float: none;\r\n                visibility: visible;\r\n                display: none;\r\n            }\r\n            .current_license.waiting .check_result .spinner,\r\n            form.register_version_form .result.show .spinner {\r\n                display: inline-block;\r\n            }\r\n            .current_license {\r\n                width: 40%;\r\n                text-align: center;\r\n            }\r\n            .current_license > .current_label {\r\n                line-height: 25px;\r\n                height: 25px;\r\n                display: inline-block;\r\n                font-weight: bold;\r\n                margin-left: 10px;\r\n            }\r\n            .current_license > code {\r\n                line-height: 25px;\r\n                height: 25px;\r\n                padding: 0 5px;\r\n                color: #c7254e;\r\n                margin-left: 10px;\r\n                display: inline-block;\r\n                -webkit-transform: translateY(2px);\r\n                -moz-transform: translateY(2px);\r\n                -ms-transform: translateY(2px);\r\n                -o-transform: translateY(2px);\r\n                transform: translateY(2px);\r\n            }\r\n            .current_license .action {\r\n                color: #fff;\r\n                line-height: 25px;\r\n                height: 25px;\r\n                padding: 0 5px;\r\n                display: inline-block;\r\n            }\r\n            .current_license .last_check {\r\n                line-height: 25px;\r\n                height: 25px;\r\n                padding: 0 5px;\r\n                display: inline-block;\r\n            }\r\n            .current_license .action.active {\r\n                background: #4CAF50;\r\n            }\r\n            .current_license .action.inactive {\r\n                background: #c7254e;\r\n            }\r\n\r\n            .current_license .keys {\r\n                float: right;\r\n                width: 100%;\r\n                text-align: center;\r\n                padding-top: 20px;\r\n                border-top: 1px solid #ddd;\r\n                margin-top: 20px;\r\n            }\r\n            .current_license .keys .wpmlr_revalidate {\r\n                margin-left: 30px;\r\n            }\r\n            .current_license .register_version_form {\r\n                display: none;\r\n                padding: 0;\r\n                float: right;\r\n                width: 80%;\r\n                margin: 20px 10%;\r\n            }\r\n            .zhk_guard_notice {\r\n                background: #fff;\r\n                border: 1px solid rgba(0,0,0,.1);\r\n                border-right: 4px solid #00a0d2;\r\n                padding: 5px 15px;\r\n                margin: 5px;\r\n            }\r\n            .zhk_guard_danger {\r\n                background: #fff;\r\n                border: 1px solid rgba(0,0,0,.1);\r\n                border-right: 4px solid #DC3232;\r\n                padding: 5px 15px;\r\n                margin: 5px;\r\n            }\r\n            .zhk_guard_success {\r\n                background: #fff;\r\n                border: 1px solid rgba(0,0,0,.1);\r\n                border-right: 4px solid #46b450;\r\n                padding: 5px 15px;\r\n                margin: 5px;\r\n            }\r\n            @media (max-width: 1024px) {\r\n                form.register_version_form,\r\n                .current_license {\r\n                    width: 90%;\r\n                }\r\n            }\r\n        </style>\r\n        <div class=\"wrap wpmlr_wrap\" data-show=\"";
        echo $data_show;
        echo "\">\r\n            <h1>";
        _e("Activate license", "mlm");
        echo "</h1>\r\n            ";
        if (isset($now) && !empty($now)) {
            echo "                <p>";
            _e("You activated the theme already. You can use this form to revalidate it.", "mlm");
            echo "</p>\r\n                <div class=\"current_license\">\r\n                    <span class=\"current_label\">";
            _e("Your license: ", "mlm");
            echo "</span>\r\n                    <code>";
            echo $starter;
            echo "</code>\r\n                    <div class=\"action ";
            echo $now->action == 1 ? "active" : "inactive";
            echo "\">\r\n                        ";
            if ($now->action == 1) {
                echo "                            <span class=\"dashicons dashicons-yes\"></span>\r\n                            ";
                echo $now->message;
                echo "                        ";
            } else {
                echo "                            <span class=\"dashicons dashicons-no-alt\"></span>\r\n                            ";
                echo $now->message;
                echo "                        ";
            }
            echo "                    </div>\r\n                    <div class=\"keys\">\r\n                        <a href=\"#\" class=\"button button-primary wpmlr_revalidate\" data-key=\"";
            echo $starter;
            echo "\">";
            _e("Revalidate", "mlm");
            echo "</a>\r\n                        <a href=\"#\" class=\"button zhk_guard_new_key\">";
            _e("Delete this and put another activation code", "mlm");
            echo "</a>\r\n                    </div>\r\n\r\n                    <form action=\"#\" method=\"post\" class=\"register_version_form\">\r\n                        <input type=\"text\" class=\"license_key\" placeholder=\"";
            _e("New activation code", "mlm");
            echo "\">\r\n                        <button class=\"button button-primary\">";
            _e("Activate", "mlm");
            echo "</button>\r\n                        <div class=\"result\">\r\n                            <div class=\"spinner\">";
            _e("Please wait ...", "mlm");
            echo "</div>\r\n                            <div class=\"result_text\"></div>\r\n                        </div>\r\n                    </form>\r\n\r\n                    <div class=\"check_result\">\r\n                        <div class=\"spinner\">";
            _e("Please wait ...", "mlm");
            echo "</div>\r\n                        <div class=\"result_text\"></div>\r\n                    </div>\r\n                    <div class=\"clear\"></div>\r\n                </div>\r\n            ";
        } else {
            echo "                <p>";
            _e("Please activate your theme to use all the theme options.", "mlm");
            echo "</p>\r\n                <form action=\"#\" method=\"post\" class=\"register_version_form\">\r\n                    <input type=\"text\" class=\"license_key\" placeholder=\"";
            _e("Activation code", "mlm");
            echo "\">\r\n                    <button class=\"button button-primary\">";
            _e("Activate", "mlm");
            echo "</button>\r\n                    <div class=\"result\">\r\n                        <div class=\"spinner\">";
            _e("Please wait ...", "mlm");
            echo "</div>\r\n                        <div class=\"result_text\"></div>\r\n                    </div>\r\n                </form>\r\n            ";
        }
        echo "            <script>\r\n                jQuery(document).ready(function(\$) {\r\n                    var ajax_url = \"";
        echo admin_url("admin-ajax.php");
        echo "\";\r\n                    jQuery(document).on('submit', '.register_version_form', function(event) {\r\n                        event.preventDefault();\r\n                        var starter = jQuery(this).find('.license_key').val(),\r\n                            thisEl = jQuery(this);\r\n                        thisEl.addClass('waiting');\r\n                        thisEl.find('.result').slideDown(300).addClass('show');\r\n                        thisEl.find('.button').addClass('disabled');\r\n                        thisEl.find('.result_text').slideUp(300).html('');\r\n                        jQuery.ajax({\r\n                            url: ajax_url,\r\n                            type: 'POST',\r\n                            dataType: 'json',\r\n                            data: {\r\n                                action: '";
        echo $this->slug;
        echo "',\r\n                                starter: starter\r\n                            },\r\n                        })\r\n                            .done(function(result) {\r\n                                thisEl.find('.result_text').append(result.data).slideDown(150)\r\n                            })\r\n                            .fail(function(result) {\r\n                                thisEl.find('.result_text').append('<div class=\"zhk_guard_danger\">";
        _e("Unknown error happend. Please try again.", "mlm");
        echo "</div>').slideDown(150)\r\n                            })\r\n                            .always(function(result) {\r\n                                console.log(result);\r\n                                thisEl.removeClass('waiting');\r\n                                thisEl.find('.result').removeClass('show');\r\n                                thisEl.find('.button').removeClass('disabled');\r\n                            });\r\n                    });\r\n\r\n                    \$(document).on('click', '.wpmlr_revalidate', function(event) {\r\n                        event.preventDefault();\r\n                        var starter = \$(this).data('key'),\r\n                            thisEl = \$(this).parents('.current_license');\r\n                        thisEl.addClass('waiting');\r\n                        thisEl.find('.check_result').slideDown(300);\r\n                        thisEl.find('.button').addClass('disabled');\r\n                        thisEl.find('.result_text').slideUp(300).html('');\r\n                        thisEl.find('.register_version_form').slideUp(300)\r\n                        \$.ajax({\r\n                            url: ajax_url,\r\n                            type: 'POST',\r\n                            dataType: 'json',\r\n                            data: {\r\n                                action: '";
        echo $this->slug;
        echo "_revalidate',\r\n                                starter: starter\r\n                            },\r\n                        })\r\n                            .done(function(result) {\r\n                                thisEl.find('.check_result .result_text').append(result.data).slideDown(150)\r\n                            })\r\n                            .fail(function(result) {\r\n                                thisEl.find('.check_result .result_text').append('<div class=\"wpmlr_danger\">";
        _e("Unknown error happend. Please try again.", "mlm");
        echo "</div>').slideDown(150)\r\n                            })\r\n                            .always(function(result) {\r\n                                thisEl.removeClass('waiting');\r\n                                thisEl.find('.button').removeClass('disabled');\r\n                            });\r\n                    });\r\n\r\n\r\n                    \$(document).on('click', '.zhk_guard_new_key', function(event) {\r\n                        event.preventDefault();\r\n                        var thisEl = \$(this).parents('.current_license');\r\n                        thisEl.find('.result_text').slideUp(300).html('');\r\n                        thisEl.find('.register_version_form').slideDown(300)\r\n                    });\r\n                });\r\n            </script>\r\n\r\n        </div>\r\n        ";
    }
    public function wp_starter()
    {
        $starter = sanitize_text_field($_POST["starter"]);
        if (empty($starter)) {
            wp_send_json_error("<div class=\"zhk_guard_danger\">" . __("Please fill in your activation code", "mlm") . "</div>");
        }
        $private_session = get_option(self::$option_name);
        delete_option($private_session);
        $product_token = $this->product_token;
        $result = self::install($starter, $product_token);
        $output = "";
        if ($result->status == "successful") {
            $rand_key = md5(wp_generate_password(12, true, true));
            update_option(self::$option_name, $rand_key);
            $result = ["starter" => base64_encode($starter), "action" => 1, "message" => __("Activation code is valid.", "mlm"), "timer" => time()];
            update_option($rand_key, json_encode($result));
            $output = "<div class=\"zhk_guard_success\">" . __("Theme already activated.", "mlm") . "</div>";
            wp_send_json_success($output);
        } else {
            if (!is_object($result->message)) {
                $output = "<div class=\"zhk_guard_danger\">" . $result->message . "</div>";
                wp_send_json_error($output);
            } else {
                foreach ($result->message as $message) {
                    foreach ($message as $msg) {
                        $output .= "<div class=\"zhk_guard_danger\">" . $msg . "</div>";
                    }
                }
                wp_send_json_error($output);
            }
        }
    }
    public function admin_notice()
    {
        $private_session = get_option(self::$option_name);
        $now = json_decode(get_option($private_session));
        echo "        ";
        if (empty($now)) {
            echo "            <div class=\"notice notice-error\">\r\n                <p>\r\n                    ";
            _e("To activate MLM theme please fill in your activation code.", "mlm");
            echo "                    <a href=\"";
            echo admin_url("admin.php?page=" . $this->slug);
            echo "\" class=\"button button-primary\">";
            _e("Activate theme", "mlm");
            echo "</a>\r\n                </p>\r\n            </div>\r\n        ";
        } else {
            if ($now->action != 1) {
                echo "            <div class=\"notice notice-error\">\r\n                <p>\r\n                    ";
                _e("There is an error with MLM activation . please check it again.", "mlm");
                echo "                    <a href=\"";
                echo admin_url("admin.php?page=" . $this->slug);
                echo "\" class=\"button button-primary\">";
                _e("Check", "mlm");
                echo "</a>\r\n                </p>\r\n            </div>\r\n        ";
            }
        }
        echo "        ";
    }
    public function revalidate_starter()
    {
        $starter = sanitize_text_field($_POST["starter"]);
        if (empty($starter)) {
            wp_send_json_error("<div class=\"zhk_guard_danger\">" . __("Please fill in your activation code.", "mlm") . "</div>");
        }
        $result = self::is_valid($starter);
        if ($result->status == "successful") {
            $rand_key = md5(wp_generate_password(12, true, true));
            update_option(self::$option_name, $rand_key);
            $how = ["starter" => base64_encode($starter), "action" => 1, "message" => $result->message, "timer" => time()];
            update_option($rand_key, json_encode($how));
            $output = "<div class=\"zhk_guard_success\">" . __("Theme already activated", "mlm") . "</div>";
            wp_send_json_success($output);
        } else {
            $rand_key = md5(wp_generate_password(12, true, true));
            update_option(self::$option_name, $rand_key);
            $how = ["starter" => base64_encode($starter), "action" => 0, "timer" => time()];
            if (!is_object($result->message)) {
                $how["message"] = $result->message;
            } else {
                foreach ($result->message as $message) {
                    foreach ($message as $msg) {
                        $how["message"] = $msg;
                    }
                }
            }
            update_option($rand_key, json_encode($how));
            $output = "<div class=\"zhk_guard_danger\">" . $how["message"] . "</div>";
            wp_send_json_success($output);
        }
    }
    public function schedule_programs()
    {
        if (!wp_next_scheduled($this->slug . "_daily_validator")) {
            wp_schedule_event(time(), "daily", $this->slug . "_daily_validator");
        }
    }
    public function daily_event()
    {
        $private_session = get_option(self::$option_name);
        $now = json_decode(get_option($private_session));
        if (isset($now) && !empty($now)) {
            $starter = isset($now->starter) && !empty($now->starter) ? base64_decode($now->starter) : "";
            $result = self::is_valid($starter);
            if ($result != NULL) {
                if ($result->status == "successful") {
                    delete_option($private_session);
                    $rand_key = md5(wp_generate_password(12, true, true));
                    update_option(self::$option_name, $rand_key);
                    $how = ["starter" => base64_encode($starter), "action" => 1, "message" => $result->message, "timer" => time()];
                    update_option($rand_key, json_encode($how));
                } else {
                    delete_option($private_session);
                    $rand_key = md5(wp_generate_password(12, true, true));
                    update_option(self::$option_name, $rand_key);
                    $how = ["starter" => base64_encode($starter), "action" => 0, "timer" => time()];
                    if (!is_object($result->message)) {
                        $how["message"] = $result->message;
                    } else {
                        foreach ($result->message as $message) {
                            foreach ($message as $msg) {
                                $how["message"] = $msg;
                            }
                        }
                    }
                    update_option($rand_key, json_encode($how));
                }
            }
        }
    }
    public static function is_activated()
    {
        $private_session = get_option(self::$option_name);
        $now = json_decode(get_option($private_session));
        if (empty($now)) {
            return true;
        }
        if ($now->action != 1) {
            return true;
        }
        return true;
    }
    public static function send_request($method, $params = [])
    {
        $param_string = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, self::$api_url . $method . "?" . $param_string);
        $content = curl_exec($ch);
        return json_decode($content);
    }
    public static function is_valid($license_token)
    {
        $result = self::send_request("validation-license", ["token" => $license_token, "domain" => self::get_host()]);
        return $result;
    }
    public static function install($license_token, $product_token)
    {
        $result = self::send_request("install-license", ["product_token" => $product_token, "token" => $license_token, "domain" => self::get_host()]);
        return $result;
    }
    public static function get_host()
    {
        $possibleHostSources = ["HTTP_X_FORWARDED_HOST", "HTTP_HOST", "SERVER_NAME", "SERVER_ADDR"];
        $sourceTransformations = ["HTTP_X_FORWARDED_HOST" => function ($value) {
            $elements = explode(",", $value);
            return trim(end($elements));
        }];
        $host = "";
        foreach ($possibleHostSources as $source) {
            if (!empty($host)) {
                $host = preg_replace("/:\\d+\$/", "", $host);
                $host = str_ireplace("www.", "", $host);
                return trim($host);
            }
            if (!empty($_SERVER[$source])) {
                $host = $_SERVER[$source];
                if (array_key_exists($source, $sourceTransformations)) {
                    $host = $sourceTransformations[$source]($host);
                }
            }
        }
    }
    public static function instance($settings)
    {
        if (self::$instance == NULL) {
            self::$instance = new self($settings);
        }
        return self::$instance;
    }
}
function Zhkt_Guard_MarketMLM_init()
{
    $settings = ["name" => "", "slug" => "mlm-zhaket-license", "parent_slug" => "mlm-wallet", "text_domain" => "mlm", "product_token" => "6e50b7e6-c05f-498c-8846-0b54af6b232b", "option_name" => "zhkt_guard_marketmlm_register_settings"];
    Zhkt_Guard_MarketMLM_SDK::instance($settings);
}

?>