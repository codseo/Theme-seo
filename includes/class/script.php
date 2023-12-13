<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

if (!function_exists("mlm_get_template")) {
    function mlm_get_template($template_name, $attributes = NULL)
    {
        if (!$attributes) {
            $attributes = [];
        }
        $theme_path = get_stylesheet_directory();
        $file_path = "/includes/" . $template_name . ".php";
        ob_start();
        if (file_exists($theme_path . $file_path)) {
            require $theme_path . $file_path;
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
if (!function_exists("mlm_page_url")) {
    function mlm_page_url($key)
    {
        switch ($key) {
            case "login":
                $id = get_option("mlm_login_page");
                break;
            case "register":
                $id = get_option("mlm_register_page");
                break;
            case "lost":
                $id = get_option("mlm_lost_page");
                break;
            case "reset":
                $id = get_option("mlm_reset_page");
                break;
            case "panel":
                $id = get_option("mlm_panel_page");
                break;
            default:
                $id = 0;
                $link = get_permalink($id);
                if ($key == "login" || $key == "register") {
                    $link = add_query_arg("_return", get_the_permalink(), $link);
                }
                return esc_url($link);
        }
    }
}
if (!function_exists("mlm_sanitize_array")) {
    function mlm_sanitize_array($data = [])
    {
        if (!is_array($data) || !count($data)) {
            return [];
        }
        foreach ($data as $k => $v) {
            if (!is_array($v) && !is_object($v)) {
                $data[$k] = esc_attr($v);
            }
            if (is_array($v)) {
                $data[$k] = mlm_sanitize_array($v);
            }
        }
        return $data;
    }
}
if (!function_exists("mlm_user_exists")) {
    function mlm_user_exists($user_id)
    {
        if (empty($user_id)) {
            return false;
        }
        $user = get_userdata($user_id);
        if ($user === false) {
            return false;
        }
        return true;
    }
}
if (!function_exists("mlm_post_exists")) {
    function mlm_post_exists($post_id)
    {
        if (empty($post_id)) {
            return false;
        }
        return is_string(get_post_status($post_id));
    }
}
if (!function_exists("mlm_get_user_name")) {
    function mlm_get_user_name($user_id, $default = NULL)
    {
        if ($default == NULL) {
            $default = __("Unknown", "mlm");
        }
        if (mlm_user_exists($user_id)) {
            $user_info = get_userdata($user_id);
            return $user_info->display_name;
        }
        return $default;
    }
}
if (!function_exists("mlm_get_user_link")) {
    function mlm_get_user_link($user_id)
    {
        if (mlm_user_exists($user_id)) {
            $link = get_edit_user_link($user_id);
        } else {
            $link = "";
        }
        return $link;
    }
}
if (!function_exists("mlm_get_post_title")) {
    function mlm_get_post_title($post_id, $default = NULL)
    {
        if ($default == NULL) {
            $default = __("Unknown", "mlm");
        }
        if (mlm_post_exists($post_id)) {
            return get_the_title($post_id);
        }
        return $default;
    }
}
if (!function_exists("mlm_get_post_link")) {
    function mlm_get_post_link($post_id)
    {
        if (mlm_post_exists($post_id)) {
            $post_link = get_edit_post_link($post_id);
        } else {
            $post_link = "";
        }
        return $post_link;
    }
}
if (!function_exists("mlm_latin_num")) {
    function mlm_latin_num($str)
    {
        $farsi_array = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫"];
        $english_array = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "."];
        return str_replace($farsi_array, $english_array, $str);
    }
}
if (!function_exists("mlm_category_list")) {
    function mlm_category_list($post_id = 0, $taxonomy = "category", $single = false, $parent = "")
    {
        $output = [];
        if ($single) {
            $selected = 0;
            if (mlm_post_exists($post_id)) {
                $post_terms = wp_get_post_terms($post_id, $taxonomy);
                if (!empty($post_terms) && !is_wp_error($post_terms)) {
                    foreach ($post_terms as $term) {
                        if (strlen($parent) < 1) {
                            $selected = $term->term_id;
                        } else {
                            if ($parent == $term->parent) {
                                $selected = $term->term_id;
                            }
                        }
                    }
                }
            }
            return $selected;
        }
        if (mlm_post_exists($post_id)) {
            $post_terms = wp_get_post_terms($post_id, $taxonomy);
            if (!empty($post_terms) && !is_wp_error($post_terms)) {
                foreach ($post_terms as $term) {
                    $output[] = $term->term_id;
                }
            }
        } else {
            if (!$post_id) {
                $all_terms = get_terms(["taxonomy" => $taxonomy, "hide_empty" => false]);
                if (!empty($all_terms) && !is_wp_error($all_terms)) {
                    foreach ($all_terms as $term) {
                        $output[] = ["id" => $term->term_id, "name" => $term->name];
                    }
                }
            }
        }
        return $output;
    }
}
if (!function_exists("mlm_update_tax")) {
    function mlm_update_tax($post_id, $taxonomy, $objects)
    {
        if (!mlm_post_exists($post_id)) {
            return false;
        }
        $new_terms = [];
        if (is_array($objects) && 0 < count($objects)) {
            foreach ($objects as $object) {
                $new_terms[] = is_numeric($object) ? absint($object) : $object;
            }
            wp_set_object_terms($post_id, $new_terms, $taxonomy, false);
        } else {
            if ($objects) {
                $new_object = is_numeric($objects) ? absint($objects) : $objects;
                wp_set_object_terms($post_id, $new_object, $taxonomy, false);
            } else {
                wp_set_object_terms($post_id, NULL, $taxonomy, false);
            }
        }
        return true;
    }
}
if (!function_exists("mlm_is_mobile")) {
    function mlm_is_mobile($mobile)
    {
        if (!preg_match("/^09[0-9]{9}\$/", $mobile)) {
            return false;
        }
        return true;
    }
}
if (!function_exists("mlm_mobile_exists")) {
    function mlm_mobile_exists($mobile, $user_id = NULL)
    {
        if (empty($mobile)) {
            return true;
        }
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("\r\n\t\t\tSELECT COUNT(ID) FROM " . $wpdb->users . " as users, \r\n\t\t\t" . $wpdb->usermeta . " as meta \r\n\t\t\tWHERE users.ID = meta.user_id \r\n\t\t\tAND meta.meta_key = 'mlm_mobile' \r\n\t\t\tAND meta.meta_value = %s \r\n\t\t\tAND users.ID <> %d\r\n\t\t\t", $mobile, $user_id));
    }
}
if (!function_exists("mlm_get_user_by_mobile")) {
    function mlm_get_user_by_mobile($mobile)
    {
        $mobile = mlm_latin_num($mobile);
        if (username_exists($mobile) || !mlm_is_mobile($mobile)) {
            return $mobile;
        }
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare("\r\n\t\t\tSELECT user_login FROM " . $wpdb->users . " as users, \r\n\t\t\t" . $wpdb->usermeta . " as meta \r\n\t\t\tWHERE users.ID = meta.user_id \r\n\t\t\tAND meta.meta_key = 'mlm_mobile' \r\n\t\t\tAND meta.meta_value = %s \r\n\t\t\tLIMIT 1\r\n\t\t\t", $mobile));
        return isset($result->user_login) ? $result->user_login : $mobile;
    }
}
if (!function_exists("mlm_sms_is_active")) {
    function mlm_sms_is_active()
    {
        $username = get_option("mlm_sms_user");
        $password = get_option("mlm_sms_pass");
        if (!empty($username) && !empty($password)) {
            return true;
        }
        return false;
    }
}
if (!function_exists("mlm_get_term_childs_count")) {
    function mlm_get_term_childs_count($term_id, $taxonomy, $append = 0)
    {
        $count = absint($append);
        $terms = get_terms(["taxonomy" => $taxonomy, "hide_empty" => true, "child_of" => $term_id]);
        if (empty($terms) || is_wp_error($terms)) {
            return $count;
        }
        foreach ($terms as $term) {
            $count += $term->count;
        }
        return $count;
    }
}
if (!function_exists("mlm_check_course")) {
    function mlm_check_course($post_id)
    {
        if (!mlm_post_exists($post_id)) {
            return false;
        }
        $is_course = get_post_meta($post_id, "mlm_is_course", true);
        if ($is_course != "yes") {
            return false;
        }
        return true;
    }
}
if (!function_exists("mlm_custom_fields_type")) {
    function mlm_custom_fields_type()
    {
        $type = get_option("mlm_custom_fields");
        return $type == "custom" ? "custom" : "mlm";
    }
}
if (!function_exists("mlm_ftp_upload")) {
    function mlm_ftp_upload()
    {
        $url = get_option("mlm_ftp_url");
        $user = get_option("mlm_ftp_user");
        $pass = get_option("mlm_ftp_pass");
        $link = get_option("mlm_ftp_link");
        if (!empty($url) && !empty($user) && !empty($pass) && !empty($link)) {
            return true;
        }
        return false;
    }
}
if (!function_exists("mlm_read_bytes")) {
    function mlm_read_bytes($bytes)
    {
        $i = floor(log($bytes) / log(1024));
        $sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
        return sprintf("%.02F", $bytes / pow(1024, $i)) * 1 . " " . $sizes[$i];
    }
}

?>