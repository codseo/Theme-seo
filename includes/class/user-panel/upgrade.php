<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id		= get_current_user_id();
$post_id		= mlmFire()->dashboard->get_request_post_id( $user_id );
$upgrade_url	= trailingslashit( mlm_page_url('panel') ) . 'section/upgrade/';
$mlm_status		= get_post_meta( $post_id, 'mlm_status', true );
$mlm_reject		= get_post_meta( $post_id, 'mlm_reject', true );

if( 
	! current_user_can('read_private_pages') && 
	$attributes['mid'] == 1 &&
	$mlm_status == 'ok'
)
{
	delete_post_meta( $post_id, 'mlm_status' );
	$mlm_status = '';
}
?>

<h3 class="mlm-box-title sm mb-0 py-2"><?php _e( 'Upgrade account', 'mlm' ); ?></h3>
<p class="text-justify font-12 mb-4 text-secondary">
	<?php _e( 'Please fill in the form below to upgrade your account.', 'mlm' ); ?>
</p>

<?php if( ! empty( $mlm_reject ) && $mlm_status == 'nok' ): ?>
	<div class="alert alert-danger text-justify"><?php echo $mlm_reject; ?></div>
<?php endif; ?>

<?php if( current_user_can('moderate_comments') ): ?>
	
	<div class="alert alert-danger text-justify">
		<?php _e( 'You have no need to upgrade. Your account already upgraded.', 'mlm' ); ?>
	</div>
	
<?php elseif( $mlm_status == 'ok' ): ?>
	
	<div class="alert alert-success text-justify">
		<?php _e( 'Your upgrade account request is verified already.', 'mlm' ); ?>
	</div>
	
<?php elseif( $mlm_status == 'wait' ): ?>
	
	<div class="alert alert-warning text-justify">
		<?php _e( 'Your upgrade request is waiting for moderation.', 'mlm' ); ?>
	</div>
	
<?php elseif( $attributes['page'] == 2 ): ?>
	
	<?php
	$mlm_melli_file		= get_post_meta( $post_id, 'mlm_melli_file', true );
	$mlm_shena_file		= get_post_meta( $post_id, 'mlm_shena_file', true );
	?>
	
	<form id="mlm_upload_account_form" action="<?php echo $upgrade_url; ?>" method="post">
		<div class="row flex-row-reverse">
			<div class="col-12 col-md-5">
				<div class="verify-identity-icon mb-4 p-2 clearfix">
					<img src="<?php echo IMAGES; ?>/identity.svg" class="d-block img-fluid" alt="">
				</div>
				<div class="verify-identity-help mb-4 clearfix">
					<a target="_blank" href="<?php echo IMAGES; ?>/valid-melli.jpg" class="btn btn-light rounded-pill py-1 px-4 font-12 mb-1">
						<span class="icon icon-profile text-primary float-right ml-2"></span> <?php _e( 'Identity card', 'mlm' ); ?>
					</a>
					<a target="_blank" href="<?php echo IMAGES; ?>/valid-shenas.jpg" class="btn btn-light rounded-pill py-1 px-4 font-12">
						<span class="icon icon-profile text-primary float-right ml-2"></span> <?php _e( 'Birth certificate', 'mlm' ); ?>
					</a>
				</div>
			</div>
			<div class="col-12 col-md-7">
				<h4 class="my-3 font-16"><?php _e( 'Please follow these steps:', 'mlm' ); ?></h4>
				<div class="verify-identity-text mb-4 font-12 text-justify clearfix">
					<p>
						<?php _e( '1- Scan your identity card or take a photo of it.', 'mlm' ); ?>
					</p>
					<p>
						<?php _e( '2- Scan or take a photo of your birth certificate.', 'mlm' ); ?>
					</p>
					<p>
						<?php _e( '3- Send the scanned files or images via the fields below.', 'mlm' ); ?>
					</p>
					<p>
						<?php _e( 'Tip: Check the sample images provided.', 'mlm' ); ?>
					</p>
				</div>
				<div class="form-group">
					<input type="hidden" name="mlm_melli_file" class="image" id="mlm_melli_file" value="<?php echo $mlm_melli_file; ?>">
					<button type="button" class="mlm-upload-image-btn dynamic-btn btn btn-light bg-white"><?php _e( 'Identity card image', 'mlm' ); ?></button>
				</div>
				<div class="form-group">
					<input type="hidden" name="mlm_shena_file" class="image" id="mlm_shena_file" value="<?php echo $mlm_shena_file; ?>">
					<button type="button" class="mlm-upload-image-btn dynamic-btn btn btn-light bg-white"><?php _e( 'Birth certificate image', 'mlm' ); ?></button>
				</div>
			</div>
		</div>
		<div class="form-group clearfix">
			<?php wp_nonce_field( 'mlm_gayapidis', 'mlm_security' ); ?>
			<button type="submit" class="btn btn-primary float-left"><?php _e( 'Send for verification', 'mlm' ); ?></button>
		</div>
	</form>
	
