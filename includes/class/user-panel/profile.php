<?php

if( ! defined( 'ABSPATH' ) )
{
    die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
    wp_die('You are not allowed here !');
}

$profile_url	= trailingslashit( mlm_page_url('panel') ) . 'section/profile/';
$user_id		= get_current_user_id();
$user_info		= get_userdata( $user_id );
$mlm_login		= $user_info->user_login;
$mlm_fname		= $user_info->first_name;
$mlm_lname		= $user_info->last_name;
$mlm_email		= $user_info->user_email;
$mlm_bio		= $user_info->description;
$mlm_avatar		= get_user_meta( $user_id, 'mlm_avatar', true );
$mlm_cover		= get_user_meta( $user_id, 'mlm_cover', true );
$mlm_mobile		= get_user_meta( $user_id, 'mlm_mobile', true );
$country_code	= get_user_meta( $user_id, 'country_code', true );
$mlm_state		= get_user_meta( $user_id, 'mlm_state', true );
$mlm_twitter	= get_user_meta( $user_id, 'mlm_twitter', true );
$mlm_aparat		= get_user_meta( $user_id, 'mlm_aparat', true );
$mlm_telegram	= get_user_meta( $user_id, 'mlm_telegram', true );
$mlm_instagram	= get_user_meta( $user_id, 'mlm_instagram', true );
$mlm_youtube	= get_user_meta( $user_id, 'mlm_youtube', true );
$avatar			= ! empty( $mlm_avatar ) ? esc_url( $mlm_avatar ) : esc_url( IMAGES .'/avatar.svg' );
$cover			= ! empty( $mlm_cover ) ? esc_url( $mlm_cover ) : esc_url( IMAGES .'/cover.png' );
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Profile', 'mlm' ); ?></h3>

