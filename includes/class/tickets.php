<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Ticket
{
    protected $wpdb = NULL;
    protected $ticket_table = NULL;
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->ticket_table = $wpdb->prefix . "mlm_ticket";
        add_action("admin_menu", [$this, "add_menu"]);
        add_action("admin_menu", [$this, "menu_bubble"]);
    }
    public function add_menu()
    {
        add_menu_page(__("Tickets", "mlm"), __("Tickets", "mlm"), "manage_options", "mlm-tickets", [$this, "menu_callback"], "dashicons-megaphone", 27);
        add_submenu_page("mlm-tickets", __("New ticket", "mlm"), __("New ticket", "mlm"), "manage_options", "mlm-new-ticket", [$this, "new_callback"]);
    }
    public function menu_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        echo "<div class=\"wrap mlm-wrap mlm-tickets-wrap clearfix\">";
        if (isset($_GET["verify"]) && wp_verify_nonce($_GET["verify"], "mlm_aisvwweda")) {
            $atts = ["nonce" => wp_create_nonce("mlm_ticket_repqpa")];
            echo mlm_get_template("class/wp-admin/tickets-open", $atts);
        } else {
            $paged = isset($_GET["paged"]) ? absint($_GET["paged"]) : 1;
            $mlm_user = isset($_GET["mlm_user"]) ? absint($_GET["mlm_user"]) : "";
            $mlm_status = isset($_GET["mlm_status"]) ? absint($_GET["mlm_status"]) : "";
            $per = 20;
            $start = intval(($paged - 1) * $per);
            if (!empty($mlm_status) && mlm_user_exists($mlm_user)) {
                $result = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM " . $this->ticket_table . " WHERE parent_id = %d AND status = %d AND ( sender_id = %d OR reciver_id = %d ) ORDER BY id DESC LIMIT %d, %d", 0, $mlm_status, $mlm_user, $mlm_user, $start, $per));
                $count_rows = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND status = %d AND ( sender_id = %d OR reciver_id = %d )", 0, $mlm_status, $mlm_user, $mlm_user));
                $link = admin_url("admin.php?page=mlm-tickets&mlm_user=" . $mlm_user . "&mlm_status=" . $mlm_status);
            } else {
                if (mlm_user_exists($mlm_user)) {
                    $result = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM " . $this->ticket_table . " WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d ) ORDER BY id DESC LIMIT %d, %d", 0, $mlm_user, $mlm_user, $start, $per));
                    $count_rows = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND ( sender_id = %d OR reciver_id = %d )", 0, $mlm_user, $mlm_user));
                    $link = admin_url("admin.php?page=mlm-tickets&mlm_user=" . $mlm_user);
                } else {
                    if (!empty($mlm_status)) {
                        $result = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM " . $this->ticket_table . " WHERE parent_id = %d AND status = %d ORDER BY id DESC LIMIT %d, %d", 0, $mlm_status, $start, $per));
                        $count_rows = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND status = %d", 0, $mlm_status));
                        $link = admin_url("admin.php?page=mlm-tickets&mlm_status=" . $mlm_status);
                    } else {
                        $result = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM " . $this->ticket_table . " WHERE parent_id = %d ORDER BY id DESC LIMIT %d, %d", 0, $start, $per));
                        $count_rows = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d", 0));
                        $link = admin_url("admin.php?page=mlm-tickets");
                    }
                }
            }
            $args = ["show_option_all" => 0, "show_option_none" => __("All users", "mlm"), "hide_if_only_one_author" => 0, "selected" => $mlm_user, "include_selected" => 1, "class" => "regular-text", "name" => "mlm_user"];
            $atts = ["query" => $result, "status" => $mlm_status, "nonce" => wp_create_nonce("mlm_aisvwweda"), "args" => $args];
            echo mlm_get_template("class/wp-admin/tickets", $atts);
            mlm_wp_navigation($count_rows, $link, $per);
        }
        echo "</div>";
    }
    public function new_callback()
    {
        if (!current_user_can("manage_options")) {
            wp_die(__("You are not allowed here", "mlm"));
        }
        echo "<div class=\"wrap mlm-wrap mlm-tickets-wrap clearfix\">";
        echo mlm_get_template("class/wp-admin/tickets-new");
        echo "</div>";
    }
    public function menu_bubble()
    {
        global $menu;
        $user_id = get_current_user_id();
        $count = $this->count_open_tickets($user_id);
        if (!$count) {
            return NULL;
        }
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == "mlm-tickets") {
                $menu[$key][0] .= " <span class=\"update-plugins count-" . $count . "\"><span class=\"plugin-count\">" . $count . "</span></span>";
                return NULL;
            }
        }
    }
    public function ticket_status($code)
    {
        switch ($code) {
            case 2:
                $text = "<span class=\"badge badge-pill badge-warning\">" . __("Ongoing", "mlm") . "</span>";
                break;
            case 3:
                $text = "<span class=\"badge badge-pill badge-success\">" . __("Replied", "mlm") . "</span>";
                break;
            case 4:
                $text = "<span class=\"badge badge-pill badge-secondary\">" . __("Closed", "mlm") . "</span>";
                break;
            default:
                $text = "<span class=\"badge badge-pill badge-danger\">" . __("Open", "mlm") . "</span>";
                return $text;
        }
    }
    public function count_open_tickets($user_id = 0)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        if (is_admin() && user_can($user_id, "manage_options")) {
            $count = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND status = %d", 0, 1));
        } else {
            $count = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND ( reciver_id = %d || sender_id = %d ) AND status = %d", 0, $user_id, $user_id, 1));
        }
        return $count;
    }
    public function count_all_user_tickets($user_id = 0)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $count = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d AND ( reciver_id = %d OR sender_id = %d )", 0, $user_id, $user_id));
        return $count;
    }
    public function count_all_tickets()
    {
        $count = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->ticket_table . " WHERE parent_id = %d", 0));
        return $count;
    }
    public function get_last_change($id, $date = "")
    {
        if (empty($id)) {
            return $date;
        }
        $last_change = $this->wpdb->get_row($this->wpdb->prepare("SELECT date FROM " . $this->ticket_table . " WHERE parent_id = %d ORDER BY id DESC LIMIT %d", $id, 1));
        return isset($last_change->date) ? $last_change->date : $date;
    }
    public function get_ticket_data($id)
    {
        if (empty($id)) {
            return false;
        }
        $ticket = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->ticket_table . " WHERE id = %s AND parent_id = %d LIMIT %d", $id, 0, 1));
        return empty($ticket) ? false : $ticket;
    }
    public function submit_contact($user_id, $guest_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($guest_id)) {
            return false;
        }
        $user_contacts = get_user_meta($user_id, "mlm_user_contacts", true);
        $guest_contacts = get_user_meta($guest_id, "mlm_user_contacts", true);
        if (empty($user_contacts) || !is_array($user_contacts)) {
            $user_contacts = [];
        }
        if (empty($guest_contacts) || !is_array($guest_contacts)) {
            $guest_contacts = [];
        }
        if (is_array($user_contacts) && !in_array($guest_id, $user_contacts)) {
            array_push($user_contacts, $guest_id);
        }
        if (is_array($guest_contacts) && !in_array($user_id, $guest_contacts)) {
            array_push($guest_contacts, $user_id);
        }
        update_user_meta($user_id, "mlm_user_contacts", $user_contacts);
        update_user_meta($guest_id, "mlm_user_contacts", $guest_contacts);
    }
    public function delete_contact($user_id, $guest_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($guest_id)) {
            return false;
        }
        $user_contacts = get_user_meta($user_id, "mlm_user_contacts", true);
        $guest_contacts = get_user_meta($guest_id, "mlm_user_contacts", true);
        if (empty($user_contacts) || !is_array($user_contacts)) {
            $user_contacts = [];
        }
        if (empty($guest_contacts) || !is_array($guest_contacts)) {
            $guest_contacts = [];
        }
        if (is_array($user_contacts) && in_array($guest_id, $user_contacts)) {
            unset($user_contacts[$guest_id]);
        }
        if (is_array($guest_contacts) && in_array($user_id, $guest_contacts)) {
            unset($guest_contacts[$user_id]);
        }
        update_user_meta($user_id, "mlm_user_contacts", $user_contacts);
        update_user_meta($guest_id, "mlm_user_contacts", $guest_contacts);
    }
    public function select_recipient()
    {
        echo "<select name=\"mlm_user\" class=\"mlm-select regular-text form-control\"><option value=\"\">" . __("Send to ...", "mlm") . "</option>";
        if (current_user_can("manage_options")) {
            $all_users = get_users(["fields" => ["ID", "display_name"]]);
            if (!empty($all_users)) {
                foreach ($all_users as $usr) {
                    echo "<option value=\"" . $usr->ID . "\">" . $usr->display_name . "</option>";
                }
            }
        } else {
            $user_id = get_current_user_id();
            $contacts = [];
            $purchases = get_posts(["numberposts" => -1, "meta_key" => "_customer_user", "meta_value" => $user_id, "post_type" => wc_get_order_types(), "post_status" => ["completed", "wc-completed"]]);
            if ($purchases) {
                foreach ($purchases as $payment) {
                    $order = wc_get_order($payment->ID);
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_id = $item->get_product_id();
                        $vendor_id = get_post_field("post_author", $product_id);
                        if (!isset($contacts[$product_id]) && mlm_user_exists($vendor_id)) {
                            $contacts[$product_id] = ["vendor" => $vendor_id, "title" => get_the_title($product_id)];
                        }
                    }
                }
            }
            if (is_array($contacts) && 0 < count($contacts)) {
                foreach ($contacts as $key => $value) {
                    echo "<option value=\"" . $value["vendor"] . "\" data-post=\"" . $key . "\">" . $value["title"] . "</option>";
                }
            } else {
                echo "<option value=\"0\">" . __("Site support", "mlm") . "</option>";
            }
        }
        echo "</select>";
    }
}

?>