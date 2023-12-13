<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Medals
{
    public function __construct()
    {
        add_filter("get_comment_author_link", [$this, "comment_badges"], 10, 3);
        add_filter("comment_text", [$this, "comment_medals"], 99, 2);
    }
    public function comment_badges($return, $author, $comment_ID)
    {
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $return;
        }
        $post_id = get_the_ID();
        $comment = get_comment($comment_ID);
        $commenter = $comment->user_id;
        if (!mlm_user_exists($commenter)) {
            return $return;
        }
        $verified = mlmFire()->dashboard->get_account_status($commenter);
        if ($verified) {
            $return = "<span class=\"user-verified\">" . $return . "</span>";
        }
        if (user_can($commenter, "manage_options")) {
            $return .= "<span class=\"badge badge-primary comment-badge admin-badge font-10 py-1 px-2 mr-2\">" . __("Administrator", "mlm") . "</span>";
        } else {
            if ($commenter == get_the_author_meta("ID") && "product" == get_post_field("post_type", $post_id)) {
                $return .= "<span class=\"badge badge-danger comment-badge vendor-badge font-10 py-1 px-2 mr-2\">" . __("Vendor", "mlm") . "</span>";
            } else {
                if ($commenter == get_the_author_meta("ID")) {
                    $return .= "<span class=\"badge badge-danger comment-badge author-badge font-10 py-1 px-2 mr-2\">" . __("Author", "mlm") . "</span>";
                } else {
                    if ("product" == get_post_field("post_type", $post_id) && function_exists("wc_customer_bought_product") && wc_customer_bought_product($comment->comment_author_email, $commenter, $post_id)) {
                        $return .= "<span class=\"badge badge-success comment-badge customer-badge font-10 py-1 px-2 mr-2\">" . __("Customer", "mlm") . "</span>";
                    }
                }
            }
        }
        return $return;
    }
    public function comment_medals($content, $comment)
    {
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }
        $commenter = $comment->user_id;
        if (!mlm_user_exists($commenter)) {
            return $content;
        }
        return $this->print_user_medals($commenter, "mlm-vendor-medal-nav mlm-comments-medal-nav nav m-0 px-0 pt-0 pb-2", false) . $content;
    }
    public function get_medals()
    {
        return ["vendor", "subset-income", "sale-income", "ref-income", "valid-ref", "valid-post", "valid-product", "valid-subset", "valid-purchase", "valid-withdraw", "valid-comment", "vip-product", "profile-ok", "account-ok"];
    }
    public function get_medal_title($item, $value = 0)
    {
        switch ($item) {
            case "vendor":
                return __("Site vendor", "mlm");
                break;
            case "subset-income":
                return sprintf(__("%s reagent income", "mlm"), mlm_filter($value));
                break;
            case "sale-income":
                return sprintf(__("%s sale income", "mlm"), mlm_filter($value));
                break;
            case "ref-income":
                return sprintf(__("%s referral income", "mlm"), mlm_filter($value));
                break;
            case "valid-ref":
                return sprintf(__("with %d valid referrals", "mlm"), $value);
                break;
            case "valid-post":
                return sprintf(__("with %d published posts", "mlm"), $value);
                break;
            case "valid-product":
                return sprintf(__("with %d verified products", "mlm"), $value);
                break;
            case "valid-subset":
                return sprintf(__("with %d subsets", "mlm"), $value);
                break;
            case "valid-purchase":
                return sprintf(__("with %d purchases", "mlm"), $value);
                break;
            case "valid-withdraw":
                return sprintf(__("with %d withdrawals", "mlm"), $value);
                break;
            case "valid-comment":
                return sprintf(__("with %d comments", "mlm"), $value);
                break;
            case "vip-product":
                return sprintf(__("with %d featured products", "mlm"), $value);
                break;
            case "profile-ok":
                return __("Profile completed", "mlm");
                break;
            case "account-ok":
                return __("Verified account", "mlm");
                break;
            default:
                return false;
        }
    }
    public function min_available_amount($item)
    {
        switch ($item) {
            case "subset-income":
                $saved = (int) get_option("mlm_medal_subset_income");
                return $saved ? $saved : 20000;
                break;
            case "sale-income":
                $saved = (int) get_option("mlm_medal_sale_income");
                return $saved ? $saved : 500000;
                break;
            case "ref-income":
                $saved = (int) get_option("mlm_medal_ref_income");
                return $saved ? $saved : 50000;
                break;
            case "valid-ref":
                $saved = (int) get_option("mlm_medal_valid_ref");
                return $saved ? $saved : 200;
                break;
            case "valid-post":
                $saved = (int) get_option("mlm_medal_valid_post");
                return $saved ? $saved : 5;
                break;
            case "valid-product":
                $saved = (int) get_option("mlm_medal_valid_product");
                return $saved ? $saved : 5;
                break;
            case "valid-subset":
                $saved = (int) get_option("mlm_medal_valid_subset");
                return $saved ? $saved : 5;
                break;
            case "valid-purchase":
                $saved = (int) get_option("mlm_medal_valid_purchase");
                return $saved ? $saved : 5;
                break;
            case "valid-withdraw":
                $saved = (int) get_option("mlm_medal_valid_withdraw");
                return $saved ? $saved : 5;
                break;
            case "valid-comment":
                $saved = (int) get_option("mlm_medal_valid_comment");
                return $saved ? $saved : 10;
                break;
            case "vip-product":
                $saved = (int) get_option("mlm_medal_vip_product");
                return $saved ? $saved : 1;
                break;
            default:
                return 0;
        }
    }
    public function get_medal_status($user_id, $item, $percent = false)
    {
        $value = 0;
        $minimum = $this->min_available_amount($item);
        switch ($item) {
            case "vendor":
                if (user_can($user_id, "mlm_vendor")) {
                    $value = 1;
                }
                break;
            case "subset-income":
                $value = mlmFire()->db->count_query_rows("SELECT SUM(amount) FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d", [$user_id, 3, 2], "wallet");
                $value = empty($value) ? 0 : floor($value / 10000) * 10000;
                break;
            case "sale-income":
                $value = mlmFire()->db->count_query_rows("SELECT SUM(amount) FROM {TABLE} WHERE user_id = %d", [$user_id], "wallet");
                $value = empty($value) ? 0 : floor($value / 100000) * 100000;
                break;
            case "ref-income":
                $value = mlmFire()->db->count_query_rows("SELECT SUM(amount) FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d", [$user_id, 2, 2], "wallet");
                $value = empty($value) ? 0 : floor($value / 10000) * 10000;
                break;
            case "valid-ref":
                $value = (int) get_user_meta($user_id, "mlm_count_refs", true);
                break;
            case "valid-post":
                $value = count_user_posts($user_id, "post");
                break;
            case "valid-product":
                $value = count_user_posts($user_id, "product");
                break;
            case "valid-subset":
                $value = mlmFire()->network->get_subs_count($user_id);
                break;
            case "valid-purchase":
                $value = wc_get_orders(["limit" => -1, "status" => ["completed"], "customer" => $user_id]);
                $value = count($value);
                break;
            case "valid-withdraw":
                $value = mlmFire()->db->count_query_rows("SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d", [$user_id, 5, 4], "wallet");
                break;
            case "valid-comment":
                global $wpdb;
                $value = $wpdb->get_var($wpdb->prepare("SELECT COUNT(comment_ID) FROM " . $wpdb->comments . " WHERE comment_approved = %d AND user_id = %d AND comment_type NOT IN ('pingback', 'trackback')", 1, $user_id));
                break;
            case "vip-product":
                $loop = new WP_Query(["post_type" => "product", "author" => $user_id, "post_status" => "publish", "numberposts" => -1, "tax_query" => [["taxonomy" => "product_visibility", "field" => "name", "terms" => "featured", "operator" => "IN"]]]);
                $value = $loop->found_posts;
                break;
            case "profile-ok":
                $value = mlmFire()->dashboard->get_profile_status($user_id);
                $value = 100 <= $value ? 1 : 0;
                break;
            case "account-ok":
                $value = 0;
                break;
            default:
                if ($percent) {
                    return $minimum <= $value ? 100 : floor($value / $minimum * 100);
                }
                if ($minimum <= $value) {
                    return $value;
                }
                return false;
        }
    }
    public function get_user_medals($user_id)
    {
        $medals = $this->get_medals();
        $user_medals = [];
        foreach ($medals as $medal) {
            $value = $this->get_medal_status($user_id, $medal);
            if ($value) {
                $user_medals[$medal] = ["value" => $value, "title" => $this->get_medal_title($medal, $value)];
            }
        }
        if (user_can($user_id, "administrator")) {
            unset($user_medals["sale-income"]);
            unset($user_medals["ref-income"]);
        }
        if (13 <= count($user_medals)) {
            update_user_meta($user_id, "mlm_verified", 1);
            $user_medals["account-ok"] = ["value" => 1, "title" => $this->get_medal_title("account-ok", 1)];
        } else {
            delete_user_meta($user_id, "mlm_verified");
        }
        return $user_medals;
    }
    public function print_user_medals($user_id, $class = "", $print = true)
    {
        $medals = $this->get_user_medals($user_id);
        if (empty($medals)) {
            return NULL;
        }
        $output = "<ul class=\"" . $class . "\">";
        foreach ($medals as $key => $value) {
            $output .= "<li class=\"nav-item\">";
            $output .= "<span class=\"nav-link text-dark py-1 px-2 m-1 medal medal-" . $key . "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"" . $value["title"] . "\"></span>";
            $output .= "</li>";
        }
        $output .= "</ul>";
        if ($print) {
            echo $output;
        }
        return $output;
    }
    public function product_medals()
    {
        return ["featured", "free", "license", "original", "sale", "iran"];
    }
    public function get_product_medal_title($item)
    {
        switch ($item) {
            case "featured":
                return __("Featured product", "mlm");
                break;
            case "free":
                return __("Free product", "mlm");
                break;
            case "iran":
                return __("Made in Iran", "mlm");
                break;
            case "license":
                return __("Licensed product", "mlm");
                break;
            case "original":
                return __("Original product", "mlm");
                break;
            case "sale":
                return __("Best sale product", "mlm");
                break;
            default:
                return false;
        }
    }
    public function get_product_medals($post_id)
    {
        $medals = $this->product_medals();
        $post_medals = [];
        foreach ($medals as $medal) {
            $value = get_post_meta($post_id, "mlm_medal_" . $medal, true);
            if ($value) {
                $post_medals[$medal] = ["value" => $value, "title" => $this->get_product_medal_title($medal)];
            }
        }
        return $post_medals;
    }
    public function print_product_medals($post_id, $class = "", $print = true)
    {
        $medals = $this->get_product_medals($post_id);
        if (empty($medals)) {
            return NULL;
        }
        $output = "<ul class=\"" . $class . "\">";
        foreach ($medals as $key => $value) {
            $output .= "<li class=\"nav-item\">";
            $output .= "<span class=\"nav-link text-dark py-1 px-2 m-1 medal product-medal medal-" . $key . "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"" . $value["title"] . "\"></span>";
            $output .= "</li>";
        }
        $output .= "</ul>";
        if ($print) {
            echo $output;
        }
        return $output;
    }
}

?>