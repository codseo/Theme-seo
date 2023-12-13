<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

if (!class_exists("MLM_Initialization")) {
    class MLM_Initialization
    {
        private static $instance = NULL;
        public static function instance()
        {
            if (!self::$instance && !self::$instance instanceof MLM_Initialization) {
                self::$instance = new MLM_Initialization();
                self::$instance->includes();
                self::$instance->db = new MLM_Database();
                self::$instance->wallet = new MLM_Wallet();
                self::$instance->referral = new MLM_Referral();
                self::$instance->network = new MLM_Network();
                self::$instance->ticket = new MLM_Ticket();
                self::$instance->wp_admin = new MLM_WP_Admin();
                self::$instance->announce = new MLM_Announce();
                self::$instance->dashboard = new MLM_Dashboard();
                self::$instance->construct = new MLM_Construct();
                self::$instance->plan = new MLM_Plans();
                self::$instance->rating = new MLM_Rating();
                self::$instance->sms = new MLM_SMS_Panels();
                self::$instance->notif = new MLM_Notification();
                self::$instance->medal = new MLM_Medals();
                self::$instance->follow = new MLM_Follow();
                self::$instance->ajax = new MLM_Ajax();
            }
            return self::$instance;
        }
        public function __construct()
        {
        }
        public function __clone()
        {
        }
        public function __wakeup()
        {
        }
        private function includes()
        {
            get_template_part("includes/class/jdf");
            get_template_part("includes/class/script");
            get_template_part("includes/class/database");
            get_template_part("includes/class/wallet");
            get_template_part("includes/class/referral");
            get_template_part("includes/class/subsets");
            get_template_part("includes/class/gateway");
            get_template_part("includes/class/tickets");
            get_template_part("includes/class/wp-admin");
            get_template_part("includes/class/announce");
            get_template_part("includes/class/dashboard");
            get_template_part("includes/class/construct");
            get_template_part("includes/class/membership");
            get_template_part("includes/class/rating");
            get_template_part("includes/class/sms-panels");
            get_template_part("includes/class/notification");
            get_template_part("includes/class/medals");
            get_template_part("includes/class/follow");
            get_template_part("includes/class/ajax");
        }
    }
}
mlmfire();
function mlmFire()
{
    return MLM_Initialization::instance();
}

?>