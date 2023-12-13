<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Announce
{
    public function __construct()
    {
        add_action("init", [$this, "custom_posttype"]);
        add_action("add_meta_boxes", [$this, "custom_metabox"], 5);
        add_action("save_post", [$this, "save_meta_values"]);
    }
    public function custom_posttype()
    {
        $labels = ["name" => __("Announces", "mlm"), "add_new" => __("Add new announce", "mlm"), "singular_name" => __("Announce", "mlm"), "menu_name" => __("Announces", "mlm"), "name_admin_bar" => __("Announce", "mlm"), "view_item" => __("View", "mlm"), "all_items" => __("Announces", "mlm"), "search_items" => __("Search announces", "mlm"), "not_found" => __("Not found", "mlm"), "not_found_in_trash" => __("Trash is empty", "mlm")];
        $args = ["label" => __("Announces", "mlm"), "labels" => $labels, "public" => false, "show_ui" => true, "has_archive" => false, "hierarchical" => false, "capability_type" => "page", "show_in_menu" => "mlm-wallet", "rewrite" => ["slug" => "mlm-announce"], "supports" => ["title", "editor"]];
        register_post_type("mlm-announce", $args);
    }
    public function custom_metabox()
    {
        add_meta_box("mlm_announce_metabox", __("Details", "mlm"), [$this, "announce_meta_callback"], "mlm-announce", "normal", "high");
    }
    public function announce_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_users = get_post_meta($post->ID, "mlm_users", true);
        $mlm_refers = get_post_meta($post->ID, "mlm_refers", true);
        $mlm_vendors = get_post_meta($post->ID, "mlm_vendors", true);
        echo "\t\t\n\t\t<table class=\"form-table\">\n\t\t\t<tr>\n\t\t\t\t<th><label>";
        _e("Users", "mlm");
        echo "</label></th>\n\t\t\t\t<td>\n\t\t\t\t\t<label for=\"mlm_users\">\n\t\t\t\t\t\t<input name=\"mlm_users\" type=\"checkbox\" id=\"mlm_users\" value=\"1\" ";
        checked($mlm_users, 1);
        echo "> \n\t\t\t\t\t\t";
        _e("Display to all users", "mlm");
        echo "\t\t\t\t\t</label>\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t\t<tr>\n\t\t\t\t<th><label>";
        _e("Referrers", "mlm");
        echo "</label></th>\n\t\t\t\t<td>\n\t\t\t\t\t<label for=\"mlm_refers\">\n\t\t\t\t\t\t<input name=\"mlm_refers\" type=\"checkbox\" id=\"mlm_refers\" value=\"1\" ";
        checked($mlm_refers, 1);
        echo "> \n\t\t\t\t\t\t";
        _e("Display to all referrers", "mlm");
        echo "\t\t\t\t\t</label>\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t\t<tr>\n\t\t\t\t<th><label>";
        _e("Sellers", "mlm");
        echo "</label></th>\n\t\t\t\t<td>\n\t\t\t\t\t<label for=\"mlm_vendors\">\n\t\t\t\t\t\t<input name=\"mlm_vendors\" type=\"checkbox\" id=\"mlm_vendors\" value=\"1\" ";
        checked($mlm_vendors, 1);
        echo "> \n\t\t\t\t\t\t";
        _e("Display to all sellers", "mlm");
        echo "\t\t\t\t\t</label>\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t</table>\n\t\t\n\t\t";
    }
    public function save_meta_values($post_id)
    {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = isset($_POST["mlm_kagan"]) && wp_verify_nonce($_POST["mlm_kagan"], basename(__FILE__)) ? "true" : "false";
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return NULL;
        }
        if (isset($_POST["mlm_users"])) {
            update_post_meta($post_id, "mlm_users", 1);
        } else {
            delete_post_meta($post_id, "mlm_users");
        }
        if (isset($_POST["mlm_refers"])) {
            update_post_meta($post_id, "mlm_refers", 1);
        } else {
            delete_post_meta($post_id, "mlm_refers");
        }
        if (isset($_POST["mlm_vendors"])) {
            update_post_meta($post_id, "mlm_vendors", 1);
        } else {
            delete_post_meta($post_id, "mlm_vendors");
        }
    }
    public function check_user_announce($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $meta_query = $this->announce_meta_query($user_id);
        $user_announces = $this->get_user_announces($user_id);
        $args = ["post_type" => "mlm-announce", "post_status" => "publish", "posts_per_page" => -1, "fields" => "ids", "no_found_rows" => true];
        if ($user_announces) {
            $args["post__not_in"] = $user_announces;
        }
        if ($meta_query) {
            $args["meta_query"] = $meta_query;
        }
        $query = new WP_Query($args);
        return $query->post_count;
    }
    public function get_user_announces($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $saved = get_user_meta($user_id, "mlm_announces", true);
        if (!empty($saved)) {
            $announces = explode(",", $saved);
            return $announces;
        }
        return false;
    }
    public function announce_seen_by_user($user_id, $post_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_post_exists($post_id)) {
            return false;
        }
        $saved = get_user_meta($user_id, "mlm_announces", true);
        if (!empty($saved)) {
            $announces = explode(",", $saved);
        } else {
            $announces = [];
        }
        if (in_array($post_id, $announces)) {
            return false;
        }
        $announces[] = $post_id;
        $new = implode(",", $announces);
        update_user_meta($user_id, "mlm_announces", $new);
    }
    public function announce_meta_query($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $meta_query = [["key" => "mlm_users", "value" => 1, "type" => "numeric", "compare" => "="]];
        if (user_can($user_id, "read_private_pages")) {
            $meta_query[] = ["key" => "mlm_vendors", "value" => 1, "type" => "numeric", "compare" => "="];
        }
        if (user_can($user_id, "unfiltered_html")) {
            $meta_query[] = ["key" => "mlm_refers", "value" => 1, "type" => "numeric", "compare" => "="];
        }
        if (1 < count($meta_query)) {
            $meta_query["relation"] = "OR";
        }
        return $meta_query;
    }
    public function check_user_access($user_id, $post_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_post_exists($post_id)) {
            return false;
        }
        $mlm_users = get_post_meta($post_id, "mlm_users", true);
        $mlm_refers = get_post_meta($post_id, "mlm_refers", true);
        $mlm_vendors = get_post_meta($post_id, "mlm_vendors", true);
        if ($mlm_users) {
            return true;
        }
        if (user_can($user_id, "unfiltered_html") && $mlm_refers) {
            return true;
        }
        if (user_can($user_id, "read_private_pages") && $mlm_vendors) {
            return true;
        }
        return false;
    }
}

?>