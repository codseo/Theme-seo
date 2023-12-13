<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

add_filter("woocommerce_available_payment_gateways", "mlm_available_payment_gateways");
if (!function_exists("mlm_available_payment_gateways")) {
    function mlm_available_payment_gateways($gateways)
    {
        global $wp;
        $order_id = isset($wp->query_vars["order-pay"]) ? $wp->query_vars["order-pay"] : '';
        if (!empty($order_id)) {
            $order = wc_get_order($order_id);
            $flag = get_post_meta($order_id, "_mlm_charge", true);
            if ($flag && $order->has_status("pending")) {
                unset($gateways["cod"]);
                unset($gateways["mlm_wallet"]);
            }
        }
        if (!is_user_logged_in()) {
            unset($gateways["mlm_wallet"]);
        }
        return $gateways;
    }
}

add_action("woocommerce_payment_complete", "mlm_increase_balance_after_payment");
if (!function_exists("mlm_increase_balance_after_payment")) {
    function mlm_increase_balance_after_payment($order_id)
    {
        $order = wc_get_order($order_id);
        $flag = get_post_meta($order_id, "_mlm_charge", true);
        if ($flag) {
            $amount = $order->get_total();
            $user_obj = $order->get_user();
            mlmFire()->db->wallet_record($user_obj->ID, 0, $order_id, $amount, 6, 2, sprintf(__("Charge wallet by order %s", "mlm"), $order_id));
            mlmFire()->wallet->update_meta($user_obj->ID, "mlm_balance", $amount);
        }
    }
}
add_action("woocommerce_order_status_changed", "mlm_activate_subscribtion_after_payment", 99, 3);
if (!function_exists("mlm_activate_subscribtion_after_payment")) {
    function mlm_activate_subscribtion_after_payment($order_id, $old_status, $new_status)
    {
        if ($new_status != "completed") {
            return $order_id;
        }
        $order = wc_get_order($order_id);
        $flag = get_post_meta($order_id, "_mlm_subscribtion", true);
        $plan_id = (int) get_post_meta($order_id, "_mlm_plan_id", true);
        if ($flag) {
            $amount = $order->get_total();
            $user_obj = $order->get_user();
            $plan_data = mlmFire()->plan->get_plans($plan_id);
            $trans_id = mlmFire()->db->wallet_record($user_obj->ID, 0, $order_id, $amount, 8, 2, __("Purchase subscription plan", "mlm"));
            $sub_id = mlmFire()->db->subscribe_record($user_obj->ID, $order_id, $plan_data);
            if ($sub_id) {
                mlmFire()->db->subscribe_update($sub_id, ["status" => 1]);
                mlmFire()->plan->set_user_active_plan($sub_id, $user_obj->ID, $plan_data["id"]);
                mlmFire()->notif->send_user_sms($user_obj->ID, "plan_activated", ["plan_id" => $plan_data["id"], "plan_name" => $plan_data["name"]]);
            }
        }
        return $order_id;
    }
}
if (class_exists("WC_Payment_Gateway") && !class_exists("MLM_Wallet_Gateway")) {
    add_filter("woocommerce_payment_gateways", "mlm_register_wallet_gateway");
    if (!function_exists("mlm_register_wallet_gateway")) {
        function mlm_register_wallet_gateway($gateways)
        {
            $gateways[] = "MLM_Wallet_Gateway";
            return $gateways;
        }
    }
    add_action("init", "mlm_wallet_class_init", 11);
    if (!function_exists("mlm_wallet_class_init")) {
        function mlm_wallet_class_init()
        {
            class MLM_Wallet_Gateway extends WC_Payment_Gateway
            {
                public function __construct()
                {
                    $this->id = "mlm_wallet";
                    $this->icon = IMAGES . "/wallet.png";
                    $this->has_fields = false;
                    $this->method_title = __("MarketMLM wallet", "mlm");
                    $this->method_description = __("Payment with user wallet", "mlm");
                    $this->init_form_fields();
                    $this->init_settings();
                    $this->title = $this->get_option("title");
                    $this->description = $this->get_option("description");
                    $this->instructions = $this->get_option("instructions", $this->description);
                    $order_total = @$this->get_order_total();
                    $user_balance = mlmFire()->wallet->get_balance(get_current_user_id());
                    $wallet_url = trailingslashit(mlm_page_url("panel")) . "section/wallet/";
                    if ($user_balance < floatval($order_total)) {
                        $this->description = __("Your balance is not enough to checkout.", "mlm") . " <a href=\"" . $wallet_url . "\" class=\"btn btn-primary font-10 py-0\">" . __("Charge wallet", "mlm") . "</a>";
                    }
                    add_action("woocommerce_update_options_payment_gateways_" . $this->id, [$this, "process_admin_options"]);
                    add_action("woocommerce_thankyou_" . $this->id, [$this, "thankyou_page"]);
                    add_action("woocommerce_email_before_order_table", [$this, "email_instructions"], 10, 3);
                }
                public function init_form_fields()
                {
                    $this->form_fields = apply_filters("wc_mlm_wallet_form_fields", ["enabled" => ["title" => __("Enable/Disable", "woocommerce"), "type" => "checkbox", "label" => __("Enable MarketMLM wallet", "mlm"), "default" => "yes"], "title" => ["title" => __("Title", "woocommerce"), "type" => "text", "description" => __("Wallet title on checkout page", "mlm"), "default" => __("Wallet", "mlm"), "desc_tip" => true], "description" => ["title" => __("Description", "woocommerce"), "type" => "textarea", "description" => __("Payment method description that the customer will see on your checkout.", "woocommerce"), "default" => __("You can complete the order with your wallet.", "mlm"), "desc_tip" => true], "instructions" => ["title" => __("Instructions", "woocommerce"), "type" => "textarea", "description" => __("Instructions that will be added to the thank you page and emails.", "woocommerce"), "default" => __("Order paid successfully with your wallet.", "mlm"), "desc_tip" => true]]);
                }
                public function thankyou_page()
                {
                    if ($this->instructions) {
                        echo wpautop(wptexturize($this->instructions));
                    }
                }
                public function email_instructions($order, $sent_to_admin, $plain_text = false)
                {
                    if ($this->instructions && !$sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status("on-hold")) {
                        echo wpautop(wptexturize($this->instructions)) . PHP_EOL;
                    }
                }
                public function process_payment($order_id)
                {
                    $order = wc_get_order($order_id);
                    $user_obj = $order->get_user();
                    $total_amount = $order->get_total();
                    $user_balance = mlmFire()->wallet->get_balance($user_obj->ID);
                    if ($user_balance <= 0 || $user_balance < $total_amount) {
                        wc_add_notice(__("Your balance is not enough to checkout.", "mlm"), "error");
                    } else {
                        mlmFire()->db->wallet_record($user_obj->ID, 0, $order_id, $total_amount, 8, 2, sprintf(__("Order %s purchased by wallet balance", "mlm"), $order_id));
                        mlmFire()->wallet->update_meta($user_obj->ID, "mlm_balance", $total_amount, "minus");
                        $virtual_order = true;
                        if (0 < count($order->get_items())) {
                            foreach ($order->get_items() as $item) {
                                if ("line_item" == $item["type"]) {
                                    $_product = $item->get_product();
                                    if (!$_product->is_virtual()) {
                                        $virtual_order = false;
                                    }
                                }
                            }
                        }
                        if ($virtual_order) {
                            $order->update_status("completed", __("Payment completed", "mlm"), true);
                        } else {
                            $order->update_status("processing", __("Payment completed", "mlm"), true);
                        }
                        wc_reduce_stock_levels($order_id);
                        WC()->cart->empty_cart();
                        return ["result" => "success", "redirect" => $this->get_return_url($order)];
                    }
                }
            }
        }
    }
}

?>