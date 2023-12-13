<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Database
{
    protected $wpdb = NULL;
    protected $ticket_table = NULL;
    protected $wallet_table = NULL;
    protected $referral_table = NULL;
    protected $network_table = NULL;
    protected $subscribe_table = NULL;
    protected $course_table = NULL;
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->ticket_table = $wpdb->prefix . "mlm_ticket";
        $this->wallet_table = $wpdb->prefix . "mlm_wallet";
        $this->referral_table = $wpdb->prefix . "mlm_refer";
        $this->network_table = $wpdb->prefix . "mlm_network";
        $this->subscribe_table = $wpdb->prefix . "mlm_subscribe";
        $this->course_table = $wpdb->prefix . "mlm_course";
        add_action("after_switch_theme", [$this, "theme_activated"]);
        add_action("admin_init", [$this, "update_table"]);
    }
    public function theme_activated()
    {
        $this->create_pages();
        $this->create_roles();
        $this->create_tables();
        update_option("mlm_version", "5.0.1");
    }
    public function create_pages()
    {
        $page_definitions = ["login" => ["title" => __("Login", "mlm"), "content" => "[mlm-login-form]", "option" => "mlm_login_page"], "register" => ["title" => __("Register", "mlm"), "content" => "[mlm-register-form]", "option" => "mlm_register_page"], "password-lost" => ["title" => __("Forget passowrd", "mlm"), "content" => "[mlm-password-lost-form]", "option" => "mlm_lost_page"], "panel" => ["title" => __("User panel", "mlm"), "content" => "", "option" => "mlm_panel_page"]];
        foreach ($page_definitions as $slug => $page) {
            $query = new WP_Query("pagename=" . $slug);
            if (!$query->have_posts()) {
                $post_id = wp_insert_post(["post_content" => $page["content"], "post_name" => $slug, "post_title" => $page["title"], "post_status" => "publish", "post_type" => "page", "ping_status" => "closed", "comment_status" => "closed"]);
                if (!empty($post_id) && !is_wp_error($post_id)) {
                    update_option($page["option"], $post_id);
                }
            }
        }
    }
    public function create_roles()
    {
        add_role("mlm_customer", __("Author", "mlm"), ["read" => true, "delete_posts" => true, "edit_posts" => true, "delete_published_posts" => true, "publish_posts" => true, "publish_post" => true, "upload_files" => true, "edit_published_posts" => true, "level_2" => true]);
        add_role("mlm_refer", __("Referrer", "mlm"), ["read" => true, "delete_posts" => true, "edit_posts" => true, "delete_published_posts" => true, "publish_posts" => true, "publish_post" => true, "upload_files" => true, "edit_published_posts" => true, "unfiltered_html" => true, "level_2" => true]);
        $result = add_role("mlm_vendor", __("Vendor", "mlm"), ["read" => true, "delete_posts" => true, "edit_posts" => true, "edit_products" => true, "delete_published_posts" => true, "publish_posts" => true, "publish_post" => true, "upload_files" => true, "edit_published_posts" => true, "unfiltered_html" => true, "read_private_pages" => true, "level_3" => true]);
        if (NULL === $result) {
            $role_object = get_role("mlm_vendor");
            $role_object->add_cap("edit_products");
            $role_object->add_cap("publish_post");
        }
    }
    public function create_tables()
    {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE " . $this->ticket_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tparent_id bigint(20) NOT NULL,\n\t\t\tpost_id bigint(20) NOT NULL,\n\t\t\tsender_id bigint(20) NOT NULL,\n\t\t\treciver_id bigint(20) NOT NULL,\n\t\t\ttitle text NOT NULL,\n\t\t\tcontent longtext NOT NULL,\n\t\t\tstatus tinyint(1) NOT NULL,\n\t\t\tdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\tuser_data text NOT NULL,\n\t\t\tattaches longtext NULL DEFAULT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";\n\t\tCREATE TABLE " . $this->wallet_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tuser_id bigint(20) NOT NULL,\n\t\t\tpost_id bigint(20) NOT NULL,\n\t\t\torder_id bigint(20) NOT NULL,\n\t\t\tamount bigint(15) NOT NULL,\n\t\t\tbalance bigint(20) NOT NULL,\n\t\t\ttype tinyint(1) NOT NULL,\n\t\t\tstatus tinyint(1) NOT NULL,\n\t\t\tdescription text NOT NULL,\n\t\t\tnotes longtext NOT NULL,\n\t\t\tdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";\n\t\tCREATE TABLE " . $this->referral_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tref_user_id bigint(20) NOT NULL,\n\t\t\tuser_id bigint(20) NOT NULL,\n\t\t\tuser_ip varchar(25) DEFAULT '' NOT NULL,\n\t\t\tuser_url text NOT NULL,\n\t\t\tuser_host text NOT NULL,\n\t\t\tinvalid tinyint(1) NOT NULL,\n\t\t\tpurchase tinyint(1) NOT NULL,\n\t\t\tdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";\n\t\tCREATE TABLE " . $this->network_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tuser_id bigint(20) NOT NULL,\n\t\t\tparent_id bigint(20) NOT NULL,\n\t\t\tdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";\n\t\tCREATE TABLE " . $this->subscribe_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tuser_id bigint(20) NOT NULL,\n\t\t\torder_id bigint(20) NOT NULL,\n\t\t\ttype tinyint(1) NOT NULL,\n\t\t\tstatus tinyint(1) NOT NULL,\n\t\t\tvalid tinyint(1) NOT NULL,\n\t\t\tplan_data text NOT NULL,\n\t\t\tuser_data text NOT NULL,\n\t\t\tdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\texpire datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";\n\t\tCREATE TABLE " . $this->course_table . " (\n\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\tpost_id bigint(20) NOT NULL,\n\t\t\tparent_id bigint(20) NOT NULL,\n\t\t\tpriority int(4) NOT NULL,\n\t\t\tcourse_data longtext NOT NULL,\n\t\t\tUNIQUE KEY id (id)\n\t\t) " . $charset_collate . ";";
        require_once ABSPATH . "wp-admin/includes/upgrade.php";
        dbDelta($sql);
    }
    public function update_table()
    {
        $migrated = get_option("mlm_migrated");
        $version = get_option("mlm_version");
        if (!$migrated && $version) {
            $this->wpdb->query("ALTER TABLE " . $this->ticket_table . " ADD attaches longtext NULL DEFAULT NULL");
            update_option("mlm_migrated", true);
        }
    }
    public function make_unique_id($code = 0)
    {
        if (empty($code)) {
            return mt_rand(1, 4) . mt_rand(0, 9) . time();
        }
        return absint($code) . mt_rand(1, 4) . mt_rand(0, 9) . time();
    }
    public function make_unique_key($key = "")
    {
        if (!empty($key)) {
            return md5($key);
        }
        return md5(microtime() . rand());
    }
    public function wallet_record($user_id, $post_id, $order_id, $amount, $type, $status, $text, $notes = [])
    {
        if (empty($amount) || empty($type) || empty($status)) {
            return false;
        }
        $data = ["id" => NULL, "user_id" => absint($user_id), "post_id" => absint($post_id), "order_id" => absint($order_id), "amount" => absint($amount), "balance" => mlmFire()->wallet->new_balance($user_id, $amount, $type), "type" => absint($type), "status" => absint($status), "description" => esc_attr($text), "notes" => maybe_serialize($notes), "date" => current_time("mysql")];
        $format = ["%d", "%d", "%d", "%d", "%d", "%d", "%d", "%d", "%s", "%s", "%s"];
        $this->wpdb->insert($this->wallet_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function wallet_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $user_id = isset($inputData["user_id"]) ? absint($inputData["user_id"]) : "";
        $post_id = isset($inputData["post_id"]) ? absint($inputData["post_id"]) : "";
        $order_id = isset($inputData["order_id"]) ? absint($inputData["order_id"]) : "";
        $amount = isset($inputData["amount"]) ? absint($inputData["amount"]) : "";
        $balance = isset($inputData["balance"]) ? absint($inputData["balance"]) : "";
        $type = isset($inputData["type"]) ? absint($inputData["type"]) : "";
        $status = isset($inputData["status"]) ? absint($inputData["status"]) : "";
        $description = isset($inputData["description"]) ? esc_attr($inputData["description"]) : "";
        $notes = isset($inputData["notes"]) ? mlm_sanitize_array($inputData["notes"]) : "";
        $date = isset($inputData["date"]) ? esc_attr($inputData["date"]) : "";
        if (!empty($user_id) && mlm_user_exists($user_id)) {
            $table_data["user_id"] = $user_id;
            array_push($table_format, "%d");
        }
        if (!empty($post_id) && mlm_post_exists($post_id)) {
            $table_data["post_id"] = $post_id;
            array_push($table_format, "%d");
        }
        if (!empty($order_id)) {
            $table_data["order_id"] = $order_id;
            array_push($table_format, "%d");
        }
        if (!empty($amount)) {
            $table_data["amount"] = $amount;
            array_push($table_format, "%d");
        }
        if (!empty($balance)) {
            $table_data["balance"] = $balance;
            array_push($table_format, "%d");
        }
        if (is_numeric($type)) {
            $table_data["type"] = $type;
            array_push($table_format, "%d");
        }
        if (is_numeric($status)) {
            $table_data["status"] = $status;
            array_push($table_format, "%d");
        }
        if (!empty($description)) {
            $table_data["description"] = $description;
            array_push($table_format, "%s");
        }
        if (!empty($notes)) {
            $table_data["notes"] = maybe_serialize($notes);
            array_push($table_format, "%s");
        }
        if (!empty($date)) {
            $table_data["date"] = $date;
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->wallet_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function wallet_delete($id)
    {
        if (empty($id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->wallet_table . " WHERE id = %d", $id));
        return true;
    }
    public function ticket_record($post_id, $sender_id, $reciver_id, $title, $content, $parent_id = 0, $user_data = [], $status = 1, $attaches = [])
    {
        if (empty($content) || empty($parent_id) && empty($title)) {
            return false;
        }
        $data = ["id" => NULL, "parent_id" => absint($parent_id), "post_id" => absint($post_id), "sender_id" => absint($sender_id), "reciver_id" => absint($reciver_id), "title" => esc_attr($title), "content" => wp_filter_post_kses($content), "status" => absint($status), "date" => current_time("mysql"), "user_data" => maybe_serialize($user_data), "attaches" => maybe_serialize($attaches)];
        $format = ["%d", "%d", "%d", "%d", "%d", "%s", "%s", "%d", "%s", "%s", "%s"];
        $this->wpdb->insert($this->ticket_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function ticket_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $parent_id = isset($inputData["parent_id"]) ? absint($inputData["parent_id"]) : "";
        $post_id = isset($inputData["post_id"]) ? absint($inputData["post_id"]) : "";
        $sender_id = isset($inputData["sender_id"]) ? absint($inputData["sender_id"]) : "";
        $reciver_id = isset($inputData["reciver_id"]) ? absint($inputData["reciver_id"]) : "";
        $title = isset($inputData["title"]) ? esc_attr($inputData["title"]) : "";
        $content = isset($inputData["content"]) ? wp_filter_post_kses($inputData["content"]) : "";
        $status = isset($inputData["status"]) ? absint($inputData["status"]) : "";
        $date = isset($inputData["date"]) ? esc_attr($inputData["date"]) : "";
        $user_data = isset($inputData["user_data"]) ? mlm_sanitize_array($inputData["user_data"]) : "";
        $attaches = isset($inputData["attaches"]) ? mlm_sanitize_array($inputData["attaches"]) : "";
        if (!empty($parent_id)) {
            $table_data["parent_id"] = $parent_id;
            array_push($table_format, "%d");
        }
        if (!empty($post_id) && mlm_post_exists($post_id)) {
            $table_data["post_id"] = $post_id;
            array_push($table_format, "%d");
        }
        if (!empty($sender_id)) {
            $table_data["sender_id"] = $sender_id;
            array_push($table_format, "%d");
        }
        if (!empty($reciver_id)) {
            $table_data["reciver_id"] = $reciver_id;
            array_push($table_format, "%d");
        }
        if (!empty($title)) {
            $table_data["title"] = $title;
            array_push($table_format, "%s");
        }
        if (!empty($content)) {
            $table_data["content"] = $content;
            array_push($table_format, "%s");
        }
        if (is_numeric($status)) {
            $table_data["status"] = $status;
            array_push($table_format, "%d");
        }
        if (!empty($date)) {
            $table_data["date"] = $date;
            array_push($table_format, "%s");
        }
        if (!empty($user_data)) {
            $table_data["user_data"] = maybe_serialize($user_data);
            array_push($table_format, "%s");
        }
        if (!empty($attaches)) {
            $table_data["attaches"] = maybe_serialize($attaches);
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->ticket_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function ticket_delete($ticket_id)
    {
        if (!current_user_can("manage_options") || empty($ticket_id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->ticket_table . " WHERE id = %d", $ticket_id));
        return true;
    }
    public function refer_record($ref_user_id, $user_id, $user_ip, $user_url, $user_host, $invalid = 0, $purchase = 0)
    {
        if (!mlm_user_exists($ref_user_id) || empty($user_ip)) {
            return false;
        }
        if ($user_id == $ref_user_id) {
            $invalid = 1;
        }
        $data = ["id" => NULL, "ref_user_id" => absint($ref_user_id), "user_id" => absint($user_id), "user_ip" => esc_attr($user_ip), "user_url" => esc_attr($user_url), "user_host" => esc_attr($user_host), "invalid" => absint($invalid), "purchase" => absint($purchase), "date" => current_time("mysql")];
        $format = ["%d", "%d", "%d", "%s", "%s", "%s", "%d", "%d", "%s"];
        $this->wpdb->insert($this->referral_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function refer_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $ref_user_id = isset($inputData["ref_user_id"]) ? absint($inputData["ref_user_id"]) : "";
        $user_id = isset($inputData["user_id"]) ? absint($inputData["user_id"]) : "";
        $user_ip = isset($inputData["user_ip"]) ? esc_attr($inputData["user_ip"]) : "";
        $user_url = isset($inputData["user_url"]) ? esc_attr($inputData["user_url"]) : "";
        $user_host = isset($inputData["user_host"]) ? esc_attr($inputData["user_host"]) : "";
        $invalid = isset($inputData["invalid"]) ? absint($inputData["invalid"]) : "";
        $purchase = isset($inputData["purchase"]) ? absint($inputData["purchase"]) : "";
        $date = isset($inputData["date"]) ? esc_attr($inputData["date"]) : "";
        if (!empty($ref_user_id) && mlm_user_exists($ref_user_id)) {
            $table_data["ref_user_id"] = $ref_user_id;
            array_push($table_format, "%d");
        }
        if (!empty($user_id)) {
            $table_data["user_id"] = $user_id;
            array_push($table_format, "%d");
        }
        if (!empty($user_ip)) {
            $table_data["user_ip"] = $user_ip;
            array_push($table_format, "%s");
        }
        if (!empty($user_url)) {
            $table_data["user_url"] = $user_url;
            array_push($table_format, "%s");
        }
        if (!empty($user_host)) {
            $table_data["user_host"] = $user_host;
            array_push($table_format, "%s");
        }
        if (is_numeric($invalid)) {
            $table_data["invalid"] = $invalid;
            array_push($table_format, "%d");
        }
        if (is_numeric($purchase)) {
            $table_data["purchase"] = $purchase;
            array_push($table_format, "%d");
        }
        if (!empty($date)) {
            $table_data["date"] = $date;
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->referral_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function refer_delete($id)
    {
        if (empty($id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->referral_table . " WHERE id = %d", $id));
        return true;
    }
    public function network_record($user_id, $parent_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($parent_id) || $user_id == $parent_id) {
            return false;
        }
        $data = ["id" => NULL, "user_id" => absint($user_id), "parent_id" => absint($parent_id), "date" => current_time("mysql")];
        $format = ["%d", "%d", "%d", "%s"];
        $this->wpdb->insert($this->network_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function network_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $user_id = isset($inputData["user_id"]) ? absint($inputData["user_id"]) : "";
        $parent_id = isset($inputData["parent_id"]) ? absint($inputData["parent_id"]) : "";
        $date = isset($inputData["date"]) ? esc_attr($inputData["date"]) : "";
        if (!empty($user_id) && mlm_user_exists($user_id)) {
            $table_data["user_id"] = $user_id;
            array_push($table_format, "%d");
        }
        if (!empty($parent_id) && mlm_user_exists($parent_id)) {
            $table_data["parent_id"] = $parent_id;
            array_push($table_format, "%d");
        }
        if (!empty($date)) {
            $table_data["date"] = $date;
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->network_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function network_delete($id)
    {
        if (empty($id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->network_table . " WHERE id = %d", $id));
        return true;
    }
    public function subscribe_record($user_id, $order_id, $plan_data = [], $type = 0)
    {
        if ($type !== 3 && empty($order_id) || !mlm_user_exists($user_id) || !is_array($plan_data) || !count($plan_data)) {
            return false;
        }
        $user_obj = get_userdata($user_id);
        $user_data = ["name" => $user_obj->display_name, "email" => $user_obj->user_email, "mobile" => get_user_meta($user_id, "mlm_mobile", true), "login" => $user_obj->user_login];
        $days = isset($plan_data["time"]) ? $plan_data["time"] : 30;
        $start = gmdate("Y-m-d H:i:s", time() + get_option("gmt_offset") * HOUR_IN_SECONDS);
        $expire = gmdate("Y-m-d H:i:s", strtotime("+" . $days . " day", time()) + get_option("gmt_offset") * HOUR_IN_SECONDS);
        $data = ["id" => NULL, "user_id" => absint($user_id), "order_id" => absint($order_id), "type" => absint($type), "status" => 0, "valid" => 0, "plan_data" => maybe_serialize($plan_data), "user_data" => maybe_serialize($user_data), "date" => $start, "expire" => $expire];
        $format = ["%d", "%d", "%d", "%d", "%d", "%d", "%s", "%s", "%s", "%s"];
        $this->wpdb->insert($this->subscribe_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function subscribe_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $user_id = isset($inputData["user_id"]) ? absint($inputData["user_id"]) : "";
        $order_id = isset($inputData["order_id"]) ? absint($inputData["order_id"]) : "";
        $type = isset($inputData["type"]) ? absint($inputData["type"]) : "";
        $status = isset($inputData["status"]) ? absint($inputData["status"]) : "";
        $valid = isset($inputData["valid"]) ? absint($inputData["valid"]) : "";
        $plan_data = isset($inputData["plan_data"]) ? mlm_sanitize_array($inputData["plan_data"]) : "";
        $user_data = isset($inputData["user_data"]) ? mlm_sanitize_array($inputData["user_data"]) : "";
        $date = isset($inputData["date"]) ? esc_attr($inputData["date"]) : "";
        $expire = isset($inputData["expire"]) ? esc_attr($inputData["expire"]) : "";
        if (!is_numeric($valid)) {
            $valid = $status == 1 ? 1 : 0;
        }
        if (!empty($user_id) && mlm_user_exists($user_id)) {
            $table_data["user_id"] = $user_id;
            array_push($table_format, "%d");
        }
        if (!empty($order_id)) {
            $table_data["order_id"] = $order_id;
            array_push($table_format, "%d");
        }
        if (is_numeric($type)) {
            $table_data["type"] = $type;
            array_push($table_format, "%d");
        }
        if (is_numeric($status)) {
            $table_data["status"] = $status;
            array_push($table_format, "%d");
        }
        if (is_numeric($valid)) {
            $table_data["valid"] = $valid;
            array_push($table_format, "%d");
        }
        if (!empty($plan_data)) {
            $table_data["plan_data"] = maybe_serialize($plan_data);
            array_push($table_format, "%s");
        }
        if (!empty($user_data)) {
            $table_data["user_data"] = maybe_serialize($user_data);
            array_push($table_format, "%s");
        }
        if (!empty($date)) {
            $table_data["date"] = $date;
            array_push($table_format, "%s");
        }
        if (!empty($expire)) {
            $table_data["expire"] = $expire;
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->subscribe_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function subscribe_delete($id)
    {
        if (empty($id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->subscribe_table . " WHERE id = %d", $id));
        return true;
    }
    public function course_record($post_id, $parent_id, $priority, $course_data = [])
    {
        if (!mlm_post_exists($post_id) || !is_array($course_data) || !count($course_data)) {
            return false;
        }
        $data = ["id" => NULL, "post_id" => absint($post_id), "parent_id" => absint($parent_id), "priority" => absint($priority), "course_data" => maybe_serialize($course_data)];
        $format = ["%d", "%d", "%d", "%d", "%s"];
        $this->wpdb->insert($this->course_table, $data, $format);
        return $this->wpdb->insert_id;
    }
    public function course_update($id, $inputData = [])
    {
        if (empty($id) || empty($inputData) || !is_array($inputData)) {
            return false;
        }
        $table_data = [];
        $table_format = [];
        $post_id = isset($inputData["post_id"]) ? absint($inputData["post_id"]) : "";
        $parent_id = isset($inputData["parent_id"]) ? absint($inputData["parent_id"]) : "";
        $priority = isset($inputData["priority"]) ? absint($inputData["priority"]) : "";
        $course_data = isset($inputData["course_data"]) ? mlm_sanitize_array($inputData["course_data"]) : "";
        if (!empty($post_id) && mlm_post_exists($post_id)) {
            $table_data["post_id"] = $post_id;
            array_push($table_format, "%d");
        }
        if (is_numeric($parent_id)) {
            $table_data["parent_id"] = $parent_id;
            array_push($table_format, "%d");
        }
        if (is_numeric($priority)) {
            $table_data["priority"] = $priority;
            array_push($table_format, "%d");
        }
        if (!empty($course_data)) {
            $table_data["course_data"] = maybe_serialize($course_data);
            array_push($table_format, "%s");
        }
        if (empty($table_data)) {
            return false;
        }
        $result = $this->wpdb->update($this->course_table, $table_data, ["id" => $id], $table_format, ["%d"]);
        if ($result) {
            return true;
        }
        return false;
    }
    public function course_delete($id)
    {
        if (empty($id)) {
            return false;
        }
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->course_table . " WHERE id = %d", $id));
        return true;
    }
    public function query_rows($string, $data, $table = "wallet", $single = false)
    {
        if (empty($string)) {
            return false;
        }
        switch ($table) {
            case "ticket":
                $table = $this->ticket_table;
                break;
            case "network":
                $table = $this->network_table;
                break;
            case "referral":
                $table = $this->referral_table;
                break;
            case "subscribe":
                $table = $this->subscribe_table;
                break;
            case "course":
                $table = $this->course_table;
                break;
            default:
                $table = $this->wallet_table;
                $string = str_replace("{TABLE}", $table, $string);
                if ($single) {
                    if (is_array($data) && 0 < count($data)) {
                        $query_obj = $this->wpdb->get_row($this->wpdb->prepare($string, $data));
                    } else {
                        $query_obj = $this->wpdb->get_row($string);
                    }
                } else {
                    if (is_array($data) && 0 < count($data)) {
                        $query_obj = $this->wpdb->get_results($this->wpdb->prepare($string, $data));
                    } else {
                        $query_obj = $this->wpdb->get_results($string);
                    }
                }
                if (empty($query_obj)) {
                    return false;
                }
                return $query_obj;
        }
    }
    public function count_query_rows($string, $data, $table = "wallet")
    {
        if (empty($string)) {
            return false;
        }
        switch ($table) {
            case "ticket":
                $table = $this->ticket_table;
                break;
            case "network":
                $table = $this->network_table;
                break;
            case "referral":
                $table = $this->referral_table;
                break;
            case "subscribe":
                $table = $this->subscribe_table;
                break;
            case "course":
                $table = $this->course_table;
                break;
            default:
                $table = $this->wallet_table;
                $string = str_replace("{TABLE}", $table, $string);
                if (is_array($data) && 0 < count($data)) {
                    return $this->wpdb->get_var($this->wpdb->prepare($string, $data));
                }
                return $this->wpdb->get_var($string);
        }
    }
}

?>