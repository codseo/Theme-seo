<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Ajax
{
    public function __construct()
    {
        add_action("wp_ajax_mlm_login", [$this, "mlm_login"]);
        add_action("wp_ajax_nopriv_mlm_login", [$this, "mlm_login"]);
        add_action("wp_ajax_mlm_register", [$this, "mlm_register"]);
        add_action("wp_ajax_nopriv_mlm_register", [$this, "mlm_register"]);
        add_action("wp_ajax_mlm_lost_code", [$this, "send_lost_code"]);
        add_action("wp_ajax_nopriv_mlm_lost_code", [$this, "send_lost_code"]);
        add_action("wp_ajax_mlm_pass_code", [$this, "submit_lost_code"]);
        add_action("wp_ajax_nopriv_mlm_pass_code", [$this, "submit_lost_code"]);
        add_action("wp_ajax_mlm_new_pass", [$this, "mlm_reset_pass"]);
        add_action("wp_ajax_nopriv_mlm_new_pass", [$this, "mlm_reset_pass"]);
        add_action("wp_ajax_mlm_follow", [$this, "follow_vendor"]);
        add_action("wp_ajax_nopriv_mlm_follow", [$this, "follow_vendor"]);
        add_action("wp_ajax_mlm_add_to_cart", [$this, "add_product_to_cart"]);
        add_action("wp_ajax_nopriv_mlm_add_to_cart", [$this, "add_product_to_cart"]);
        add_action("wp_ajax_mlm_rating", [$this, "rate_post"]);
        add_action("wp_ajax_nopriv_mlm_rating", [$this, "rate_post"]);
        add_action("wp_ajax_mlm_record_ticket", [$this, "record_ticket_callback"]);
        add_action("wp_ajax_mlm_reply_ticket", [$this, "reply_ticket_callback"]);
        add_action("wp_ajax_mlm_delete_ticket", [$this, "delete_ticket_callback"]);
        add_action("wp_ajax_mlm_ticket_status", [$this, "change_ticket_status"]);
        add_action("wp_ajax_mlm_charge_wallet", [$this, "admin_charge_wallet"]);
        add_action("wp_ajax_mlm_bookmark", [$this, "bookmark_post"]);
        add_action("wp_ajax_nopriv_mlm_bookmark", [$this, "bookmark_post"]);
        add_action("wp_ajax_mlm_profile", [$this, "mlm_profile"]);
        add_action("wp_ajax_mlm_change_pass", [$this, "mlm_change_pass"]);
        add_action("wp_ajax_mlm_submit_post", [$this, "submit_post"]);
        add_action("wp_ajax_mlm_submit_product", [$this, "submit_product"]);
        add_action("wp_ajax_mlm_draft_product", [$this, "draft_product"]);
        add_action("wp_ajax_mlm_submit_course", [$this, "submit_course"]);
        add_action("wp_ajax_mlm_draft_course", [$this, "draft_course"]);
        add_action("wp_ajax_mlm_submit_physical", [$this, "submit_physical"]);
        add_action("wp_ajax_mlm_draft_physical", [$this, "draft_physical"]);
        add_action("wp_ajax_mlm_increase_balance", [$this, "increase_balance"]);
        add_action("wp_ajax_mlm_like_comment", [$this, "like_comment"]);
        add_action("wp_ajax_nopriv_mlm_like_comment", [$this, "like_comment"]);
        add_action("wp_ajax_mlm_withdrawal", [$this, "withdraw_request"]);
        add_action("wp_ajax_mlm_upgrade_one", [$this, "upgrade_step_one"]);
        add_action("wp_ajax_mlm_upgrade_two", [$this, "upgrade_step_two"]);
        add_action("wp_ajax_mlm_search", [$this, "ajax_search"]);
        add_action("wp_ajax_nopriv_mlm_search", [$this, "ajax_search"]);
        add_action("wp_ajax_mlm_parent", [$this, "submit_parent"]);
        add_action("wp_ajax_mlm_submit_coupon", [$this, "submit_coupon"]);
        add_action("wp_ajax_mlm_delete_coupon", [$this, "delete_coupon"]);
        add_action("wp_ajax_mlm_purchase_plan", [$this, "purchase_plan"]);
        add_action("wp_ajax_mlm_new_subscribe", [$this, "admin_new_subscribe"]);
        add_action("wp_ajax_mlm_activate", [$this, "activate_theme"]);
        add_action("wp_ajax_mlm_save_chapter", [$this, "save_chapter"]);
        add_action("wp_ajax_mlm_save_lesson", [$this, "save_lesson"]);
        add_action("wp_ajax_mlm_delete_chapter", [$this, "delete_chapter"]);
        add_action("wp_ajax_mlm_delete_lesson", [$this, "delete_lesson"]);
        add_action("wp_ajax_mlm_hide_notif", [$this, "hide_notification"]);
        add_action("wp_ajax_nopriv_mlm_hide_notif", [$this, "hide_notification"]);
        add_action("wp_ajax_mlm_save_fields", [$this, "save_fields"]);
        add_action("wp_ajax_mlm_delete_trans", [$this, "delete_trans_callback"]);
        add_action("wp_ajax_mlm_save_sms_texts", [$this, "save_sms_texts"]);
        add_action("wp_ajax_mlm_save_patterns", [$this, "save_patterns"]);
        add_action("wp_ajax_mlm_save_mail_texts", [$this, "save_mail_texts"]);
        add_action("wp_ajax_mlm_upload", [$this, "upload_file"]);
        add_action("wp_ajax_mlm_attach", [$this, "upload_attach_file"]);
        add_action("wp_ajax_mlm_send_mobile_code", [$this, "send_mobile_code"]);
        add_action("wp_ajax_mlm_send_email_code", [$this, "send_email_code"]);
        add_action("wp_ajax_mlm_verify_mobile", [$this, "verify_mobile"]);
        add_action("wp_ajax_mlm_verify_email", [$this, "verify_email"]);
    }
    public function mlm_login()
    {
        check_ajax_referer("mlm_lavinap", "security");
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $login = isset($user_data["login"]) ? sanitize_text_field($user_data["login"]) : "";
        $pass = isset($user_data["pass"]) ? sanitize_text_field($user_data["pass"]) : "";
        $remember = isset($user_data["remember"]) ? sanitize_text_field($user_data["remember"]) : true;
        $return = isset($user_data["return"]) ? esc_url($user_data["return"]) : "";
        $mustReturn = isset($user_data["must_return"]) ? sanitize_text_field($user_data["must_return"]) : "";
        $registered = false;
        $redirect = false;
        if (empty($login) || empty($pass)) {
            $response = __("User login and password are required.", "mlm");
        } else {
            $info = [];
            $info["user_login"] = mlm_get_user_by_mobile($login);
            $info["user_password"] = $pass;
            $info["remember"] = $remember;
            $is_ssl = is_ssl() ? true : false;
            $user_signon = wp_signon($info, $is_ssl);
            if (is_wp_error($user_signon)) {
                $errors = $user_signon->get_error_codes();
                if (is_array($errors) && in_array("invalid_captcha", $errors)) {
                    $response = $user_signon->get_error_message("invalid_captcha");
                } else {
                    $response = __("Invalid login or password.", "mlm");
                }
            } else {
                wp_set_current_user($user_signon->ID);
                $response = __("Signed in successfully. Loading ...", "mlm");
                $registered = true;
                if (function_exists("WC") && !WC()->cart->is_empty()) {
                    $redirect = wc_get_checkout_url();
                } else {
                    if ($mustReturn == "yes") {
                        $redirect = $return;
                    } else {
                        $redirect = get_dashboard_url();
                    }
                }
            }
        }
        echo wp_send_json(["redirect" => $redirect, "registered" => $registered, "response" => $response]);
        wp_die();
    }
    public function mlm_register()
    {
        check_ajax_referer("mlm_lavinap", "security");
        $registered = false;
        $redirect = false;
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $uname = isset($user_data["uname"]) ? sanitize_text_field($user_data["uname"]) : "";
        $email = isset($user_data["email"]) ? sanitize_text_field($user_data["email"]) : "";
        $mobile = isset($user_data["mobile"]) ? sanitize_text_field($user_data["mobile"]) : "";
        $country_code = isset($user_data["country_code"]) ? sanitize_text_field($user_data["country_code"]) : "";
        $pass = isset($user_data["pass"]) ? sanitize_text_field($user_data["pass"]) : "";
        $code = isset($user_data["code"]) ? sanitize_text_field($user_data["code"]) : "";
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        $return = isset($user_data["return"]) ? esc_url($user_data["return"]) : "";
        $mustReturn = isset($user_data["must_return"]) ? sanitize_text_field($user_data["must_return"]) : "";
        if (!mlmFire()->dashboard->verify_recaptcha($captcha)) {
            $response = __("You couldn't pass the captcha test. please reload the page and try again.", "mlm");
        } else {
            if (empty($uname) || empty($pass)) {
                $response = __("Marked fields are required.", "mlm");
            } else {
                if (empty($email) && mlmFire()->dashboard->is_email_required()) {
                    $response = __("Marked fileds are required", "mlm");
                } else {
                    if (empty($code) && mlmFire()->dashboard->is_code_enabled() && mlmFire()->dashboard->is_code_required()) {
                        $response = __("Reagent code is required.", "mlm");
                    } else {
                        if (!preg_match("/^[a-zA-Z0-9_-]+\$/", $uname)) {
                            $response = __("Only latin characters and numbers are allowed for login field.", "mlm");
                        } else {
                            if (username_exists($uname) || strlen($uname) < 3) {
                                $response = __("User login already reserved. Please try another one.", "mlm");
                            } else {
                                if (!is_email($email) && (mlmFire()->dashboard->is_email_required() || !empty($email))) {
                                    $response = __("Email address is not valid.", "mlm");
                                } else {
                                    if (email_exists($email) && (mlmFire()->dashboard->is_email_required() || !empty($email))) {
                                        $response = __("Email address already registered.", "mlm");
                                    } else {
                                        if (mlm_mobile_exists($mobile)) {
                                            $response = __("Mobile number already registered.", "mlm");
                                        } else {
                                            if (strlen($pass) < 7) {
                                                $response = __("Passowrd must have at least 7 characters.", "mlm");
                                            } else {
                                                $user_data = ["user_login" => $uname, "user_pass" => $pass, "display_name" => $uname, "nickname" => $uname];
                                                if (!mlmFire()->dashboard->is_email_disabled() && !empty($email)) {
                                                    $user_data["user_email"] = $email;
                                                }
                                                $user_id = wp_insert_user($user_data);
                                                if (is_wp_error($user_id)) {
                                                    $response = __("Unknown error occurred. Please try again.", "mlm");
                                                } else {
                                                    update_user_meta($user_id, "mlm_mobile", $mobile);
                                                    update_user_meta($user_id, "country_code", $country_code);
                                                    if (!empty($code)) {
                                                        $parent_id = mlmFire()->referral->get_userid_by_ref($code);
                                                        mlmFire()->network->add_user_to_network($user_id, $parent_id);
                                                    }
                                                    $response = __("Registered successfully. Loading ...", "mlm");
                                                    $registered = true;
                                                    $info = [];
                                                    $info["user_login"] = $uname;
                                                    $info["user_password"] = $pass;
                                                    $info["remember"] = "forever";
                                                    $is_ssl = is_ssl() ? true : false;
                                                    $user_signon = wp_signon($info, $is_ssl);
                                                    if (is_wp_error($user_signon)) {
                                                        $redirect = mlm_page_url("login");
                                                        $redirect = add_query_arg("registered", "true", $redirect);
                                                    } else {
                                                        if (function_exists("WC") && !WC()->cart->is_empty()) {
                                                            $redirect = wc_get_checkout_url();
                                                        } else {
                                                            if ($mustReturn == "yes") {
                                                                $redirect = $return;
                                                            } else {
                                                                $redirect = get_dashboard_url();
                                                            }
                                                        }
                                                    }
                                                    mlmFire()->notif->send_user_sms($user_id, "register", ["user_name" => $uname, "password" => $pass]);
                                                    mlmFire()->notif->send_user_mail($user_id, "register", ["user_name" => $uname, "password" => $pass]);
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
        echo wp_send_json(["redirect" => $redirect, "registered" => $registered, "response" => $response]);
        wp_die();
    }
    public function send_lost_code()
    {
        check_ajax_referer("mlm_activate_nemab", "security");
        $submited = false;
        $user_login = isset($_POST["login"]) ? sanitize_text_field($_POST["login"]) : "";
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        $user_login = mlm_get_user_by_mobile($user_login);
        if (empty($user_login)) {
            $response = __("Login, email or mobile is required.", "mlm");
        } else {
            if (!username_exists($user_login) && !email_exists($user_login)) {
                $response = __("We couldn't find any user with the given login, email or mobile.", "mlm");
            } else {
                if (is_email($user_login) && email_exists($user_login)) {
                    $user_obj = get_user_by("email", $user_login);
                } else {
                    $user_obj = get_user_by("login", $user_login);
                }
                $now = time();
                $db_code = get_transient("mlm_lost_" . $user_obj->ID);
                $db_time = get_transient("mlm_time_" . $user_obj->ID);
                if (!empty($db_time)) {
                    $diff = $now - absint($db_time);
                } else {
                    $diff = 200;
                }
                if ($diff < 120) {
                    $response = __("Verification code sent. please wait for a few minutes and then try again.", "mlm");
                } else {
                    $code = rand(10000, 99999);
                    $mobile = get_user_meta($user_obj->ID, "mlm_mobile", true);
                    $result = false;
                    if (mlm_is_mobile($mobile) && mlm_sms_is_active()) {
                        $result = mlmFire()->notif->send_user_sms($user_obj->ID, "lost_code", ["code" => $code]);
                        $response = __("Verification code sent to your mobile number.", "mlm");
                    }
                    if (!$result) {
                        $result = mlmFire()->notif->send_user_mail($user_obj->ID, "lost_code", ["code" => $code]);
                        $response = __("Verification code sent to your email address.", "mlm");
                    }
                    if ($result) {
                        set_transient("mlm_lost_" . $user_obj->ID, $code, HOUR_IN_SECONDS);
                        set_transient("mlm_time_" . $user_obj->ID, $now, HOUR_IN_SECONDS);
                        $submited = true;
                    } else {
                        $response = __("Sending verification code failed. Please try again.", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function submit_lost_code()
    {
        check_ajax_referer("mlm_activate_nemab", "security");
        $submited = false;
        $redirect = false;
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $code = isset($user_data["code"]) ? absint($user_data["code"]) : "";
        $user_login = isset($user_data["login"]) ? sanitize_text_field($user_data["login"]) : "";
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        $user_login = mlm_get_user_by_mobile($user_login);
        $db_code = "";
        if (is_email($user_login) && email_exists($user_login)) {
            $user_obj = get_user_by("email", $user_login);
            $db_code = (int) get_transient("mlm_lost_" . $user_obj->ID);
        } else {
            if (username_exists($user_login)) {
                $user_obj = get_user_by("login", $user_login);
                $db_code = (int) get_transient("mlm_lost_" . $user_obj->ID);
            }
        }
        if (empty($user_login)) {
            $response = __("Login, email or mobile is required.", "mlm");
        } else {
            if (!username_exists($user_login) && !email_exists($user_login)) {
                $response = __("We couldn't find any user with the given login, email or mobile.", "mlm");
            } else {
                if (empty($code)) {
                    $response = __("Verification code is required.", "mlm");
                } else {
                    if (empty($db_code)) {
                        $response = __("You didn't received the verification code. Tap on the send code button first.", "mlm");
                    } else {
                        if ($code !== $db_code) {
                            $response = __("Verification code isn't valid.", "mlm");
                        } else {
                            delete_transient("mlm_lost_" . $user_obj->ID);
                            delete_transient("mlm_time_" . $user_obj->ID);
                            set_transient("mlm_reset_" . $user_obj->ID, $code, HOUR_IN_SECONDS);
                            $submited = true;
                            $redirect = mlm_page_url("lost");
                            $redirect = add_query_arg("verified", $user_login, $redirect);
                            $response = __("Verified successfully. loading ...", "mlm");
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "redirect" => $redirect, "response" => $response]);
        wp_die();
    }
    public function mlm_reset_pass()
    {
        check_ajax_referer("mlm_password_nemab", "security");
        $registered = false;
        $redirect = false;
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $user_login = isset($user_data["login"]) ? sanitize_text_field($user_data["login"]) : "";
        $pass = isset($user_data["pass"]) ? sanitize_text_field($user_data["pass"]) : "";
        $repeat = isset($user_data["repeat"]) ? sanitize_text_field($user_data["repeat"]) : "";
        $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
        $db_code = "";
        if (username_exists($user_login)) {
            $user_obj = get_user_by("login", $user_login);
            $db_code = get_transient("mlm_reset_" . $user_obj->ID);
        }
        if (empty($pass) || empty($repeat)) {
            $response = __("Password and password repeat fields are required.", "mlm");
        } else {
            if (!username_exists($user_login)) {
                $response = __("User login is invalid.", "mlm");
            } else {
                if (empty($db_code)) {
                    $response = __("Password recovery verification is not done.", "mlm");
                } else {
                    if (strlen($pass) < 7) {
                        $response = __("Password must have at least 7 characters.", "mlm");
                    } else {
                        if ($pass !== $repeat) {
                            $response = __("Passwords mismatched. Please fill in password and repeat fields again.", "mlm");
                        } else {
                            wp_set_password($pass, $user_obj->ID);
                            delete_transient("mlm_reset_" . $user_obj->ID);
                            $response = __("Password changed successfully. loading ...", "mlm");
                            $registered = true;
                            $redirect = mlm_page_url("login");
                            $redirect = add_query_arg("password", "changed", $redirect);
                        }
                    }
                }
            }
        }
        echo wp_send_json(["redirect" => $redirect, "registered" => $registered, "response" => $response]);
        wp_die();
    }
    public function follow_vendor()
    {
        check_ajax_referer("mlm_okanodada", "security");
        $submited = false;
        $popup = false;
        $user_id = get_current_user_id();
        $vendor_id = isset($_POST["vendor_id"]) ? absint($_POST["vendor_id"]) : "";
        $followed = mlmFire()->follow->do_user_follows($user_id, $vendor_id);
        $mode = $followed ? "unfollow" : "follow";
        if (!is_user_logged_in()) {
            $popup = true;
            $response = __("You have to login to follow a vendor.", "mlm");
        } else {
            if (!mlm_user_exists($vendor_id)) {
                $response = __("Vendor ID is invalid.", "mlm");
            } else {
                if ($vendor_id == $user_id) {
                    $response = __("You can't follow yourself.", "mlm");
                } else {
                    if ($mode == "unfollow") {
                        $result = mlmFire()->follow->unfollow_user($user_id, $vendor_id);
                        $response = __("You will not receive notifications about this vendor.", "mlm");
                    } else {
                        $result = mlmFire()->follow->follow_user($user_id, $vendor_id);
                        $response = __("You will be notified of this vendor's new products.", "mlm");
                    }
                    if ($result) {
                        $submited = true;
                    } else {
                        $response = __("Unknown error occurred. Please try again.", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["mode" => $mode, "popup" => $popup, "submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function add_product_to_cart()
    {
        $product_id = isset($_POST["product_id"]) ? absint($_POST["product_id"]) : "";
        $quantity = isset($_POST["quantity"]) ? wc_stock_amount($_POST["quantity"]) : 1;
        $variation_id = isset($_POST["variation_id"]) ? absint($_POST["variation_id"]) : 0;
        $product_id = apply_filters("woocommerce_add_to_cart_product_id", $product_id);
        $passed_validation = apply_filters("woocommerce_add_to_cart_validation", true, $product_id, $quantity);
        $product_status = get_post_status($product_id);
        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && "publish" === $product_status) {
            do_action("woocommerce_ajax_added_to_cart", $product_id);
            if ("yes" === get_option("woocommerce_cart_redirect_after_add")) {
                wc_add_to_cart_message([$product_id => $quantity], true);
            }
            WC_AJAX::get_refreshed_fragments();
        } else {
            $data = ["error" => true, "product_url" => apply_filters("woocommerce_cart_redirect_after_error", get_permalink($product_id), $product_id)];
            echo wp_send_json($data);
        }
        wp_die();
    }
    public function rate_post()
    {
        check_ajax_referer("mlm_askgfazop", "security");
        $submited = false;
        $popup = false;
        $post_id = isset($_POST["post_id"]) ? absint($_POST["post_id"]) : "";
        $rating = isset($_POST["rating"]) ? absint($_POST["rating"]) : "";
        if (!is_user_logged_in()) {
            $popup = true;
            $response = __("You have to login to submit your rating.", "mlm");
        } else {
            if (!mlm_post_exists($post_id)) {
                $response = __("Invalid post id. Please reload the page.", "mlm");
            } else {
                $result = mlmFire()->rating->add_rate($post_id, $rating);
                if ($result) {
                    $submited = true;
                    $response = __("Rating submitted successfully.", "mlm");
                } else {
                    $response = __("Unknown error occurred. Please try again.", "mlm");
                }
            }
        }
        echo wp_send_json(["popup" => $popup, "submited" => $submited, "average" => mlmFire()->rating->get_average($post_id), "total" => mlmFire()->rating->total_count($post_id), "response" => $response]);
        wp_die();
    }
    public function record_ticket_callback()
    {
        check_ajax_referer("mlm_ticket_fsaz", "security");
        $tid = 0;
        $submited = false;
        $user_id = get_current_user_id();
        $ticket_data = isset($_POST["ticket_data"]) ? $_POST["ticket_data"] : [];
        $subject = isset($ticket_data["subject"]) ? absint($ticket_data["subject"]) : 0;
        $unit = isset($ticket_data["unit"]) ? sanitize_text_field($ticket_data["unit"]) : "";
        $recipient = isset($ticket_data["recipient"]) ? absint($ticket_data["recipient"]) : 0;
        $post_id = isset($ticket_data["post_id"]) ? absint($ticket_data["post_id"]) : 0;
        $title = isset($ticket_data["title"]) ? sanitize_text_field($ticket_data["title"]) : "";
        $content = isset($ticket_data["content"]) ? $ticket_data["content"] : "";
        $attaches = isset($ticket_data["attaches"]) ? mlm_sanitize_array($ticket_data["attaches"]) : [];
        if (!current_user_can("manage_options") && empty($subject)) {
            $response = __("Please select the ticket subject.", "mlm");
        } else {
            if ($subject == 1 && !mlm_user_exists($recipient)) {
                $response = __("Please select the related product.", "mlm");
            } else {
                if ($subject == 2 && empty($unit)) {
                    $response = __("Please select the related department.", "mlm");
                } else {
                    if (empty($title) || empty($content)) {
                        $response = __("Ticket subject and content are required.", "mlm");
                    } else {
                        if (!empty($recipient) && !mlm_user_exists($recipient)) {
                            $response = __("Recipient ID is invalid.", "mlm");
                        } else {
                            if (!empty($user_id) && $recipient === $user_id) {
                                $response = __("You can't sent ticket to yourself.", "mlm");
                            } else {
                                if (!mlm_user_exists($recipient) && !empty($unit)) {
                                    $user_data = ["unit" => $unit];
                                } else {
                                    $user_data = [];
                                }
                                $tid = mlmfire()->db->ticket_record($post_id, $user_id, $recipient, $title, $content, 0, $user_data, 1, $attaches);
                                if ($tid) {
                                    $response = __("Ticket submitted successfully.", "mlm");
                                    $submited = true;
                                    mlmFire()->notif->send_user_mail($user_id, "new_ticket", ["ticket_id" => $tid]);
                                    if (!mlm_user_exists($recipient)) {
                                        mlmFire()->notif->send_admin_sms("new_ticket", ["ticket_id" => $tid]);
                                    } else {
                                        mlmFire()->notif->send_user_sms($recipient, "new_ticket", ["ticket_id" => $tid, "sender_name" => mlm_get_user_name($user_id)]);
                                    }
                                } else {
                                    $response = __("Unknown error occurred. Please try again.", "mlm");
                                }
                            }
                        }
                    }
                }
            }
        }
        if (is_admin() && current_user_can("manage_options")) {
            $redirect = esc_url(admin_url("admin.php?page=mlm-tickets"));
        } else {
            $redirect = trailingslashit(mlm_page_url("panel")) . "section/tickets-all/";
        }
        echo wp_send_json(["tid" => $tid, "submited" => $submited, "redirect" => $redirect, "response" => $response]);
        wp_die();
    }
    public function reply_ticket_callback()
    {
        check_ajax_referer("mlm_ticket_repqpa", "security");
        $tid = 0;
        $redirect = false;
        $submited = false;
        $user_id = get_current_user_id();
        $ticket_data = isset($_POST["ticket_data"]) ? $_POST["ticket_data"] : [];
        $content = isset($ticket_data["content"]) ? $ticket_data["content"] : "";
        $status = isset($ticket_data["status"]) ? absint($ticket_data["status"]) : 0;
        $parent = isset($ticket_data["parent"]) ? absint($ticket_data["parent"]) : 0;
        $attaches = isset($ticket_data["attaches"]) ? mlm_sanitize_array($ticket_data["attaches"]) : [];
        $ticket_obj = mlmfire()->ticket->get_ticket_data($parent);
        if (empty($status) || empty($content)) {
            $response = __("Reply and status fields are required.", "mlm");
        } else {
            if (!isset($ticket_obj->sender_id)) {
                $response = __("Ticket ID is invalid.", "mlm");
            } else {
                if (!current_user_can("manage_options") && $user_id != $ticket_obj->sender_id && $user_id != $ticket_obj->reciver_id) {
                    $response = __("You are not allowed to do this.", "mlm");
                } else {
                    $tid = mlmfire()->db->ticket_record(0, $user_id, 0, "پاسخ", $content, $parent, [], $status, $attaches);
                    if ($tid) {
                        mlmfire()->db->ticket_update($parent, ["status" => $status]);
                        $response = __("Ticket submitted successfully.", "mlm");
                        $submited = true;
                        if ($user_id == $ticket_obj->sender_id) {
                            if (!mlm_user_exists($ticket_obj->reciver_id)) {
                                mlmFire()->notif->send_admin_sms("new_ticket", ["ticket_id" => $tid]);
                            } else {
                                mlmFire()->notif->send_user_sms($ticket_obj->reciver_id, "ticket_replied", ["ticket_id" => $tid, "sender_name" => mlm_get_user_name($user_id)]);
                            }
                        } else {
                            mlmFire()->notif->send_user_sms($ticket_obj->sender_id, "ticket_replied", ["ticket_id" => $tid, "sender_name" => mlm_get_user_name($user_id)]);
                        }
                    } else {
                        $response = __("Unknown error occurred. Please try again.", "mlm");
                    }
                }
            }
        }
        if (is_admin() && current_user_can("manage_options")) {
            $redirect = esc_url(admin_url("admin.php?page=mlm-tickets"));
        }
        echo wp_send_json(["tid" => $tid, "submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function delete_ticket_callback()
    {
        check_ajax_referer("mlm_ticket_repqpa", "security");
        $deleted = false;
        $ticket_id = isset($_POST["ticket_id"]) ? absint($_POST["ticket_id"]) : 0;
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (empty($ticket_id)) {
                $response = __("Ticket ID is invalid.", "mlm");
            } else {
                $res = mlmfire()->db->ticket_delete($ticket_id);
                if ($res) {
                    $deleted = true;
                    $response = __("Ticket deleted successfully.", "mlm");
                } else {
                    $response = __("Unknown error occurred. Please try again.", "mlm");
                }
            }
        }
        echo wp_send_json(["deleted" => $deleted, "response" => $response]);
        wp_die();
    }
    public function change_ticket_status()
    {
        check_ajax_referer("mlm_ticket_repqpa", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $ticket_data = isset($_POST["ticket_data"]) ? $_POST["ticket_data"] : [];
        $status = isset($ticket_data["status"]) ? absint($ticket_data["status"]) : 0;
        $parent = isset($ticket_data["parent"]) ? absint($ticket_data["parent"]) : 0;
        $ticket_obj = mlmfire()->ticket->get_ticket_data($parent);
        if (empty($status)) {
            $response = __("Ticket status is not selected.", "mlm");
        } else {
            if (!isset($ticket_obj->sender_id)) {
                $response = __("Ticket ID is invalid.", "mlm");
            } else {
                if (!current_user_can("manage_options") && $user_id != $ticket_obj->sender_id && $user_id != $ticket_obj->reciver_id) {
                    $response = __("You are not allowed to do this.", "mlm");
                } else {
                    mlmfire()->db->ticket_update($parent, ["status" => $status]);
                    $submited = true;
                    $response = __("Ticket status updated.", "mlm");
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function admin_charge_wallet()
    {
        check_ajax_referer("mlm_charge_wiks", "security");
        $submited = false;
        $charge_data = isset($_POST["charge_data"]) ? $_POST["charge_data"] : [];
        $user = isset($charge_data["user"]) ? absint($charge_data["user"]) : "";
        $type = isset($charge_data["type"]) ? absint($charge_data["type"]) : "";
        $amount = isset($charge_data["amount"]) ? absint($charge_data["amount"]) : "";
        $text = isset($charge_data["text"]) ? sanitize_text_field($charge_data["text"]) : "";
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (!mlm_user_exists($user)) {
                $response = __("User ID is invalid. Please select one user from the dropdown list.", "mlm");
            } else {
                if ($amount < 1) {
                    $response = __("Amount is not valid.", "mlm");
                } else {
                    if (empty($text)) {
                        $response = __("Description field is required.", "mlm");
                    } else {
                        if ($type == 2) {
                            $type = 7;
                            $op = "minus";
                        } else {
                            $type = 6;
                            $op = "plus";
                        }
                        $result = mlmFire()->db->wallet_record($user, 0, 0, $amount, $type, 2, $text);
                        if ($result) {
                            mlmFire()->wallet->update_meta($user, "mlm_balance", $amount, $op);
                            $response = __("Transaction submitted successfully.", "mlm");
                            $submited = true;
                        } else {
                            $response = __("Unknown error occurred. Please try again.", "mlm");
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function bookmark_post()
    {
        check_ajax_referer("mlm_pogtrawz", "security");
        $action = "";
        $submited = false;
        $user_id = get_current_user_id();
        $post_id = isset($_POST["post_id"]) ? absint($_POST["post_id"]) : "";
        if (!is_user_logged_in()) {
            $response = __("Please sign in to your account.", "mlm");
        } else {
            if (!mlm_post_exists($post_id)) {
                $response = __("Invalid post ID. Please reload the page.", "mlm");
            } else {
                $bookmarked = mlmFire()->rating->check_post_bookmark($post_id, $user_id);
                if ($bookmarked) {
                    $result = mlmFire()->rating->remove_post_bookmark($user_id, $post_id);
                    $action = "unbook";
                    $response = __("Item removed from your bookmarks list.", "mlm");
                } else {
                    $result = mlmFire()->rating->bookmark_post($user_id, $post_id);
                    $action = "book";
                    $response = __("Item added to your bookmarks list.", "mlm");
                }
                if (!$result) {
                    $response = __("Unknown error occurred. Please try again.", "mlm");
                } else {
                    $submited = true;
                }
            }
        }
        echo wp_send_json(["action" => $action, "submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function mlm_profile()
    {
        check_ajax_referer("mlm_vakasizuma", "security");
        $submited = false;
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $avatar = isset($user_data["avatar"]) ? esc_url($user_data["avatar"]) : "";
        $cover = isset($user_data["cover"]) ? esc_url($user_data["cover"]) : "";
        $fname = isset($user_data["fname"]) ? sanitize_text_field($user_data["fname"]) : "";
        $lname = isset($user_data["lname"]) ? sanitize_text_field($user_data["lname"]) : "";
        $email = isset($user_data["email"]) ? sanitize_text_field($user_data["email"]) : "";
        $mobile = isset($user_data["mobile"]) ? sanitize_text_field($user_data["mobile"]) : "";
        $country_code = isset($user_data["country_code"]) ? sanitize_text_field($user_data["country_code"]) : "";
        $state = isset($user_data["state"]) ? sanitize_text_field($user_data["state"]) : "";
        $bio = isset($user_data["bio"]) ? esc_textarea($user_data["bio"]) : "";
        $twitter = isset($user_data["twitter"]) ? esc_url($user_data["twitter"]) : "";
        $aparat = isset($user_data["aparat"]) ? esc_url($user_data["aparat"]) : "";
        $instagram = isset($user_data["instagram"]) ? esc_url($user_data["instagram"]) : "";
        $telegram = isset($user_data["telegram"]) ? esc_url($user_data["telegram"]) : "";
        $youtube = isset($user_data["youtube"]) ? esc_url($user_data["youtube"]) : "";
        $user_id = get_current_user_id();
        $u_info = get_userdata($user_id);
        $u_email = $u_info->user_email;
        $s_email = sanitize_email($email);
        if (empty($fname) || empty($lname) || empty($email) || empty($mobile) || empty($state)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if (!is_email($s_email)) {
                $response = __("Email address is not valid.", "mlm");
            } else {
                if (email_exists($s_email) && $u_email != $s_email) {
                    $response = __("Email address already registered.", "mlm");
                } else {
                    if (mlm_mobile_exists($mobile, $user_id)) {
                        $response = __("Mobile number already registered.", "mlm");
                    } else {
                        $userdata = ["ID" => $user_id, "first_name" => $fname, "last_name" => $lname, "user_email" => $s_email, "display_name" => $fname . " " . $lname, "description" => $bio];
                        wp_update_user($userdata);
                        update_user_meta($user_id, "mlm_avatar", $avatar);
                        update_user_meta($user_id, "mlm_cover", $cover);
                        update_user_meta($user_id, "mlm_mobile", $mobile);
                        update_user_meta($user_id, "country_code", $country_code);
                        update_user_meta($user_id, "mlm_state", $state);
                        update_user_meta($user_id, "mlm_twitter", $twitter);
                        update_user_meta($user_id, "mlm_aparat", $aparat);
                        update_user_meta($user_id, "mlm_telegram", $telegram);
                        update_user_meta($user_id, "mlm_instagram", $instagram);
                        update_user_meta($user_id, "mlm_youtube", $youtube);
                        mlmFire()->dashboard->get_profile_status($user_id, true);
                        $response = __("Profile updated successfully.", "mlm");
                        $submited = true;
                    }
                }
            }
        }
        echo wp_send_json(["avatar" => $avatar, "submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function mlm_change_pass()
    {
        check_ajax_referer("mlm_settings_dojon", "security");
        $registered = false;
        $redirect = false;
        $user_id = get_current_user_id();
        $user_obj = get_userdata($user_id);
        $db_pass = $user_obj->user_pass;
        $user_data = isset($_POST["user_data"]) ? $_POST["user_data"] : [];
        $pass = isset($user_data["pass"]) ? sanitize_text_field($user_data["pass"]) : "";
        $new_pass = isset($user_data["new"]) ? sanitize_text_field($user_data["new"]) : "";
        $valid_user = wp_check_password($pass, $db_pass, $user_id);
        if (empty($pass) || empty($new_pass)) {
            $response = __("Current password and new password fields are required.", "mlm");
        } else {
            if (!$valid_user) {
                $response = __("Current password is not correct.", "mlm");
            } else {
                if (strlen($new_pass) < 7) {
                    $response = __("Passowrd must have at least 7 characters.", "mlm");
                } else {
                    wp_set_password($new_pass, $user_id);
                    $response = __("Password changed successfully. Redirecting to login page ...", "mlm");
                    $registered = true;
                    $redirect = mlm_page_url("login");
                    $redirect = add_query_arg("password", "changed", $redirect);
                    mlmFire()->notif->send_user_sms($user_id, "password_changed", ["password" => $new_pass]);
                }
            }
        }
        echo wp_send_json(["redirect" => $redirect, "registered" => $registered, "response" => $response]);
        wp_die();
    }
    public function submit_post()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $user_id = get_current_user_id();
        $submited = false;
        $redirect = trailingslashit(mlm_page_url("panel")) . "section/posts-all/";
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        if (empty($title) || empty($content) || empty($cats) || empty($tags)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if (!current_user_can("upload_files")) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if ($post_id) {
                    if (!mlm_post_exists($post_id)) {
                        $response = __("Post ID is invalid.", "mlm");
                    } else {
                        $author = get_post_field("post_author", $post_id);
                        $type = get_post_field("post_type", $post_id);
                        $status = get_post_field("post_status", $post_id);
                        if ($author != $user_id || $type != "post" || $status != "publish" && $status != "pending") {
                            $response = __("You are not allowed to do this.", "mlm");
                        } else {
                            $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending"];
                            wp_update_post($update_post);
                            $submited = true;
                            $response = __("Post updated successfully.", "mlm");
                            $redirect = add_query_arg("updated", "OK", $redirect);
                        }
                    }
                } else {
                    $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending", "post_type" => "post", "post_author" => $user_id];
                    $post_id = wp_insert_post($submit_post);
                    $submited = true;
                    $response = __("Post submitted successfully.", "mlm");
                    $redirect = add_query_arg("submited", "OK", $redirect);
                }
            }
        }
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        mlm_update_tax($post_id, "category", $cats);
        mlm_update_tax($post_id, "post_tag", $tags);
        mlmFire()->notif->send_user_mail($user_id, "post_moderation", ["post_id" => $post_id]);
        mlmFire()->notif->send_admin_mail("post_moderation", ["post_id" => $post_id]);
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function submit_product()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $redirect = trailingslashit(mlm_page_url("panel")) . "section/products-all/";
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $files = isset($post_data["mlm_file[i"]) ? mlm_sanitize_array($post_data["mlm_file[i"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        } else {
            $file_type = isset($post_data["type"]) ? sanitize_text_field($post_data["type"]) : "";
            $count = isset($post_data["count"]) ? sanitize_text_field($post_data["count"]) : "";
            $part = isset($post_data["part"]) ? sanitize_text_field($post_data["part"]) : "";
            $author = isset($post_data["author"]) ? sanitize_text_field($post_data["author"]) : "";
            $size = isset($post_data["size"]) ? sanitize_text_field($post_data["size"]) : "";
            $format = isset($post_data["format"]) ? sanitize_text_field($post_data["format"]) : "";
            $language = isset($post_data["language"]) ? sanitize_text_field($post_data["language"]) : "";
            $step = isset($post_data["step"]) ? sanitize_text_field($post_data["step"]) : "";
        }
        if (empty($title) || empty($content) || empty($cats) || empty($tags) || !is_numeric($price) || !is_numeric($percent)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if ($fields_type == "custom" && mlmFire()->dashboard->check_required_fields($fields)) {
                $response = __("Marked fields are required.", "mlm");
            } else {
                if (!is_array($files) || !count($files)) {
                    $response = __("No files has been uploaded for the product.", "mlm");
                } else {
                    if (!current_user_can("level_3")) {
                        $response = __("You are not allowed to do this.", "mlm");
                    } else {
                        if ($post_id) {
                            if (!mlm_post_exists($post_id)) {
                                $response = __("Product ID is invalid.", "mlm");
                            } else {
                                $vendor = get_post_field("post_author", $post_id);
                                $type = get_post_field("post_type", $post_id);
                                $status = get_post_field("post_status", $post_id);
                                if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || mlm_check_course($post_id)) {
                                    $response = __("You are not allowed to do this.", "mlm");
                                } else {
                                    $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending"];
                                    wp_update_post($update_post);
                                    $submited = true;
                                    $response = __("Product updated successfully.", "mlm");
                                    $redirect = add_query_arg("updated", "OK", $redirect);
                                }
                            }
                        } else {
                            $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending", "post_type" => "product", "post_author" => $user_id];
                            $post_id = wp_insert_post($submit_post);
                            $submited = true;
                            $response = __("Product submitted successfully.", "mlm");
                            $redirect = add_query_arg("submited", "OK", $redirect);
                            update_post_meta($post_id, "total_sales", 0);
                        }
                    }
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        $dl_files = [];
        foreach ($files as $f) {
            $md5_num = md5($f["file"]);
            $dl_files[$md5_num] = ["id" => $md5_num, "name" => empty($f["name"]) ? __("Download file", "mlm") : $f["name"], "file" => $f["file"]];
        }
        update_post_meta($post_id, "_downloadable_files", $dl_files);
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "yes");
        update_post_meta($post_id, "_downloadable", "yes");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        } else {
            update_post_meta($post_id, "mlm_file_type", $file_type);
            update_post_meta($post_id, "mlm_page_count", $count);
            update_post_meta($post_id, "mlm_part_count", $part);
            update_post_meta($post_id, "mlm_file_author", $author);
            update_post_meta($post_id, "mlm_file_size", $size);
            update_post_meta($post_id, "mlm_file_format", $format);
            update_post_meta($post_id, "mlm_file_language", $language);
            update_post_meta($post_id, "mlm_file_step", $step);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        mlmFire()->wallet->post_ref_amount($post_id);
        mlmFire()->notif->send_user_mail($user_id, "product_moderation", ["post_id" => $post_id]);
        mlmFire()->notif->send_admin_mail("product_moderation", ["post_id" => $post_id]);
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function draft_product()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $new_id = false;
        $user_id = get_current_user_id();
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $files = isset($post_data["mlm_file[i"]) ? mlm_sanitize_array($post_data["mlm_file[i"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        } else {
            $file_type = isset($post_data["type"]) ? sanitize_text_field($post_data["type"]) : "";
            $count = isset($post_data["count"]) ? sanitize_text_field($post_data["count"]) : "";
            $part = isset($post_data["part"]) ? sanitize_text_field($post_data["part"]) : "";
            $author = isset($post_data["author"]) ? sanitize_text_field($post_data["author"]) : "";
            $size = isset($post_data["size"]) ? sanitize_text_field($post_data["size"]) : "";
            $format = isset($post_data["format"]) ? sanitize_text_field($post_data["format"]) : "";
            $language = isset($post_data["language"]) ? sanitize_text_field($post_data["language"]) : "";
            $step = isset($post_data["step"]) ? sanitize_text_field($post_data["step"]) : "";
        }
        if (empty($title)) {
            $response = __("Title field is required to save the draft.", "mlm");
        } else {
            if (!current_user_can("level_3")) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if ($post_id) {
                    if (!mlm_post_exists($post_id)) {
                        $response = __("Product ID is invalid.", "mlm");
                    } else {
                        $vendor = get_post_field("post_author", $post_id);
                        $type = get_post_field("post_type", $post_id);
                        $status = get_post_field("post_status", $post_id);
                        if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || mlm_check_course($post_id)) {
                            $response = __("You are not allowed to do this.", "mlm");
                        } else {
                            $update_post = ["ID" => $post_id, "post_title" => $post_slug, "post_content" => $content, "post_status" => "draft"];
                            wp_update_post($update_post);
                            $submited = true;
                            $response = __("Product saved as draft.", "mlm");
                        }
                    }
                } else {
                    $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "draft", "post_type" => "product", "post_author" => $user_id];
                    $post_id = wp_insert_post($submit_post);
                    $submited = true;
                    $response = __("Product saved as draft.", "mlm");
                    update_post_meta($post_id, "total_sales", 0);
                    $new_id = $post_id;
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        $dl_files = [];
        foreach ($files as $f) {
            $md5_num = md5($f["file"]);
            $dl_files[$md5_num] = ["id" => $md5_num, "name" => empty($f["name"]) ? __("Download file", "mlm") : $f["name"], "file" => $f["file"]];
        }
        update_post_meta($post_id, "_downloadable_files", $dl_files);
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "yes");
        update_post_meta($post_id, "_downloadable", "yes");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        } else {
            update_post_meta($post_id, "mlm_file_type", $file_type);
            update_post_meta($post_id, "mlm_page_count", $count);
            update_post_meta($post_id, "mlm_part_count", $part);
            update_post_meta($post_id, "mlm_file_author", $author);
            update_post_meta($post_id, "mlm_file_size", $size);
            update_post_meta($post_id, "mlm_file_format", $format);
            update_post_meta($post_id, "mlm_file_language", $language);
            update_post_meta($post_id, "mlm_file_step", $step);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response, "post_id" => $new_id]);
        wp_die();
    }
    public function submit_physical()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $redirect = trailingslashit(mlm_page_url("panel")) . "section/products-all/";
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $weight = isset($post_data["weight"]) ? absint($post_data["weight"]) : "";
        $quantity = isset($post_data["quantity"]) ? absint($post_data["quantity"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        }
        if (empty($title) || empty($content) || empty($cats) || empty($tags) || !is_numeric($price) || !is_numeric($percent) || empty($weight) || empty($quantity)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if ($fields_type == "custom" && mlmFire()->dashboard->check_required_fields($fields)) {
                $response = __("Marked fields are required.", "mlm");
            } else {
                if (!current_user_can("level_3")) {
                    $response = __("You are not allowed to do this.", "mlm");
                } else {
                    if ($post_id) {
                        if (!mlm_post_exists($post_id)) {
                            $response = __("Product ID is invalid.", "mlm");
                        } else {
                            $vendor = get_post_field("post_author", $post_id);
                            $type = get_post_field("post_type", $post_id);
                            $status = get_post_field("post_status", $post_id);
                            if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || mlm_check_course($post_id)) {
                                $response = __("You are not allowed to do this.", "mlm");
                            } else {
                                $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending"];
                                wp_update_post($update_post);
                                $submited = true;
                                $response = __("Product updated successfully.", "mlm");
                                $redirect = add_query_arg("updated", "OK", $redirect);
                            }
                        }
                    } else {
                        $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending", "post_type" => "product", "post_author" => $user_id];
                        $post_id = wp_insert_post($submit_post);
                        $submited = true;
                        $response = __("Product submitted successfully.", "mlm");
                        $redirect = add_query_arg("submited", "OK", $redirect);
                        update_post_meta($post_id, "total_sales", 0);
                    }
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "no");
        update_post_meta($post_id, "_downloadable", "no");
        update_post_meta($post_id, "_manage_stock", "yes");
        update_post_meta($post_id, "_weight", $weight);
        update_post_meta($post_id, "_stock", $quantity);
        update_post_meta($post_id, "_stock_status", "instock");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        mlmFire()->wallet->post_ref_amount($post_id);
        mlmFire()->notif->send_user_mail($user_id, "product_moderation", ["post_id" => $post_id]);
        mlmFire()->notif->send_admin_mail("product_moderation", ["post_id" => $post_id]);
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function draft_physical()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $new_id = false;
        $user_id = get_current_user_id();
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $weight = isset($post_data["weight"]) ? absint($post_data["weight"]) : "";
        $quantity = isset($post_data["quantity"]) ? absint($post_data["quantity"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        }
        if (empty($title)) {
            $response = __("Title field is required to save the draft.", "mlm");
        } else {
            if (!current_user_can("level_3")) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if ($post_id) {
                    if (!mlm_post_exists($post_id)) {
                        $response = __("Product ID is invalid.", "mlm");
                    } else {
                        $vendor = get_post_field("post_author", $post_id);
                        $type = get_post_field("post_type", $post_id);
                        $status = get_post_field("post_status", $post_id);
                        if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || mlm_check_course($post_id)) {
                            $response = __("You are not allowed to do this.", "mlm");
                        } else {
                            $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "draft"];
                            wp_update_post($update_post);
                            $submited = true;
                            $response = __("Product saved as draft.", "mlm");
                        }
                    }
                } else {
                    $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "draft", "post_type" => "product", "post_author" => $user_id];
                    $post_id = wp_insert_post($submit_post);
                    $submited = true;
                    $response = __("Product saved as draft.", "mlm");
                    update_post_meta($post_id, "total_sales", 0);
                    $new_id = $post_id;
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "no");
        update_post_meta($post_id, "_downloadable", "no");
        update_post_meta($post_id, "_manage_stock", "yes");
        update_post_meta($post_id, "_weight", $weight);
        update_post_meta($post_id, "_stock", $quantity);
        update_post_meta($post_id, "_stock_status", "instock");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response, "post_id" => $new_id]);
        wp_die();
    }
    public function submit_course()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $redirect = trailingslashit(mlm_page_url("panel")) . "section/course-new/page/2/";
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $teacher_image = isset($post_data["teacher_image"]) ? esc_url($post_data["teacher_image"]) : "";
        $teacher_name = isset($post_data["teacher_name"]) ? sanitize_text_field($post_data["teacher_name"]) : "";
        $course_fill = isset($post_data["course_fill"]) ? absint($post_data["course_fill"]) : "";
        $teacher_bio = isset($post_data["teacher_bio"]) ? esc_textarea($post_data["teacher_bio"]) : "";
        $course_video = isset($post_data["course_video"]) ? esc_textarea($post_data["course_video"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        } else {
            $file_type = isset($post_data["type"]) ? sanitize_text_field($post_data["type"]) : "";
            $count = isset($post_data["count"]) ? sanitize_text_field($post_data["count"]) : "";
            $part = isset($post_data["part"]) ? sanitize_text_field($post_data["part"]) : "";
            $author = isset($post_data["author"]) ? sanitize_text_field($post_data["author"]) : "";
            $size = isset($post_data["size"]) ? sanitize_text_field($post_data["size"]) : "";
            $format = isset($post_data["format"]) ? sanitize_text_field($post_data["format"]) : "";
            $language = isset($post_data["language"]) ? sanitize_text_field($post_data["language"]) : "";
            $step = isset($post_data["step"]) ? sanitize_text_field($post_data["step"]) : "";
        }
        if (empty($title) || empty($content) || empty($cats) || empty($tags) || !is_numeric($price) || !is_numeric($percent) || empty($teacher_name)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if ($fields_type == "custom" && mlmFire()->dashboard->check_required_fields($fields)) {
                $response = __("Marked fields are required.", "mlm");
            } else {
                if (!current_user_can("level_3")) {
                    $response = __("You are not allowed to do this.", "mlm");
                } else {
                    if ($post_id) {
                        if (!mlm_post_exists($post_id)) {
                            $response = __("Product ID is invalid.", "mlm");
                        } else {
                            $vendor = get_post_field("post_author", $post_id);
                            $type = get_post_field("post_type", $post_id);
                            $status = get_post_field("post_status", $post_id);
                            if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || !mlm_check_course($post_id)) {
                                $response = __("You are not allowed to do this.", "mlm");
                            } else {
                                $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending"];
                                wp_update_post($update_post);
                                $submited = true;
                                $response = __("Saved changes. Loading next step ...", "mlm");
                                $redirect = $redirect . "mid/" . $post_id . "/";
                            }
                        }
                    } else {
                        $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "pending", "post_type" => "product", "post_author" => $user_id];
                        $post_id = wp_insert_post($submit_post);
                        $submited = true;
                        $response = __("Saved changes. Loading next step ...", "mlm");
                        $redirect = $redirect . "mid/" . $post_id . "/";
                        update_post_meta($post_id, "total_sales", 0);
                        update_post_meta($post_id, "mlm_is_course", "yes");
                    }
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "yes");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_teacher_image", $teacher_image);
        update_post_meta($post_id, "mlm_teacher_name", $teacher_name);
        update_post_meta($post_id, "mlm_course_fill", $course_fill);
        update_post_meta($post_id, "mlm_teacher_bio", $teacher_bio);
        update_post_meta($post_id, "mlm_course_video", $course_video);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        } else {
            update_post_meta($post_id, "mlm_file_type", $file_type);
            update_post_meta($post_id, "mlm_page_count", $count);
            update_post_meta($post_id, "mlm_part_count", $part);
            update_post_meta($post_id, "mlm_file_author", $author);
            update_post_meta($post_id, "mlm_file_size", $size);
            update_post_meta($post_id, "mlm_file_format", $format);
            update_post_meta($post_id, "mlm_file_language", $language);
            update_post_meta($post_id, "mlm_file_step", $step);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        mlmFire()->wallet->post_ref_amount($post_id);
        mlmFire()->notif->send_user_mail($user_id, "product_moderation", ["post_id" => $post_id]);
        mlmFire()->notif->send_admin_mail("product_moderation", ["post_id" => $post_id]);
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function draft_course()
    {
        check_ajax_referer("mlm_submit_abilia", "security");
        $submited = false;
        $new_id = false;
        $user_id = get_current_user_id();
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $title = isset($post_data["title"]) ? sanitize_text_field($post_data["title"]) : "";
        $content = isset($post_data["content"]) ? wp_filter_post_kses($post_data["content"]) : "";
        $thumb = isset($post_data["thumb"]) ? absint($post_data["thumb"]) : 0;
        $cats = isset($post_data["cats"]) ? mlm_sanitize_array($post_data["cats"]) : "";
        $tags = isset($post_data["tags"]) ? mlm_sanitize_array($post_data["tags"]) : "";
        $percent = isset($post_data["percent"]) ? absint($post_data["percent"]) : "";
        $price = isset($post_data["price"]) ? absint($post_data["price"]) : "";
        $sale_price = isset($post_data["sale_price"]) ? absint($post_data["sale_price"]) : "";
        $button_text = isset($post_data["button_text"]) ? sanitize_text_field($post_data["button_text"]) : "";
        $button_link = isset($post_data["button_link"]) ? esc_url($post_data["button_link"]) : "";
        $button_2_text = isset($post_data["button_2_text"]) ? sanitize_text_field($post_data["button_2_text"]) : "";
        $button_2_link = isset($post_data["button_2_link"]) ? esc_url($post_data["button_2_link"]) : "";
        $teacher_image = isset($post_data["teacher_image"]) ? esc_url($post_data["teacher_image"]) : "";
        $teacher_name = isset($post_data["teacher_name"]) ? sanitize_text_field($post_data["teacher_name"]) : "";
        $course_fill = isset($post_data["course_fill"]) ? absint($post_data["course_fill"]) : "";
        $teacher_bio = isset($post_data["teacher_bio"]) ? esc_textarea($post_data["teacher_bio"]) : "";
        $course_video = isset($post_data["course_video"]) ? sanitize_text_field($post_data["course_video"]) : "";
        $thumb_image = isset($post_data["thumb_image"]) ? esc_url($post_data["thumb_image"]) : "";
        $image_one = isset($post_data["image_one"]) ? esc_url($post_data["image_one"]) : "";
        $image_two = isset($post_data["image_two"]) ? esc_url($post_data["image_two"]) : "";
        $stock = isset($post_data["stock"]) ? sanitize_text_field($post_data["stock"]) : "";
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $fields = isset($post_data["mlm_custom[i"]) ? mlm_sanitize_array($post_data["mlm_custom[i"]) : "";
        } else {
            $file_type = isset($post_data["type"]) ? sanitize_text_field($post_data["type"]) : "";
            $count = isset($post_data["count"]) ? sanitize_text_field($post_data["count"]) : "";
            $part = isset($post_data["part"]) ? sanitize_text_field($post_data["part"]) : "";
            $author = isset($post_data["author"]) ? sanitize_text_field($post_data["author"]) : "";
            $size = isset($post_data["size"]) ? sanitize_text_field($post_data["size"]) : "";
            $format = isset($post_data["format"]) ? sanitize_text_field($post_data["format"]) : "";
            $language = isset($post_data["language"]) ? sanitize_text_field($post_data["language"]) : "";
            $step = isset($post_data["step"]) ? sanitize_text_field($post_data["step"]) : "";
        }
        if (empty($title)) {
            $response = __("Title field is required to save the draft.", "mlm");
        } else {
            if (!current_user_can("level_3")) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if ($post_id) {
                    if (!mlm_post_exists($post_id)) {
                        $response = __("Product ID is invalid.", "mlm");
                    } else {
                        $vendor = get_post_field("post_author", $post_id);
                        $type = get_post_field("post_type", $post_id);
                        $status = get_post_field("post_status", $post_id);
                        if ($vendor != $user_id || $type != "product" || !in_array($status, ["publish", "pending", "draft"]) || !mlm_check_course($post_id)) {
                            $response = __("You are not allowed to do this.", "mlm");
                        } else {
                            $update_post = ["ID" => $post_id, "post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "draft"];
                            wp_update_post($update_post);
                            $submited = true;
                            $response = __("Course saved as draft.", "mlm");
                        }
                    }
                } else {
                    $submit_post = ["post_title" => wp_strip_all_tags($title), "post_content" => $content, "post_status" => "draft", "post_type" => "product", "post_author" => $user_id];
                    $post_id = wp_insert_post($submit_post);
                    $submited = true;
                    $response = __("Course saved as draft.", "mlm");
                    update_post_meta($post_id, "total_sales", 0);
                    update_post_meta($post_id, "mlm_is_course", "yes");
                    $new_id = $post_id;
                }
            }
        }
        mlm_update_tax($post_id, "product_cat", $cats);
        mlm_update_tax($post_id, "product_tag", $tags);
        if (!empty($thumb) && wp_get_attachment_url($thumb)) {
            update_post_meta($post_id, "_thumbnail_id", $thumb);
        } else {
            delete_post_meta($post_id, "_thumbnail_id");
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, "_price", $sale_price);
            update_post_meta($post_id, "_sale_price", $sale_price);
        } else {
            update_post_meta($post_id, "_price", $price);
            delete_post_meta($post_id, "_sale_price");
        }
        update_post_meta($post_id, "_regular_price", $price);
        update_post_meta($post_id, "_virtual", "yes");
        update_post_meta($post_id, "mlm_ref_value", $percent);
        update_post_meta($post_id, "mlm_button_text", $button_text);
        update_post_meta($post_id, "mlm_button_link", $button_link);
        update_post_meta($post_id, "mlm_button_2_text", $button_2_text);
        update_post_meta($post_id, "mlm_button_2_link", $button_2_link);
        update_post_meta($post_id, "mlm_teacher_image", $teacher_image);
        update_post_meta($post_id, "mlm_teacher_name", $teacher_name);
        update_post_meta($post_id, "mlm_course_fill", $course_fill);
        update_post_meta($post_id, "mlm_teacher_bio", $teacher_bio);
        update_post_meta($post_id, "mlm_course_video", $course_video);
        update_post_meta($post_id, "mlm_image_thumb", $thumb_image);
        update_post_meta($post_id, "mlm_image_one", $image_one);
        update_post_meta($post_id, "mlm_image_two", $image_two);
        if ($fields_type == "custom") {
            update_post_meta($post_id, "mlm_saved_fields", $fields);
        } else {
            update_post_meta($post_id, "mlm_file_type", $file_type);
            update_post_meta($post_id, "mlm_page_count", $count);
            update_post_meta($post_id, "mlm_part_count", $part);
            update_post_meta($post_id, "mlm_file_author", $author);
            update_post_meta($post_id, "mlm_file_size", $size);
            update_post_meta($post_id, "mlm_file_format", $format);
            update_post_meta($post_id, "mlm_file_language", $language);
            update_post_meta($post_id, "mlm_file_step", $step);
        }
        if ($stock == "no") {
            update_post_meta($post_id, "_stock", 0);
            update_post_meta($post_id, "_stock_status", "outofstock");
        } else {
            update_post_meta($post_id, "_stock_status", "instock");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response, "post_id" => $new_id]);
        wp_die();
    }
    public function increase_balance()
    {
        check_ajax_referer("mlm_ioytdagud", "security");
        $submited = false;
        $redirect = "";
        $amount = isset($_POST["amount"]) ? absint($_POST["amount"]) : 0;
        if (empty($amount) || $amount < 100) {
            $response = sprintf(__("Minimum acceptable amount is %s", "mlm"), mlm_filter(100));
        } else {
            global $woocommerce;
            $user_id = get_current_user_id();
            $user_obj = get_userdata($user_id);
            $address = ["first_name" => $user_obj->first_name, "last_name" => $user_obj->last_name, "email" => $user_obj->user_email, "phone" => get_user_meta($user_id, "mlm_mobile", true)];
            $order = wc_create_order(["customer_id" => $user_id]);
            $order->set_address($address, "billing");
            $order->set_address($address, "shipping");
            $order->update_status("pending");
            $item_id = wc_add_order_item($order->get_id(), ["order_item_name" => __("Charge wallet", "mlm"), "order_item_type" => "fee"]);
            wc_add_order_item_meta($item_id, "_line_total", $amount);
            update_post_meta($order->get_id(), "_mlm_charge", 1);
            $order->calculate_totals();
            $submited = true;
            $redirect = $order->get_checkout_payment_url();
            $response = __("Request submitted. Redirecting ...", "mlm");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function like_comment()
    {
        check_ajax_referer("mlm_zoxpoastvr", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $form_data = isset($_POST["form_data"]) ? $_POST["form_data"] : "";
        $comment_id = isset($form_data["comment_id"]) ? absint($form_data["comment_id"]) : "";
        $reaction = isset($form_data["reaction"]) ? esc_attr($form_data["reaction"]) : "";
        if (!is_user_logged_in()) {
            $response = __("You have to login to rate a comment.", "mlm");
        } else {
            if (empty($comment_id)) {
                $response = __("Comment ID is invalid.", "mlm");
            } else {
                mlmFire()->rating->like_comment($comment_id, $reaction);
                $submited = true;
                $response = __("Comment rating saved successfully", "mlm");
            }
        }
        $counts = mlmFire()->rating->get_comment_likes($comment_id);
        echo wp_send_json(["submited" => $submited, "response" => $response, "likes" => $counts["like"], "dislikes" => $counts["dislike"]]);
        wp_die();
    }
    public function withdraw_request()
    {
        check_ajax_referer("mlm_jaharfetim", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $balance = mlmFire()->wallet->get_balance($user_id);
        $min = mlmFire()->wallet->min_withdraw_amount();
        $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : [];
        $amount = isset($user_input["amount"]) ? absint($user_input["amount"]) : "";
        $card = isset($user_input["card"]) ? sanitize_text_field($user_input["card"]) : "";
        $sheba = isset($user_input["sheba"]) ? sanitize_text_field($user_input["sheba"]) : "";
        $owner = isset($user_input["owner"]) ? sanitize_text_field($user_input["owner"]) : "";
        $pending = mlmFire()->db->query_rows("SELECT id FROM {TABLE} WHERE user_id = %d AND type = %d AND status = %d LIMIT %d", [$user_id, 5, 1, 1], "wallet", true);
        if (empty($amount) || empty($card) || empty($sheba) || empty($owner)) {
            $response = __("Marked fields are required.", "mlm");
        } else {
            if ($balance < $amount) {
                $response = __("Requested amount is more than your wallet balance.", "mlm");
            } else {
                if ($amount < $min || $amount <= 0) {
                    $response = __("Requsted amount is less than minimum allowed amount.", "mlm");
                } else {
                    if (!empty($pending)) {
                        $response = __("Please wait until your last active withdraw request moderation.", "mlm");
                    } else {
                        mlmFire()->db->wallet_record($user_id, 0, 0, $amount, 5, 1, __("Waiting for moderation and payment", "mlm"));
                        mlmFire()->wallet->update_meta($user_id, "mlm_balance", $amount, "minus");
                        update_user_meta($user_id, "mlm_card", $card);
                        update_user_meta($user_id, "mlm_sheba", $sheba);
                        update_user_meta($user_id, "mlm_owner", $owner);
                        mlmFire()->notif->send_admin_mail("withdrawal", ["user_id" => $user_id, "amount" => $amount]);
                        mlmFire()->notif->send_user_mail($user_id, "withdrawal_request", ["amount" => $amount]);
                        $submited = true;
                        $response = __("Withdrawal request submitted. Loading ...", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function submit_parent()
    {
        check_ajax_referer("mlm_takarino", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $db_parent = mlmFire()->network->get_user_parent($user_id);
        $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : [];
        $parent = isset($user_input["parent"]) ? sanitize_text_field($user_input["parent"]) : "";
        $parent_id = mlmFire()->referral->get_userid_by_ref($parent);
        if (mlm_user_exists($db_parent)) {
            $response = __("Your reagent submitted already.", "mlm");
        } else {
            if (empty($parent)) {
                $response = __("Reagent code is required.", "mlm");
            } else {
                if (!mlm_user_exists($parent_id)) {
                    $response = __("Reagent code is not valid.", "mlm");
                } else {
                    if ($parent_id == $user_id) {
                        $response = __("You can't be yourselves reagent.", "mlm");
                    } else {
                        mlmFire()->network->add_user_to_network($user_id, $parent_id);
                        $submited = true;
                        $response = __("Reagent submitted successfully. Loading ...", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function upgrade_step_one()
    {
        check_ajax_referer("mlm_gayapidis", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $post_id = mlmFire()->dashboard->get_request_post_id($user_id);
        $upgrade_url = trailingslashit(mlm_page_url("panel")) . "section/upgrade/";
        $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : [];
        $gender = isset($user_input["gender"]) ? sanitize_text_field($user_input["gender"]) : "m";
        $fname = isset($user_input["fname"]) ? sanitize_text_field($user_input["fname"]) : "";
        $lname = isset($user_input["lname"]) ? sanitize_text_field($user_input["lname"]) : "";
        $birth = isset($user_input["birth"]) ? sanitize_text_field($user_input["birth"]) : "";
        $melli = isset($user_input["melli"]) ? sanitize_text_field($user_input["melli"]) : "";
        $address = isset($user_input["address"]) ? esc_textarea($user_input["address"]) : "";
        $phone = isset($user_input["phone"]) ? sanitize_text_field($user_input["phone"]) : "";
        $postal = isset($user_input["postal"]) ? sanitize_text_field($user_input["postal"]) : "";
        $role = isset($user_input["role"]) ? absint($user_input["role"]) : "";
        $status = get_post_meta($post_id, "mlm_status", true);
        if (current_user_can("moderate_comments")) {
            $response = __("You have no need to upgrade. Your account already upgraded.", "mlm");
        } else {
            if ($status == "ok") {
                $response = __("Your upgrade account request is verified already.", "mlm");
            } else {
                if ($status == "wait") {
                    $response = __("Your upgrade request is waiting for moderation.", "mlm");
                } else {
                    if (empty($gender) || empty($fname) || empty($lname) || empty($birth) || empty($melli) || empty($address) || empty($phone) || empty($postal)) {
                        $response = __("Marked fields are required.", "mlm");
                    } else {
                        if (!mlm_post_exists($post_id)) {
                            $submit_post = ["post_title" => wp_strip_all_tags(mlm_get_user_name($user_id)), "post_status" => "pending", "post_type" => "mlm-requests", "post_author" => $user_id];
                            $post_id = wp_insert_post($submit_post);
                            update_post_meta($post_id, "mlm_role", $role);
                        } else {
                            $update_post = ["ID" => $post_id, "post_status" => "pending"];
                            wp_update_post($update_post);
                        }
                        update_post_meta($post_id, "mlm_gender", $gender);
                        update_post_meta($post_id, "mlm_fname", $fname);
                        update_post_meta($post_id, "mlm_lname", $lname);
                        update_post_meta($post_id, "mlm_birth", $birth);
                        update_post_meta($post_id, "mlm_melli", $melli);
                        update_post_meta($post_id, "mlm_phone", $phone);
                        update_post_meta($post_id, "mlm_address", $address);
                        update_post_meta($post_id, "mlm_postal", $postal);
                        update_post_meta($post_id, "mlm_status", "nok");
                        $submited = true;
                        $response = __("Form submitted successfully. Loading next step ...", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "redirect" => add_query_arg("paged", 2, $upgrade_url), "response" => $response]);
        wp_die();
    }
    public function upgrade_step_two()
    {
        check_ajax_referer("mlm_gayapidis", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $post_id = mlmFire()->dashboard->get_request_post_id($user_id);
        $upgrade_url = trailingslashit(mlm_page_url("panel")) . "section/upgrade/";
        $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : [];
        $melli = isset($user_input["melli"]) ? esc_url($user_input["melli"]) : "";
        $shena = isset($user_input["shena"]) ? esc_url($user_input["shena"]) : "";
        $status = get_post_meta($post_id, "mlm_status", true);
        if (current_user_can("moderate_comments")) {
            $response = __("You have no need to upgrade. Your account already upgraded.", "mlm");
        } else {
            if ($status == "ok") {
                $response = __("Your upgrade account request is verified already.", "mlm");
            } else {
                if ($status == "wait") {
                    $response = __("Your upgrade request is waiting for moderation.", "mlm");
                } else {
                    if (empty($melli) || empty($shena)) {
                        $response = __("Identity card and birth certificate images are required.", "mlm");
                    } else {
                        if (!mlm_post_exists($post_id)) {
                            $submit_post = ["post_title" => wp_strip_all_tags(mlm_get_user_name($user_id)), "post_status" => "pending", "post_type" => "mlm-requests", "post_author" => $user_id];
                            $post_id = wp_insert_post($submit_post);
                            update_post_meta($post_id, "mlm_role", 1);
                        } else {
                            $update_post = ["ID" => $post_id, "post_status" => "pending"];
                            wp_update_post($update_post);
                        }
                        update_post_meta($post_id, "mlm_melli_file", $melli);
                        update_post_meta($post_id, "mlm_shena_file", $shena);
                        update_post_meta($post_id, "mlm_status", "wait");
                        $submited = true;
                        $response = __("Form submitted successfully. Loading next step ...", "mlm");
                        mlmFire()->notif->send_admin_mail("upgrade", ["user_id" => $user_id]);
                        mlmFire()->notif->send_user_mail($user_id, "upgrade_request");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "redirect" => $upgrade_url, "response" => $response]);
        wp_die();
    }
    public function ajax_search()
    {
        check_ajax_referer("mlm_farolmokr", "security");
        $s = isset($_POST["query"]) ? sanitize_text_field($_POST["query"]) : "";
        $html = "<div class=\"alert alert-warning m-2 py-2\">" . __("No items found.", "mlm") . "</div>";
        if (!empty($s)) {
            $query = new WP_Query(["post_type" => ["post", "product"], "post_status" => "publish", "posts_per_page" => 10, "s" => $s]);
            if ($query->have_posts()) {
                $html = "<ul class=\"m-0 p-1 slimscroll\">";
                while ($query->have_posts()) {
                    $query->the_post();
                    $html .= "<li class=\"d-block py-2 mx-2 my-0 clearfix\">";
                    $html .= "<a href=\"" . get_permalink() . "\" class=\"media align-items-center\">";
                    $html .= "<img src=\"" . mlm_image_url(get_the_ID(), "thumbnail", false) . "\" class=\"item-image ml-1 d-flex\" alt=\"\">";
                    $html .= "<div class=\"media-body\">";
                    $html .= "<h5 class=\"item-title m-0 font-12 bold-500 text-secondary\">" . get_the_title() . "</h5>";
                    $html .= "</div>";
                    $html .= "</a>";
                    $html .= "</li>";
                }
                wp_reset_postdata();
                $html .= "</ul>";
            }
        }
        echo wp_send_json(["html" => $html]);
        wp_die();
    }
    public function submit_coupon()
    {
        check_ajax_referer("mlm_rakonojipan", "security");
        global $woocommerce;
        $submited = false;
        $user_id = get_current_user_id();
        $redirect = trailingslashit(mlm_page_url("panel")) . "section/coupons/";
        $post_data = isset($_POST["post_data"]) ? $_POST["post_data"] : [];
        $post_id = isset($post_data["post_id"]) ? absint($post_data["post_id"]) : 0;
        $code = isset($post_data["code"]) ? sanitize_text_field($post_data["code"]) : "";
        $amount = isset($post_data["amount"]) ? absint($post_data["amount"]) : "";
        $mlm_type = isset($post_data["type"]) ? sanitize_text_field($post_data["type"]) : "";
        $expire = isset($post_data["expire"]) ? sanitize_text_field($post_data["expire"]) : "";
        $products = isset($post_data["products"]) ? mlm_sanitize_array($post_data["products"]) : "";
        $count = is_array($products) ? count($products) : 0;
        $exist = new WC_Coupon($code);
        $can_edit = true;
        if (is_array($products) && count($products)) {
            foreach ($products as $product) {
                if (get_post_field("post_author", $product) != $user_id && !current_user_can("moderate_comments")) {
                    $can_edit = false;
                }
            }
        }
        if (!$can_edit) {
            $response = __("You can't submit coupon for these products.", "mlm");
        } else {
            if (empty($code) || empty($amount) || $count < 1) {
                $response = __("Marked fields are required.", "mlm");
            } else {
                if (!preg_match("/^[a-zA-Z0-9_-]+\$/", $code)) {
                    $response = __("Coupon code must be in latin letters and numbers only.", "mlm");
                } else {
                    if (strlen($code) < 3) {
                        $response = __("Coupon code is too short.", "mlm");
                    } else {
                        if (20 < strlen($code)) {
                            $response = __("Coupon code is too long.", "mlm");
                        } else {
                            if ($exist->get_id() && $exist->get_id() != $post_id) {
                                $response = __("Coupon code already exists.", "mlm");
                            } else {
                                if ($amount < 1 || 100 < $amount) {
                                    $response = __("Percent amount must be a numeric value between 1 and 100.", "mlm");
                                } else {
                                    if ($post_id) {
                                        if (!mlm_post_exists($post_id)) {
                                            $response = __("Coupon ID is invalid.", "mlm");
                                        } else {
                                            $author = get_post_field("post_author", $post_id);
                                            $type = get_post_field("post_type", $post_id);
                                            $status = get_post_field("post_status", $post_id);
                                            if ($author != $user_id || $type != "shop_coupon" || $status != "publish") {
                                                $response = __("You are not allowed to do this.", "mlm");
                                            } else {
                                                $update_post = ["ID" => $post_id, "post_title" => $code, "post_status" => "publish"];
                                                wp_update_post($update_post);
                                                $submited = true;
                                                $response = __("Coupon updated successfully.", "mlm");
                                                $redirect = add_query_arg("updated", "OK", $redirect);
                                            }
                                        }
                                    } else {
                                        $submit_post = ["post_title" => $code, "post_content" => "", "post_status" => "publish", "post_type" => "shop_coupon", "post_author" => $user_id];
                                        $post_id = wp_insert_post($submit_post);
                                        $submited = true;
                                        $response = __("Coupon submitted successfully.", "mlm");
                                        $redirect = add_query_arg("submited", "OK", $redirect);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($expire)) {
            $explode_finish = explode("-", $expire);
            $expire = mlm_jmktime(23, 59, 59, $explode_finish[1], $explode_finish[2], $explode_finish[0]);
        }
        update_post_meta($post_id, "individual_use", "yes");
        update_post_meta($post_id, "discount_type", "percent");
        update_post_meta($post_id, "usage_limit", 0);
        update_post_meta($post_id, "limit_usage_to_x_items", 0);
        update_post_meta($post_id, "usage_limit_per_user", 0);
        update_post_meta($post_id, "coupon_amount", $amount);
        update_post_meta($post_id, "mlm_type", $mlm_type);
        update_post_meta($post_id, "date_expires", $expire);
        update_post_meta($post_id, "product_ids", implode(",", $products));
        update_post_meta($post_id, "free_shipping", "no");
        update_post_meta($post_id, "apply_before_tax", "yes");
        echo wp_send_json(["submited" => $submited, "response" => $response, "redirect" => $redirect]);
        wp_die();
    }
    public function delete_coupon()
    {
        check_ajax_referer("mlm_rakonojipan", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $post_id = isset($_POST["post_id"]) ? absint($_POST["post_id"]) : 0;
        if (!mlm_post_exists($post_id)) {
            $response = __("Coupon ID is invalid.", "mlm");
        } else {
            $author = get_post_field("post_author", $post_id);
            $type = get_post_field("post_type", $post_id);
            $status = get_post_field("post_status", $post_id);
            if ($author != $user_id && !current_user_can("moderate_comments") || $type != "shop_coupon" || $status != "publish") {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                wp_delete_post($post_id);
                $submited = true;
                $response = __("Coupon deleted successfully.", "mlm");
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function purchase_plan()
    {
        check_ajax_referer("mlm_zoxolunsaw", "security");
        $submited = false;
        $redirect = "";
        $user_id = get_current_user_id();
        $plan_id = isset($_POST["plan_id"]) ? absint($_POST["plan_id"]) : "";
        $plan_data = mlmFire()->plan->get_plans($plan_id);
        $user_balance = mlmFire()->wallet->get_balance($user_id);
        if (empty($plan_id) || !isset($plan_data["id"])) {
            $response = __("Plan ID is invalid. Please reload the page.", "mlm");
        } else {
            if ($plan_data["time"] < 1) {
                $response = __("Selected plan time is invalid. Please contact the site support.", "mlm");
            } else {
                if ($user_balance < $plan_data["price"]) {
                    global $woocommerce;
                    $user_obj = get_userdata($user_id);
                    $address = ["first_name" => $user_obj->first_name, "last_name" => $user_obj->last_name, "email" => $user_obj->user_email, "phone" => get_user_meta($user_id, "mlm_mobile", true)];
                    $order = wc_create_order(["customer_id" => $user_id]);
                    $order->set_address($address, "billing");
                    $order->set_address($address, "shipping");
                    $order->update_status("pending");
                    $item_id = wc_add_order_item($order->get_id(), ["order_item_name" => $plan_data["name"], "order_item_type" => "fee"]);
                    wc_add_order_item_meta($item_id, "_line_total", $plan_data["price"]);
                    update_post_meta($order->get_id(), "_mlm_subscribtion", 1);
                    update_post_meta($order->get_id(), "_mlm_plan_id", $plan_id);
                    $order->calculate_totals();
                    $submited = true;
                    $redirect = $order->get_checkout_payment_url();
                    $response = __("Request submitted. Redirecting ...", "mlm");
                } else {
                    $order_id = mlmFire()->db->wallet_record($user_id, 0, 0, $plan_data["price"], 8, 2, __("Purchase subscription plan", "mlm"));
                    if ($order_id) {
                        mlmFire()->wallet->update_meta($user_id, "mlm_balance", $plan_data["price"], "minus");
                        $sub_id = mlmFire()->db->subscribe_record($user_id, $order_id, $plan_data);
                        if ($sub_id) {
                            mlmFire()->db->subscribe_update($sub_id, ["status" => 1]);
                            mlmFire()->plan->set_user_active_plan($sub_id, $user_id, $plan_data["id"]);
                            mlmFire()->notif->send_user_sms($user_id, "plan_activated", ["plan_id" => $plan_data["id"], "plan_name" => $plan_data["name"]]);
                            $submited = true;
                            $response = __("Subscription plan activated successfully.", "mlm");
                        } else {
                            $response = __("Unknown error occurred. Please try again.", "mlm");
                        }
                    } else {
                        $response = __("Unknown error occurred. Please try again.", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "redirect" => $redirect, "response" => $response]);
        wp_die();
    }
    public function admin_new_subscribe()
    {
        check_ajax_referer("mlm_takafopij", "security");
        $submited = false;
        $redirect = false;
        $plan_data = isset($_POST["plan_data"]) ? $_POST["plan_data"] : [];
        $user_id = isset($plan_data["user"]) ? absint($plan_data["user"]) : "";
        $plan_id = isset($plan_data["plan"]) ? absint($plan_data["plan"]) : "";
        $plan_data = mlmFire()->plan->get_plans($plan_id);
        if (!mlm_user_exists($user_id)) {
            $response = __("User ID is invalid.", "mlm");
        } else {
            if (!$plan_data) {
                $response = __("Plan ID is invalid.", "mlm");
            } else {
                $time = isset($plan_data["time"]) ? absint($plan_data["time"]) : 0;
                $amount = isset($plan_data["price"]) ? absint($plan_data["price"]) : 0;
                if ($time < 1) {
                    $response = __("Selected plan time is not valid. Please check the plan settings.", "mlm");
                } else {
                    $sub_id = mlmFire()->db->subscribe_record($user_id, 0, $plan_data, 3);
                    if ($sub_id) {
                        mlmFire()->db->subscribe_update($sub_id, ["status" => 1]);
                        mlmFire()->plan->set_user_active_plan($sub_id, $user_id, $plan_id);
                        $submited = true;
                        $response = __("Subscription plan submitted successfully. Loading ...", "mlm");
                        $nonce = wp_create_nonce("mlm_subscribe_lex");
                        $redirect = admin_url("admin.php?page=mlm-subscribes&id=" . $sub_id . "&verify=" . $nonce);
                    } else {
                        $response = __("Unknown error occurred. Please try again.", "mlm");
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "redirect" => $redirect, "response" => $response]);
        wp_die();
    }
    public function activate_theme()
    {
        check_ajax_referer("mlm_uyfaloji", "security");
        $submited = false;
        $token = isset($_POST["token"]) ? sanitize_text_field($_POST["token"]) : "";
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (empty($token)) {
                $response = __("License code is required.", "mlm");
            } else {
                $result = mlmFire()->shop->install($token);
                if ($result->status == "successful") {
                    $submited = true;
                    $response = $result->message;
                    $token = base64_encode($token);
                    update_option("wp_permalink_tk", $token);
                    update_option("wp_permalink_lc", $token);
                    update_option("wp_permalink_dm", get_home_url());
                } else {
                    if (!is_object($result->message)) {
                        $response = $result->message;
                    } else {
                        $response = "";
                        foreach ($result->message as $message) {
                            foreach ($message as $msg) {
                                $response .= $msg . "<br>";
                            }
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function save_chapter()
    {
        check_ajax_referer("mlm_lhsaugpqytsr", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $form_data = isset($_POST["form_data"]) ? $_POST["form_data"] : [];
        $post_id = isset($form_data["post_id"]) ? absint($form_data["post_id"]) : "";
        $chapter_id = isset($form_data["chapter"]) ? absint($form_data["chapter"]) : "";
        $image_id = isset($form_data["image"]) ? absint($form_data["image"]) : "";
        $number = isset($form_data["number"]) ? absint($form_data["number"]) : "";
        $title = isset($form_data["title"]) ? sanitize_text_field($form_data["title"]) : "";
        $desc = isset($form_data["desc"]) ? sanitize_text_field($form_data["desc"]) : "";
        $author = get_post_field("post_author", $post_id);
        $type = get_post_field("post_type", $post_id);
        if (!mlm_post_exists($post_id) || $type != "product") {
            $response = __("Product ID is invalid.", "mlm");
        } else {
            if (!current_user_can("moderate_comments") && $author != $user_id) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if (empty($number) || empty($title) || empty($desc)) {
                    $response = __("Marked fields are required.", "mlm");
                } else {
                    if ($chapter_id) {
                        $course_data = ["priority" => $number, "course_data" => ["title" => $title, "text" => $desc, "image_id" => $image_id]];
                        mlmFire()->db->course_update($chapter_id, $course_data);
                        $submited = true;
                        $response = __("Article updated successfully.", "mlm");
                    } else {
                        $course_data = ["title" => $title, "text" => $desc, "image_id" => $image_id];
                        $result = mlmFire()->db->course_record($post_id, 0, $number, $course_data);
                        if ($result) {
                            $submited = true;
                            $response = __("Article submitted successfully.", "mlm");
                        } else {
                            $response = __("Unknown error occurred. Please try again.", "mlm");
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function delete_chapter()
    {
        check_ajax_referer("mlm_lhsaugpqytsr", "security");
        $deleted = false;
        $user_id = get_current_user_id();
        $chapter_id = isset($_POST["chapter_id"]) ? absint($_POST["chapter_id"]) : "";
        $course_obj = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE id = %d LIMIT %d", [$chapter_id, 1], "course", true);
        if (!isset($course_obj->post_id)) {
            $response = __("Article ID is invalid.", "mlm");
        } else {
            $author = get_post_field("post_author", $course_obj->post_id);
            if (!current_user_can("moderate_comments") && $author != $user_id) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                mlmFire()->db->course_delete($chapter_id);
                $childs = mlmFire()->db->query_rows("SELECT id FROM {TABLE} WHERE parent_id = %d", [$chapter_id], "course");
                if (!empty($childs)) {
                    foreach ($childs as $child) {
                        mlmFire()->db->course_delete($child->id);
                    }
                }
                $deleted = true;
                $response = __("Article deleted successfully.", "mlm");
            }
        }
        echo wp_send_json(["deleted" => $deleted, "response" => $response]);
        wp_die();
    }
    public function save_lesson()
    {
        check_ajax_referer("mlm_lhsaugpqytsr", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $form_data = isset($_POST["form_data"]) ? $_POST["form_data"] : [];
        $post_id = isset($form_data["post_id"]) ? absint($form_data["post_id"]) : "";
        $lesson_id = isset($form_data["lesson"]) ? absint($form_data["lesson"]) : "";
        $chapter_id = isset($form_data["chapter"]) ? absint($form_data["chapter"]) : "";
        $number = isset($form_data["number"]) ? absint($form_data["number"]) : "";
        $content = isset($form_data["content"]) ? wp_filter_post_kses($form_data["content"]) : "";
        $title = isset($form_data["title"]) ? sanitize_text_field($form_data["title"]) : "";
        $desc = isset($form_data["desc"]) ? sanitize_text_field($form_data["desc"]) : "";
        $status = isset($form_data["status"]) ? sanitize_text_field($form_data["status"]) : "";
        $files = isset($form_data["mlm_file[i"]) ? mlm_sanitize_array($form_data["mlm_file[i"]) : "";
        $author = get_post_field("post_author", $post_id);
        $type = get_post_field("post_type", $post_id);
        $course_obj = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE id = %d LIMIT %d", [$chapter_id, 1], "course", true);
        if (!mlm_post_exists($post_id) || $type != "product") {
            $response = __("Product ID is invalid.", "mlm");
        } else {
            if (!current_user_can("moderate_comments") && $author != $user_id) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                if (!isset($course_obj->post_id)) {
                    $response = __("Article ID is invalid.", "mlm");
                } else {
                    if (empty($number) || empty($title) || empty($desc) || empty($status)) {
                        $response = __("Marked fields are required.", "mlm");
                    } else {
                        if ($lesson_id) {
                            $course_data = ["priority" => $number, "course_data" => ["title" => $title, "text" => $desc, "status" => $status, "content" => $content, "links" => $files]];
                            mlmFire()->db->course_update($lesson_id, $course_data);
                            $submited = true;
                            $response = __("Lesson updated successfully.", "mlm");
                        } else {
                            $course_data = ["title" => $title, "text" => $desc, "status" => $status, "content" => $content, "links" => $files];
                            $result = mlmFire()->db->course_record($post_id, $chapter_id, $number, $course_data);
                            if ($result) {
                                $submited = true;
                                $response = __("Lesson submitted successfully.", "mlm");
                            } else {
                                $response = __("Unknown error occurred. Please try again.", "mlm");
                            }
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function delete_lesson()
    {
        check_ajax_referer("mlm_lhsaugpqytsr", "security");
        $deleted = false;
        $user_id = get_current_user_id();
        $lesson_id = isset($_POST["lesson_id"]) ? absint($_POST["lesson_id"]) : "";
        $course_obj = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE id = %d LIMIT %d", [$lesson_id, 1], "course", true);
        if (!isset($course_obj->post_id)) {
            $response = __("Lesson ID is invalid.", "mlm");
        } else {
            $author = get_post_field("post_author", $course_obj->post_id);
            if (!current_user_can("moderate_comments") && $author != $user_id) {
                $response = __("You are not allowed to do this.", "mlm");
            } else {
                mlmFire()->db->course_delete($lesson_id);
                $deleted = true;
                $response = __("Lesson deleted successfully.", "mlm");
            }
        }
        echo wp_send_json(["deleted" => $deleted, "response" => $response]);
        wp_die();
    }
    public function hide_notification()
    {
        setcookie("mlm_hide_notif", 1, time() + 3600, "/");
        echo wp_send_json(["submited" => true]);
        wp_die();
    }
    public function save_fields()
    {
        check_ajax_referer("mlm_asyfkashc", "security");
        $submited = false;
        $form_data = isset($_POST["form_data"]) ? mlm_sanitize_array($_POST["form_data"]) : "";
        $fields = isset($form_data["mlm_field[i"]) ? $form_data["mlm_field[i"] : "";
        $new_fields = [];
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (is_array($fields) && 0 < count($fields)) {
                foreach ($fields as $item) {
                    if (!empty($item["text"])) {
                        $new_fields[] = $item;
                    }
                }
            }
            if (is_array($new_fields) && 0 < count($new_fields)) {
                update_option("mlm_fields", $new_fields);
            } else {
                delete_option("mlm_fields");
            }
            $submited = true;
            $response = __("Saved changes.", "mlm");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function delete_trans_callback()
    {
        check_ajax_referer("mlm_ygrftafdew", "security");
        $deleted = false;
        $trans_id = isset($_POST["trans_id"]) ? absint($_POST["trans_id"]) : 0;
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (empty($trans_id)) {
                $response = __("Invalid transaction ID.", "mlm");
            } else {
                $res = mlmfire()->db->wallet_delete($trans_id);
                if ($res) {
                    $deleted = true;
                    $response = __("Transaction deleted successfully.", "mlm");
                } else {
                    $response = __("Unknown error occurred. Please try again.", "mlm");
                }
            }
        }
        echo wp_send_json(["deleted" => $deleted, "response" => $response]);
        wp_die();
    }
    public function save_sms_texts()
    {
        check_ajax_referer("mlm_lsadjyfast", "security");
        $submited = false;
        $form_data = isset($_POST["form_data"]) ? $_POST["form_data"] : "";
        $texts = isset($form_data["mlm_sms[i"]) ? $form_data["mlm_sms[i"] : "";
        $new_texts = [];
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (is_array($texts) && 0 < count($texts)) {
                foreach ($texts as $k => $v) {
                    $new_texts[$k] = esc_textarea($v);
                }
            }
            if (is_array($new_texts) && 0 < count($new_texts)) {
                update_option("mlm_sms_texts", $new_texts);
            } else {
                delete_option("mlm_sms_texts");
            }
            $submited = true;
            $response = __("Saved changes.", "mlm");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function save_patterns()
    {
        check_ajax_referer("mlm_lsadjyfast", "security");
        $submited = false;
        $form_data = isset($_POST["form_data"]) ? $_POST["form_data"] : "";
        $patterns = isset($form_data["mlm_pattern[i"]) ? $form_data["mlm_pattern[i"] : "";
        $new_val = [];
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (is_array($patterns) && 0 < count($patterns)) {
                foreach ($patterns as $k => $v) {
                    $new_val[$k] = sanitize_text_field($v);
                }
            }
            if (is_array($new_val) && 0 < count($new_val)) {
                update_option("mlm_sms_patterns", $new_val);
            } else {
                delete_option("mlm_sms_patterns");
            }
            $submited = true;
            $response = __("Saved changes.", "mlm");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function save_mail_texts()
    {
        check_ajax_referer("mlm_lsadjyfast", "security");
        $submited = false;
        $texts = isset($_POST["form_data"]) ? $_POST["form_data"] : "";
        $new_texts = [];
        if (!current_user_can("manage_options")) {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if (is_array($texts) && 0 < count($texts)) {
                foreach ($texts as $k => $v) {
                    $new_texts[$k] = wp_filter_post_kses($v);
                }
            }
            if (is_array($new_texts) && 0 < count($new_texts)) {
                update_option("mlm_mail_texts", $new_texts);
            } else {
                delete_option("mlm_mail_texts");
            }
            $submited = true;
            $response = __("Saved changes.", "mlm");
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function upload_file()
    {
        check_ajax_referer("mlm_bngadsrwa", "security");
        $id = 0;
        $url = false;
        $uploaded = false;
        $file = isset($_FILES["file"]) ? $_FILES["file"] : "";
        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);
        $user_name = $user_data->user_login;
        $allowed = ["mp3", "mp4", "png", "jpg", "gif", "mpg", "amr", "avi", "wma", "m4a", "aac", "wav", "pdf", "ppt", "doc", "docx", "text", "excel", "zip", "epub", "srt", "mkv"];
        $max = 2122317824;
        $ftp_url = get_option("mlm_ftp_url");
        $ftp_user = get_option("mlm_ftp_user");
        $ftp_pass = get_option("mlm_ftp_pass");
        $ftp_link = get_option("mlm_ftp_link");
        if (!current_user_can("upload_files")) {
            $response = __("You are not allowed to upload files.", "mlm");
        } else {
            if (empty($ftp_url) || empty($ftp_user) || empty($ftp_pass) || empty($ftp_link)) {
                $response = __("FTP upload is disabled at the moment.", "mlm");
            } else {
                if (isset($file) && !empty($file)) {
                    if ($file["error"] === 0) {
                        $uploads = wp_upload_dir();
                        $path = str_replace("\\", "/", $uploads["basedir"]);
                        $base_url = str_replace("\\", "/", $uploads["baseurl"]);
                        $sub = str_replace("\\", "/", $uploads["subdir"]);
                        $folder = trailingslashit($path) . $sub;
                        $folder_url = trailingslashit($base_url) . $sub;
                        if (!file_exists($folder)) {
                            wp_mkdir_p($folder);
                        }
                        $temp = $file["tmp_name"];
                        $ext = explode(".", $file["name"]);
                        $ext = strtolower(end($ext));
                        $full = $user_name . "-" . time() . "." . $ext;
                        if (!in_array($ext, $allowed)) {
                            $formats = implode(" - ", $allowed);
                            $response = sprintf(__("File format is not valid. Allowed formats are %s", "mlm"), $formats);
                        } else {
                            if ($max < $file["size"]) {
                                $response = sprintf(__("Maximum upload size is %s", "mlm"), mlm_read_bytes($max));
                            } else {
                                if (move_uploaded_file($temp, $folder . "/" . $full)) {
                                    $connection = ftp_connect($ftp_url);
                                    if (@ftp_login($connection, $ftp_user, $ftp_pass)) {
                                        ftp_put($connection, $full, $folder . "/" . $full, FTP_BINARY);
                                        ftp_close($connection);
                                        $url = trailingslashit($ftp_link) . $full;
                                        $uploaded = true;
                                        $response = __("File uploaded successfully.", "mlm");
                                    } else {
                                        $response = __("FTP connection failed.", "mlm");
                                    }
                                    @unlink($folder . "/" . $full);
                                } else {
                                    $response = __("File upload failed. Please try again.", "mlm");
                                }
                            }
                        }
                    } else {
                        $response = __("File upload failed. Please try again.", "mlm");
                    }
                } else {
                    $response = __("No files selected.", "mlm");
                }
            }
        }
        echo json_encode(["id" => $id, "url" => $url, "uploaded" => $uploaded, "response" => $response]);
        exit;
    }
    public function upload_attach_file()
    {
        check_ajax_referer("mlm_asdkugfas", "security");
        $id = 0;
        $url = false;
        $uploaded = false;
        $file = isset($_FILES["file"]) ? $_FILES["file"] : "";
        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);
        $user_name = $user_data->user_login;
        $allowed = ["jpg", "jpeg", "png", "gif"];
        $max = 5242880;
        if (!current_user_can("upload_files")) {
            $response = __("You are not allowed to upload files.", "mlm");
        } else {
            if (isset($file) && !empty($file)) {
                if ($file["error"] === 0) {
                    $uploads = wp_upload_dir();
                    $path = str_replace("\\", "/", $uploads["basedir"]);
                    $base_url = str_replace("\\", "/", $uploads["baseurl"]);
                    $sub = str_replace("\\", "/", $uploads["subdir"]);
                    $folder = trailingslashit($path) . $sub;
                    $folder_url = trailingslashit($base_url) . $sub;
                    if (!file_exists($folder)) {
                        wp_mkdir_p($folder);
                    }
                    $temp = $file["tmp_name"];
                    $ext = explode(".", $file["name"]);
                    $ext = strtolower(end($ext));
                    $full = $user_name . "-" . time() . "." . $ext;
                    if (!in_array($ext, $allowed)) {
                        $formats = implode(" - ", $allowed);
                        $response = sprintf(__("File format is not valid. Allowed formats are %s", "mlm"), $formats);
                    } else {
                        if ($max < $file["size"]) {
                            $response = sprintf(__("Maximum upload size is %s", "mlm"), mlm_read_bytes($max));
                        } else {
                            if (move_uploaded_file($temp, $folder . "/" . $full)) {
                                $connection = ftp_connect($ftp_url);
                                if (@ftp_login($connection, $ftp_user, $ftp_pass)) {
                                    ftp_put($connection, $full, $folder . "/" . $full, FTP_BINARY);
                                    ftp_close($connection);
                                    $url = trailingslashit($ftp_link) . $full;
                                    $uploaded = true;
                                    $response = __("File uploaded successfully.", "mlm");
                                    @unlink($folder . "/" . $full);
                                } else {
                                    $url = trailingslashit($folder_url) . $full;
                                    $uploaded = true;
                                    $response = __("File uploaded successfully.", "mlm");
                                }
                            } else {
                                $response = __("File upload failed. Please try again.", "mlm");
                            }
                        }
                    }
                } else {
                    $response = __("File upload failed. Please try again.", "mlm");
                }
            } else {
                $response = __("No files selected.", "mlm");
            }
        }
        echo json_encode(["id" => $id, "url" => $url, "uploaded" => $uploaded, "response" => $response]);
        exit;
    }
    public function send_mobile_code()
    {
        check_ajax_referer("mlm_ujakopibar", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $mobile_verify = get_option("mlm_verify_mobile");
        $mobile_verified = get_user_meta($user_id, "mlm_mobile_verified", true);
        $mobile_code = get_user_meta($user_id, "mlm_mobile_verify_code", true);
        $mobile_saved = get_user_meta($user_id, "mlm_mobile_verify_db", true);
        $mobile = isset($_POST["mobile"]) ? sanitize_text_field($_POST["mobile"]) : "";
        if ($mobile_verify != "yes") {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if ($mobile_verified) {
                $response = __("You already verified your mobile number.", "mlm");
            } else {
                if (empty($mobile)) {
                    $response = __("Mobile field is required.", "mlm");
                } else {
                    if (!mlm_is_mobile($mobile)) {
                        $response = __("Enter mobile number as a 11-digit numeric value.", "mlm");
                    } else {
                        if (mlm_mobile_exists($mobile, $user_id)) {
                            $response = __("Mobile number already registered.", "mlm");
                        } else {
                            $code = rand(10000, 99999);
                            $result = mlmFire()->notif->send_user_sms($user_id, "verify_code", ["code" => $code]);
                            update_user_meta($user_id, "mlm_mobile_verify_code", $code);
                            update_user_meta($user_id, "mlm_mobile_verify_db", $mobile);
                            update_user_meta($user_id, "mlm_mobile", $mobile);
                            $submited = true;
                            $response = __("Verification code sent successfully.", "mlm");
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function send_email_code()
    {
        check_ajax_referer("mlm_ujakopibar", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $email_verify = get_option("mlm_verify_email");
        $email_verified = get_user_meta($user_id, "mlm_email_verified", true);
        $email_code = get_user_meta($user_id, "mlm_email_verify_code", true);
        $email_saved = get_user_meta($user_id, "mlm_email_verify_db", true);
        $email = isset($_POST["email"]) ? sanitize_text_field($_POST["email"]) : "";
        $user_info = get_userdata($user_id);
        $u_email = $user_info->user_email;
        $s_email = sanitize_email($email);
        if ($email_verify != "yes") {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if ($email_verified) {
                $response = __("You already verified your email address.", "mlm");
            } else {
                if (empty($email)) {
                    $response = __("Email field is required.", "mlm");
                } else {
                    if (!is_email($s_email)) {
                        $response = __("Email address is not valid.", "mlm");
                    } else {
                        if (email_exists($s_email) && $u_email != $s_email) {
                            $response = __("Email address already registered.", "mlm");
                        } else {
                            $code = rand(10000, 99999);
                            $result = mlmFire()->notif->send_user_mail($user_id, "verify_code", ["code" => $code]);
                            wp_update_user(["ID" => $user_id, "user_email" => $s_email]);
                            update_user_meta($user_id, "mlm_email_verify_code", $code);
                            update_user_meta($user_id, "mlm_email_verify_db", $s_email);
                            $submited = true;
                            $response = __("Verification code sent successfully.", "mlm");
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function verify_mobile()
    {
        check_ajax_referer("mlm_ujakopibar", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $mobile_verify = get_option("mlm_verify_mobile");
        $mlm_mobile = get_user_meta($user_id, "mlm_mobile", true);
        $mobile_verified = get_user_meta($user_id, "mlm_mobile_verified", true);
        $mobile_code = get_user_meta($user_id, "mlm_mobile_verify_code", true);
        $mobile_saved = get_user_meta($user_id, "mlm_mobile_verify_db", true);
        $code = isset($_POST["code"]) ? sanitize_text_field($_POST["code"]) : "";
        $mobile = isset($_POST["mobile"]) ? sanitize_text_field($_POST["mobile"]) : "";
        if ($mobile_verify != "yes") {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if ($mobile_verified) {
                $response = __("You already verified your mobile number.", "mlm");
            } else {
                if (empty($mobile)) {
                    $response = __("Mobile field is required.", "mlm");
                } else {
                    if (!mlm_is_mobile($mobile)) {
                        $response = __("Enter mobile number as a 11-digit numeric value.", "mlm");
                    } else {
                        if ($mobile != $mlm_mobile) {
                            $response = __("Code is not sent to this mobile number. Click the send code button first.", "mlm");
                        } else {
                            if ($mobile_code != $code) {
                                $response = __("The code is not correct.", "mlm");
                            } else {
                                update_user_meta($user_id, "mlm_mobile_verified", 1);
                                $submited = true;
                                $response = __("Mobile verified successfully", "mlm");
                            }
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
    public function verify_email()
    {
        check_ajax_referer("mlm_ujakopibar", "security");
        $submited = false;
        $user_id = get_current_user_id();
        $email_verify = get_option("mlm_verify_email");
        $user_info = get_userdata($user_id);
        $mlm_email = $user_info->user_email;
        $email_verified = get_user_meta($user_id, "mlm_email_verified", true);
        $email_code = get_user_meta($user_id, "mlm_email_verify_code", true);
        $email_saved = get_user_meta($user_id, "mlm_email_verify_db", true);
        $code = isset($_POST["code"]) ? sanitize_text_field($_POST["code"]) : "";
        $email = isset($_POST["email"]) ? sanitize_text_field($_POST["email"]) : "";
        if ($email_verify != "yes") {
            $response = __("You are not allowed to do this.", "mlm");
        } else {
            if ($email_verified) {
                $response = __("You already verified your email address.", "mlm");
            } else {
                if (empty($email)) {
                    $response = __("Email field is required.", "mlm");
                } else {
                    if (!is_email($email)) {
                        $response = __("Email address is not valid.", "mlm");
                    } else {
                        if ($mlm_email != $email) {
                            $response = __("Code is not sent to this email address. Click the send code button first.", "mlm");
                        } else {
                            if ($email_code != $code) {
                                $response = __("The code is not correct.", "mlm");
                            } else {
                                update_user_meta($user_id, "mlm_email_verified", 1);
                                $submited = true;
                                $response = __("Email verified successfully", "mlm");
                            }
                        }
                    }
                }
            }
        }
        echo wp_send_json(["submited" => $submited, "response" => $response]);
        wp_die();
    }
}

?>