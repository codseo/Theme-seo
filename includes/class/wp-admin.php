<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_WP_Admin
{
    public function __construct()
    {
        if (!current_user_can("manage_options")) {
            add_filter("show_admin_bar", "__return_false");
        }
        add_action("admin_enqueue_scripts", [$this, "admin_scripts"]);
        add_filter("parse_query", [$this, "filter_attachments"]);
        add_filter("manage_users_columns", [$this, "custom_column"]);
        add_filter("manage_users_custom_column", [$this, "custom_column_content"], 10, 3);
        add_action("show_user_profile", [$this, "user_profile_fields"], 999);
        add_action("edit_user_profile", [$this, "user_profile_fields"], 999);
        add_action("personal_options_update", [$this, "save_user_fields"]);
        add_action("edit_user_profile_update", [$this, "save_user_fields"]);
        add_action("personal_options_update", [$this, "check_user_mobile"]);
        add_action("edit_user_profile_update", [$this, "check_user_mobile"]);
        add_action("admin_bar_menu", [$this, "toolbar_bubble"], 999);
        add_action("init", [$this, "custom_posttype"]);
        add_action("add_meta_boxes", [$this, "custom_metabox"], 5);
        add_action("save_post", [$this, "save_meta_values"]);
        add_action("admin_menu", [$this, "menu_pages"], 9);
        add_filter("wp_mail_from", [$this, "mail_sender_email"]);
        add_filter("wp_mail_from_name", [$this, "mail_sender_name"]);
        add_action("pre_user_query", [$this, "search_users_by_mobile"]);
    }
    public function admin_scripts()
    {
        global $pagenow;
        $pages = ["profile.php", "user-edit.php", "edit-tags.php", "term.php"];
        if (in_array($pagenow, $pages) || isset($_GET["page"]) && $_GET["page"] == "mlm-course") {
            wp_enqueue_media();
        }
        if (isset($_GET["page"]) && $_GET["page"] == "mlm-subscribes") {
            wp_enqueue_script("jquery-ui-core");
            wp_enqueue_script("datepicker", FRAMEWORKS . "/datepicker/date.js", ["jquery-ui-core"]);
            wp_enqueue_script("calendar-fa", FRAMEWORKS . "/datepicker/calendar.js", ["jquery-ui-core"]);
            wp_enqueue_script("datepicker-fa", FRAMEWORKS . "/datepicker/fa.js", ["jquery-ui-core"]);
        }
        wp_enqueue_style("select2", FRAMEWORKS . "/select2/select2.min.css");
        wp_enqueue_style("toastr", FRAMEWORKS . "/toastr/toastr.min.css");
        wp_enqueue_style("izimodal", FRAMEWORKS . "/izimodal/css/iziModal.min.css");
        wp_enqueue_style("mlm-admin", STYLES . "/admin70.min.css");
        wp_enqueue_script("select2", FRAMEWORKS . "/select2/select2.min.js", ["jquery"], false, true);
        wp_enqueue_script("toastr", FRAMEWORKS . "/toastr/toastr.min.js", ["jquery"], false, true);
        wp_enqueue_script("izimodal", FRAMEWORKS . "/izimodal/js/iziModal.min.js", ["jquery"], false, true);
        wp_enqueue_script("mlm-admin", SCRIPTS . "/admin70.min.js", ["jquery"], false, true);
        wp_localize_script("mlm-admin", "mlm_local_object", ["ajax_url" => esc_url(admin_url("admin-ajax.php")), "rtl" => is_rtl() ? true : false, "upload_image" => __("Select or upload image", "mlm"), "upload_file" => __("Select or upload file", "mlm"), "choose" => __("Select", "mlm"), "wait" => __("Please wait ...", "mlm"), "all_fields" => __("All fields are required", "mlm"), "no_response" => __("Server not responded. Please try again", "mlm"), "ticket_req" => __("Ticket subject and content are required.", "mlm"), "ticket_reply" => __("Reply field is required.", "mlm"), "invalid_ticket" => __("Ticket ID is invalid.", "mlm"), "delete_ticket" => __("Are you sure you want to delete the ticket? recovery is not possible then!", "mlm"), "invalid_article" => __("Article ID is invalid.", "mlm"), "invalid_lesson" => __("Lesson ID is invalid.", "mlm"), "invalid_trans" => __("Invalid transaction ID.", "mlm"), "delete_article" => __("Are you sure you want to delete the article? recovery is not possible then!", "mlm"), "delete_lesson" => __("Are you sure you want to delete the lesson? recovery is not possible then!", "mlm"), "delete_trans" => __("Are you sure you want to delete the transaction? recovery is not possible then!", "mlm"), "plan_req" => __("User and plan fields are required", "mlm"), "license_req" => __("License code is required.", "mlm"), "license_check" => __("Validating license code ...", "mlm"), "marked_fields" => __("Marked fields are required.", "mlm"), "text_field" => __("Text field", "mlm")]);
    }
    public function filter_attachments($wp_query)
    {
        if (!current_user_can("activate_plugins") && isset($wp_query->query_vars["post_type"]) && $wp_query->query_vars["post_type"] == "attachment") {
            $wp_query->set("author", get_current_user_id());
        }
    }
    public function custom_column($column)
    {
        $column["products"] = __("Products", "mlm");
        $column["balance"] = __("Balance", "mlm");
        $column["mobile_verify_status"] = "وضعیت موبایل";
        $column["email_verify_status"] = "وضعیت ایمیل";
        return $column;
    }
    public function custom_column_content($value, $column_name, $user_id)
    {
        switch ($column_name) {
            case "products":
                $posts_count = count_user_posts($user_id, "product");
                $row_content = "<a href=\"" . admin_url("edit.php?author=" . $user_id . "&post_type=product") . "\">" . $posts_count . "</a>";
                return $row_content;
                break;
            case "balance":
                return mlm_filter(mlmFire()->wallet->get_balance($user_id));
                break;
            case "mobile_verify_status":
                $mobile_verified = get_user_meta($user_id, "mlm_mobile_verified", true);
                if ($mobile_verified) {
                    return "<span class=\"dashicons dashicons-yes\" style=\"font-size:24px;\"></span> تایید شده";
                }
                return "<span class=\"dashicons dashicons-no\" style=\"font-size:22px;\"></span> تایید نشده";
                break;
            case "email_verify_status":
                $email_verified = get_user_meta($user_id, "mlm_email_verified", true);
                if ($email_verified) {
                    return "<span class=\"dashicons dashicons-yes\" style=\"font-size:24px;\"></span> تایید شده";
                }
                return "<span class=\"dashicons dashicons-no\" style=\"font-size:22px;\"></span> تایید نشده";
                break;
            default:
                return $value;
        }
    }
    public function category_extra_fields_add($taxonomy)
    {
        $image_src = IMAGES . "/avatar.svg";
        echo "\r\n\t\t<div class=\"form-field term-group\">\r\n\t\t\t<label for=\"mlm_image\">";
        _e("Image", "mlm");
        echo "</label>\r\n\t\t\t<input type=\"text\" name=\"mlm_image\" class=\"regular-text image\">\r\n\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t<img src=\"";
        echo esc_url($image_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t</div>\r\n\t\t</div>\r\n\r\n\t\t";
    }
    public function category_extra_fields_edit($term, $taxonomy)
    {
        $mlm_image = get_term_meta($term->term_id, "mlm_image", true);
        $image_src = empty($mlm_image) ? IMAGES . "/avatar.svg" : $mlm_image;
        echo "\r\n\t\t<tr class=\"form-field term-group-wrap\">\r\n\t\t\t<th scope=\"row\"><label for=\"mlm_image\">";
        _e("Image", "mlm");
        echo "</label></th>\r\n\t\t\t<td>\r\n\t\t\t\t<input type=\"text\" name=\"mlm_image\" class=\"regular-text image\" value=\"";
        echo $mlm_image;
        echo "\">\r\n\t\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t<img src=\"";
        echo esc_url($image_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t</div>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\r\n\t\t";
    }
    public function category_extra_fields_save($term_id, $tt_id)
    {
        if (isset($_POST["mlm_image"])) {
            update_term_meta($term_id, "mlm_image", esc_url($_POST["mlm_image"]));
        }
    }
    public function category_extra_fields_update($term_id, $tt_id)
    {
        if (isset($_POST["mlm_image"])) {
            update_term_meta($term_id, "mlm_image", esc_url($_POST["mlm_image"]));
        }
    }
    public function user_profile_fields($user)
    {
        $mlm_blue_badge = get_user_meta($user->ID, "mlm_blue_badge", true);
        $mlm_card = get_user_meta($user->ID, "mlm_card", true);
        $mlm_sheba = get_user_meta($user->ID, "mlm_sheba", true);
        $mlm_owner = get_user_meta($user->ID, "mlm_owner", true);
        $mlm_mobile = get_user_meta($user->ID, "mlm_mobile", true);
        $mlm_state = get_user_meta($user->ID, "mlm_state", true);
        $mlm_twitter = get_user_meta($user->ID, "mlm_twitter", true);
        $mlm_aparat = get_user_meta($user->ID, "mlm_aparat", true);
        $mlm_telegram = get_user_meta($user->ID, "mlm_telegram", true);
        $mlm_instagram = get_user_meta($user->ID, "mlm_instagram", true);
        $mlm_youtube = get_user_meta($user->ID, "mlm_youtube", true);
        $mlm_avatar = get_user_meta($user->ID, "mlm_avatar", true);
        $mlm_cover = get_user_meta($user->ID, "mlm_cover", true);
        $image_src = empty($mlm_avatar) ? IMAGES . "/avatar.svg" : $mlm_avatar;
        $cover_src = empty($mlm_cover) ? IMAGES . "/cover.png" : $mlm_cover;
        echo "\r\n\t\t<h2>";
        _e("MarketMLM user information", "mlm");
        echo "</h2>\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_blue_badge\">";
        _e("Blue badge", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_blue_badge\" id=\"mlm_blue_badge\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option value=\"no\" ";
        selected($mlm_blue_badge, "no");
        echo ">";
        _e("No", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"yes\" ";
        selected($mlm_blue_badge, "yes");
        echo ">";
        _e("Yes", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_card\">";
        _e("Card number", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_card\" id=\"mlm_card\" value=\"";
        echo esc_attr($mlm_card);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_sheba\">";
        _e("Sheba code", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_sheba\" id=\"mlm_sheba\" value=\"";
        echo esc_attr($mlm_sheba);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_owner\">";
        _e("Card owner", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_owner\" id=\"mlm_owner\" value=\"";
        echo esc_attr($mlm_owner);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_mobile\">";
        _e("Mobile", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_mobile\" id=\"mlm_mobile\" value=\"";
        echo esc_attr($mlm_mobile);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_state\">";
        _e("State", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_state\" id=\"mlm_state\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("East Azerbaijan", "mlm"));
        echo ">";
        _e("East Azerbaijan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("West Azerbaijan", "mlm"));
        echo ">";
        _e("West Azerbaijan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Ardabil", "mlm"));
        echo ">";
        _e("Ardabil", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Isfahan", "mlm"));
        echo ">";
        _e("Isfahan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Ilam", "mlm"));
        echo ">";
        _e("Alborz", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Ilam", "mlm"));
        echo ">";
        _e("Ilam", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Bushehr", "mlm"));
        echo ">";
        _e("Bushehr", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Tehran", "mlm"));
        echo ">";
        _e("Tehran", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Chaharmahal-o bakhtiari", "mlm"));
        echo ">";
        _e("Chaharmahal-o bakhtiari", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("South Khorasan", "mlm"));
        echo ">";
        _e("South Khorasan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Razavi Khorasan", "mlm"));
        echo ">";
        _e("Razavi Khorasan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("North Khorasan", "mlm"));
        echo ">";
        _e("North Khorasan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Khuzestan", "mlm"));
        echo ">";
        _e("Khuzestan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Zanjan", "mlm"));
        echo ">";
        _e("Zanjan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Semnan", "mlm"));
        echo ">";
        _e("Semnan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Sistan-o Baluchestan", "mlm"));
        echo ">";
        _e("Sistan-o Baluchestan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Fars", "mlm"));
        echo ">";
        _e("Fars", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Qazvin", "mlm"));
        echo ">";
        _e("Qazvin", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Qom", "mlm"));
        echo ">";
        _e("Qom", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Kordestan", "mlm"));
        echo ">";
        _e("Kordestan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Kerman", "mlm"));
        echo ">";
        _e("Kerman", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Kermanshah", "mlm"));
        echo ">";
        _e("Kermanshah", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Kohgiluye Buyer Ahmad", "mlm"));
        echo ">";
        _e("Kohgiluye Buyer Ahmad", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Golestan", "mlm"));
        echo ">";
        _e("Golestan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Guilan", "mlm"));
        echo ">";
        _e("Guilan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Lorestan", "mlm"));
        echo ">";
        _e("Lorestan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Mazandaran", "mlm"));
        echo ">";
        _e("Mazandaran", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Markazi", "mlm"));
        echo ">";
        _e("Markazi", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Hormozgan", "mlm"));
        echo ">";
        _e("Hormozgan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Hamedan", "mlm"));
        echo ">";
        _e("Hamedan", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option ";
        selected($mlm_state, __("Yazd", "mlm"));
        echo ">";
        _e("Yazd", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_twitter\">";
        _e("Twitter", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_twitter\" id=\"mlm_twitter\" value=\"";
        echo esc_attr($mlm_twitter);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_aparat\">";
        _e("Aparat", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_aparat\" id=\"mlm_aparat\" value=\"";
        echo esc_attr($mlm_aparat);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_telegram\">";
        _e("Telegram", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_telegram\" id=\"mlm_telegram\" value=\"";
        echo esc_attr($mlm_telegram);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_instagram\">";
        _e("Instagram", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_instagram\" id=\"mlm_instagram\" value=\"";
        echo esc_attr($mlm_instagram);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_youtube\">";
        _e("Youtube", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_youtube\" id=\"mlm_youtube\" value=\"";
        echo esc_attr($mlm_youtube);
        echo "\" class=\"regular-text\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_avatar\">";
        _e("Profile image", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_avatar\" id=\"mlm_avatar\" value=\"";
        echo esc_attr($mlm_avatar);
        echo "\" class=\"regular-text image\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-primary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($image_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_cover\">";
        _e("Cover image", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_cover\" id=\"mlm_cover\" value=\"";
        echo esc_attr($mlm_cover);
        echo "\" class=\"regular-text image\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-primary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($cover_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function save_user_fields($user_id)
    {
        if (!current_user_can("manage_options")) {
            return false;
        }
        if (isset($_POST["mlm_blue_badge"])) {
            update_user_meta($user_id, "mlm_blue_badge", sanitize_text_field($_POST["mlm_blue_badge"]));
        }
        if (isset($_POST["mlm_card"])) {
            update_user_meta($user_id, "mlm_card", sanitize_text_field($_POST["mlm_card"]));
        }
        if (isset($_POST["mlm_sheba"])) {
            update_user_meta($user_id, "mlm_sheba", sanitize_text_field($_POST["mlm_sheba"]));
        }
        if (isset($_POST["mlm_owner"])) {
            update_user_meta($user_id, "mlm_owner", sanitize_text_field($_POST["mlm_owner"]));
        }
        if (isset($_POST["mlm_state"])) {
            update_user_meta($user_id, "mlm_state", sanitize_text_field($_POST["mlm_state"]));
        }
        if (isset($_POST["mlm_twitter"])) {
            update_user_meta($user_id, "mlm_twitter", esc_url($_POST["mlm_twitter"]));
        }
        if (isset($_POST["mlm_aparat"])) {
            update_user_meta($user_id, "mlm_aparat", esc_url($_POST["mlm_aparat"]));
        }
        if (isset($_POST["mlm_telegram"])) {
            update_user_meta($user_id, "mlm_telegram", esc_url($_POST["mlm_telegram"]));
        }
        if (isset($_POST["mlm_instagram"])) {
            update_user_meta($user_id, "mlm_instagram", esc_url($_POST["mlm_instagram"]));
        }
        if (isset($_POST["mlm_youtube"])) {
            update_user_meta($user_id, "mlm_youtube", esc_url($_POST["mlm_youtube"]));
        }
        if (isset($_POST["mlm_avatar"])) {
            update_user_meta($user_id, "mlm_avatar", esc_url($_POST["mlm_avatar"]));
        }
        if (isset($_POST["mlm_cover"])) {
            update_user_meta($user_id, "mlm_cover", esc_url($_POST["mlm_cover"]));
        }
        mlmFire()->dashboard->get_profile_status($user_id, true);
    }
    public function check_user_mobile($user_id)
    {
        if (!isset($_POST["mlm_mobile"])) {
            return NULL;
        }
        $err["mobile"] = mlm_mobile_exists($_POST["mlm_mobile"], $user_id);
        if (1 <= $err["mobile"] || !mlm_is_mobile($_POST["mlm_mobile"])) {
            add_filter("user_profile_update_errors", [$this, "check_mobile_field"], 10, 3);
        } else {
            update_user_meta($user_id, "mlm_mobile", esc_attr($_POST["mlm_mobile"]));
        }
    }
    public function check_mobile_field($errors, $update, $user)
    {
        $errors->add("display_mobile_error", __("Mobile number is registered for another user.", "mlm"));
        return false;
    }
    public function toolbar_bubble($wp_admin_bar)
    {
        if (!current_user_can("manage_options")) {
            return NULL;
        }
        global $wpdb;
        $r_cnt = $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM " . $wpdb->posts . " WHERE post_type = %s AND post_status = %s", "mlm-requests", "pending"));
        $w_cnt = mlmFire()->db->count_query_rows("SELECT COUNT(id) FROM {TABLE} WHERE type = %d AND status = %d", [5, 1]);
        $t_cnt = mlmFire()->ticket->count_open_tickets(get_current_user_id());
        $posts = wp_count_posts("post");
        $products = wp_count_posts("product");
        $p_cnt = isset($posts->pending) ? $posts->pending : 0;
        $pr_cnt = isset($products->pending) ? $products->pending : 0;
        $r_append = 0 < $r_cnt ? " <span class=\"cnt\">" . $r_cnt . "</span>" : "";
        $w_append = 0 < $w_cnt ? " <span class=\"cnt\">" . $w_cnt . "</span>" : "";
        $t_append = 0 < $t_cnt ? " <span class=\"cnt\">" . $t_cnt . "</span>" : "";
        $p_append = 0 < $p_cnt ? " <span class=\"cnt\">" . $p_cnt . "</span>" : "";
        $pr_append = 0 < $pr_cnt ? " <span class=\"cnt\">" . $pr_cnt . "</span>" : "";
        $t_cnt = $r_cnt + $w_cnt + $t_cnt + $p_cnt + $pr_cnt;
        $total = 0 < $t_cnt ? " <span class=\"cnt\">" . $t_cnt . "</span>" : "";
        $wp_admin_bar->add_node(["id" => "mlm_admin_alerts", "title" => __("Market events", "mlm") . $total, "meta" => ["class" => "mlm-toolbar-bubble"]]);
        $wp_admin_bar->add_node(["id" => "mlm_request_bubble", "title" => __("Upgrade requests", "mlm") . $r_append, "href" => admin_url("edit.php?post_type=mlm-requests"), "meta" => ["class" => "mlm-toolbar-bubble"], "parent" => "mlm_admin_alerts"]);
        $wp_admin_bar->add_node(["id" => "mlm_withdraw_bubble", "title" => __("Withdraw requests", "mlm") . $w_append, "href" => admin_url("admin.php?page=mlm-withdrawals"), "meta" => ["class" => "mlm-toolbar-bubble"], "parent" => "mlm_admin_alerts"]);
        $wp_admin_bar->add_node(["id" => "mlm_ticket_bubble", "title" => __("Open tickets", "mlm") . $t_append, "href" => admin_url("admin.php?page=mlm-tickets"), "meta" => ["class" => "mlm-toolbar-bubble"], "parent" => "mlm_admin_alerts"]);
        $wp_admin_bar->add_node(["id" => "mlm_post_bubble", "title" => __("Pending posts", "mlm") . $p_append, "href" => admin_url("edit.php"), "meta" => ["class" => "mlm-toolbar-bubble"], "parent" => "mlm_admin_alerts"]);
        $wp_admin_bar->add_node(["id" => "mlm_product_bubble", "title" => __("Pending products", "mlm") . $pr_append, "href" => admin_url("edit.php?post_type=product"), "meta" => ["class" => "mlm-toolbar-bubble"], "parent" => "mlm_admin_alerts"]);
    }
    public function menu_bubble()
    {
        global $menu;
        $pending = mlmFire()->db->count_query_rows("SELECT COUNT(id) FROM {TABLE} WHERE type = %d AND status = %d", [5, 1]);
        if (!$pending) {
            return NULL;
        }
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == "mlm-shop") {
                $menu[$key][0] .= " <span class=\"update-plugins count-" . $pending . "\"><span class=\"plugin-count\">" . $pending . "</span></span>";
                return NULL;
            }
        }
    }
    public function custom_posttype()
    {
        if (class_exists("Zhkt_Guard_MarketMLM_SDK") && !Zhkt_Guard_MarketMLM_SDK::is_activated()) {
            return NULL;
        }
        $labels = ["name" => __("Requests", "mlm"), "add_new" => __("Add new request", "mlm"), "singular_name" => __("Request", "mlm"), "menu_name" => __("Upgrade requests", "mlm"), "name_admin_bar" => __("Upgrade request", "mlm"), "view_item" => __("View", "mlm"), "all_items" => __("Upgrade request", "mlm"), "search_items" => __("Search requests", "mlm"), "not_found" => __("No requests found", "mlm"), "not_found_in_trash" => __("trash is empty", "mlm")];
        $args = ["label" => __("Upgrade requests", "mlm"), "labels" => $labels, "public" => false, "show_ui" => true, "has_archive" => false, "hierarchical" => false, "capability_type" => "page", "capabilities" => ["create_posts" => "do_not_allow"], "map_meta_cap" => true, "show_in_menu" => "mlm-wallet", "rewrite" => ["slug" => "mlm-requests"], "supports" => ["title"]];
        register_post_type("mlm-requests", $args);
        $q_labels = ["name" => __("Questions", "mlm"), "add_new" => __("New question", "mlm"), "singular_name" => __("Question", "mlm"), "menu_name" => __("Faq", "mlm"), "name_admin_bar" => __("Questions", "mlm"), "view_item" => __("View", "mlm"), "all_items" => __("Questions", "mlm"), "search_items" => __("Search questions", "mlm"), "not_found" => __("No questions found", "mlm"), "not_found_in_trash" => __("trash is empty", "mlm")];
        $q_args = ["label" => __("Faq", "mlm"), "labels" => $q_labels, "public" => false, "show_ui" => true, "has_archive" => false, "hierarchical" => false, "capability_type" => "page", "show_in_menu" => "mlm-wallet", "rewrite" => ["slug" => "mlm-questions"], "supports" => ["title", "editor"]];
        register_post_type("mlm-questions", $q_args);
    }
    public function custom_metabox()
    {
        add_meta_box("mlm_product_metabox", __("Details", "mlm"), [$this, "product_meta_callback"], "product", "normal", "high");
        add_meta_box("mlm_moderation_metabox", __("Moderation result", "mlm"), [$this, "post_moderation_callback"], ["post", "product"], "normal", "high");
        add_meta_box("mlm_request_metabox", __("Status", "mlm"), [$this, "request_meta_callback"], "mlm-requests", "normal", "high");
        add_meta_box("mlm_request_user_metabox", __("User details", "mlm"), [$this, "request_user_callback"], "mlm-requests", "normal", "high");
        $demo = mlm_selected_demo();
        if ($demo == "zhaket") {
            add_meta_box("mlm_images_metabox", __("Images", "mlm"), [$this, "images_meta_callback"], "product", "normal", "high");
        }
        add_meta_box("mlm_course_metabox", __("Course details", "mlm"), [$this, "course_meta_callback"], "product", "normal", "high");
        add_meta_box("mlm_medals_metabox", __("Medals", "mlm"), [$this, "medals_meta_callback"], "product", "normal", "high");
        add_meta_box("mlm_percent_metabox", __("Percents", "mlm"), [$this, "percent_meta_callback"], "product", "normal", "high");
    }
    public function product_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_ref_value = get_post_meta($post->ID, "mlm_ref_value", true);
        $mlm_button_text = get_post_meta($post->ID, "mlm_button_text", true);
        $mlm_button_link = get_post_meta($post->ID, "mlm_button_link", true);
        $mlm_button_2_text = get_post_meta($post->ID, "mlm_button_2_text", true);
        $mlm_button_2_link = get_post_meta($post->ID, "mlm_button_2_link", true);
        $mlm_file_publish = get_post_meta($post->ID, "mlm_file_publish", true);
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            $mlm_saved_fields = get_post_meta($post->ID, "mlm_saved_fields", true);
        } else {
            $types = $this->supported_file_types();
            $mlm_file_type = get_post_meta($post->ID, "mlm_file_type", true);
            $mlm_page_count = get_post_meta($post->ID, "mlm_page_count", true);
            $mlm_part_count = get_post_meta($post->ID, "mlm_part_count", true);
            $mlm_file_author = get_post_meta($post->ID, "mlm_file_author", true);
            $mlm_file_size = get_post_meta($post->ID, "mlm_file_size", true);
            $mlm_file_format = get_post_meta($post->ID, "mlm_file_format", true);
            $mlm_file_language = get_post_meta($post->ID, "mlm_file_language", true);
            $mlm_file_step = get_post_meta($post->ID, "mlm_file_step", true);
        }
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_ref_value\">";
        _e("Referrer share", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_ref_value\" id=\"mlm_ref_value\" class=\"regular-text\">\r\n\t\t\t\t\t\t";
        $i = 0;
        while ($i <= 80) {
            echo "\t\t\t\t\t\t\t<option value=\"";
            echo $i;
            echo "\" ";
            selected($mlm_ref_value, $i);
            echo ">";
            echo $i;
            echo " ";
            _e("percent", "mlm");
            echo "</option>\r\n\t\t\t\t\t\t";
            $i = $i + 5;
        }
        echo "\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_button_text\">";
        _e("Demo button title", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_button_text\" id=\"mlm_button_text\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_button_text);
        echo "\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_button_link\">";
        _e("Demo button link", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_button_link\" id=\"mlm_button_link\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_button_link);
        echo "\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n            <tr>\r\n                <th><label for=\"mlm_button_2_text\">";
        _e("Demo button 2 title", "mlm");
        echo "</label></th>\r\n                <td>\r\n                    <input type=\"text\" name=\"mlm_button_2_text\" id=\"mlm_button_2_text\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_button_2_text);
        echo "\">\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <th><label for=\"mlm_button_2_link\">";
        _e("Demo button 2 link", "mlm");
        echo "</label></th>\r\n                <td>\r\n                    <input type=\"text\" name=\"mlm_button_2_link\" id=\"mlm_button_2_link\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_button_2_link);
        echo "\">\r\n                </td>\r\n            </tr>\r\n\t\t\t";
        if ($fields_type == "custom") {
            echo "\t\t\t\t";
            $this->custom_fields($mlm_saved_fields);
            echo "\t\t\t";
        } else {
            echo "\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_type\">";
            _e("File type", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<select name=\"mlm_file_type\" id=\"mlm_file_type\" class=\"regular-text\">\r\n\t\t\t\t\t\t";
            foreach ($types as $k => $v) {
                echo "\t\t\t\t\t\t\t<option value=\"";
                echo $k;
                echo "\" ";
                selected($mlm_file_type, $k);
                echo ">";
                echo $v["name"];
                echo "</option>\r\n\t\t\t\t\t\t";
            }
            echo "\t\t\t\t\t\t</select>\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_page_count\">";
            _e("Page count or duration", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_page_count\" id=\"mlm_page_count\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_page_count);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_part_count\">";
            _e("Parts count", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_part_count\" id=\"mlm_part_count\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_part_count);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_author\">";
            _e("Organizer or author", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_file_author\" id=\"mlm_file_author\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_file_author);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_size\">";
            _e("File size", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_file_size\" id=\"mlm_file_size\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_file_size);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_format\">";
            _e("File format", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_file_format\" id=\"mlm_file_format\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_file_format);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_language\">";
            _e("Language", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_file_language\" id=\"mlm_file_language\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_file_language);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label for=\"mlm_file_step\">";
            _e("Step", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<input type=\"text\" name=\"mlm_file_step\" id=\"mlm_file_step\" class=\"regular-text\" value=\"";
            echo esc_attr($mlm_file_step);
            echo "\">\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t";
        }
        echo "\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_file_publish\">";
        _e("Publish time", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_file_publish\" id=\"mlm_file_publish\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_file_publish);
        echo "\">\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Enter a gregorian date with this format 2021-02-03", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function post_moderation_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_reject = get_post_meta($post->ID, "mlm_reject", true);
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_notification\">";
        _e("Notifications", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_notification\" id=\"mlm_notification\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option value=\"\">";
        _e("Don't send SMS or email", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"publish\">";
        _e("Send publish post email & SMS", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"reject\">";
        _e("Send reject post email & SMS", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_reject\">";
        _e("Problems", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<textarea name=\"mlm_reject\" id=\"mlm_reject\" class=\"large-text\" cols=\"10\" rows=\"5\">";
        echo esc_textarea($mlm_reject);
        echo "</textarea>\r\n\t\t\t\t\t<p class=\"description\">";
        _e("In case that a vendor submitted the product, you can write the problems to solve and it will displayed in vendor panel.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function request_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_role = get_post_meta($post->ID, "mlm_role", true);
        $mlm_status = get_post_meta($post->ID, "mlm_status", true);
        $mlm_reject = get_post_meta($post->ID, "mlm_reject", true);
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"\">";
        _e("User", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<a href=\"";
        echo mlm_get_user_link($post->post_author);
        echo "\" class=\"button button-primary button-large\">\r\n\t\t\t\t\t\t";
        _e("View profile", "mlm");
        echo " ";
        echo mlm_get_user_name($post->post_author);
        echo "\t\t\t\t\t</a>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_role\">";
        _e("Upgrade to", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_role\" id=\"mlm_role\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option value=\"1\" ";
        selected($mlm_role, 1);
        echo ">";
        _e("Vendor", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"2\" ";
        selected($mlm_role, 2);
        echo ">";
        _e("Referrer", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_status\">";
        _e("Status", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_status\" id=\"mlm_status\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option value=\"wait\" ";
        selected($mlm_status, "wait");
        echo ">";
        _e("Pending", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"nok\" ";
        selected($mlm_status, "nok");
        echo ">";
        _e("Rejected", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"ok\" ";
        selected($mlm_status, "ok");
        echo ">";
        _e("Verified", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t\t<p class=\"description\">\r\n\t\t\t\t\t\t";
        _e("In case of verification, user role will upgraded.", "mlm");
        echo "<br />\r\n\t\t\t\t\t\t";
        _e("In case of reject, user role will be customer.", "mlm");
        echo "\t\t\t\t\t</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_reject\">";
        _e("Problems", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<textarea name=\"mlm_reject\" id=\"mlm_reject\" class=\"large-text\" cols=\"10\" rows=\"5\">";
        echo esc_textarea($mlm_reject);
        echo "</textarea>\r\n\t\t\t\t\t<p class=\"description\">";
        _e("In case that user information are incomplete you can write here ant it will be displayed in user panel.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function request_user_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_gender = get_post_meta($post->ID, "mlm_gender", true);
        $mlm_fname = get_post_meta($post->ID, "mlm_fname", true);
        $mlm_lname = get_post_meta($post->ID, "mlm_lname", true);
        $mlm_birth = get_post_meta($post->ID, "mlm_birth", true);
        $mlm_melli = get_post_meta($post->ID, "mlm_melli", true);
        $mlm_phone = get_post_meta($post->ID, "mlm_phone", true);
        $mlm_address = get_post_meta($post->ID, "mlm_address", true);
        $mlm_postal = get_post_meta($post->ID, "mlm_postal", true);
        $mlm_melli_file = get_post_meta($post->ID, "mlm_melli_file", true);
        $mlm_shena_file = get_post_meta($post->ID, "mlm_shena_file", true);
        $melli_src = empty($mlm_melli_file) ? IMAGES . "/no-thumbnail.png" : $mlm_melli_file;
        $shena_src = empty($mlm_shena_file) ? IMAGES . "/no-thumbnail.png" : $mlm_shena_file;
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_gender\">";
        _e("Gender", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select id=\"mlm_gender\" class=\"regular-text\" disabled=\"disabled\">\r\n\t\t\t\t\t\t<option value=\"m\" ";
        selected($mlm_gender, "m");
        echo ">";
        _e("Male", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"f\" ";
        selected($mlm_gender, "f");
        echo ">";
        _e("Female", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_fname\">";
        _e("First name", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_fname\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_fname);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_lname\">";
        _e("Last name", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_lname\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_lname);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_birth\">";
        _e("Birth", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_birth\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_birth);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_melli\">";
        _e("National code", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_melli\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_melli);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_phone\">";
        _e("Phone", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_phone\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_phone);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_address\">";
        _e("Address", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<textarea id=\"mlm_address\" class=\"large-text\" cols=\"10\" rows=\"3\" disabled=\"disabled\">";
        echo esc_textarea($mlm_address);
        echo "</textarea>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_postal\">";
        _e("Postal code", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" id=\"mlm_postal\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_postal);
        echo "\" disabled=\"disabled\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_melli_file\">";
        _e("National card image", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<a target=\"_blank\" href=\"";
        echo esc_url($melli_src);
        echo "\" class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($melli_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</a>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th>\r\n\t\t\t\t\t<label for=\"mlm_shena_file\">";
        _e("Birth certificate image", "mlm");
        echo "</label>\r\n\t\t\t\t</th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<a target=\"_blank\" href=\"";
        echo esc_url($shena_src);
        echo "\" class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($shena_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</a>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function images_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_image_thumb = get_post_meta($post->ID, "mlm_image_thumb", true);
        $mlm_image_one = get_post_meta($post->ID, "mlm_image_one", true);
        $mlm_image_two = get_post_meta($post->ID, "mlm_image_two", true);
        $thumb_src = empty($mlm_image_thumb) ? IMAGES . "/no-thumbnail.png" : $mlm_image_thumb;
        $one_src = empty($mlm_image_one) ? IMAGES . "/no-thumbnail.png" : $mlm_image_one;
        $two_src = empty($mlm_image_two) ? IMAGES . "/no-thumbnail.png" : $mlm_image_two;
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_image_thumb\">";
        _e("80*80 image", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_image_thumb\" id=\"mlm_image_thumb\" class=\"regular-text image\" value=\"";
        echo esc_attr($mlm_image_thumb);
        echo "\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($thumb_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_image_one\">";
        _e("700*700 main image", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_image_one\" id=\"mlm_image_one\" class=\"regular-text image\" value=\"";
        echo esc_attr($mlm_image_one);
        echo "\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($one_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_image_two\">";
        _e("700*700 sub image", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_image_two\" id=\"mlm_image_two\" class=\"regular-text image\" value=\"";
        echo esc_attr($mlm_image_two);
        echo "\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($two_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function course_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_is_course = get_post_meta($post->ID, "mlm_is_course", true);
        $mlm_teacher_name = get_post_meta($post->ID, "mlm_teacher_name", true);
        $mlm_teacher_image = get_post_meta($post->ID, "mlm_teacher_image", true);
        $mlm_teacher_bio = get_post_meta($post->ID, "mlm_teacher_bio", true);
        $mlm_course_video = get_post_meta($post->ID, "mlm_course_video", true);
        $mlm_course_fill = (int) get_post_meta($post->ID, "mlm_course_fill", true);
        $image_src = empty($mlm_teacher_image) ? IMAGES . "/avatar.svg" : $mlm_teacher_image;
        $row_class = $mlm_is_course == "yes" ? "class=\"ac\"" : "class=\"kapa\"";
        $course_page = admin_url("edit.php?post_type=product");
        $course_page = add_query_arg("page", "mlm-course", $course_page);
        $course_page = add_query_arg("pid", $post->ID, $course_page);
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_is_course\">";
        _e("Product type", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<select name=\"mlm_is_course\" id=\"mlm_is_course\" class=\"regular-text\">\r\n\t\t\t\t\t\t<option value=\"no\" ";
        selected($mlm_is_course, "no");
        echo ">";
        _e("Product", "mlm");
        echo "</option>\r\n\t\t\t\t\t\t<option value=\"yes\" ";
        selected($mlm_is_course, "yes");
        echo ">";
        _e("Course", "mlm");
        echo "</option>\r\n\t\t\t\t\t</select>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr ";
        echo $row_class;
        echo ">\r\n\t\t\t\t<th><label for=\"mlm_teacher_image\">";
        _e("Course teacher image", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_teacher_image\" id=\"mlm_teacher_image\" class=\"regular-text image\" value=\"";
        echo esc_attr($mlm_teacher_image);
        echo "\">\r\n\t\t\t\t\t<button class=\"upload_image_button button button-secondary\">";
        _e("Upload", "mlm");
        echo "</button>\r\n\t\t\t\t\t<div class=\"mlm-image clearfix\">\r\n\t\t\t\t\t\t<img src=\"";
        echo esc_url($image_src);
        echo "\" alt=\"site-logo\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr ";
        echo $row_class;
        echo ">\r\n\t\t\t\t<th><label for=\"mlm_teacher_name\">";
        _e("Course teacher name", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"text\" name=\"mlm_teacher_name\" id=\"mlm_teacher_name\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_teacher_name);
        echo "\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr ";
        echo $row_class;
        echo ">\r\n\t\t\t\t<th><label for=\"mlm_teacher_bio\">";
        _e("Course teacher bio", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<textarea name=\"mlm_teacher_bio\" id=\"mlm_teacher_bio\" class=\"regular-text\" cols=\"10\" rows=\"4\">";
        echo esc_attr($mlm_teacher_bio);
        echo "</textarea>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr ";
        echo $row_class;
        echo ">\r\n\t\t\t\t<th><label for=\"mlm_course_video\">";
        _e("Course video", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<textarea name=\"mlm_course_video\" id=\"mlm_course_video\" class=\"regular-text\" cols=\"10\" rows=\"4\">";
        echo $mlm_course_video;
        echo "</textarea>\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Youtube or Vimeo embed code.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr ";
        echo $row_class;
        echo ">\r\n\t\t\t\t<th><label for=\"mlm_course_fill\">";
        _e("Course progress status", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_course_fill\" id=\"mlm_course_fill\" class=\"regular-text\" value=\"";
        echo esc_attr($mlm_course_fill);
        echo "\" min=\"0\" max=\"100\" step=\"1\">\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t";
        if (mlm_post_exists($post->ID)) {
            echo "\t\t\t\t<tr ";
            echo $row_class;
            echo ">\r\n\t\t\t\t\t<th><label for=\"\">";
            _e("Articles and lessons", "mlm");
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<a href=\"";
            echo $course_page;
            echo "\" class=\"button button-primary button-large\">";
            _e("Add articles and lessons", "mlm");
            echo "</a>\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t";
        }
        echo "\t\t</table>\r\n\r\n\t\t";
    }
    public function medals_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $medals = mlmFire()->medal->product_medals();
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t";
        foreach ((array) $medals as $medal) {
            echo "\t\t\t\t";
            $value = get_post_meta($post->ID, "mlm_medal_" . $medal, true);
            echo "\t\t\t\t<tr>\r\n\t\t\t\t\t<th><label>";
            echo mlmFire()->medal->get_product_medal_title($medal);
            echo "</label></th>\r\n\t\t\t\t\t<td>\r\n\t\t\t\t\t\t<label for=\"mlm_medal_";
            echo $medal;
            echo "\">\r\n\t\t\t\t\t\t\t<input name=\"mlm_medal_";
            echo $medal;
            echo "\" type=\"checkbox\" id=\"mlm_medal_";
            echo $medal;
            echo "\" value=\"1\" ";
            checked($value, 1);
            echo ">\r\n\t\t\t\t\t\t\t";
            _e("Enable medal for the product", "mlm");
            echo "\t\t\t\t\t\t</label>\r\n\t\t\t\t\t</td>\r\n\t\t\t\t</tr>\r\n\t\t\t";
        }
        echo "\t\t</table>\r\n\r\n\t\t";
    }
    public function percent_meta_callback($post)
    {
        wp_nonce_field(basename(__FILE__), "mlm_kagan");
        $mlm_site_ref = get_post_meta($post->ID, "mlm_site_ref", true);
        $mlm_buyer_ref = get_post_meta($post->ID, "mlm_buyer_ref", true);
        $mlm_zir_ref1 = get_post_meta($post->ID, "mlm_zir_ref1", true);
        $mlm_zir_ref2 = get_post_meta($post->ID, "mlm_zir_ref2", true);
        $mlm_zir_ref3 = get_post_meta($post->ID, "mlm_zir_ref3", true);
        $mlm_zir_ref4 = get_post_meta($post->ID, "mlm_zir_ref4", true);
        $mlm_zir_ref5 = get_post_meta($post->ID, "mlm_zir_ref5", true);
        echo "\r\n\t\t<table class=\"form-table\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_site_ref\">";
        _e("Site percent", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_site_ref\" id=\"mlm_site_ref\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_site_ref;
        echo "\" />\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_buyer_ref\">";
        _e("Customer percent", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_buyer_ref\" id=\"mlm_buyer_ref\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_buyer_ref;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Enter a numeric value between 0 and 100. For example 10 equals to 10%% of cart total amount.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_zir_ref1\">";
        _e("Reagent percent step 1", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_zir_ref1\" id=\"mlm_zir_ref1\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_zir_ref1;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Reagent percent from site share. for example 15 equals to 15%% of site share.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_zir_ref2\">";
        _e("Reagent percent step 2", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_zir_ref2\" id=\"mlm_zir_ref2\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_zir_ref2;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Reagent percent from site share. for example 15 equals to 15%% of site share.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_zir_ref3\">";
        _e("Reagent percent step 3", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_zir_ref3\" id=\"mlm_zir_ref3\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_zir_ref3;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Reagent percent from site share. for example 15 equals to 15%% of site share.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_zir_ref4\">";
        _e("Reagent percent step 4", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_zir_ref4\" id=\"mlm_zir_ref4\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_zir_ref4;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Reagent percent from site share. for example 15 equals to 15%% of site share.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<th><label for=\"mlm_zir_ref5\">";
        _e("Reagent percent step 5", "mlm");
        echo "</label></th>\r\n\t\t\t\t<td>\r\n\t\t\t\t\t<input type=\"number\" name=\"mlm_zir_ref5\" id=\"mlm_zir_ref5\" class=\"regular-text\" min=\"0\" max=\"100\" step=\"1\" value=\"";
        echo $mlm_zir_ref5;
        echo "\" />\r\n\t\t\t\t\t<p class=\"description\">";
        _e("Reagent percent from site share. for example 15 equals to 15%% of site share.", "mlm");
        echo "</p>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</table>\r\n\r\n\t\t";
    }
    public function save_meta_values($post_id)
    {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = isset($_POST["mlm_kagan"]) && wp_verify_nonce($_POST["mlm_kagan"], basename(__FILE__)) ? "true" : "false";
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return NULL;
        }
        $user_id = get_post_field("post_author", $post_id);
        if (isset($_POST["mlm_ref_value"])) {
            update_post_meta($post_id, "mlm_ref_value", absint($_POST["mlm_ref_value"]));
            mlmFire()->wallet->post_ref_amount($post_id);
        }
        if (isset($_POST["mlm_file_type"])) {
            update_post_meta($post_id, "mlm_file_type", esc_attr($_POST["mlm_file_type"]));
        }
        if (isset($_POST["mlm_page_count"])) {
            update_post_meta($post_id, "mlm_page_count", esc_attr($_POST["mlm_page_count"]));
        }
        if (isset($_POST["mlm_part_count"])) {
            update_post_meta($post_id, "mlm_part_count", esc_attr($_POST["mlm_part_count"]));
        }
        if (isset($_POST["mlm_file_author"])) {
            update_post_meta($post_id, "mlm_file_author", esc_attr($_POST["mlm_file_author"]));
        }
        if (isset($_POST["mlm_file_size"])) {
            update_post_meta($post_id, "mlm_file_size", esc_attr($_POST["mlm_file_size"]));
        }
        if (isset($_POST["mlm_file_format"])) {
            update_post_meta($post_id, "mlm_file_format", esc_attr($_POST["mlm_file_format"]));
        }
        if (isset($_POST["mlm_file_language"])) {
            update_post_meta($post_id, "mlm_file_language", esc_attr($_POST["mlm_file_language"]));
        }
        if (isset($_POST["mlm_file_step"])) {
            update_post_meta($post_id, "mlm_file_step", esc_attr($_POST["mlm_file_step"]));
        }
        if (isset($_POST["mlm_file_publish"])) {
            update_post_meta($post_id, "mlm_file_publish", esc_attr($_POST["mlm_file_publish"]));
        }
        if (isset($_POST["mlm_custom"])) {
            update_post_meta($post_id, "mlm_saved_fields", mlm_sanitize_array($_POST["mlm_custom"]));
        }
        if (isset($_POST["mlm_image_thumb"])) {
            update_post_meta($post_id, "mlm_image_thumb", esc_url($_POST["mlm_image_thumb"]));
        }
        if (isset($_POST["mlm_image_one"])) {
            update_post_meta($post_id, "mlm_image_one", esc_url($_POST["mlm_image_one"]));
        }
        if (isset($_POST["mlm_image_two"])) {
            update_post_meta($post_id, "mlm_image_two", esc_url($_POST["mlm_image_two"]));
        }
        if (isset($_POST["mlm_is_course"])) {
            update_post_meta($post_id, "mlm_is_course", esc_attr($_POST["mlm_is_course"]));
        }
        if (isset($_POST["mlm_button_text"])) {
            update_post_meta($post_id, "mlm_button_text", esc_attr($_POST["mlm_button_text"]));
        }
        if (isset($_POST["mlm_button_link"])) {
            update_post_meta($post_id, "mlm_button_link", esc_url($_POST["mlm_button_link"]));
        }
        if (isset($_POST["mlm_button_2_text"])) {
            update_post_meta($post_id, "mlm_button_2_text", esc_attr($_POST["mlm_button_2_text"]));
        }
        if (isset($_POST["mlm_button_2_link"])) {
            update_post_meta($post_id, "mlm_button_2_link", esc_url($_POST["mlm_button_2_link"]));
        }
        if (isset($_POST["mlm_teacher_name"])) {
            update_post_meta($post_id, "mlm_teacher_name", esc_attr($_POST["mlm_teacher_name"]));
        }
        if (isset($_POST["mlm_teacher_image"])) {
            update_post_meta($post_id, "mlm_teacher_image", esc_url($_POST["mlm_teacher_image"]));
        }
        if (isset($_POST["mlm_teacher_bio"])) {
            update_post_meta($post_id, "mlm_teacher_bio", esc_textarea($_POST["mlm_teacher_bio"]));
        }
        if (isset($_POST["mlm_course_video"])) {
            update_post_meta($post_id, "mlm_course_video", esc_textarea($_POST["mlm_course_video"]));
        }
        if (isset($_POST["mlm_course_fill"])) {
            update_post_meta($post_id, "mlm_course_fill", absint($_POST["mlm_course_fill"]));
        }
        if (isset($_POST["mlm_site_ref"])) {
            update_post_meta($post_id, "mlm_site_ref", sanitize_text_field($_POST["mlm_site_ref"]));
        }
        if (isset($_POST["mlm_buyer_ref"])) {
            update_post_meta($post_id, "mlm_buyer_ref", sanitize_text_field($_POST["mlm_buyer_ref"]));
        }
        if (isset($_POST["mlm_zir_ref1"])) {
            update_post_meta($post_id, "mlm_zir_ref1", sanitize_text_field($_POST["mlm_zir_ref1"]));
        }
        if (isset($_POST["mlm_zir_ref2"])) {
            update_post_meta($post_id, "mlm_zir_ref2", sanitize_text_field($_POST["mlm_zir_ref2"]));
        }
        if (isset($_POST["mlm_zir_ref3"])) {
            update_post_meta($post_id, "mlm_zir_ref3", sanitize_text_field($_POST["mlm_zir_ref3"]));
        }
        if (isset($_POST["mlm_zir_ref4"])) {
            update_post_meta($post_id, "mlm_zir_ref4", sanitize_text_field($_POST["mlm_zir_ref4"]));
        }
        if (isset($_POST["mlm_zir_ref5"])) {
            update_post_meta($post_id, "mlm_zir_ref5", sanitize_text_field($_POST["mlm_zir_ref5"]));
        }
        if (isset($_POST["mlm_reject"])) {
            update_post_meta($post_id, "mlm_reject", esc_textarea($_POST["mlm_reject"]));
            if (isset($_POST["mlm_notification"]) && !empty($_POST["mlm_notification"])) {
                $notif = esc_attr($_POST["mlm_notification"]);
                $type = get_post_field("post_type", $post_id);
                if ($type == "product" && $notif == "publish") {
                    mlmFire()->notif->send_user_sms($user_id, "product_published", ["post_id" => $post_id]);
                    mlmFire()->follow->notify_user_followers($user_id, $post_id);
                } else {
                    if ($type == "product" && $notif == "reject") {
                        mlmFire()->notif->send_user_sms($user_id, "product_rejected", ["post_id" => $post_id, "reason" => esc_textarea($_POST["mlm_reject"])]);
                    } else {
                        if ($type == "post" && $notif == "publish") {
                            mlmFire()->notif->send_user_sms($user_id, "post_published", ["post_id" => $post_id]);
                        } else {
                            if ($type == "post" && $notif == "reject") {
                                mlmFire()->notif->send_user_sms($user_id, "post_rejected", ["post_id" => $post_id, "reason" => esc_textarea($_POST["mlm_reject"])]);
                            }
                        }
                    }
                }
            }
        }
        if (isset($_POST["mlm_status"])) {
            $status = isset($_POST["mlm_status"]) ? esc_attr($_POST["mlm_status"]) : "wait";
            $role = isset($_POST["mlm_role"]) ? absint($_POST["mlm_role"]) : 1;
            update_post_meta($post_id, "mlm_role", $role);
            update_post_meta($post_id, "mlm_status", $status);
            if (!user_can($user_id, "moderate_comments")) {
                $userObj = new WP_User($user_id);
                if ($status == "ok") {
                    if ($role == 2) {
                        $userObj->set_role("mlm_refer");
                    } else {
                        $userObj->set_role("mlm_vendor");
                    }
                    mlmFire()->notif->send_user_mail($user_id, "upgraded");
                    mlmFire()->notif->send_user_sms($user_id, "upgraded");
                } else {
                    if ($status == "nok") {
                        $userObj->set_role("mlm_customer");
                    }
                }
            }
        }
        $medals = mlmFire()->medal->product_medals();
        foreach ((array) $medals as $medal) {
            if (isset($_POST["mlm_medal_" . $medal])) {
                update_post_meta($post_id, "mlm_medal_" . $medal, 1);
            } else {
                delete_post_meta($post_id, "mlm_medal_" . $medal);
            }
        }
    }
    public function menu_pages()
    {
        add_menu_page(__("Theme options", "mlm"), __("Theme options", "mlm"), "manage_options", "mlm-wallet", NULL, "dashicons-groups", 26);
        if (class_exists("Zhkt_Guard_MarketMLM_SDK") && !Zhkt_Guard_MarketMLM_SDK::is_activated()) {
            return NULL;
        }
        add_submenu_page("mlm-wallet", __("Transactions", "mlm"), __("Transactions", "mlm"), "manage_options", "mlm-wallet", [$this, "wallet_callback"]);
        add_submenu_page("mlm-wallet", __("Withdrawals", "mlm"), __("Withdrawals", "mlm"), "manage_options", "mlm-withdrawals", [$this, "withdrawals_callback"]);
        add_submenu_page("mlm-wallet", __("Charge wallet", "mlm"), __("Charge wallet", "mlm"), "manage_options", "mlm-charge", [$this, "charge_callback"]);
        add_submenu_page("mlm-wallet", __("Referrals", "mlm"), __("Referrals", "mlm"), "manage_options", "mlm-referral", [$this, "referral_callback"]);
        add_submenu_page("mlm-wallet", __("Subsets", "mlm"), __("Subsets", "mlm"), "manage_options", "mlm-network", [$this, "network_callback"]);
        add_submenu_page(NULL, __("Course articles and lessons", "mlm"), __("Course articles and lessons", "mlm"), "moderate_comments", "mlm-course", [$this, "course_callback"]);
        $fields_type = mlm_custom_fields_type();
        if ($fields_type == "custom") {
            add_submenu_page("edit.php?post_type=product", __("Custom fields", "mlm"), __("Custom fields", "mlm"), "manage_options", "mlm-fields", [$this, "fields_callback"]);
        }
    }
    public function shop_callback()
    {
    }
    public function wallet_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-wallet-wrap clearfix\">";
        $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
        $mlm_id = isset($_GET["mlm_id"]) ? absint($_GET["mlm_id"]) : "";
        $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
        $mlm_type = isset($_GET["mlm_type"]) ? absint($_GET["mlm_type"]) : "";
        $per = 20;
        $start = intval(($paged - 1) * $per);
        if (!empty($mlm_id)) {
            $string = "SELECT * FROM {TABLE} WHERE id = %d AND type != %d ORDER BY id DESC LIMIT %d";
            $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE id = %d AND type != %d";
            $values = [$mlm_id, 5, 1];
            $c_values = [$mlm_id, 5];
            $link = admin_url("admin.php?page=mlm-wallet&mlm_id=" . $mlm_id);
        } else {
            if (!empty($mlm_user) && mlm_user_exists($mlm_user) && !empty($mlm_type)) {
                $string = "SELECT * FROM {TABLE} WHERE user_id = %d AND type = %d ORDER BY id DESC LIMIT %d, %d";
                $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type = %d";
                $values = [$mlm_user, $mlm_type, $start, $per];
                $c_values = [$mlm_user, $mlm_type];
                $link = admin_url("admin.php?page=mlm-wallet&mlm_user=" . $mlm_user . "&mlm_type=" . $mlm_type);
            } else {
                if (!empty($mlm_user) && mlm_user_exists($mlm_user)) {
                    $string = "SELECT * FROM {TABLE} WHERE user_id = %d AND type != %d ORDER BY id DESC LIMIT %d, %d";
                    $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type != %d";
                    $values = [$mlm_user, 5, $start, $per];
                    $c_values = [$mlm_user, 5];
                    $link = admin_url("admin.php?page=mlm-wallet&mlm_user=" . $mlm_user);
                } else {
                    if (!empty($mlm_type)) {
                        $string = "SELECT * FROM {TABLE} WHERE type = %d ORDER BY id DESC LIMIT %d, %d";
                        $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE type = %d";
                        $values = [$mlm_type, $start, $per];
                        $c_values = [$mlm_type];
                        $link = admin_url("admin.php?page=mlm-wallet&mlm_type=" . $mlm_type);
                    } else {
                        $string = "SELECT * FROM {TABLE} WHERE type != %d ORDER BY id DESC LIMIT %d, %d";
                        $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE type != %d";
                        $values = [5, $start, $per];
                        $c_values = [5];
                        $link = admin_url("admin.php?page=mlm-wallet");
                    }
                }
            }
        }
        $args = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "selected" => $mlm_user, "include_selected" => 1, "class" => "regular-text mlm-select", "name" => "mlm_user"];
        $atts = [];
        $atts["query"] = mlmFire()->db->query_rows($string, $values);
        $atts["args"] = $args;
        $atts["type"] = $mlm_type;
        $count = mlmFire()->db->count_query_rows($c_string, $c_values);
        echo mlm_get_template("class/wp-admin/wallet", $atts);
        mlm_wp_navigation($count, $link, $per);
        echo "</div>";
    }
    public function withdrawals_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-wallet-wrap clearfix\">";
        if (isset($_GET["verify"]) && wp_verify_nonce($_GET["verify"], "mlm_with_vaks")) {
            $trans_id = isset($_GET["id"]) ? absint($_GET["id"]) : 0;
            $string = "SELECT * FROM {TABLE} WHERE id = %d LIMIT %d";
            $values = [$trans_id, 1];
            $atts = [];
            $atts["query"] = mlmFire()->db->query_rows($string, $values, "wallet", true);
            $atts["id"] = $trans_id;
            echo mlm_get_template("class/wp-admin/withdrawals-open", $atts);
        } else {
            $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
            $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
            $per = 20;
            $start = intval(($paged - 1) * $per);
            if (!empty($mlm_user) && mlm_user_exists($mlm_user)) {
                $string = "SELECT * FROM {TABLE} WHERE user_id = %d AND type = %d ORDER BY id DESC LIMIT %d, %d";
                $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type = %d";
                $values = [$mlm_user, 5, $start, $per];
                $c_values = [$mlm_user, 5];
                $link = admin_url("admin.php?page=mlm-withdrawals&mlm_user=" . $mlm_user);
            } else {
                $string = "SELECT * FROM {TABLE} WHERE type = %d ORDER BY id DESC LIMIT %d, %d";
                $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE type = %d";
                $values = [5, $start, $per];
                $c_values = [5];
                $link = admin_url("admin.php?page=mlm-withdrawals");
            }
            $atts = [];
            $atts["query"] = mlmFire()->db->query_rows($string, $values);
            $atts["args"] = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "selected" => $mlm_user, "include_selected" => 1, "class" => "regular-text mlm-select", "name" => "mlm_user"];
            $count = mlmFire()->db->count_query_rows($c_string, $c_values);
            echo mlm_get_template("class/wp-admin/withdrawals", $atts);
            mlm_wp_navigation($count, $link, $per);
        }
        echo "</div>";
    }
    public function charge_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-charge-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/wallet-charge");
        echo "</div>";
    }
    public function referral_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-referral-wrap clearfix\">";
        $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
        $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
        $per = 20;
        $start = intval(($paged - 1) * $per);
        if (!empty($mlm_user) && mlm_user_exists($mlm_user)) {
            $string = "SELECT * FROM {TABLE} WHERE ref_user_id = %d ORDER BY id DESC LIMIT %d, %d";
            $c_string = "SELECT COUNT(id) FROM {TABLE} WHERE ref_user_id = %d";
            $values = [$mlm_user, $start, $per];
            $c_values = [$mlm_user];
            $link = admin_url("admin.php?page=mlm-referral&mlm_user=" . $mlm_user);
        } else {
            $string = "SELECT * FROM {TABLE} ORDER BY id DESC LIMIT %d, %d";
            $c_string = "SELECT COUNT(id) FROM {TABLE}";
            $values = [$start, $per];
            $c_values = "";
            $link = admin_url("admin.php?page=mlm-referral");
        }
        $atts = [];
        $atts["query"] = mlmFire()->db->query_rows($string, $values, "referral");
        $atts["args"] = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "selected" => $mlm_user, "include_selected" => 1, "class" => "regular-text mlm-select", "name" => "mlm_user"];
        $count = mlmFire()->db->count_query_rows($c_string, $c_values, "referral");
        echo mlm_get_template("class/wp-admin/referrals", $atts);
        mlm_wp_navigation($count, $link, $per);
        echo "</div>";
    }
    public function network_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-network-wrap clearfix\">";
        $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
        $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
        $per = 20;
        if (mlm_user_exists($mlm_user)) {
            $string = "SELECT user_id, date FROM {TABLE} WHERE parent_id = %d ORDER BY id DESC LIMIT %d";
            $values = [$mlm_user, 100];
            $atts = [];
            $atts["id"] = $mlm_user;
            $atts["query"] = mlmFire()->db->query_rows($string, $values, "network", false);
            echo mlm_get_template("class/wp-admin/subsets-open", $atts);
        } else {
            $query = new WP_User_Query(["number" => $per, "paged" => $paged, "orderby" => "login", "role" => 0]);
            $total_records = $query->get_total();
            $total_pages = ceil($total_records / $per);
            $link = admin_url("admin.php?page=mlm-network");
            $atts = ["query" => $query->results];
            echo mlm_get_template("class/wp-admin/subsets", $atts);
            mlm_wp_navigation($total_records, $link, $per);
        }
        echo "</div>";
    }
    public function course_callback()
    {
        echo "<div class=\"wrap mlm-wrap mlm-course-wrap clearfix\">";
        $post_id = isset($_GET["pid"]) ? absint($_GET["pid"]) : "";
        $query = mlmFire()->db->query_rows("SELECT * FROM {TABLE} WHERE post_id = %d AND parent_id = %d ORDER BY priority ASC", [$post_id, 0], "course");
        $atts = ["pid" => $post_id, "url" => admin_url("edit.php?post_type=product&page=mlm-course"), "nonce" => wp_create_nonce("mlm_lhsaugpqytsr"), "query" => $query];
        echo mlm_get_template("class/wp-admin/course", $atts);
        echo "</div>";
    }
    public function fields_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        $fields = $this->get_fields();
        if (!$fields) {
            $fields = [["type" => "text", "text" => __("Text field", "mlm"), "place" => __("Help text", "mlm"), "req" => "no"]];
        }
        $atts = ["fields" => $fields, "url" => admin_url("edit.php?post_type=product&page=mlm-fields")];
        echo "<div class=\"wrap mlm-wrap mlm-fields-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/fields", $atts);
        echo "</div>";
    }
    public function mail_sender_email($email)
    {
        $saved = get_option("mlm_sender_email");
        if (!empty($saved) && is_email($saved)) {
            return $saved;
        }
        return $email;
    }
    public function mail_sender_name($name)
    {
        $saved = get_option("mlm_sender_name");
        if (!empty($saved) && is_email($saved)) {
            return $saved;
        }
        return $name;
    }
    public function search_users_by_mobile($uqi)
    {
        global $wpdb;
        $search = "";
        if (isset($uqi->query_vars["search"])) {
            $search = trim($uqi->query_vars["search"]);
        }
        if ($search) {
            $search = trim($search, "*");
            $the_search = "%" . $search . "%";
            $search_meta = $wpdb->prepare("ID IN ( SELECT user_id FROM " . $wpdb->usermeta . "\r\n\t\t\t\tWHERE ( meta_key='mlm_mobile' AND " . $wpdb->usermeta . ".meta_value LIKE '%s' ) )", $the_search);
            $uqi->query_where = str_replace("WHERE 1=1 AND (", "WHERE 1=1 AND (" . $search_meta . " OR ", $uqi->query_where);
        }
    }
    public function supported_file_types()
    {
        $types = ["pdf" => ["name" => "PDF", "icon" => "icon-book-open", "title" => __("Pages count", "mlm")], "voice" => ["name" => __("Audio", "mlm"), "icon" => "icon-headphones", "title" => __("Time", "mlm")], "video" => ["name" => __("Video", "mlm"), "icon" => "icon-film", "title" => __("Time", "mlm")], "pack" => ["name" => __("Packed", "mlm"), "icon" => "icon-gift1", "title" => __("Contains", "mlm")], "app" => ["name" => __("Application", "mlm"), "icon" => "icon-mobile1", "title" => __("Contains", "mlm")], "zip" => ["name" => __("Zip", "mlm"), "icon" => "icon-file-zip", "title" => __("Contains", "mlm")], "script" => ["name" => __("Script", "mlm"), "icon" => "icon-edit", "title" => __("Contains", "mlm")], "psd" => ["name" => __("PSD", "mlm"), "icon" => "icon-layers", "title" => __("Contains", "mlm")], "theme" => ["name" => __("Theme", "mlm"), "icon" => "icon-paintbrush", "title" => __("Contains", "mlm")], "plugin" => ["name" => __("Plugin", "mlm"), "icon" => "icon-gears", "title" => __("Contains", "mlm")], "image" => ["name" => __("Image", "mlm"), "icon" => "icon-pictures", "title" => __("Contains", "mlm")], "word" => ["name" => __("Word", "mlm"), "icon" => "icon-documents", "title" => __("Contains", "mlm")], "ppt" => ["name" => __("Power Point", "mlm"), "icon" => "icon-drawer", "title" => __("Contains", "mlm")], "atc" => ["name" => __("Autocad", "mlm"), "icon" => "icon-leaf", "title" => __("Contains", "mlm")]];
        return $types;
    }
    public function get_fields()
    {
        $fields = get_option("mlm_fields");
        if (empty($fields) && empty($reset)) {
            $fields = [["type" => "text", "text" => __("Field title", "mlm"), "place" => __("Help text", "mlm"), "req" => "no"]];
        }
        if (!is_array($fields) || !count($fields)) {
            return false;
        }
        return $fields;
    }
    public function get_fields_value($input_fields)
    {
        $custom_fields = $this->get_fields();
        if (!$custom_fields) {
            return false;
        }
        $value = [];
        foreach ($custom_fields as $k => $v) {
            $value[] = ["key" => $k, "id" => $v["id"], "type" => $v["type"], "title" => $v["text"], "value" => $input_fields[$v["id"]]];
        }
        return $value;
    }
    public function custom_fields($saved_fields = [])
    {
        $custom_fields = $this->get_fields();
        if (!$custom_fields) {
            return NULL;
        }
        foreach ($custom_fields as $k => $v) {
            $req = $v["req"] == "yes" ? " <i class=\"required\">*</i>" : "";
            $val = isset($saved_fields[$v["id"]]) ? $saved_fields[$v["id"]] : "";
            echo "<tr><th>";
            echo "<label>" . $v["text"] . $req . "</label>";
            echo "</th><td>";
            echo "<input type=\"text\" name=\"mlm_custom[" . $v["id"] . "]\" class=\"regular-text\" placeholder=\"" . $v["place"] . "\" value=\"" . $val . "\" />";
            echo "</td></tr>";
        }
    }
}

?>