<?php else: ?>
	
	<?php
	$mlm_gender		= get_post_meta( $post_id, 'mlm_gender', true );
	$mlm_fname		= get_post_meta( $post_id, 'mlm_fname', true );
	$mlm_lname		= get_post_meta( $post_id, 'mlm_lname', true );
	$mlm_birth		= get_post_meta( $post_id, 'mlm_birth', true );
	$mlm_melli		= get_post_meta( $post_id, 'mlm_melli', true );
	$mlm_phone		= get_post_meta( $post_id, 'mlm_phone', true );
	$mlm_address	= get_post_meta( $post_id, 'mlm_address', true );
	$mlm_postal		= get_post_meta( $post_id, 'mlm_postal', true );
	
	if( ! $post_id )
	{
		$user_obj	= get_userdata( $user_id );
		$mlm_fname	= $user_obj->first_name;
		$mlm_lname	= $user_obj->last_name;
		$mlm_gender	= 'm';
	}
	?>
	
	<form id="mlm_upgrade_account_form" action="<?php echo $upgrade_url; ?>" method="post">
		<div class="alert alert-warning"><?php _e( 'Your profile data will not change and there will be no public access to your information.', 'mlm' ); ?></div>
		<div class="form-row">
			<div class="form-group col-12">
				<label class="ml-3"><?php _e( 'Gender', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="mlm_gender" id="mlm_gender_m" value="m" <?php checked( $mlm_gender, 'm' ); ?>>
					<label class="form-check-label" for="mlm_gender_m"><?php _e( 'Male', 'mlm' ); ?></label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="mlm_gender" id="mlm_gender_f" value="f" <?php checked( $mlm_gender, 'f' ); ?>>
					<label class="form-check-label" for="mlm_gender_f"><?php _e( 'Female', 'mlm' ); ?></label>
				</div>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_fname"><?php _e( 'First name', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_fname" class="form-control" id="mlm_fname" value="<?php echo $mlm_fname; ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_lname"><?php _e( 'Last name', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_lname" class="form-control" id="mlm_lname" value="<?php echo $mlm_lname; ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_birth"><?php _e( 'Birth', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_birth" class="form-control mlm-datepicker ltr" id="mlm_birth" value="<?php echo $mlm_birth; ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_melli"><?php _e( 'National code', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_melli" class="form-control ltr" id="mlm_melli" value="<?php echo $mlm_melli; ?>">
			</div>
			<div class="form-group col-12">
				<label for="mlm_address"><?php _e( 'Address', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<textarea name="mlm_address" class="form-control" id="mlm_address" rows="3" cols="10"><?php echo $mlm_address; ?></textarea>
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_phone"><?php _e( 'Phone', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_phone" class="form-control ltr" id="mlm_phone" value="<?php echo $mlm_phone; ?>">
			</div>
			<div class="form-group col-12 col-md-6">
				<label for="mlm_postal"><?php _e( 'Postal code', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_postal" class="form-control ltr" id="mlm_postal" value="<?php echo $mlm_postal; ?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<input type="hidden" name="mlm_role" value="<?php echo $attributes['mid']; ?>">
			<?php wp_nonce_field( 'mlm_gayapidis', 'mlm_security' ); ?>
			<button type="submit" class="btn btn-primary float-left"><?php _e( 'Next step', 'mlm' ); ?></button>
		</div>		
	</form>

<?php endif; ?>