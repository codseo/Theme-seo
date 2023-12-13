<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Referral
{
    public function __construct()
    {
        add_action("template_redirect", [$this, "catch_referreal_code"], 1);
        add_action("woocommerce_order_status_changed", [$this, "calculate_profits"], 99, 3);
        add_action("woocommerce_order_status_changed", [$this, "status_changed"], 99, 3);
    }
    public function generate_ref_code($user_id = 0)
    {
        if (empty($user_id)) {
            $user_id = get_current_user_id();
        }
        if (!mlm_user_exists($user_id)) {
            return NULL;
        }
        $ref = get_user_meta($user_id, "mlm_ref_code", true);
        if (empty($ref)) {
            $ref = $user_id;
            update_user_meta($user_id, "mlm_ref_code", $ref);
        }
        return urlencode($ref);
    }
    public function add_ref_to_url($url = NULL)
    {
        if (!is_user_logged_in()) {
            return NULL;
        }
        if (empty($url)) {
            $url = home_url("/");
        }
        return add_query_arg(["ref" => $this->generate_ref_code()], $url);
    }
    public function get_ref_for_url($url = "")
    {
        if (!is_user_logged_in() || empty($url)) {
            return NULL;
        }
        return add_query_arg(["ref" => $this->generate_ref_code()], $url);
    }
    public function get_userid_by_ref($ref_id = NULL)
    {
        if ($ref_id === NULL) {
            return false;
        }
        global $wpdb;
        if (mlm_is_mobile($ref_id)) {
            $user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key = %s AND meta_value = %s;", "mlm_mobile", $ref_id));
        } else {
            $user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key = %s AND meta_value = %s;", "mlm_ref_code", $ref_id));
        }
        if ($user_id === NULL) {
            return false;
        }
        return intval($user_id);
    }
    public function get_refs_count($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        return (int) get_user_meta($user_id, "mlm_count_refs", true);
    }
    public function get_current_page_url()
    {
        $pageURL = "http";
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    private function referral_install_data($ref_user_id, $user_id, $user_ip, $user_url, $user_host)
    {
        if (empty($user_ip) || !mlm_user_exists($ref_user_id)) {
            return false;
        }
        $invalid = 0;
        $string = "SELECT id FROM {TABLE} WHERE user_ip = %s LIMIT %d";
        $values = [$user_ip, 1];
        $ip_check = mlmFire()->db->query_rows($string, $values, "referral", true);
        if (!empty($ip_check) && isset($ip_check->id)) {
            mlmFire()->db->refer_update($ip_check->id, ["invalid" => 1]);
        }
        if ($user_id == $ref_user_id) {
            $invalid = 1;
        }
        return mlmFire()->db->refer_record($ref_user_id, $user_id, $user_ip, $user_url, $user_host, $invalid);
    }
    public function catch_referreal_code()
    {
        if (!isset($_GET["ref"]) || empty($_GET["ref"])) {
            return NULL;
        }
        $ref_id = urldecode($_GET["ref"]);
        $ref_user_id = $this->get_userid_by_ref($ref_id);
        if (!$ref_user_id) {
            return NULL;
        }
        $user_id = get_current_user_id();
        $user_ip = $this->get_user_ip();
        $user_url = $this->get_current_page_url();
        $user_host = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
        setcookie("mlm_bazaryab_cookie", $ref_user_id, time() + 604800, "/");
        $result = $this->referral_install_data($ref_user_id, $user_id, $user_ip, $user_url, $user_host);
        if (!$result) {
            return NULL;
        }
        setcookie("mlm_referral_cookie", $result, time() + 604800, "/");
        wp_redirect(remove_query_arg("ref"));
        exit;
    }
    public function order_referrer_id($payment_id, $user_ip)
    {
        if (empty($payment_id)) {
            return false;
        }
        $user_id = 0;
        $cookie_id = isset($_COOKIE["mlm_referral_cookie"]) ? absint($_COOKIE["mlm_referral_cookie"]) : 0;
        if ($cookie_id) {
            $result = mlmFire()->db->query_rows("SELECT id, ref_user_id FROM {TABLE} WHERE id = %d AND purchase = %d LIMIT %d", [$cookie_id, 0, 1], "referral", true);
            if (!empty($result)) {
                $user_id = $result->ref_user_id;
                mlmFire()->db->refer_update($result->id, ["purchase" => 1]);
            }
        }
        if ($user_id == 0) {
            if (empty($user_ip)) {
                return false;
            }
            $result = mlmFire()->db->query_rows("SELECT id, ref_user_id FROM {TABLE} WHERE user_ip = %s AND purchase = %d LIMIT %d", [$user_ip, 0, 1], "referral", true);
            if (!empty($result)) {
                $user_id = $result->ref_user_id;
                mlmFire()->db->refer_update($result->id, ["purchase" => 1]);
            }
        }
        return $user_id;
    }
    public function calculate_profits($order_id, $old_status, $new_status)
    {
        if ($new_status != "completed") {
            return $order_id;
        }
        $order_object = new WC_Order($order_id);
        $order_details = $order_object->get_data();
        $customer_ip = $order_details["customer_ip_address"];
        $customer_id = $order_object->get_user_id();
        $referrer_id = $this->order_referrer_id($order_id, $customer_ip);
        $cart_items = $order_object->get_items();
        $customer_profit = 0;
        $flag = false;
        foreach ($cart_items as $item) {
            $product_id = (int) $item["product_id"];
            $vendor_id = get_post_field("post_author", $product_id);
            $ref_percent = mlmFire()->wallet->post_ref_rate($product_id);
            $site_percent = mlmFire()->wallet->get_site_rate($product_id);
            $sum = $ref_percent + $site_percent;
            if (100 < $sum) {
                $ref_percent = $sum - 100;
            }
            $price = (int) $item["total"];
            if (0 < $price) {
                $site_profit = $price * $site_percent / 100;
                $refer_profit = $price * $ref_percent / 100;
                $vendor_profit = $price - $site_profit - $refer_profit;
                if (mlm_user_exists($referrer_id)) {
                    mlmFire()->db->wallet_record($referrer_id, $product_id, $order_id, $refer_profit, 2, 2, sprintf(__("Order %s referral share", "mlm"), $order_id));
                    mlmFire()->wallet->update_meta($referrer_id, "mlm_balance", $refer_profit);
                    mlmFire()->wallet->update_meta($referrer_id, "mlm_count_refs", 1);
                } else {
                    $vendor_profit = $vendor_profit + $refer_profit;
                }
                if (mlm_check_course($product_id)) {
                    $v_type = 9;
                    $v_desc = sprintf(__("Order %s course sale", "mlm"), $order_id);
                } else {
                    $v_type = 1;
                    $v_desc = sprintf(__("Order %s product sale", "mlm"), $order_id);
                }
                mlmFire()->db->wallet_record($vendor_id, $product_id, $order_id, $vendor_profit, $v_type, 2, $v_desc);
                mlmFire()->wallet->update_meta($vendor_id, "mlm_balance", $vendor_profit);
                mlmFire()->wallet->update_meta($vendor_id, "mlm_count_sales", 1);
                if (mlm_user_exists($referrer_id)) {
                    mlmFire()->network->calc_sub_shares($referrer_id, $site_profit, $order_id, $product_id);
                } else {
                    mlmFire()->network->calc_sub_shares($customer_id, $site_profit, $order_id, $product_id);
                }
                mlmFire()->notif->send_user_sms($vendor_id, "new_sale", ["post_id" => $product_id, "order_id" => $order_id, "total" => $order_object->get_total(), "customer" => $order_object->get_billing_first_name() . " " . $order_object->get_billing_last_name()]);
                $flag = true;
            }
        }
        if (mlm_user_exists($customer_id) && $flag) {
            $customer_rate = mlmFire()->wallet->get_customer_rate($product_id);
            $cust_profit = intval($order_object->get_total()) * $customer_rate / 100;
            $customer_profit = $customer_profit + $cust_profit;
        }
        if (0 < $customer_profit) {
            mlmFire()->db->wallet_record($customer_id, 0, $order_id, $customer_profit, 6, 2, sprintf(__("Order %s gift for purchase", "mlm"), $order_id));
            mlmFire()->wallet->update_meta($customer_id, "mlm_balance", $customer_profit);
        }
        if (mlm_user_exists($customer_id)) {
            mlmFire()->notif->send_user_sms($customer_id, "new_purchase", ["order_id" => $order_id]);
        }
        return $order_id;
    }
    public function status_changed($order_id, $old_status, $new_status)
    {
        if ($old_status != "completed") {
            return $order_id;
        }
        if ($new_status == "completed") {
            return $order_id;
        }
        $transactions = mlmFire()->db->query_rows("SELECT id, user_id, amount, type FROM {TABLE} WHERE order_id = %d AND status = %s", [$order_id, 2], "wallet");
        if (empty($transactions)) {
            return $order_id;
        }
        $order_object = new WC_Order($order_id);
        $customer_id = $order_object->get_user_id();
        foreach ($transactions as $trans) {
            if ($trans->type == 4 && 0 < $trans->amount) {
                mlmFire()->wallet->update_site_meta($trans->amount, "minus");
            } else {
                if ($trans->type == 3 && 0 < $trans->amount) {
                    mlmFire()->wallet->update_meta($trans->user_id, "mlm_balance", $trans->amount, "minus");
                } else {
                    if ($trans->type == 2 && 0 < $trans->amount) {
                        mlmFire()->wallet->update_meta($trans->user_id, "mlm_balance", $trans->amount, "minus");
                        mlmFire()->wallet->update_meta($trans->user_id, "mlm_count_refs", 1, "minus");
                    } else {
                        if (($trans->type == 1 || $trans->type == 9) && 0 < $trans->amount) {
                            mlmFire()->wallet->update_meta($trans->user_id, "mlm_balance", $trans->amount, "minus");
                            mlmFire()->wallet->update_meta($trans->user_id, "mlm_count_sales", 1, "minus");
                        } else {
                            if ($trans->type == 6 && 0 < $trans->amount) {
                                mlmFire()->wallet->update_meta($trans->user_id, "mlm_balance", $trans->amount, "minus");
                            } else {
                                if ($trans->type == 8 && 0 < $trans->amount) {
                                    mlmFire()->wallet->update_meta($trans->user_id, "mlm_balance", $trans->amount);
                                }
                            }
                        }
                    }
                }
            }
            mlmFire()->db->wallet_update($trans->id, ["status" => 3]);
        }
        return $order_id;
    }
    private function get_user_ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"]) && $this->validate_ip($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            if (strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ",") !== false) {
                $iplist = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
                foreach ($iplist as $ip) {
                    if ($this->validate_ip($ip)) {
                        return $ip;
                    }
                }
            } else {
                if ($this->validate_ip($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    return $_SERVER["HTTP_X_FORWARDED_FOR"];
                }
            }
        }
        if (!empty($_SERVER["HTTP_X_FORWARDED"]) && $this->validate_ip($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        if (!empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && $this->validate_ip($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
            return $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
        }
        if (!empty($_SERVER["HTTP_FORWARDED_FOR"]) && $this->validate_ip($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        if (!empty($_SERVER["HTTP_FORWARDED"]) && $this->validate_ip($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        }
        return $_SERVER["REMOTE_ADDR"];
    }
    private function validate_ip($ip)
    {
        if (strtolower($ip) === "unknown") {
            return false;
        }
        $ip = ip2long($ip);
        if ($ip !== false && $ip !== -1) {
            $ip = sprintf("%u", $ip);
            if (0 <= $ip && $ip <= 50331647) {
                return false;
            }
            if (167772160 <= $ip && $ip <= 184549375) {
                return false;
            }
            if (2130706432 <= $ip && $ip <= 2147483647) {
                return false;
            }
            if (0 <= $ip && $ip <= 0) {
                return false;
            }
            if (0 <= $ip && $ip <= 0) {
                return false;
            }
            if (0 <= $ip && $ip <= 0) {
                return false;
            }
            if (0 <= $ip && $ip <= 0) {
                return false;
            }
            if (0 <= $ip) {
                return false;
            }
        }
        return true;
    }
}

?>