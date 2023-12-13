<?php

if( ! is_user_logged_in() )
{
    return;
}

$flag               = false;
$mobile_verify      = get_option( 'seo_verify_mobile' ); // Changed 'mlm_verify_mobile' to 'seo_verify_mobile'
$email_verify       = get_option( 'seo_verify_email' ); // Changed 'mlm_verify_email' to 'seo_verify_email'
$verify_type        = get_option( 'seo_verify_type' ); // Changed 'mlm_verify_type' to 'seo_verify_type'
$verify_link        = trailingslashit( get_option( 'seo_verify_link' ) ); // Changed 'mlm_verify_link' to 'seo_verify_link'
$permalink          = untrailingslashit( home_url() ) . $_SERVER['REQUEST_URI'];
$permalink          = trailingslashit( $permalink );
$nonce              = wp_create_nonce( 'seo_ujakopibar' ); // Changed 'mlm_ujakopibar' to 'seo_ujakopibar'

$user_id            = get_current_user_id();
$user_info          = get_userdata( $user_id );
$seo_email          = $user_info->user_email; // Changed variable name from $mlm_email
$seo_mobile         = get_user_meta( $user_id, 'seo_mobile', true ); // Changed 'mlm_mobile' to 'seo_mobile'
$mobile_verified    = get_user_meta( $user_id, 'seo_mobile_verified', true ); // Changed 'mlm_mobile_verified' to 'seo_mobile_verified'
$country_code       = get_user_meta( $user_id, 'country_code', true ) ? get_user_meta( $user_id, 'country_code', true ) : '+98';
$mobile_code        = get_user_meta( $user_id, 'seo_mobile_verify_code', true ); // Changed 'mlm_mobile_verify_code' to 'seo_mobile_verify_code'
$mobile_saved       = get_user_meta( $user_id, 'seo_mobile_verify_db', true ); // Changed 'mlm_mobile_verify_db' to 'seo_mobile_verify_db'
$email_verified     = get_user_meta( $user_id, 'seo_email_verified', true ); // Changed 'mlm_email_verified' to 'seo_email_verified'
$email_code         = get_user_meta( $user_id, 'seo_email_verify_code', true ); // Changed 'mlm_email_verify_code' to 'seo_email_verify_code'
$email_saved        = get_user_meta( $user_id, 'seo_email_verify_db', true ); // Changed 'mlm_email_verify_db' to 'seo_email_verify_db'

if( ( $mobile_verify == 'yes' && empty( $mobile_verified ) ) ( $email_verify == 'yes' && empty( $email_verified ) ) )
{
    if( $verify_type == 'all' ( $verify_type == 'custom' && $verify_link == $permalink ) )
    {
        $flag   = true;
    }
}

if( ! $flag )
{
    return;
}
?>

<!-- ...Rest of your HTML and PHP code... -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#seo_verify_modal').modal('show'); // Changed '#mlm_verify_modal' to '#seo_verify_modal'
    });
</script>