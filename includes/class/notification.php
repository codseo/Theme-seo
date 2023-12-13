<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Notification
{
    public function __construct()
    {
        add_action("comment_post", [$this, "comment_notifications"], 10, 3);
        add_action("admin_menu", [$this, "menu_pages"], 10);
    }
    public function get_site_domain()
    {
        $site_url = esc_url(home_url());
        $site_domain = preg_replace("#^http(s)?://#", "", $site_url);
        $site_domain = preg_replace("/^www\\./", "", $site_domain);
        return $site_domain;
    }
    public function send_sms($mobile, $text)
    {
        $sms_panel = get_option("mlm_sms_panel");
        if (empty($mobile) || empty($text) || empty($sms_panel)) {
            return false;
        }
        $result = mlmFire()->sms->{$sms_panel}($mobile, $text);
        return $result;
    }
    public function send_email($to, $subject, $body)
    {
        if (empty($to) || empty($subject) || empty($body)) {
            return false;
        }
        add_filter("wp_mail_content_type", function ($content_type) {
            return "text/html";
        });
        $site_logo = get_option("mlm_logo");
        $telegram = get_option("mlm_sc_telegram");
        $instagram = get_option("mlm_sc_instagram");
        $site_title = get_bloginfo("name");
        $site_description = get_bloginfo("description");
        $site_domain = $this->get_site_domain();
        $template = mlm_get_template("class/wp-admin/mail-template");
        if (empty($site_logo)) {
            $site_logo = IMAGES . "/mail-template/logo.png";
        }
        $template = str_replace("{site_logo}", esc_url($site_logo), $template);
        $template = str_replace("{telegram}", $telegram, $template);
        $template = str_replace("{instagram}", $instagram, $template);
        $template = str_replace("{site_title}", $site_title, $template);
        $template = str_replace("{site_description}", $site_description, $template);
        $template = str_replace("{site_url}", home_url(), $template);
        $template = str_replace("{site_domain}", $site_domain, $template);
        $template = str_replace("{mail_subject}", $subject, $template);
        $template = str_replace("{mail_body}", $body, $template);
        $headers = ["Content-Type: text/html; charset=UTF-8"];
        $result = wp_mail($to, $subject, $template, $headers);
        remove_filter("wp_mail_content_type", function ($content_type) {
            return "text/html";
        });
        return $result;
    }
    public function send_admin_sms($case, $extra = [])
    {
        switch ($case) {
            case "new_ticket":
                $ticket_id = isset($extra["ticket_id"]) ? $extra["ticket_id"] : "";
                $text = __("Hello", "mlm") . "\r\n";
                $text .= sprintf(__("You have a new ticket at %s. Please check it as soon as you can.", "mlm"), get_bloginfo("name"));
                break;
            default:
                $text = "";
                if (empty($text)) {
                    return false;
                }
                $mobile = get_option("mlm_admin_mobile");
                return $this->send_sms($mobile, $text);
        }
    }
    public function send_admin_mail($case, $extra = [])
    {
        switch ($case) {
            case "withdrawal":
                $user_id = isset($extra["user_id"]) ? $extra["user_id"] : "";
                $amount = isset($extra["amount"]) ? $extra["amount"] : "";
                $title = __("New withdrawal request", "mlm");
                $content = "<p>" . __("New withdrawal request", "mlm") . "</p>";
                $content .= sprintf("<p>" . __("Amount", "mlm") . ": %s</p>", mlm_filter($amount));
                $content .= sprintf("<p>" . __("User", "mlm") . ": %s</p>", mlm_get_user_name($user_id));
                break;
            case "upgrade":
                $user_id = isset($extra["user_id"]) ? $extra["user_id"] : "";
                $title = __("Upgrade account request", "mlm");
                $content = "<p>" . __("New upgrade request has been submitted on your site.", "mlm") . "</p>";
                $content .= sprintf("<p>" . __("User", "mlm") . ": %s</p>", mlm_get_user_name($user_id));
                break;
            case "post_moderation":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("Pending post", "mlm");
                $content = "<p>" . __("New post has been submitted on your site and waiting for moderation.", "mlm") . "</p>";
                $content .= sprintf("<p>" . __("Post title", "mlm") . ": %s</p>", mlm_get_post_title($post_id));
                break;
            case "product_moderation":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("Pending product", "mlm");
                $content = "<p>" . __("New product has been submitted on your site and waiting for moderation.", "mlm") . "</p>";
                $content .= sprintf("<p>" . __("Product title", "mlm") . ": %s</p>", mlm_get_post_title($post_id));
                break;
            default:
                $title = "";
                $content = "";
                if (empty($title) || empty($content)) {
                    return false;
                }
                $mail = get_option("admin_email");
                return $this->send_email($mail, $title, $content);
        }
    }
    public function send_user_sms($user_id, $case, $extra = [])
    {
        $texts = get_option("mlm_sms_texts");
        $pattern = get_option("mlm_sms_pattern");
        $patterns = get_option("mlm_sms_patterns");
        $sms_panel = get_option("mlm_sms_panel");
        $patt_code = "";
        $patt_data = [];
        switch ($case) {
            case "register":
                $user_name = isset($extra["user_name"]) ? $extra["user_name"] : "";
                $password = isset($extra["password"]) ? $extra["password"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["register"]) ? $patterns["register"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "user" => $user_name, "password" => $password];
                } else {
                    if (isset($texts["register"]) && !empty($texts["register"])) {
                        $text = $texts["register"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{USERNAME}", $user_name, $text);
                        $text = str_replace("{PASSWORD}", $password, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("You have registered successfully on %s", "mlm"), get_bloginfo("name")) . "\r\n";
                        $text .= sprintf(__("Login: %s", "mlm"), $user_name) . "\r\n";
                        $text .= sprintf(__("Password: %s", "mlm"), $password) . "\r\n";
                        $text .= __("Please don't share your account info with anyone.", "mlm");
                    }
                }
                break;
            case "lost_code":
                $code = isset($extra["code"]) ? $extra["code"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["lost_code"]) ? $patterns["lost_code"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "code" => $code];
                } else {
                    if (isset($texts["lost_code"]) && !empty($texts["lost_code"])) {
                        $text = $texts["lost_code"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{CODE}", $code, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("Your verification code at %s", "mlm"), get_bloginfo("name")) . "\r\n";
                        $text .= sprintf("%s", $code) . "\r\n";
                        $text .= __("Delete this if you don't want to change anything.", "mlm");
                    }
                }
                break;
            case "verify_code":
                $code = isset($extra["code"]) ? $extra["code"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["verify_code"]) ? $patterns["verify_code"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "code" => $code];
                } else {
                    if (isset($texts["verify_code"]) && !empty($texts["verify_code"])) {
                        $text = $texts["verify_code"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{CODE}", $code, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("Your verification code at %s", "mlm"), get_bloginfo("name")) . "\r\n";
                        $text .= sprintf("%s", $code) . "\r\n";
                    }
                }
                break;
            case "product_published":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["product_published"]) ? $patterns["product_published"] : "";
                    $patt_data = ["title" => mlm_get_post_title($post_id)];
                } else {
                    if (isset($texts["product_published"]) && !empty($texts["product_published"])) {
                        $text = $texts["product_published"];
                        $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                    } else {
                        $text = sprintf(__("Congratulations! Your product, \"%s\", has been verified and published.", "mlm"), mlm_get_post_title($post_id));
                    }
                }
                break;
            case "product_rejected":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $reason = isset($extra["reason"]) ? $extra["reason"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["product_rejected"]) ? $patterns["product_rejected"] : "";
                    $patt_data = ["title" => mlm_get_post_title($post_id), "reason" => $reason];
                } else {
                    if (isset($texts["product_rejected"]) && !empty($texts["product_rejected"])) {
                        $text = $texts["product_rejected"];
                        $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                        $text = str_replace("{REASON}", $reason, $text);
                    } else {
                        $text = sprintf(__("Sorry! Your product, \"%s\" has been rejected.", "mlm"), mlm_get_post_title($post_id)) . "\r\n";
                        $text .= sprintf(__("Reject reason: %s", "mlm"), $reason);
                    }
                }
                break;
            case "post_published":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["post_published"]) ? $patterns["post_published"] : "";
                    $patt_data = ["title" => mlm_get_post_title($post_id)];
                } else {
                    if (isset($texts["post_published"]) && !empty($texts["post_published"])) {
                        $text = $texts["post_published"];
                        $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                    } else {
                        $text = sprintf(__("Congratulations! Your post, \"%s\", has been verified and published.", "mlm"), mlm_get_post_title($post_id));
                    }
                }
                break;
            case "post_rejected":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $reason = isset($extra["reason"]) ? $extra["reason"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["post_rejected"]) ? $patterns["post_rejected"] : "";
                    $patt_data = ["title" => mlm_get_post_title($post_id), "reason" => $reason];
                } else {
                    if (isset($texts["post_rejected"]) && !empty($texts["post_rejected"])) {
                        $text = $texts["post_rejected"];
                        $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                        $text = str_replace("{REASON}", $reason, $text);
                    } else {
                        $text = sprintf(__("Sorry! Your post, \"%s\" has been rejected.", "mlm"), mlm_get_post_title($post_id)) . "\r\n";
                        $text .= sprintf(__("Reject reason: %s", "mlm"), $reason);
                    }
                }
                break;
            case "ticket_replied":
                $ticket_id = isset($extra["ticket_id"]) ? $extra["ticket_id"] : "";
                $sender_name = isset($extra["sender_name"]) ? $extra["sender_name"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["ticket_replied"]) ? $patterns["ticket_replied"] : "";
                    $patt_data = ["id" => $ticket_id, "name" => $sender_name];
                } else {
                    if (isset($texts["ticket_replied"]) && !empty($texts["ticket_replied"])) {
                        $text = $texts["ticket_replied"];
                        $text = str_replace("{ID}", $ticket_id, $text);
                        $text = str_replace("{NAME}", $sender_name, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = __("You have a new reply on your ticket. Please check your panel for details.", "mlm");
                    }
                }
                break;
            case "new_ticket":
                $ticket_id = isset($extra["ticket_id"]) ? $extra["ticket_id"] : "";
                $sender_name = isset($extra["sender_name"]) ? $extra["sender_name"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["new_ticket"]) ? $patterns["new_ticket"] : "";
                    $patt_data = ["id" => $ticket_id, "name" => mlm_get_user_name($user_id), "sender" => $sender_name];
                } else {
                    if (isset($texts["new_ticket"]) && !empty($texts["new_ticket"])) {
                        $text = $texts["new_ticket"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{SENDER}", $sender_name, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                        $text = str_replace("{ID}", $ticket_id, $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("You have a new ticket at %s. Please check your panel as soon as possible.", "mlm"), get_bloginfo("name"));
                    }
                }
                break;
            case "password_changed":
                $password = isset($extra["password"]) ? $extra["password"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["password_changed"]) ? $patterns["password_changed"] : "";
                    $patt_data = ["password" => $password];
                } else {
                    if (isset($texts["password_changed"]) && !empty($texts["password_changed"])) {
                        $text = $texts["password_changed"];
                        $text = str_replace("{PASSWORD}", $password, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Your password for %s", "mlm"), get_bloginfo("name")) . "\r\n";
                        $text .= sprintf("%s", $password);
                        $text .= __("Please keep it secure", "mlm");
                    }
                }
                break;
            case "new_purchase":
                $order_id = isset($extra["order_id"]) ? $extra["order_id"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["new_purchase"]) ? $patterns["new_purchase"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "order" => $order_id];
                } else {
                    if (isset($texts["new_purchase"]) && !empty($texts["new_purchase"])) {
                        $text = $texts["new_purchase"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                        $text = str_replace("{ORDER}", $order_id, $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= __("Thank you for choosing us", "mlm") . "\r\n";
                        $text .= sprintf(__("Order %d completed. Please check your panel to download and see more details.", "mlm"), $order_id);
                    }
                }
                break;
            case "new_sale":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $order_id = isset($extra["order_id"]) ? $extra["order_id"] : "";
                $customer = isset($extra["customer"]) ? $extra["customer"] : "";
                $total = isset($extra["total"]) ? $extra["total"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["new_sale"]) ? $patterns["new_sale"] : "";
                    $patt_data = ["title" => mlm_get_post_title($post_id), "order" => $order_id, "total" => $total, "customer" => $customer];
                } else {
                    if (isset($texts["new_sale"]) && !empty($texts["new_sale"])) {
                        $text = $texts["new_sale"];
                        $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                        $text = str_replace("{CUSTOMER}", $customer, $text);
                        $text = str_replace("{TOTAL}", $total, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                        $text = str_replace("{ORDER}", $order_id, $text);
                    } else {
                        $text = __("New sale", "mlm") . "\r\n";
                        $text .= sprintf(__("Product name: %s", "mlm"), mlm_get_post_title($post_id));
                    }
                }
                break;
            case "plan_activated":
                $plan_id = isset($extra["plan_id"]) ? $extra["plan_id"] : "";
                $plan_name = isset($extra["plan_name"]) ? $extra["plan_name"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["plan_activated"]) ? $patterns["plan_activated"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "plan" => $plan_name];
                } else {
                    if (isset($texts["plan_activated"]) && !empty($texts["plan_activated"])) {
                        $text = $texts["plan_activated"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                        $text = str_replace("{PLAN}", $plan_name, $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("Plan %s has been activated successfully. please check your panel to download products.", "mlm"), $plan_name);
                    }
                }
                break;
            case "plan_expire_in_20":
                $plan_id = isset($extra["plan_id"]) ? $extra["plan_id"] : "";
                $plan_name = isset($extra["plan_name"]) ? $extra["plan_name"] : "";
                $plan_time = isset($extra["plan_time"]) ? $extra["plan_time"] : "";
                $plan_link = isset($extra["plan_link"]) ? $extra["plan_link"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["plan_expire_in_20"]) ? $patterns["plan_expire_in_20"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "plan" => $plan_name, "time" => $plan_time];
                } else {
                    if (isset($texts["plan_expire_in_20"]) && !empty($texts["plan_expire_in_20"])) {
                        $text = $texts["plan_expire_in_20"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{PLAN}", $plan_name, $text);
                        $text = str_replace("{TIME}", $plan_time, $text);
                        $text = str_replace("{LINK}", $plan_link, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("Only %d days of your subscription %s has been left on %s. Please check our site to download products.", "mlm"), $plan_time, $plan_name, get_bloginfo("name")) . "\r\n";
                        $text .= sprintf("%s", $plan_link);
                    }
                }
                break;
            case "plan_expire_in_10":
                $plan_id = isset($extra["plan_id"]) ? $extra["plan_id"] : "";
                $plan_name = isset($extra["plan_name"]) ? $extra["plan_name"] : "";
                $plan_time = isset($extra["plan_time"]) ? $extra["plan_time"] : "";
                $plan_link = isset($extra["plan_link"]) ? $extra["plan_link"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["plan_expire_in_10"]) ? $patterns["plan_expire_in_10"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "plan" => $plan_name, "time" => $plan_time];
                } else {
                    if (isset($texts["plan_expire_in_10"]) && !empty($texts["plan_expire_in_10"])) {
                        $text = $texts["plan_expire_in_10"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{PLAN}", $plan_name, $text);
                        $text = str_replace("{TIME}", $plan_time, $text);
                        $text = str_replace("{LINK}", $plan_link, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= sprintf(__("Only %d days of your subscription %s has been left on %s. Please check our site to download products. We will add more files every week. So you can renew your subscription in case that you dont want to miss them.", "mlm"), $plan_time, $plan_name, get_bloginfo("name")) . "\r\n";
                        $text .= sprintf("%s", $plan_link);
                    }
                }
                break;
            case "plan_expire_in_1":
                $plan_id = isset($extra["plan_id"]) ? $extra["plan_id"] : "";
                $plan_link = isset($extra["plan_link"]) ? $extra["plan_link"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["plan_expire_in_1"]) ? $patterns["plan_expire_in_1"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id), "plan" => $plan_id];
                } else {
                    if (isset($texts["plan_expire_in_1"]) && !empty($texts["plan_expire_in_1"])) {
                        $text = $texts["plan_expire_in_1"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{PLAN}", $plan_id, $text);
                        $text = str_replace("{LINK}", $plan_link, $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = sprintf(__("Dear %s", "mlm"), mlm_get_user_name($user_id)) . "\r\n";
                        $text .= __("Only 24 hours left of your subscription plan. Please check our site to renew your subscription.", "mlm") . "\r\n";
                        $text .= sprintf("%s", $plan_link);
                    }
                }
                break;
            case "upgraded":
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["upgraded"]) ? $patterns["upgraded"] : "";
                    $patt_data = ["name" => mlm_get_user_name($user_id)];
                } else {
                    if (isset($texts["upgraded"]) && !empty($texts["upgraded"])) {
                        $text = $texts["upgraded"];
                        $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                        $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    } else {
                        $text = __("Dear user. Your upgrade account request has been verified.", "mlm");
                    }
                }
                break;
            case "comment_replied":
                $comment_id = isset($extra["comment_id"]) ? $extra["comment_id"] : "";
                $name = isset($extra["name"]) ? $extra["name"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["comment_replied"]) ? $patterns["comment_replied"] : "";
                    $patt_data = ["name" => $name];
                } else {
                    if (isset($texts["comment_replied"]) && !empty($texts["comment_replied"])) {
                        $text = $texts["comment_replied"];
                        $text = str_replace("{NAME}", $name, $text);
                    } else {
                        $text = sprintf(__("Dear %s, New reply for your comment.", "mlm"), $name);
                    }
                }
                break;
            case "new_comment":
                $comment_id = isset($extra["comment_id"]) ? $extra["comment_id"] : "";
                $name = isset($extra["name"]) ? $extra["name"] : "";
                $title = isset($extra["title"]) ? $extra["title"] : "";
                if ($pattern == "yes" && $sms_panel == "ipanel") {
                    $patt_code = isset($patterns["new_comment"]) ? $patterns["new_comment"] : "";
                    $patt_data = ["name" => $name, "title" => $title];
                } else {
                    if (isset($texts["new_comment"]) && !empty($texts["new_comment"])) {
                        $text = $texts["new_comment"];
                        $text = str_replace("{NAME}", $name, $text);
                        $text = str_replace("{TITLE}", $title, $text);
                    } else {
                        $text = sprintf(__("Dear %s, You have a new comment for %s. Please check your panel for details and reply.", "mlm"), $name, $title);
                    }
                }
                break;
            default:
                $text = "";
                $mobile = isset($extra["mobile"]) ? $extra["mobile"] : get_user_meta($user_id, "mlm_mobile", true);
                if (!empty($patt_code)) {
                    return mlmFire()->sms->ipanel_pattern($mobile, $patt_data, $patt_code);
                }
                if (empty($text)) {
                    return false;
                }
                return $this->send_sms($mobile, $text);
        }
    }
    public function send_user_mail($user_id, $case, $extra = [])
    {
        $texts = get_option("mlm_mail_texts");
        switch ($case) {
            case "register":
                $user_name = isset($extra["user_name"]) ? $extra["user_name"] : "";
                $password = isset($extra["password"]) ? $extra["password"] : "";
                $title = __("Register completed", "mlm");
                if (isset($texts["register"]) && !empty($texts["register"])) {
                    $text = $texts["register"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{USERNAME}", $user_name, $text);
                    $text = str_replace("{PASSWORD}", $password, $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("You have registered successfully on %s", "mlm") . "</p>", get_bloginfo("name")) . "<br /><br/>";
                    $content .= sprintf("<p>" . __("Login: %s", "mlm") . "</p>", $user_name);
                    $content .= sprintf("<p>" . __("Password: %s", "mlm") . "</p>", $password) . "<br /><br/>";
                    $content .= "<p>" . __("Please don't share your account info with anyone.", "mlm") . "</p>";
                }
                break;
            case "lost_code":
                $code = isset($extra["code"]) ? $extra["code"] : "";
                $title = __("Password recovery code", "mlm");
                if (isset($texts["lost_code"]) && !empty($texts["lost_code"])) {
                    $text = $texts["lost_code"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    $text = str_replace("{CODE}", $code, $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("You have requested a verification code at %s", "mlm") . "</p>", get_bloginfo("name")) . "<br /><br/>";
                    $content .= sprintf("<p>" . __("Your verification code: %s", "mlm") . "</p>", $code) . "<br /><br/>";
                    $content .= "<p>" . __("Delete this if you don't want to change anything.", "mlm") . "</p>";
                }
                break;
            case "verify_code":
                $code = isset($extra["code"]) ? $extra["code"] : "";
                $title = __("Verify Email", "mlm");
                if (isset($texts["verify_code"]) && !empty($texts["verify_code"])) {
                    $text = $texts["verify_code"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                    $text = str_replace("{CODE}", $code, $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("You have requested a verification code at %s", "mlm") . "</p>", get_bloginfo("name")) . "<br /><br/>";
                    $content .= sprintf("<p>" . __("Your verification code: %s", "mlm") . "</p>", $code) . "<br /><br/>";
                }
                break;
            case "product_moderation":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("Product waiting for moderation", "mlm");
                if (isset($texts["product_moderation"]) && !empty($texts["product_moderation"])) {
                    $text = $texts["product_moderation"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= "<p>" . __("Your product will be moderated soon.", "mlm") . "</p>";
                    $content .= "<p>" . __("You will be notified with sms messages.", "mlm") . "</p>";
                }
                break;
            case "post_moderation":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("Post waiting for moderation", "mlm");
                if (isset($texts["post_moderation"]) && !empty($texts["post_moderation"])) {
                    $text = $texts["post_moderation"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= "<p>" . __("Your post will be moderated soon.", "mlm") . "</p>";
                    $content .= "<p>" . __("You will be notified with sms messages.", "mlm") . "</p>";
                }
                break;
            case "withdrawal_paid":
                $amount = isset($extra["amount"]) ? $extra["amount"] : "";
                $desc = isset($extra["desc"]) ? $extra["desc"] : "";
                $title = __("Withdrawal request paid", "mlm");
                if (isset($texts["withdrawal_paid"]) && !empty($texts["withdrawal_paid"])) {
                    $text = $texts["withdrawal_paid"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{AMOUNT}", mlm_filter($amount), $text);
                    $text = str_replace("{DESC}", $desc, $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("Your withdrawal request in the amount of %s paid.", "mlm") . "</p>", mlm_filter($amount));
                    $content .= sprintf("<p>%s</p>", $desc);
                }
                break;
            case "withdrawal_request":
                $amount = isset($extra["amount"]) ? $extra["amount"] : "";
                $title = __("New withdrawal request", "mlm");
                if (isset($texts["withdrawal_request"]) && !empty($texts["withdrawal_request"])) {
                    $text = $texts["withdrawal_request"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{AMOUNT}", mlm_filter($amount), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("Your withdrawal request in the amount of %s submitted and will be moderated soon.", "mlm") . "</p>", mlm_filter($amount));
                    $content .= "<p>" . __("Please wait until your request verification.", "mlm") . "</p>";
                    $content .= sprintf("<p>" . __("Regards. Accounting department of %s", "mlm") . "</p>", get_bloginfo("name"));
                }
                break;
            case "upgrade_request":
                $title = __("Upgrade account request", "mlm");
                if (isset($texts["upgrade_request"]) && !empty($texts["upgrade_request"])) {
                    $text = $texts["upgrade_request"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= "<p>" . __("Your upgrade account request has been received and will be moderated soon.", "mlm") . "</p>";
                    $content .= "<p>" . __("Please wait until your request verification.", "mlm") . "</p>";
                    $content .= sprintf("<p>" . __("Regards. %s", "mlm") . "</p>", get_bloginfo("name"));
                }
                break;
            case "upgraded":
                $title = __("Upgrade account request", "mlm");
                if (isset($texts["upgraded"]) && !empty($texts["upgraded"])) {
                    $text = $texts["upgraded"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= "<p>" . __("Your upgrade account request has been verified.", "mlm") . "</p>";
                    $content .= sprintf("<p>" . __("Regards. %s", "mlm") . "</p>", get_bloginfo("name"));
                }
                break;
            case "comment_replied":
                $comment_id = isset($extra["comment_id"]) ? $extra["comment_id"] : "";
                $title = __("Comment replied", "mlm");
                if (isset($texts["comment_replied"]) && !empty($texts["comment_replied"])) {
                    $text = $texts["comment_replied"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("New reply for your comment at %s", "mlm") . "</p>", get_bloginfo("name"));
                }
                break;
            case "new_comment":
                $comment_id = isset($extra["comment_id"]) ? $extra["comment_id"] : "";
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("New comment", "mlm");
                if (isset($texts["new_comment"]) && !empty($texts["new_comment"])) {
                    $text = $texts["new_comment"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= sprintf("<p>" . __("You have a new comment at %s. Please check your panel for details and reply.", "mlm") . "</p>", mlm_get_post_title($post_id));
                }
                break;
            case "new_ticket":
                $ticket_id = isset($extra["ticket_id"]) ? $extra["ticket_id"] : "";
                $title = __("Ticket submitted", "mlm");
                if (isset($texts["new_ticket"]) && !empty($texts["new_ticket"])) {
                    $text = $texts["new_ticket"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = sprintf("<p>" . __("Dear %s", "mlm") . "</p>", mlm_get_user_name($user_id));
                    $content .= "<p>" . __("Your ticket has been submitted successfully.", "mlm") . "</p>";
                }
                break;
            case "follower_new_product":
                $post_id = isset($extra["post_id"]) ? $extra["post_id"] : "";
                $title = __("Product published", "mlm");
                if (isset($texts["follower_new_product"]) && !empty($texts["follower_new_product"])) {
                    $text = $texts["follower_new_product"];
                    $text = str_replace("{NAME}", mlm_get_user_name($user_id), $text);
                    $text = str_replace("{TITLE}", mlm_get_post_title($post_id), $text);
                    $text = str_replace("{LINK}", get_the_permalink($post_id), $text);
                    $text = str_replace("{SITE}", get_bloginfo("name"), $text);
                } else {
                    $content = "<p>" . __("Hello", "mlm") . "</p>";
                    $content .= sprintf("<p>" . __("\"%s\" has published a new product. as you follow this vendor you may want to check the product.", "mlm") . "</p>", mlm_get_user_name($user_id)) . "<br /><br/>";
                    $content .= sprintf("<p>" . __("Product title: %s", "mlm") . "</p>", mlm_get_post_title($post_id));
                    $content .= sprintf("<p>" . __("Product link", "mlm") . " <a href=\"%1\$s\">%1\$s</a></p>", get_the_permalink($post_id)) . "<br /><br/>";
                    $content .= sprintf("<p>" . __("Regards. %s", "mlm") . "</p>", get_bloginfo("name"));
                }
                break;
            default:
                $title = "";
                $content = "";
                if (empty($title) || empty($content)) {
                    return false;
                }
                $user_obj = get_userdata($user_id);
                $user_mail = isset($extra["email"]) ? $extra["email"] : $user_obj->user_email;
                return $this->send_email($user_mail, $title, $content);
        }
    }
    public function product_published($ID, $post)
    {
        if (wp_is_post_revision($ID)) {
            return NULL;
        }
        $this->send_user_sms($post->post_author, "product_published", ["post_id" => $ID]);
    }
    public function post_published($ID, $post)
    {
        if (wp_is_post_revision($ID)) {
            return NULL;
        }
        $this->send_user_sms($post->post_author, "post_published", ["post_id" => $ID]);
    }
    public function comment_mail_recipients($emails, $comment_id)
    {
        $comment = get_comment($comment_id);
        $post = get_post($comment->comment_post_ID);
        $user = get_user_by("id", $post->post_author);
        if (user_can($user->ID, "edit_published_posts") && isset($user->user_email) && !empty($user->user_email)) {
            $emails = [$user->user_email];
        }
        return $emails;
    }
    public function comment_notifications($comment_id, $comment_approved, $comment_data)
    {
        if (1 !== $comment_approved) {
            return NULL;
        }
        $parent = isset($comment_data["comment_parent"]) ? $comment_data["comment_parent"] : 0;
        $post_id = isset($comment_data["comment_post_ID"]) ? $comment_data["comment_post_ID"] : 0;
        $author_name = isset($comment_data["comment_author"]) ? $comment_data["comment_author"] : "";
        $author_email = isset($comment_data["comment_author_email"]) ? $comment_data["comment_author_email"] : "";
        $post_author = get_post_field("post_author", $post_id);
        if ($parent) {
            $parent_data = get_comment($parent, ARRAY_A);
            $author_name = isset($parent_data["comment_author"]) ? $parent_data["comment_author"] : "";
            $author_email = isset($parent_data["comment_author_email"]) ? $parent_data["comment_author_email"] : "";
            $parent_id = isset($parent_data["user_id"]) ? $parent_data["user_id"] : "";
            $this->send_user_mail($parent_id, "comment_replied", ["comment_id" => $parent, "email" => $author_email]);
            $this->send_user_sms($parent_id, "comment_replied", ["comment_id" => $parent, "name" => $author_name]);
        } else {
            $this->send_user_mail($post_author, "new_comment", ["post_id" => $post_id, "comment_id" => $comment_id]);
            $this->send_user_sms($post_author, "new_comment", ["comment_id" => $comment_id, "name" => $author_name, "title" => mlm_get_post_title($post_id)]);
        }
    }
    public function menu_pages()
    {
        $pattern = get_option("mlm_sms_pattern");
        add_submenu_page("mlm-wallet", __("SMS texts", "mlm"), __("SMS texts", "mlm"), "manage_options", "mlm-sms-settings", [$this, "sms_callback"]);
        if ($pattern == "yes") {
            add_submenu_page("mlm-wallet", __("Patterns", "mlm"), __("Patterns", "mlm"), "manage_options", "mlm-sms-patterns", [$this, "pattern_callback"]);
        }
        add_submenu_page("mlm-wallet", __("Email texts", "mlm"), __("Email texts", "mlm"), "manage_options", "mlm-mail-settings", [$this, "mail_callback"]);
    }
    public function sms_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        echo "<div class=\"wrap mlm-wrap mlm-sms-settings-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/sms-texts");
        echo "</div>";
    }
    public function pattern_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        echo "<div class=\"wrap mlm-wrap mlm-sms-settings-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/sms-patterns");
        echo "</div>";
    }
    public function mail_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        echo "<div class=\"wrap mlm-wrap mlm-mail-settings-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/mail-texts");
        echo "</div>";
    }
}

?>