<form id="mlm_profile_form" action="<?php echo $profile_url; ?>" method="post">
    <div class="row flex-row-reverse">
        <div class="col-12 col-md-6">
            <?php if( current_user_can('upload_files') ): ?>
                <div class="form-group">
                    <div class="mlm-image-preview mb-2 text-center">
                        <img src="<?php echo $avatar; ?>" class="avatar rounded-circle" alt="<?php echo $mlm_login; ?>">
                    </div>
                    <input type="hidden" name="mlm_avatar" class="image" id="mlm_avatar" value="<?php echo $mlm_avatar; ?>">
                    <button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select profile image', 'mlm' ); ?></button>
                </div>
                <div class="form-group">
                    <div class="mlm-image-preview mb-2 text-center">
                        <img src="<?php echo $cover; ?>" class="cover rounded" alt="<?php echo $mlm_login; ?>">
                    </div>
                    <input type="hidden" name="mlm_cover" class="image" id="mlm_cover" value="<?php echo $mlm_cover; ?>">
                    <button type="button" class="mlm-upload-image-btn btn btn-secondary btn-block"><?php _e( 'Upload or select cover image', 'mlm' ); ?></button>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="mlm_login"><?php _e( 'Username', 'mlm' ); ?></label>
                <input type="text" name="mlm_login" id="mlm_login" class="form-control ltr" value="<?php echo $mlm_login; ?>" disabled="disabled">
                <small class="form-text text-muted"><?php _e( "you can't edit user login once you registered", 'mlm' ); ?></small>
            </div>
            <div class="form-group">
                <label for="mlm_fname"><?php _e( 'First name', 'mlm' ); ?> <i class="text-danger">*</i></label>
                <input type="text" name="mlm_fname" id="mlm_fname" class="form-control" value="<?php echo $mlm_fname; ?>" placeholder="<?php _e( 'Your first name', 'mlm' ); ?>">
            </div>
            <div class="form-group">
                <label for="mlm_lname"><?php _e( 'Last name', 'mlm' ); ?> <i class="text-danger">*</i></label>
                <input type="text" name="mlm_lname" id="mlm_lname" class="form-control" value="<?php echo $mlm_lname; ?>" placeholder="<?php _e( 'Your last name', 'mlm' ); ?>">
            </div>
            <div class="form-group">
                <label for="mlm_email"><?php _e( 'Email', 'mlm' ); ?> <i class="text-danger">*</i></label>
                <input type="text" name="mlm_email" id="mlm_email" class="form-control ltr" value="<?php echo $mlm_email; ?>" placeholder="example@domain.com">
            </div>
            <div class="form-group">
                <label for="mlm_mobile"><?php _e( 'Mobile', 'mlm' ); ?> <i class="text-danger">*</i></label>
                <div class="row">
                    <div class="col-sm-9" style="padding-left: 5px;     flex: 0 0 75%;
    max-width: 75%;">
                        <input type="text" name="mlm_mobile" id="mlm_mobile" class="form-control ltr" value="<?php echo $mlm_mobile; ?>" placeholder="<?php _e( '09', 'mlm' ); ?>">
                    </div>
                    <div class="col-sm-3" style="padding-right: 5px; flex: 0 0 25%;
    max-width: 25%;">
                        <input type="text" name="mlm_country_code" class="form-control m-0" dir="ltr" style="text-align:center;" value="<?php echo $country_code; ?>" placeholder="<?php _e( '+98', 'mlm' ); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="mlm_state"><?php _e( 'State', 'mlm' ); ?> <i class="text-danger">*</i></label>
                <select name="mlm_state" id="mlm_state" class="form-control">
                    <option <?php selected( $mlm_state, __( 'East Azerbaijan', 'mlm' ) ); ?>><?php _e( 'East Azerbaijan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'West Azerbaijan', 'mlm' ) ); ?>><?php _e( 'West Azerbaijan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Ardabil', 'mlm' ) ); ?>><?php _e( 'Ardabil', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Isfahan', 'mlm' ) ); ?>><?php _e( 'Isfahan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Ilam', 'mlm' ) ); ?>><?php _e( 'Alborz', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Ilam', 'mlm' ) ); ?>><?php _e( 'Ilam', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Bushehr', 'mlm' ) ); ?>><?php _e( 'Bushehr', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Tehran', 'mlm' ) ); ?>><?php _e( 'Tehran', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Chaharmahal-o bakhtiari', 'mlm' ) ); ?>><?php _e( 'Chaharmahal-o bakhtiari', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'South Khorasan', 'mlm' ) ); ?>><?php _e( 'South Khorasan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Razavi Khorasan', 'mlm' ) ); ?>><?php _e( 'Razavi Khorasan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'North Khorasan', 'mlm' ) ); ?>><?php _e( 'North Khorasan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Khuzestan', 'mlm' ) ); ?>><?php _e( 'Khuzestan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Zanjan', 'mlm' ) ); ?>><?php _e( 'Zanjan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Semnan', 'mlm'  ) ); ?>><?php _e( 'Semnan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Sistan-o Baluchestan', 'mlm' ) ); ?>><?php _e( 'Sistan-o Baluchestan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Fars', 'mlm' ) ); ?>><?php _e( 'Fars', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Qazvin', 'mlm' ) ); ?>><?php _e( 'Qazvin', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Qom', 'mlm' ) ); ?>><?php _e( 'Qom', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Kordestan', 'mlm' ) ); ?>><?php _e( 'Kordestan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Kerman', 'mlm' ) ); ?>><?php _e( 'Kerman', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Kermanshah', 'mlm' ) ); ?>><?php _e( 'Kermanshah', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Kohgiluye Buyer Ahmad', 'mlm' ) ); ?>><?php _e( 'Kohgiluye Buyer Ahmad', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Golestan', 'mlm' ) ); ?>><?php _e( 'Golestan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Guilan', 'mlm' ) ); ?>><?php _e( 'Guilan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Lorestan', 'mlm' ) ); ?>><?php _e( 'Lorestan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Mazandaran', 'mlm' ) ); ?>><?php _e( 'Mazandaran', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Markazi', 'mlm' ) ); ?>><?php _e( 'Markazi', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Hormozgan', 'mlm' ) ); ?>><?php _e( 'Hormozgan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Hamedan', 'mlm' ) ); ?>><?php _e( 'Hamedan', 'mlm' ); ?></option>
                    <option <?php selected( $mlm_state, __( 'Yazd', 'mlm' ) ); ?>><?php _e( 'Yazd', 'mlm' ); ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="mlm_bio"><?php _e( 'Bio', 'mlm' ); ?></label>
        <textarea name="mlm_bio" id="mlm_bio" class="form-control" cols="10" rows="5"><?php echo $mlm_bio; ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <label for="mlm_twitter"><?php _e( 'Twitter', 'mlm' ); ?></label>
            <input type="text" name="mlm_twitter" id="mlm_twitter" class="form-control ltr" value="<?php echo $mlm_twitter; ?>" placeholder="https://twitter.com/">
        </div>
        <div class="form-group col-6">
            <label for="mlm_aparat"><?php _e( 'Aparat', 'mlm' ); ?></label>
            <input type="text" name="mlm_aparat" id="mlm_aparat" class="form-control ltr" value="<?php echo $mlm_aparat; ?>" placeholder="https://www.aparat.com/">
        </div>
        <div class="form-group col-6">
            <label for="mlm_instagram"><?php _e( 'Instagram', 'mlm' ); ?></label>
            <input type="text" name="mlm_instagram" id="mlm_instagram" class="form-control ltr" value="<?php echo $mlm_instagram; ?>" placeholder="https://instagram.com/">
        </div>
        <div class="form-group col-6">
            <label for="mlm_telegram"><?php _e( 'Telegram', 'mlm' ); ?></label>
            <input type="text" name="mlm_telegram" id="mlm_telegram" class="form-control ltr" value="<?php echo $mlm_telegram; ?>" placeholder="https://telegram.me/">
        </div>
        <div class="form-group col-6">
            <label for="mlm_youtube"><?php _e( 'Youtube', 'mlm' ); ?></label>
            <input type="text" name="mlm_youtube" id="mlm_youtube" class="form-control ltr" value="<?php echo $mlm_youtube; ?>" placeholder="https://youtube.com/">
        </div>
    </div>
    <div class="form-group">
        <?php wp_nonce_field( 'mlm_vakasizuma', 'mlm_security' ); ?>
        <button type="submit" class="btn btn-primary btn-block"><?php _e( 'Update', 'mlm' ); ?></button>
    </div>
</form>