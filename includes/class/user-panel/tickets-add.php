<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$user_id	= get_current_user_id();
$submit_url	= trailingslashit( mlm_page_url('panel') ) . 'section/tickets-new/';
$qw_query	= new WP_Query( array(
	'post_type'				=> 'mlm-questions',
	'post_status'			=> 'publish',
	'posts_per_page'		=> -1
) );

$departments	= array(
	__( 'Technical department', 'mlm' ),
	__( 'Financial department', 'mlm' ),
	__( 'Complain a seller', 'mlm' ),
);

if( current_user_can('read_private_pages') )
{
	$departments[]	= __( 'Product moderation department', 'mlm' );
	$departments[]	= __( 'Post moderation department', 'mlm' );
	$departments[]	= __( 'Course moderation department', 'mlm' );
}
?>

<h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'New ticket', 'mlm' ); ?></h3>

<div class="alert alert-danger text-justify font-12">
	<p><?php _e( 'Please read the common questions before sending a new ticket.', 'mlm' ); ?></p>
	<p class="m-0"><?php _e( 'Please avoid to send multiple tickets for the same problem and wait for your answer after sending the ticket.', 'mlm' ); ?></p>
</div>

<div class="mlm-frequent-questions-wrapper clearfix">
	<div class="collapse show multi-collapse" id="mlm-frequent-questions-collapse">
		<?php if( $qw_query->have_posts() ): ?>
			<div class="mlm-questions accordion clearfix" id="mlm-frequent-questions-accordion">
				<?php while( $qw_query->have_posts() ): $qw_query->the_post(); ?>
					<?php $post_id = get_the_ID(); ?>
					<div class="card">
						<div class="card-header p-0" id="mlm-heading-<?php echo $post_id; ?>">
							<a href="#" class="d-block p-2 text-dark font-12" data-toggle="collapse" data-target="#mlm-collapse-<?php echo $post_id; ?>" aria-expanded="false" aria-controls="mlm-collapse-<?php echo $post_id; ?>">
								<span class="icon icon-bullhorn float-right ml-3"></span> <?php the_title(); ?>
							</a>
						</div>
						<div id="mlm-collapse-<?php echo $post_id; ?>" class="collapse" aria-labelledby="mlm-heading-<?php echo $post_id; ?>" data-parent="#mlm-frequent-questions-accordion">
							<div class="card-body font-11 text-justify"><?php the_content(); ?></div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="my-4 text-center clearfix">
	<button class="btn btn-primary px-4" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="mlm-frequent-questions-collapse mlm-new-ticket-collapse"><?php _e( "I can't find my answer", 'mlm' ); ?></button>
</div>
<div class="mlm-new-ticket-wrapper clearfix">
	<div class="collapse multi-collapse" id="mlm-new-ticket-collapse">
		<form id="mlm_new_ticket_form" action="<?php echo $submit_url; ?>" method="post">
			<div class="form-group mlm-select-subject-group state-0 state-1 state-2">
				<label for="mlm_subject"><?php _e( 'Please select the subject', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_subject" class="form-control" id="mlm_subject">
					<option value="0"><?php _e( 'Select', 'mlm' ); ?></option>
					<option value="1"><?php _e( 'Pronlem in purchased products', 'mlm' ); ?></option>
					<option value="2"><?php _e( 'Site support', 'mlm' ); ?></option>
				</select>
			</div>
			<div class="form-group mlm-select-unit-group state-2 gzl">
				<label for="mlm_unit"><?php _e( 'Please select a department', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<select name="mlm_unit" class="form-control" id="mlm_unit">
					<?php foreach( $departments as $department ): ?>
						<option><?php echo $department; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group mlm-select-user-group state-1 gzl">
				<label for="mlm_user"><?php _e( 'Please select the product', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<?php mlmFire()->ticket->select_recipient(); ?>
			</div>
			<div class="form-group state-1 state-2 gzl">
				<label for="mlm_title"><?php _e( 'Subject', 'mlm' ); ?> <i class="text-danger">*</i></label>
				<input type="text" name="mlm_title" id="mlm_title" class="form-control" placeholder="<?php _e( 'Ticket subject', 'mlm' ); ?>">
			</div>
			<div class="form-group state-1 state-2 gzl">
				<?php
				wp_editor( NULL, 'mlm_content', array(
					'textarea_name'	=> 'mlm_content',
					'media_buttons'	=> false,
					'editor_height'	=> 300,
					'teeny'			=> true,
					'quicktags'		=> false
				) );
				?>
			</div>
			<?php if( current_user_can( 'upload_files' ) ): ?>
				<div class="mlm-attach-field-wrap form-group state-1 state-2 gzl">
					<div class="ticket-attaches-placeholder clearfix">
						<span class="placeholder"><?php _e('Ticket attaches', 'mlm'); ?></span>
					</div>
					<div class="mlm-attach-upload-holder">
						<input type="file" class="upload-toggle" data-verify="<?php echo wp_create_nonce('mlm_asdkugfas'); ?>">
						<button class="btn btn-secondary btn-block" type="button"><?php _e( 'Attach file', 'mlm' ); ?></button>
					</div>
					<div class="mlm-attach-upload-progress">
						<div class="progress">
							<div class="progress-bar bg-success" aria-valuemin="0" aria-valuemax="100" width="0%"></div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group state-1 state-2 gzl">
				<?php wp_nonce_field( 'mlm_ticket_fsaz', 'mlm_security' ); ?>
				<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Send', 'mlm' ); ?></button>
			</div>
		</form>
	</div>
</div>