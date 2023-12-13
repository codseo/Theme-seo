<?php
global $product;
$post_id = get_the_ID();
$product = wc_get_product($post_id);
$user_id = get_current_user_id();
$user_email = '';
$access = false;
$purchased = false;

if (is_user_logged_in()) {
    $user_obj = get_userdata($user_id);
    $user_email = $user_obj->user_email;
    $access = mlmFire()->plan->check_user_access($post_id, $user_id);

    if (function_exists('wc_customer_bought_product') && wc_customer_bought_product($user_email, $user_id, $post_id)) {
        $purchased = true;
    }
}

if ($product->is_downloadable() && (mlm_is_product_free($post_id) || $access || $purchased)) : ?>
    <div class="mlm-show-history-download">
        <h3 class="mlm-box-title icon icon-presentation sm mb-3">دسترسی به فایل های خریداری شده</h3>
        <?php mlm_add_to_cart_btn($post_id, 'mlm-btn-dl', true, false, true); ?>
    </div>
<?php endif;
