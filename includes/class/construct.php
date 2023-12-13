<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Construct
{
    public function __construct()
    {
        add_action("login_form_login", [$this, "redirect_to_custom_login"]);
        add_filter("authenticate", [$this, "maybe_redirect_at_authenticate"], 101, 3);
        add_filter("login_redirect", [$this, "redirect_after_login"], 10, 3);
        add_action("wp_logout", [$this, "redirect_after_logout"]);
        add_action("login_form_register", [$this, "redirect_to_custom_register"]);
        add_action("login_form_lostpassword", [$this, "redirect_to_custom_lostpassword"]);
        add_action("login_form_rp", [$this, "redirect_to_custom_lostpassword"]);
        add_action("login_form_resetpass", [$this, "redirect_to_custom_lostpassword"]);
        add_action("login_form_register", [$this, "do_register_user"]);
        add_action("login_form_lostpassword", [$this, "do_password_lost"]);
        add_action("login_form_rp", [$this, "do_password_reset"]);
        add_action("login_form_resetpass", [$this, "do_password_reset"]);
        add_filter("retrieve_password_message", [$this, "replace_retrieve_password_message"], 10, 4);
        add_action("admin_init", [$this, "redirect_admin_restrict"], 1);
        add_shortcode("mlm-login-form", [$this, "render_login_form"]);
        add_shortcode("mlm-register-form", [$this, "render_register_form"]);
        add_shortcode("mlm-password-lost-form", [$this, "render_password_lost_form"]);
    }
    public function redirect_to_custom_login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
                exit;
            }
            $login_url = mlm_page_url("login");
            if (!empty($_REQUEST["redirect_to"])) {
                $login_url = add_query_arg("redirect_to", $_REQUEST["redirect_to"], $login_url);
            }
            if (!empty($_REQUEST["checkemail"])) {
                $login_url = add_query_arg("checkemail", $_REQUEST["checkemail"], $login_url);
            }
            wp_redirect($login_url);
            exit;
        }
    }
    public function maybe_redirect_at_authenticate($user, $username, $password)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && is_wp_error($user) && (!defined("DOING_AJAX") || !DOING_AJAX)) {
            $error_codes = join(",", $user->get_error_codes());
            $login_url = mlm_page_url("login");
            $login_url = add_query_arg("login", $error_codes, $login_url);
            wp_redirect($login_url);
            exit;
        }
        return $user;
    }
    public function redirect_after_login($redirect_to, $requested_redirect_to, $user)
    {
        $redirect_url = home_url();
        if (!isset($user->ID)) {
            return $redirect_url;
        }
        if (user_can($user, "moderate_comments")) {
            if ($requested_redirect_to == "") {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $redirect_to;
            }
        } else {
            $redirect_url = mlm_page_url("panel");
        }
        return wp_validate_redirect($redirect_url, home_url());
    }
    public function redirect_after_logout()
    {
        $redirect_url = mlm_page_url("login");
        $redirect_url = add_query_arg("logged_out", "true", $redirect_url);
        wp_redirect($redirect_url);
        exit;
    }
    public function redirect_to_custom_register()
    {
        if ("GET" == $_SERVER["REQUEST_METHOD"]) {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
            } else {
                $register_url = mlm_page_url("register");
                wp_redirect($register_url);
            }
            exit;
        }
    }
    public function redirect_to_custom_lostpassword()
    {
        if ("GET" == $_SERVER["REQUEST_METHOD"]) {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
                exit;
            }
            $lost_url = mlm_page_url("lost");
            wp_redirect($lost_url);
            exit;
        }
    }
    public function redirect_to_custom_password_reset()
    {
        if ("GET" == $_SERVER["REQUEST_METHOD"]) {
            $user = check_password_reset_key($_REQUEST["key"], $_REQUEST["login"]);
            $login_url = mlm_page_url("login");
            if (!$user || is_wp_error($user)) {
                if ($user && $user->get_error_code() === "expired_key") {
                    $login_url = add_query_arg("login", "expiredkey", $login_url);
                } else {
                    $login_url = add_query_arg("login", "invalidkey", $login_url);
                }
                wp_redirect($login_url);
                exit;
            }
            $redirect_url = mlm_page_url("reset");
            $redirect_url = add_query_arg("login", esc_attr($_REQUEST["login"]), $redirect_url);
            $redirect_url = add_query_arg("key", esc_attr($_REQUEST["key"]), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        }
    }
    public function redirect_admin_restrict()
    {
        $redirect_url = mlm_page_url("panel");
        if (!current_user_can("moderate_comments") && (!defined("DOING_AJAX") || !DOING_AJAX)) {
            wp_redirect($redirect_url);
            exit;
        }
    }
    public function render_login_form($attributes, $content = NULL)
    {
        $default_attributes = ["show_title" => false];
        $attributes = shortcode_atts($default_attributes, $attributes);
        if (is_user_logged_in()) {
            return "<div class=\"alert alert-success\">" . __("You are logged in to your account.", "mlm") . "</div>";
        }
        $attributes["redirect"] = "";
        if (isset($_REQUEST["redirect_to"])) {
            $attributes["redirect"] = wp_validate_redirect($_REQUEST["redirect_to"], $attributes["redirect"]);
        }
        $attributes["_return"] = isset($_REQUEST["_return"]) ? $_REQUEST["_return"] : "";
        $errors = [];
        if (isset($_REQUEST["login"])) {
            $error_codes = explode(",", $_REQUEST["login"]);
            foreach ($error_codes as $code) {
                $errors[] = $this->get_error_message($code);
            }
        }
        $attributes["errors"] = $errors;
        $attributes["logged_out"] = isset($_REQUEST["logged_out"]) && $_REQUEST["logged_out"];
        $attributes["registered"] = isset($_REQUEST["registered"]);
        $attributes["lost_password_sent"] = isset($_REQUEST["checkemail"]) && $_REQUEST["checkemail"] == "confirm";
        $attributes["password_updated"] = isset($_REQUEST["password"]) && $_REQUEST["password"] == "changed";
        return mlm_get_template("class/user-forms/login", $attributes);
    }
    public function render_register_form($attributes, $content = NULL)
    {
        $default_attributes = ["show_title" => false];
        $attributes = shortcode_atts($default_attributes, $attributes);
        if (is_user_logged_in()) {
            return "<div class=\"alert alert-success\">" . __("You are logged in to your account.", "mlm") . "</div>";
        }
        if (!get_option("users_can_register")) {
            return "<div class=\"alert alert-danger\">" . __("User registration is disabled at the moment.", "mlm") . "</div>";
        }
        $attributes["_return"] = isset($_REQUEST["_return"]) ? $_REQUEST["_return"] : "";
        $attributes["errors"] = [];
        if (isset($_REQUEST["register-errors"])) {
            $error_codes = explode(",", $_REQUEST["register-errors"]);
            foreach ($error_codes as $error_code) {
                $attributes["errors"][] = $this->get_error_message($error_code);
            }
        }
        return mlm_get_template("class/user-forms/register", $attributes);
    }
    public function render_password_lost_form($attributes, $content = NULL)
    {
        $default_attributes = ["show_title" => false];
        $attributes = shortcode_atts($default_attributes, $attributes);
        if (is_user_logged_in()) {
            return "<div class=\"alert alert-success\">" . __("You are logged in to your account.", "mlm") . "</div>";
        }
        $attributes["errors"] = [];
        if (isset($_REQUEST["errors"])) {
            $error_codes = explode(",", $_REQUEST["errors"]);
            foreach ($error_codes as $error_code) {
                $attributes["errors"][] = $this->get_error_message($error_code);
            }
        }
        $user_login = isset($_REQUEST["verified"]) ? sanitize_text_field($_REQUEST["verified"]) : "";
        $verified = false;
        if (username_exists($user_login)) {
            $user_obj = get_user_by("login", $user_login);
            $user_id = $user_obj->ID;
            $db_code = get_transient("mlm_reset_" . $user_id);
            if (!empty($db_code)) {
                $verified = $user_login;
            }
        } else {
            if (email_exists($user_login)) {
                $user_obj = get_user_by("email", $user_login);
                $user_id = $user_obj->ID;
                $db_code = get_transient("mlm_reset_" . $user_id);
                if (!empty($db_code)) {
                    $verified = $user_obj->user_login;
                }
            }
        }
        $attributes["verified"] = $verified;
        return mlm_get_template("class/user-forms/password-lost", $attributes);
    }
    public function render_password_reset_form($attributes, $content = NULL)
    {
        $default_attributes = ["show_title" => false];
        $attributes = shortcode_atts($default_attributes, $attributes);
        if (is_user_logged_in()) {
            return "<div class=\"alert alert-success\">" . __("You are logged in to your account.", "mlm") . "</div>";
        }
        return "<div class=\"alert alert-danger\">" . __("Password reset link is not valid.", "mlm") . "</div>";
    }
    public function do_register_user()
    {
        if ("POST" == $_SERVER["REQUEST_METHOD"]) {
            $redirect_url = mlm_page_url("register");
            $captcha = isset($_POST["mlm_recaptcha"]) ? sanitize_text_field($_POST["mlm_recaptcha"]) : "";
            if (!get_option("users_can_register")) {
                $redirect_url = add_query_arg("register-errors", "closed", $redirect_url);
            } else {
                if (!mlmFire()->dashboard->verify_recaptcha($captcha)) {
                    $redirect_url = add_query_arg("register-errors", "captcha", $redirect_url);
                } else {
                    $uname = isset($_POST["mlm_uname"]) ? esc_attr($_POST["mlm_uname"]) : "";
                    $email = isset($_POST["mlm_email"]) ? esc_attr($_POST["mlm_email"]) : "";
                    $mobile = isset($_POST["mlm_mobile"]) ? esc_attr($_POST["mlm_mobile"]) : "";
                    $pass = isset($_POST["mlm_pass"]) ? esc_attr($_POST["mlm_pass"]) : "";
                    $code = isset($_POST["mlm_code"]) ? esc_attr($_POST["mlm_code"]) : "";
                    $result = $this->register_user($uname, $email, $mobile, $pass, $code);
                    if (is_wp_error($result)) {
                        $errors = join(",", $result->get_error_codes());
                        $redirect_url = add_query_arg("register-errors", $errors, $redirect_url);
                    } else {
                        $redirect_url = mlm_page_url("login");
                        $redirect_url = add_query_arg("registered", $mobile, $redirect_url);
                    }
                }
            }
            wp_redirect($redirect_url);
            exit;
        }
    }
    public function do_password_lost()
    {
        if ("POST" == $_SERVER["REQUEST_METHOD"]) {
            $errors = retrieve_password();
            if (is_wp_error($errors)) {
                $redirect_url = mlm_page_url("lost");
                $redirect_url = add_query_arg("errors", join(",", $errors->get_error_codes()), $redirect_url);
            } else {
                $redirect_url = mlm_page_url("login");
                $redirect_url = add_query_arg("checkemail", "confirm", $redirect_url);
                if (!empty($_REQUEST["redirect_to"])) {
                    $redirect_url = $_REQUEST["redirect_to"];
                }
            }
            wp_safe_redirect($redirect_url);
            exit;
        }
    }
    public function do_password_reset()
    {
        if ("POST" == $_SERVER["REQUEST_METHOD"]) {
            $rp_key = $_REQUEST["rp_key"];
            $rp_login = $_REQUEST["rp_login"];
            $user = check_password_reset_key($rp_key, $rp_login);
            $login_url = mlm_page_url("login");
            if (!$user || is_wp_error($user)) {
                if ($user && $user->get_error_code() === "expired_key") {
                    $login_url = add_query_arg("login", "expiredkey", $login_url);
                } else {
                    $login_url = add_query_arg("login", "invalidkey", $login_url);
                }
                wp_redirect($login_url);
                exit;
            }
            if (isset($_POST["pass1"])) {
                if ($_POST["pass1"] != $_POST["pass2"]) {
                    $redirect_url = mlm_page_url("reset");
                    $redirect_url = add_query_arg("key", $rp_key, $redirect_url);
                    $redirect_url = add_query_arg("login", $rp_login, $redirect_url);
                    $redirect_url = add_query_arg("error", "password_reset_mismatch", $redirect_url);
                    wp_redirect($redirect_url);
                    exit;
                }
                if (empty($_POST["pass1"])) {
                    $redirect_url = mlm_page_url("reset");
                    $redirect_url = add_query_arg("key", $rp_key, $redirect_url);
                    $redirect_url = add_query_arg("login", $rp_login, $redirect_url);
                    $redirect_url = add_query_arg("error", "password_reset_empty", $redirect_url);
                    wp_redirect($redirect_url);
                    exit;
                }
                reset_password($user, $_POST["pass1"]);
                $login_url = add_query_arg("password", "changed", $login_url);
                wp_redirect($login_url);
            } else {
                echo __("You are not allowed to do this.", "mlm");
            }
            exit;
        }
    }
    public function replace_retrieve_password_message($message, $key, $user_login, $user_data)
    {
        $msg = __("Hello", "mlm") . "\r\n\r\n";
        $msg .= __("It seems like you have requested a password reset for the following account:", "mlm") . "\r\n\r\n";
        $msg .= sprintf(__("Username: %s", "mlm"), $user_login) . "\r\n\r\n";
        $msg .= __("If this was a mistake, just ignore this email and nothing will happen.", "mlm") . "\r\n\r\n";
        $msg .= __("To reset your password, visit the following address:", "mlm") . "\r\n\r\n";
        $msg .= site_url("wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode($user_login), "login") . "\r\n\r\n";
        $msg .= __("Best Regards", "mlm") . "\r\n\r\n";
        return $msg;
    }
    private function register_user($uname, $email, $mobile, $pass, $code)
    {
        $errors = new WP_Error();
        if (empty($uname) || empty($pass)) {
            $errors->add("empty", $this->get_error_message("empty"));
            return $errors;
        }
        if (empty($email) && !mlmFire()->dashboard->is_email_disabled()) {
            $errors->add("empty", $this->get_error_message("empty"));
            return $errors;
        }
        if (empty($code) && mlmFire()->dashboard->is_code_enabled() && mlmFire()->dashboard->is_code_required()) {
            $errors->add("code_empty", $this->get_error_message("code_empty"));
            return $errors;
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+\$/", $uname)) {
            $errors->add("username_invalid", $this->get_error_message("username_invalid"));
            return $errors;
        }
        if (username_exists($uname) || strlen($uname) < 3) {
            $errors->add("user_reserved", $this->get_error_message("user_reserved"));
            return $errors;
        }
        if (!is_email($email) && !mlmFire()->dashboard->is_email_disabled()) {
            $errors->add("email", $this->get_error_message("email"));
            return $errors;
        }
        if (email_exists($email) && !mlmFire()->dashboard->is_email_disabled()) {
            $errors->add("email_exists", $this->get_error_message("email_exists"));
            return $errors;
        }
        if (!mlm_is_mobile($mobile)) {
            $errors->add("invalid_mobile", $this->get_error_message("invalid_mobile"));
            return $errors;
        }
        if (mlm_mobile_exists($mobile)) {
            $errors->add("mobile_exists", $this->get_error_message("mobile_exists"));
            return $errors;
        }
        if (strlen($pass) < 7) {
            $errors->add("password", $this->get_error_message("password"));
            return $errors;
        }
        $user_data = ["user_login" => $uname, "user_pass" => $pass, "display_name" => $uname, "nickname" => $uname];
        if (!mlmFire()->dashboard->is_email_disabled()) {
            $user_data["user_email"] = $email;
        }
        $user_id = wp_insert_user($user_data);
        if (!empty($code)) {
            $parent_id = mlmFire()->referral->get_userid_by_ref($code);
            mlmFire()->network->add_user_to_network($user_id, $parent_id);
        }
        update_user_meta($user_id, "mlm_mobile", $mobile);
        return $user_id;
    }
    private function redirect_logged_in_user($redirect_to = NULL)
    {
        if (current_user_can("moderate_comments")) {
            if ($redirect_to) {
                wp_safe_redirect($redirect_to);
            } else {
                $panel_url = mlm_page_url("panel");
                wp_redirect($panel_url);
            }
        } else {
            $panel_url = mlm_page_url("panel");
            wp_redirect($panel_url);
        }
    }
    private function get_error_message($error_code)
    {
        switch ($error_code) {
            case "empty":
                return __("All fields are required.", "mlm");
                break;
            case "code_empty":
                return __("Reagent code is required.", "mlm");
                break;
            case "empty_username":
                return __("Email address is required.", "mlm");
                break;
            case "username_invalid":
                return __("Alphabets and numbers are allowed as Username.", "mlm");
                break;
            case "user_exists":
                return __("Username is not correct. Please check again.", "mlm");
                break;
            case "incorrect_password":
                $err = __("Password is not correct. <a href=\"%s\">Forgot your password</a> ?", "mlm");
                return sprintf($err, wp_lostpassword_url());
                break;
            case "password":
                return __("Password must contain at least 7 characters.", "mlm");
                break;
            case "user_reserved":
                return __("Mobile number is not correct.", "mlm");
                break;
            case "email":
                return __("Email address is not valid.", "mlm");
                break;
            case "email_exists":
                return __("Email address is for another user.", "mlm");
                break;
            case "closed":
                return __("Registration not available at this time.", "mlm");
                break;
            case "captcha":
                return __("Captcha field is required to prevent fake registerations.", "mlm");
                break;
            case "invalid_mobile":
                return __("Enter mobile number as a 11-digit numeric value.", "mlm");
                break;
            case "not_activated":
                return __("Mobile number is not verified.", "mlm");
                break;
            case "mobile_exists":
                return __("Mobile number already registered.", "mlm");
                break;
            case "empty_username":
                return __("Username field is required.", "mlm");
                break;
            case "invalid_email":
            case "invalidcombo":
                return __("No users found with this Email address.", "mlm");
                break;
            case "expiredkey":
            case "invalidkey":
                return __("Recover password link is not valid.", "mlm");
                break;
            case "password_reset_mismatch":
                return __("Password repeat does not match the password", "mlm");
                break;
            case "password_reset_empty":
                return __("Password field can&rsquo;t be empty.", "mlm");
                break;
            default:
                return __("Unexpected error occurred! Please try again later.", "mlm");
        }
    }
}

?>