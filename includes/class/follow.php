<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Follow
{
    public function __construct()
    {
    }
    public function product_published($ID, $post)
    {
        if (wp_is_post_revision($ID)) {
            return NULL;
        }
        $this->notify_user_followers($post->post_author, $ID);
    }
    public function follow_user($user_id, $vendor_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($vendor_id) || $user_id == $vendor_id) {
            return false;
        }
        $who_follows_me = get_user_meta($vendor_id, "mlm_who_follows_me", true);
        $who_i_follow = get_user_meta($user_id, "mlm_who_i_follow", true);
        if (!is_array($who_follows_me)) {
            $who_follows_me = [];
        }
        if (!is_array($who_i_follow)) {
            $who_i_follow = [];
        }
        if (!isset($who_follows_me[$user_id])) {
            $who_follows_me[$user_id] = 1;
            update_user_meta($vendor_id, "mlm_who_follows_me", $who_follows_me);
        }
        if (!isset($who_i_follow[$vendor_id])) {
            $who_i_follow[$vendor_id] = 1;
            update_user_meta($user_id, "mlm_who_i_follow", $who_i_follow);
        }
        return true;
    }
    public function unfollow_user($user_id, $vendor_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($vendor_id) || $user_id == $vendor_id) {
            return false;
        }
        $who_follows_me = get_user_meta($vendor_id, "mlm_who_follows_me", true);
        $who_i_follow = get_user_meta($user_id, "mlm_who_i_follow", true);
        if (is_array($who_follows_me) && isset($who_follows_me[$user_id])) {
            unset($who_follows_me[$user_id]);
            update_user_meta($vendor_id, "mlm_who_follows_me", $who_follows_me);
        }
        if (is_array($who_i_follow) && isset($who_i_follow[$vendor_id])) {
            unset($who_i_follow[$vendor_id]);
            update_user_meta($user_id, "mlm_who_i_follow", $who_i_follow);
        }
        return true;
    }
    public function get_user_list($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $who_i_follow = get_user_meta($user_id, "mlm_who_i_follow", true);
        if (!is_array($who_i_follow)) {
            return false;
        }
        return $who_i_follow;
    }
    public function get_user_followers($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        $mlm_who_follows_me = get_user_meta($user_id, "mlm_who_follows_me", true);
        if (!is_array($mlm_who_follows_me)) {
            return false;
        }
        return $mlm_who_follows_me;
    }
    public function notify_user_followers($user_id, $post_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_post_exists($post_id)) {
            return false;
        }
        $flag = (int) get_post_meta($post_id, "mlm_followers_know", true);
        $followers = $this->get_user_followers($user_id);
        if (!$followers) {
            return false;
        }
        $to = [];
        foreach ($followers as $k => $v) {
            $follower = get_userdata($k);
            if (isset($follower->user_email) && is_email($follower->user_email)) {
                $to[] = $follower->user_email;
            }
        }
        if (!count($to)) {
            return false;
        }
        $to = array_values($to);
        update_post_meta($post_id, "mlm_followers_know", time());
        return mlmFire()->notif->send_user_mail($user_id, "follower_new_product", ["post_id" => $post_id, "email" => $to]);
    }
    public function do_user_follows($user_id, $vendor_id)
    {
        if (!mlm_user_exists($user_id) || !mlm_user_exists($vendor_id)) {
            return false;
        }
        $who_follows_me = $this->get_user_followers($vendor_id);
        if (!is_array($who_follows_me) || !isset($who_follows_me[$user_id])) {
            return false;
        }
        return true;
    }
    public function count_followers($user_id)
    {
        $followers = $this->get_user_followers($user_id);
        if (!$followers) {
            return 0;
        }
        return count($followers);
    }
    public function print_follow_button($user_id, $class = "")
    {
        $nonce = wp_create_nonce("mlm_okanodada");
        $followed = $this->do_user_follows(get_current_user_id(), $user_id);
        if ($followed) {
            $text = __("Unfollow", "mlm");
            $class .= " bg-light border text-dark";
        } else {
            $text = __("Follow", "mlm");
            $class .= " btn-primary";
        }
        echo "\t\t\n\t\t<a href=\"#mlm-follow-btn\" class=\"mlm-follow-btn btn py-0 font-12 line-20 bold-300 ";
        echo $class;
        echo "\" \n\t\t\tdata-vendor=\"";
        echo $user_id;
        echo "\" \n\t\t\tdata-verify=\"";
        echo $nonce;
        echo "\"\n\t\t\t>\n\t\t\t";
        echo $text;
        echo "\t\t</a>\n\t\t\n\t\t";
    }
}

?>