<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Network
{
    public function __construct()
    {
        add_action("deleted_user", [$this, "delete_user_from_network"]);
    }
    public function get_subs_count($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        return mlmFire()->db->count_query_rows("SELECT COUNT(id) FROM {TABLE} WHERE parent_id = %d", [$user_id], "network");
    }
    public function add_user_to_network($user_id, $parent_id)
    {
        $this->delete_user_from_network($user_id);
        return mlmFire()->db->network_record($user_id, $parent_id);
    }
    public function delete_user_from_network($user_id)
    {
        $id = mlmFire()->db->count_query_rows("SELECT id FROM {TABLE} WHERE user_id = %d LIMIT %d", [$user_id, 1], "network");
        if (!$id) {
            return false;
        }
        return mlmFire()->db->network_delete($id);
    }
    public function get_user_parent($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        return mlmFire()->db->count_query_rows("SELECT parent_id FROM {TABLE} WHERE user_id = %d LIMIT %d", [$user_id, 1], "network");
    }
    public function get_sub_rate($step, $post_id = NULL)
    {
        $gb_value = (int) get_option("mlm_sub_" . $step);
        $sp_value = get_post_meta($post_id, "mlm_zir_ref" . $step, true);
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
    public function calc_sub_shares($user_id, $amount, $order_id, $product_id = 0, $step = 1, $main_amount = false)
    {
        if (empty($amount) || empty($order_id)) {
            return false;
        }
        $parent_id = $this->get_user_parent($user_id);
        if (!mlm_user_exists($parent_id) || 5 < $step) {
            mlmFire()->db->wallet_record(0, $product_id, $order_id, $amount, 4, 2, sprintf(__("Order %s site share", "mlm"), $order_id));
            mlmFire()->wallet->update_site_meta($amount);
            return true;
        }
        if (!$main_amount) {
            $main_amount = $amount;
        }
        $percent = $this->get_sub_rate($step, $product_id);
        $user_share = $percent * $main_amount / 100;
        if (0 < $user_share) {
            mlmFire()->db->wallet_record($parent_id, $product_id, $order_id, $user_share, 1, 3, sprintf(__("Order %s reagent share", "mlm"), $order_id));
            mlmFire()->wallet->update_meta($parent_id, "mlm_balance", $user_share);
        }
        $step++;
        $amount = $amount - $user_share;
        $this->calc_sub_shares($parent_id, $amount, $order_id, $product_id, $step, $main_amount);
    }
}

?>