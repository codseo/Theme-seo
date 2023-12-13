<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_Rating
{
    public function __construct()
    {
        add_filter("comment_text", [$this, "comments_interactions"], 99, 2);
    }
    public function add_rate($post_id, $point = 0)
    {
        if (!mlm_post_exists($post_id) || !is_user_logged_in() || $point < 0 || 5 < $point) {
            return false;
        }
        $user_id = get_current_user_id();
        $db_ratings = get_post_meta($post_id, "mlm_ratings", true);
        if (!is_array($db_ratings)) {
            $db_ratings = [];
        }
        if (isset($db_ratings[$user_id])) {
            unset($db_ratings[$user_id]);
        }
        $db_ratings[$user_id] = absint($point);
        update_post_meta($post_id, "mlm_ratings", $db_ratings);
        return true;
    }
    public function remove_rate($post_id)
    {
        if (!mlm_post_exists($post_id) || !is_user_logged_in()) {
            return false;
        }
        $user_id = get_current_user_id();
        $db_ratings = get_post_meta($post_id, "mlm_ratings", true);
        if (!is_array($db_ratings) || !isset($db_ratings[$user_id])) {
            return false;
        }
        unset($db_ratings[$user_id]);
        update_post_meta($post_id, "mlm_ratings", $db_ratings);
        return true;
    }
    public function get_average($post_id)
    {
        if (!mlm_post_exists($post_id)) {
            return 1;
        }
        $total = 0;
        $db_ratings = get_post_meta($post_id, "mlm_ratings", true);
        if (!is_array($db_ratings)) {
            return 1;
        }
        foreach ($db_ratings as $rate) {
            $total = $total + $rate;
        }
        $average = number_format($total / count($db_ratings), 1);
        if ($average < 1) {
            return 1;
        }
        return $average;
    }
    public function total_count($post_id)
    {
        if (!mlm_post_exists($post_id)) {
            return 1;
        }
        $db_ratings = get_post_meta($post_id, "mlm_ratings", true);
        if (!is_array($db_ratings)) {
            return 1;
        }
        return count($db_ratings);
    }
    public function get_user_rating($post_id)
    {
        if (!mlm_post_exists($post_id) || !is_user_logged_in()) {
            return 0;
        }
        $user_id = get_current_user_id();
        $db_ratings = get_post_meta($post_id, "mlm_ratings", true);
        if (!is_array($db_ratings) || !isset($db_ratings[$user_id])) {
            return 0;
        }
        return $db_ratings[$user_id];
    }
    public function get_bookmarks($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return false;
        }
        return get_user_meta($user_id, "mlm_bookmarks", true);
    }
    public function count_bookmark($user_id)
    {
        if (!mlm_user_exists($user_id)) {
            return 0;
        }
        $bookmarks = get_user_meta($user_id, "mlm_bookmarks", true);
        return empty($bookmarks) ? 0 : count($bookmarks);
    }
    public function bookmark_post($user_id, $post_id)
    {
        if (!mlm_post_exists($post_id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $bookmarks = get_user_meta($user_id, "mlm_bookmarks", true);
        if (empty($bookmarks)) {
            $bookmarks = [];
        }
        if (isset($bookmarks[$post_id])) {
            return false;
        }
        $bookmarks[$post_id] = get_post_type($post_id);
        update_user_meta($user_id, "mlm_bookmarks", $bookmarks);
        return true;
    }
    public function remove_post_bookmark($user_id, $post_id)
    {
        if (!mlm_post_exists($post_id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $bookmarks = get_user_meta($user_id, "mlm_bookmarks", true);
        if (!is_array($bookmarks) || !isset($bookmarks[$post_id])) {
            return false;
        }
        unset($bookmarks[$post_id]);
        update_user_meta($user_id, "mlm_bookmarks", $bookmarks);
        return true;
    }
    public function check_post_bookmark($post_id, $user_id = false)
    {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!mlm_post_exists($post_id) || !mlm_user_exists($user_id)) {
            return false;
        }
        $bookmarks = get_user_meta($user_id, "mlm_bookmarks", true);
        if (!is_array($bookmarks) || !isset($bookmarks[$post_id])) {
            return false;
        }
        return true;
    }
    public function like_comment($comment_id, $rate = "like")
    {
        $likes_cnt = (int) get_comment_meta($comment_id, "mlm_likes_cnt", true);
        $dislikes_cnt = (int) get_comment_meta($comment_id, "mlm_dislikes_cnt", true);
        $user_id = get_current_user_id();
        $user_db = get_user_meta($user_id, "mlm_rated_comments", true);
        if (!is_array($user_db)) {
            $user_db = [];
        }
        if (isset($user_db[$comment_id])) {
            if ($user_db[$comment_id] == "like" && 0 < $likes_cnt) {
                $likes_cnt--;
            } else {
                if ($user_db[$comment_id] == "dislike" && 0 < $dislikes_cnt) {
                    $dislikes_cnt--;
                }
            }
        }
        $user_db[$comment_id] = $rate;
        if ($rate == "dislike") {
            $dislikes_cnt++;
        } else {
            $likes_cnt++;
        }
        update_comment_meta($comment_id, "mlm_likes_cnt", $likes_cnt);
        update_comment_meta($comment_id, "mlm_dislikes_cnt", $dislikes_cnt);
        update_user_meta($user_id, "mlm_rated_comments", $user_db);
        return true;
    }
    public function get_comment_likes($comment_id)
    {
        return ["like" => (int) get_comment_meta($comment_id, "mlm_likes_cnt", true), "dislike" => (int) get_comment_meta($comment_id, "mlm_dislikes_cnt", true)];
    }
    public function comments_interactions($content, $comment)
    {
        if (is_admin()) {
            return $content;
        }
        $counts = $this->get_comment_likes($comment->comment_ID);
        $nonce = wp_create_nonce("mlm_zoxpoastvr");
        $reaction = "<div class=\"mlm-interaction clearfix\">";
        $reaction .= "<div class=\"row justify-content-end no-gutters\">";
        $reaction .= "<div class=\"col-auto px-1\">";
        $reaction .= "<button type=\"button\" class=\"mlm-like-comment btn btn-success btn-sm border-0 icon icon-plus\" data-type=\"like\" data-id=\"" . $comment->comment_ID . "\" data-verify=\"" . $nonce . "\">" . $counts["like"] . "</button>";
        $reaction .= "</div>";
        $reaction .= "<div class=\"col-auto px-1\">";
        $reaction .= "<button type=\"button\" class=\"mlm-like-comment btn btn-danger btn-sm border-0 icon icon-minus\" data-type=\"dislike\" data-id=\"" . $comment->comment_ID . "\" data-verify=\"" . $nonce . "\">" . $counts["dislike"] . "</button>";
        $reaction .= "</div>";
        $reaction .= "</div>";
        $reaction .= "</div>";
        return $content . $reaction;
    }
}

?>