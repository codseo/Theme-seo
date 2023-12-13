<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Wallet
{
    public function __construct()
    {
    }
    public function get_balance($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        return (int) get_user_meta($user_id, "mlm_balance", true);
    }
    public function get_site_balance()
    {
        return (int) get_option("mlm_total_balance");
    }
    public function min_withdraw_amount()
    {
        return (int) get_option("mlm_min_cash");
    }
    public function get_site_rate($post_id = NULL)
    {
        $gb_value = (int) get_option("mlm_rate");
        $sp_value = get_post_meta($post_id, "mlm_site_ref", true);
        if ($gb_value < 0 || 100 < $gb_value) {
            $gb_value = 0;
        }
        if (!mlm_post_exists($post_id) || empty($sp_value) && !is_numeric($sp_value)) {
            return $gb_value;
        }
        $sp_value = intval($sp_value);
        if ($sp_value < 0 || 100 < $sp_value) {
            return 0;
        }
        return $sp_value;
    }
    public function get_customer_rate($post_id = NULL)
    {
        $gb_value = (int) get_option("mlm_customer_rate");
        $sp_value = get_post_meta($post_id, "mlm_buyer_ref", true);
        if ($gb_value < 0 || 100 < $gb_value) {
            $gb_value = 0;
        }
        if (!mlm_post_exists($post_id) || empty($sp_value) && !is_numeric($sp_value)) {
            return $gb_value;
        }
        $sp_value = intval($sp_value);
        if ($sp_value < 0 || 100 < $sp_value) {
            return 0;
        }
        return $sp_value;
    }
    public function post_ref_rate($post_id = NULL)
    {
        if (!mlm_post_exists($post_id)) {
            return 0;
        }
        $value = (int) get_post_meta($post_id, "mlm_ref_value", true);
        if ($value < 0 || 100 < $value) {
            return 0;
        }
        return $value;
    }
    public function post_ref_amount($post_id = NULL)
    {
        if (!mlm_post_exists($post_id)) {
            return 0;
        }
        $percent = $this->post_ref_rate($post_id);
        $price = mlm_get_product_price($post_id);
        $ref_amount = ceil($price * $percent / 100 / 10) * 10;
        update_post_meta($post_id, "mlm_ref_amount", $ref_amount);
        return $ref_amount;
    }
    public function update_meta($user_id, $meta, $amount, $op = "plus")
    {
        if (!mlm_user_exists($user_id) || empty($meta) || empty($amount)) {
            return false;
        }
        $old = (int) get_user_meta($user_id, $meta, true);
        if ($op == "minus") {
            $new = $old - intval($amount);
        } else {
            $new = $old + intval($amount);
        }
        update_user_meta($user_id, $meta, $new);
        return true;
    }
    public function update_site_meta($amount, $op = "plus")
    {
        if (empty($amount) || $amount < 0) {
            return false;
        }
        $old = (int) get_option("mlm_total_balance");
        if ($op == "minus") {
            $new = $old - intval($amount);
        } else {
            $new = $old + intval($amount);
        }
        update_option("mlm_total_balance", $new);
        return true;
    }
    public function new_balance($user_id, $amount, $type, $status = "success")
    {
        if ($user_id == 0 && $type == 4) {
            $balance = $this->get_site_balance();
            if ($status == "fail") {
                return $balance - $amount;
            }
            return $balance + $amount;
        }
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $balance = $this->get_balance($user_id);
        switch ($type) {
            case 1:
            case 2:
            case 3:
            case 6:
                if ($status == "fail") {
                    return $balance - $amount;
                }
                return $balance + $amount;
                break;
            case 5:
            case 7:
            case 8:
                if ($status == "fail") {
                    return $balance + $amount;
                }
                return $balance - $amount;
                break;
            default:
                return 0;
        }
    }
    public function get_statuses($id = "")
    {
        $all = [1 => __("Pending", "mlm"), 2 => __("Succeed", "mlm"), 3 => __("Failed", "mlm"), 4 => __("Paid", "mlm")];
        if (empty($id)) {
            return $all;
        }
        return isset($all[$id]) ? $all[$id] : __("Other", "mlm");
    }
    public function get_status_text($status)
    {
        return $this->get_statuses($status);
    }
    public function get_types($id = "")
    {
        $all = [1 => __("Sale", "mlm"), 2 => __("Refer", "mlm"), 3 => __("Subset", "mlm"), 4 => __("Site share", "mlm"), 5 => __("Withdraw", "mlm"), 6 => __("Charge", "mlm"), 7 => __("Decrease", "mlm"), 8 => __("Purchase", "mlm"), 9 => __("Course sale", "mlm")];
        if (empty($id)) {
            return $all;
        }
        return isset($all[$id]) ? $all[$id] : __("Other", "mlm");
    }
    public function get_type_class($type)
    {
        if (in_array($type, [5, 7, 8])) {
            return "danger";
        }
        return "success";
    }
    public function get_type_sign($type)
    {
        if (in_array($type, [5, 7, 8])) {
            return "-";
        }
        return "+";
    }
    public function get_type_text($type)
    {
        return $this->get_types($type);
    }
    public function get_total_sales_count()
    {
        return mlmFire()->db->count_query_rows("SELECT COUNT(id) FROM {TABLE} WHERE type = %d AND status = %d", [1, 2], "wallet");
    }
    public function get_user_sales_count($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        return (int) get_user_meta($user_id, "mlm_count_sales", true);
    }
    public function total_withdraw_amount($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $result = mlmFire()->db->count_query_rows("SELECT SUM(amount) AS Amount FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d", [$user_id, 5, 4], "wallet", true);
        return intval($result);
    }
    public function recent_withdraw_amount($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $result = mlmFire()->db->count_query_rows("SELECT amount FROM {TABLE} WHERE user_id = %d AND type = %d ORDER BY id DESC LIMIT %d", [$user_id, 5, 1], "wallet", true);
        return intval($result);
    }
    public function total_income_amount($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $result = mlmFire()->db->count_query_rows("SELECT SUM(amount) AS Amount FROM {TABLE} WHERE user_id = %d AND status = %d AND ( type = %d || type = %d || type = %d || type = %d )", [$user_id, 2, 1, 2, 3, 6], "wallet", true);
        return intval($result);
    }
}

?>