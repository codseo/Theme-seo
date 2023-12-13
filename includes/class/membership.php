<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Plans
{
    public function __construct()
    {
        add_action("init", [$this, "handle_download"]);
        add_action("init", [$this, "custom_taxonomy"], 0);
        add_action("admin_menu", [$this, "membership_menu"]);
        add_action("plans_add_form_fields", [$this, "plans_extra_fields_add"], 10, 2);
        add_action("plans_edit_form_fields", [$this, "plans_extra_fields_edit"], 10, 2);
        add_action("created_plans", [$this, "plans_extra_fields_save"], 10, 2);
        add_action("edited_plans", [$this, "plans_extra_fields_update"], 10, 2);
    }
    public function handle_download()
    {
        if (!isset($_GET["download_file"]) || !isset($_GET["key"]) || !isset($_GET["subscribe"])) {
            return NULL;
        }
        $login_req = get_option("mlm_login_req");
        $product_id = absint($_GET["download_file"]);
        $_product = wc_get_product($product_id);
        if (!is_user_logged_in() && ($login_req == "yes" || !mlm_is_product_free($product_id))) {
            return NULL;
        }
        $user_id = get_current_user_id();
        $user_obj = get_userdata($user_id);
        $user_email = $user_obj->user_email;
        $access = mlmFire()->plan->check_user_access($product_id, $user_id, true);
        if (mlm_is_product_free($product_id) || $access || function_exists("wc_customer_bought_product") && wc_customer_bought_product($user_email, $user_id, $product_id) && $_product->is_downloadable()) {
            WC_Download_Handler::download($_product->get_file_download_path(filter_var($_GET["key"], FILTER_SANITIZE_STRING)), $product_id);
        } else {
            header("Location: " . get_permalink($product_id));
        }
    }
    public function custom_taxonomy()
    {
        $labels = ["name" => __("Plans", "mlm"), "singular_name" => __("Plan", "mlm"), "search_items" => __("Search plans", "mlm"), "all_items" => __("All plans", "mlm"), "parent_item" => __("Parent", "mlm"), "parent_item_colon" => __("Parent item", "mlm"), "edit_item" => __("Edit plan", "mlm"), "update_item" => __("Update plan", "mlm"), "add_new_item" => __("Add new plan", "mlm"), "new_item_name" => __("New plan name", "mlm"), "menu_name" => __("Plans", "mlm"), "not_found" => __("No plans found", "mlm")];
        $args = ["hierarchical" => true, "labels" => $labels, "public" => false, "publicly_queryable" => false, "show_ui" => true, "show_in_menu" => true, "show_in_nav_menus" => false, "show_tagcloud" => false, "show_admin_column" => false, "query_var" => false, "rewrite" => false];
        register_taxonomy("plans", ["product"], $args);
    }
    public function membership_menu()
    {
        add_submenu_page("mlm-wallet", __("Subscriptions", "mlm"), __("Subscriptions", "mlm"), "manage_options", "mlm-subscribes", [$this, "membership_menu_callback"]);
        add_submenu_page("mlm-wallet", __("New subscription", "mlm"), __("New subscription", "mlm"), "manage_options", "mlm-new-subscribe", [$this, "new_subscribe_callback"]);
    }
    public function membership_menu_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-subscribes-wrap clearfix\">";
        if (isset($_GET["verify"]) && wp_verify_nonce($_GET["verify"], "mlm_subscribe_lex")) {
            $id = isset($_GET["id"]) ? absint($_GET["id"]) : 0;
            $atts = [];
            $atts["query"] = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE id = %d LIMIT %d", [$id, 1], "subscribe", true);
            $atts["id"] = $id;
            echo mlm_get_template("class/wp-admin/subscribes-open", $atts);
        } else {
            $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
            $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
            $per = 20;
            $start = intval(($paged - 1) * $per);
            if (!empty($mlm_user) && mlm_user_exists($mlm_user)) {
                $string = "SELECT * FROM {TABLE} WHERE user_id = %d ORDER BY id DESC LIMIT %d, %d";
                $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d";
                $values = [$mlm_user, $start, $per];
                $c_values = [$mlm_user];
                $link = admin_url("admin.php?page=mlm-subscribes&mlm_user=" . $mlm_user);
            } else {
                $string = "SELECT * FROM {TABLE} ORDER BY id DESC LIMIT %d, %d";
                $c_string = "SELECT COUNT(id) FROM {TABLE}";
                $values = [$start, $per];
                $c_values = "";
                $link = admin_url("admin.php?page=mlm-subscribes");
            }
            $atts = [];
            $atts["query"] = mlmFire()->db->query_rows($string, $values, "subscribe");
            $atts["args"] = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "selected" => $mlm_user, "include_selected" => 1, "class" => "regular-text mlm-select", "name" => "mlm_user"];
            $count = mlmFire()->db->count_query_rows($c_string, $c_values, "subscribe");
            echo mlm_get_template("class/wp-admin/subscribes", $atts);
            mlm_wp_navigation($count, $link, $per);
        }
        echo "</div>";
    }
    public function new_subscribe_callback()
    {
        $atts = [];
        $atts["plans"] = $this->get_plans(0, true);
        $atts["args"] = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "include_selected" => 1, "class" => "regular-text mlm-select", "name" => "mlm_user"];
        echo "<div class=\"wrap mlm-wrap mlm-subscribes-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/subscribes-new", $atts);
        echo "</div>";
    }
    public function plans_extra_fields_add($taxonomy)
    {
        echo "\t\t\r\n\t\t<div class=\"form-field term-group\">\r\n\t\t\t<label for=\"mlm_plan_price\">";
        _e("Price", "mlm");
        echo "</label>\r\n\t\t\t<input type=\"number\" name=\"mlm_plan_price\" class=\"regular-text\" value=\"\" min=\"0\" step=\"100\">\r\n\t\t\t<p class=\"description\">";
        _e("Enter a numeric value based on Woocommerce currency.", "mlm");
        echo "</p>\r\n\t\t</div>\r\n\t\t<div class=\"form-field term-group\">\r\n\t\t\t<label for=\"mlm_plan_expire\">";
        _e("Validity time", "mlm");
        echo "</label>\r\n\t\t\t<input type=\"number\" name=\"mlm_plan_expire\" class=\"regular-text\" value=\"\" min=\"0\" step=\"1\">\r\n\t\t\t<p class=\"description\">";
        _e("Enter a numeric value as number of the days.", "mlm");
        echo "</p>\r\n\t\t</div>\r\n\t\t<div class=\"form-field term-group\">\r\n\t\t\t<label for=\"mlm_plan_limit\">";
        _e("Daily download limit", "mlm");
        echo "</label>\r\n\t\t\t<input type=\"number\" name=\"mlm_plan_limit\" class=\"regular-text\" value=\"\" min=\"0\" step=\"1\">\r\n\t\t\t<p class=\"description\">";
        _e("Enter a numeric value.", "mlm");
        echo "</p>\r\n\t\t</div>\r\n\t\t\r\n\t\t";
    }
    public function plans_extra_fields_edit($term, $taxonomy)
    {
        $mlm_plan_price = (int) get_term_meta($term->term_id, "mlm_plan_price", true);
        $mlm_plan_expire = (int) get_term_meta($term->term_id, "mlm_plan_expire", true);
        $mlm_plan_limit = (int) get_term_meta($term->term_id, "mlm_plan_limit", true);
        echo "\r\n\t\t<tr class=\"form-field term-group-wrap\">\r\n\t\t\t<th scope=\"row\"><label for=\"mlm_plan_price\">";
        _e("Price", "mlm");
        echo "</label></th>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"number\" name=\"mlm_plan_price\" class=\"regular-text\" value=\"";
        echo $mlm_plan_price;
        echo "\" min=\"0\" step=\"100\">\r\n\t\t\t\t<p class=\"description\">";
        _e("Enter a numeric value based on Woocommerce currency.", "mlm");
        echo "</p>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr class=\"form-field term-group-wrap\">\r\n\t\t\t<th scope=\"row\"><label for=\"mlm_plan_expire\">";
        _e("Validity time", "mlm");
        echo "</label></label></th>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"number\" name=\"mlm_plan_expire\" class=\"regular-text\" value=\"";
        echo $mlm_plan_expire;
        echo "\" min=\"0\" step=\"1\">\r\n\t\t\t\t<p class=\"description\">";
        _e("Enter a numeric value as number of the days.", "mlm");
        echo "</p>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr class=\"form-field term-group-wrap\">\r\n\t\t\t<th scope=\"row\"><label for=\"mlm_plan_limit\">";
        _e("Daily download limit", "mlm");
        echo "</label></label></th>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"number\" name=\"mlm_plan_limit\" class=\"regular-text\" value=\"";
        echo $mlm_plan_limit;
        echo "\" min=\"0\" step=\"1\">\r\n\t\t\t\t<p class=\"description\">";
        _e("Enter a numeric value.", "mlm");
        echo "</p>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\r\n\t\t";
    }
    public function plans_extra_fields_save($term_id, $tt_id)
    {
        if (isset($_POST["mlm_plan_price"])) {
            update_term_meta($term_id, "mlm_plan_price", absint($_POST["mlm_plan_price"]));
        }
        if (isset($_POST["mlm_plan_expire"])) {
            update_term_meta($term_id, "mlm_plan_expire", absint($_POST["mlm_plan_expire"]));
        }
        if (isset($_POST["mlm_plan_limit"])) {
            update_term_meta($term_id, "mlm_plan_limit", absint($_POST["mlm_plan_limit"]));
        }
    }
    public function plans_extra_fields_update($term_id, $tt_id)
    {
        if (isset($_POST["mlm_plan_price"])) {
            update_term_meta($term_id, "mlm_plan_price", absint($_POST["mlm_plan_price"]));
        }
        if (isset($_POST["mlm_plan_expire"])) {
            update_term_meta($term_id, "mlm_plan_expire", absint($_POST["mlm_plan_expire"]));
        }
        if (isset($_POST["mlm_plan_limit"])) {
            update_term_meta($term_id, "mlm_plan_limit", absint($_POST["mlm_plan_limit"]));
        }
    }
    public function get_plans($id, $all = false)
    {
        if (empty($id) && !$all) {
            return false;
        }
        if ($all) {
            $plans = get_terms(["taxonomy" => "plans", "hide_empty" => false]);
            if (empty($plans) || is_wp_error($plans)) {
                return false;
            }
            $out = [];
            foreach ($plans as $plan) {
                $out[$plan->term_id] = ["id" => $plan->term_id, "name" => $plan->name, "text" => $plan->description, "count" => $plan->count, "price" => (int) get_term_meta($plan->term_id, "mlm_plan_price", true), "time" => (int) get_term_meta($plan->term_id, "mlm_plan_expire", true), "limit" => (int) get_term_meta($plan->term_id, "mlm_plan_limit", true)];
            }
            return $out;
        } else {
            $plan = get_term($id, "plans");
            if (empty($plan) || is_wp_error($plan)) {
                return false;
            }
            $out[$plan->term_id] = ["id" => $plan->term_id, "name" => $plan->name, "text" => $plan->description, "count" => $plan->count, "price" => (int) get_term_meta($plan->term_id, "mlm_plan_price", true), "time" => (int) get_term_meta($plan->term_id, "mlm_plan_expire", true), "limit" => (int) get_term_meta($plan->term_id, "mlm_plan_limit", true)];
            return $out[$plan->term_id];
        }
    }
    public function get_user_active_plan($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $new_plans = get_user_meta($user_id, "mlm_plans", true);
        $old_db = (int) get_user_meta($user_id, "mlm_plan", true);
        if (!empty($old_db)) {
            $plan_data = $this->get_plan_info($old_db, "plan_data");
            $plan_id = isset($plan_data["id"]) ? $plan_data["id"] : "";
            $new_plans = [$old_db => $plan_id];
            update_user_meta($user_id, "mlm_plans", $new_plans);
            delete_user_meta($user_id, "mlm_plan");
        }
        if (is_array($new_plans) && 0 < count($new_plans)) {
            return $new_plans;
        }
        return false;
    }
    public function set_user_active_plan($id, $user_id, $plan_id)
    {
        if (empty($id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $plans = $this->get_user_active_plan($user_id);
        if (!is_array($plans)) {
            $plans = [];
        }
        $plans[$id] = $plan_id;
        return update_user_meta($user_id, "mlm_plans", $plans);
    }
    public function delete_user_active_plan($user_id, $id)
    {
        if (empty($id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $plans = $this->get_user_active_plan($user_id);
        if (!is_array($plans) || !isset($plans[$id])) {
            return false;
        }
        unset($plans[$id]);
        return update_user_meta($user_id, "mlm_plans", $plans);
    }
    public function get_plan_info($id, $case = "plan_data")
    {
        if (empty($id)) {
            return false;
        }
        $query = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE id = %d LIMIT %d", [$id, 1], "subscribe", true);
        switch ($case) {
            case "expire":
                return isset($query->expire) ? $query->expire : "";
                break;
            case "plan_data":
                return isset($query->plan_data) ? maybe_unserialize($query->plan_data) : "";
                break;
            default:
                return $query;
        }
    }
    public function get_user_role($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return __("Guest user", "mlm");
        }
        $current_plans = $this->get_user_active_plan($user_id);
        if (!is_array($current_plans)) {
            return __("No subscriptions", "mlm");
        }
        foreach ($current_plans as $k => $v) {
            $plan_data = $this->get_plan_info($k, "plan_data");
            return isset($plan_data["name"]) ? $plan_data["name"] : __("No subscriptions", "mlm");
        }
    }
    public function check_user_limit($post_id, $user_id, $downloading = false)
    {
        $limit_download = 0;
        if (!mlm_post_exists($post_id) || !mlm_user_exists($user_id)) {
            return $limit_download;
        }
        $_product = wc_get_product($post_id);
        if (!$_product->is_downloadable()) {
            return $limit_download;
        }
        $terms = [];
        $current_plans = $this->get_user_active_plan($user_id);
        $course_flag = mlm_check_course($post_id);
        if (!is_array($current_plans)) {
            return $limit_download;
        }
        foreach ($current_plans as $key => $value) {
            if ($course_flag) {
                $terms[] = $value;
            } else {
                $plan_obj = $this->get_plans($value);
                $today_dl = $this->get_daily_download_count($user_id, $value);
                if ($today_dl < $plan_obj["limit"]) {
                    $terms[] = $value;
                    $limit_download = 0;
                } else {
                    $limit_download = 5;
                }
                if ($downloading) {
                    $this->new_download_for_today($user_id, $value, $post_id);
                }
            }
        }
        if (count($terms) < 1) {
            return $limit_download;
        }
        if (has_term($terms, "plans", $post_id)) {
            return $limit_download;
        }
        return $limit_download;
    }
    public function check_user_access($post_id, $user_id, $downloading = false)
    {
        if (!mlm_post_exists($post_id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $_product = wc_get_product($post_id);
        if (!$_product->is_downloadable()) {
            return false;
        }
        $terms = [];
        $current_plans = $this->get_user_active_plan($user_id);
        $course_flag = mlm_check_course($post_id);
        if (!is_array($current_plans)) {
            return false;
        }
        foreach ($current_plans as $key => $value) {
            if ($course_flag) {
                $terms[] = $value;
            } else {
                $plan_obj = $this->get_plans($value);
                $today_dl = $this->get_daily_download_count($user_id, $value);
                if ($today_dl < $plan_obj["limit"]) {
                    $terms[] = $value;
                }
                if ($downloading) {
                    $this->new_download_for_today($user_id, $value, $post_id);
                }
            }
        }
        if (count($terms) < 1) {
            return false;
        }
        if (has_term($terms, "plans", $post_id)) {
            return true;
        }
        return false;
    }
    public function get_subscription_text($post_id, $user_id)
    {
        if (!mlm_post_exists($post_id)) {
            return false;
        }
        $output = "";
        $post_plans = wp_get_post_terms($post_id, "plans");
        $plan_names = [];
        if (!empty($post_plans) && !is_wp_error($post_plans)) {
            foreach ($post_plans as $pl) {
                $plan_names[] = $pl->name;
            }
            $output = sprintf(__("Available by these plans: %s", "mlm"), implode(" - ", $plan_names));
            $output .= "<div class=\"w-100 py-1\"></div>";
        }
        if (mlm_user_exists($user_id)) {
            $current_plans = $this->get_user_active_plan($user_id);
            if (is_array($current_plans) && 0 < count($current_plans)) {
                $terms = [];
                $plan_name = __("VIP plan", "mlm");
                foreach ($current_plans as $key => $value) {
                    $terms[] = $value;
                }
                if (has_term($terms, "plans", $post_id)) {
                    $output .= sprintf(__("You have %s and allowed to download this product.", "mlm"), $plan_name);
                }
            }
        }
        $cnt = 0;
        $terms = [];
        $all_plans = $this->get_plans(0, true);
        $plans_url = trailingslashit(mlm_page_url("panel")) . "section/subscribes/";
        if ($all_plans) {
            foreach ($all_plans as $k => $v) {
                $terms[] = $k;
            }
        }
        if (count($terms) && has_term($terms, "plans", $post_id)) {
            $query = new WP_Query(["post_type" => "product", "post_status" => "publish", "posts_per_page" => -1, "fields" => "ids", "no_found_rows" => true, "tax_query" => [["taxonomy" => "plans", "field" => "term_id", "terms" => $terms]]]);
            $cnt = $query->post_count - 1;
            $output .= sprintf(__("You can download this and %1\$d other products with minimum price by <a href=\"%2\$s\">Purchasing VIP plan</a>.", "mlm"), $cnt, $plans_url);
        }
        return $output;
    }
    public function get_subscription_status_class($id)
    {
        switch ($id) {
            case 0:
                return "pending";
                break;
            case 1:
                return "paid";
                break;
            case 2:
                return "failed";
                break;
            default:
                return "unknown";
        }
    }
    public function get_subscription_status($id)
    {
        switch ($id) {
            case 0:
                return __("Pending", "mlm");
                break;
            case 1:
                return __("Paid", "mlm");
                break;
            case 2:
                return __("Failed", "mlm");
                break;
            default:
                return __("Unknown", "mlm");
        }
    }
    public function get_daily_download_count($user_id, $plan_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $today = current_time("Ymd");
        $download_stats = get_user_meta($user_id, "mlm_download_stats", true);
        if (!isset($download_stats[$today])) {
            $download_stats = [$today => []];
        }
        if (!isset($download_stats[$today][$plan_id])) {
            $download_stats = [$today => [$plan_id => []]];
        }
        return count($download_stats[$today][$plan_id]);
    }
    public function new_download_for_today($user_id, $plan_id, $post_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $today = current_time("Ymd");
        $download_stats = get_user_meta($user_id, "mlm_download_stats", true);
        if (!isset($download_stats[$today])) {
            $download_stats = [$today => []];
        }
        if (!isset($download_stats[$today][$plan_id])) {
            $download_stats = [$today => [$plan_id => []]];
        }
        $flag = false;
        foreach ($download_stats[$today][$plan_id] as $p) {
            if ($p == $post_id) {
                $flag = true;
                if (!$flag) {
                    $download_stats[$today][$plan_id][] = $post_id;
                }
                update_user_meta($user_id, "mlm_download_stats", $download_stats);
                return true;
            }
        }
    }
}

?>