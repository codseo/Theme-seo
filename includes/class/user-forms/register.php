<div class="mlm-form-wrapper mlm-register-form-wrapper clearfix">

    <?php if( count( $attributes['errors'] ) > 0 ): ?>
        <div class="alert alert-danger">
            <?php foreach( $attributes['errors'] as $error ): ?>
                <?php echo $error; ?><br />
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php do_action( 'wordpress_social_login' ); ?>

    <form id="mlm_signup_form" action="<?php echo mlm_page_url( 'register' ); ?>" method="post">
        <div class="form-group">
            <label for=""><?php _e( 'User login', 'mlm' ); ?> <i class="text-danger">*</i></label>
            <input type="text" name="mlm_uname" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( "you can't edit user login after you registered", 'mlm' ); ?>">
        </div>
        <div class="form-group">
            <label for=""><?php _e( 'Mobile', 'mlm' ); ?> <i class="text-danger">*</i></label>
            <div class="row">
                <div class="col-sm-9" style="padding-left: 5px;     flex: 0 0 75%;
    max-width: 75%;">
                    <input type="text" name="mlm_mobile" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( '09', 'mlm' ); ?>">
                </div>
                <div class="col-sm-3" style="padding-right: 5px; flex: 0 0 25%;
    max-width: 25%;">
                    <input type="text" name="mlm_country_code" class="form-control m-0 rounded-pill" dir="ltr" style="text-align:center;" value="+98" placeholder="<?php _e( '+98', 'mlm' ); ?>">
                </div>
            </div>
        </div>
        <?php if( ! mlmFire()->dashboard->is_email_disabled() ): ?>
            <div class="form-group">
                <label for=""><?php _e( 'Email', 'mlm' ); ?><?php if( mlmFire()->dashboard->is_email_required() ): ?> <i class="text-danger">*</i><?php endif; ?></label>
                <input type="text" name="mlm_email" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( 'Enter your email address', 'mlm' ); ?>">
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for=""><?php _e( 'Password', 'mlm' ); ?> <i class="text-danger">*</i></label>
            <input type="password" name="mlm_pass" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( 'Password must be at least 7 characters', 'mlm' ); ?>">
        </div>
        <?php if( mlmFire()->dashboard->is_code_enabled() ): ?>
            <?php $cookie = isset($_COOKIE['mlm_bazaryab_cookie']) ? absint($_COOKIE['mlm_bazaryab_cookie']) : ''; ?>
            <div class="form-group">
                <label for=""><?php _e( 'Reagent code', 'mlm' ); ?><?php if( mlmFire()->dashboard->is_code_required() ): ?> <i class="text-danger">*</i><?php endif; ?></label>
                <input type="text" name="mlm_code" class="form-control m-0 rounded-pill" dir="ltr" placeholder="<?php _e( 'Enter your reagent code.', 'mlm' ); ?>" value="<?php echo $cookie; ?>">
            </div>
        <?php endif; ?>
        <div class="form-group">
            <?php if( mlmFire()->dashboard->is_code_required() ): ?>
                <?php $cookie = isset($_COOKIE['mlm_bazaryab_cookie']) ? absint($_COOKIE['mlm_bazaryab_cookie']) : ''; ?>
                <input type="hidden" name="mlm_code" value="<?php echo $cookie; ?>">
            <?php endif; ?>
            <input type="hidden" name="mlm_recaptcha" data-reason="register" value="">
            <input type="hidden" name="mlm_return" value="<?php $attributes['_return']; ?>">
			<input type="hidden" name="mlm_must_return" value="<?php ( $attributes['_return'] ) ? 'yes' : 'no'; ?>">
            <button type="submit" class="btn btn-primary btn-block rounded-pill" data-verify="<?php echo wp_create_nonce('mlm_lavinap'); ?>"><?php _e( 'Register', 'mlm' ); ?></button>
        </div>
    </form>
    <?php $demo = mlm_selected_demo(); ?>
    <?php if( $demo == 'zhaket' ): ?>
        <nav class="auth-nav nav m-0 p-0 align-items-center justify-content-center">
            <a href="<?php echo mlm_page_url('login'); ?>" class="btn btn-light m-1"><?php _e( 'Login', 'mlm' ); ?></a>
            <a href="<?php echo mlm_page_url('lost'); ?>" class="btn btn-light m-1"><?php _e( 'Forgot password', 'mlm' ); ?></a>
        </nav>
    <?php endif; ?>
</div>