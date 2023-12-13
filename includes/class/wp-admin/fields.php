<?php

if( ! defined( 'ABSPATH' ) )
{
	die( 'You are not allowed to call this page directly !' );
}

$i	= 0;
?>

<h1 class="wp-heading-inline"><?php _e( 'Custom fields', 'mlm' ); ?></h1>
<hr class="wp-header-end">

<div class="mlm_alert alert-danger">
	<?php _e( 'Add more custom fileds for your products.', 'mlm' ); ?>
</div>

<form name="mlm-custom-fields-form" action="<?php echo $attributes['url']; ?>" method="post">
	<div class="mlm-custom-fields clearfix">
		<div class="mlm-fields-wrapper clearfix">
			<?php foreach( (array) $attributes['fields'] as $item ): ?>
				<div class="mlm-fields-template clearfix">
					<div class="top">
						<span class="title"><?php echo $item['text']; ?></span>
						<a href="#mlm-delete-field" class="mlm-delete-btn btn">X</a>
						<a href="#mlm-toggle-field" class="mlm-toggle-btn btn"><?php _e( 'edit', 'mlm' ); ?></a>
					</div>
					<div class="bottom">
						<div class="mlm-form-group">
							<label><?php _e( 'Title', 'mlm' ); ?></label>
							<input type="text" name="mlm_field[i][<?php echo $i; ?>][text]" class="regular-text text" value="<?php echo $item['text']; ?>">
						</div>
						<div class="mlm-form-group">
							<label><?php _e( 'ID (only latin characters)', 'mlm' ); ?></label>
							<input type="text" name="mlm_field[i][<?php echo $i; ?>][id]" class="regular-text id" value="<?php echo $item['id']; ?>">
						</div>
						<div class="mlm-form-group">
							<label><?php _e( 'Help text', 'mlm' ); ?></label>
							<input type="text" name="mlm_field[i][<?php echo $i; ?>][place]" class="regular-text place" value="<?php echo $item['place']; ?>">
						</div>
						<div class="mlm-form-group">
							<label><?php _e( 'Required', 'mlm' ); ?></label>
							<select name="mlm_field[i][<?php echo $i; ?>][req]" class="regular-text req">
								<option value="yes" <?php selected( $item['req'], 'yes' ); ?>><?php _e( 'Yes', 'mlm' ); ?></option>
								<option value="no" <?php selected( $item['req'], 'no' ); ?>><?php _e( 'No', 'mlm' ); ?></option>
							</select>
							<input type="hidden" name="mlm_field[i][<?php echo $i; ?>][type]" value="text" />
						</div>
					</div>
				</div>
				<?php $i++; ?>
			<?php endforeach; ?>
		</div>
		<button type="submit" class="mlm-save-btn button button-primary"><?php _e( 'Save', 'mlm' ); ?></button>
		<button type="button" class="mlm-submit-btn button button-secondary"><?php _e( '+ add new field', 'mlm' ); ?></button>
		<?php wp_nonce_field( 'mlm_asyfkashc', 'mlm_security' ); ?>
	</div>
</form>