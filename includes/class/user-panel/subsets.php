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
$parent_id	= mlmFire()->network->get_user_parent( $user_id );
$subset_url	= trailingslashit( mlm_page_url('panel') ) . 'section/subsets/';
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$results	= mlmFire()->db->query_rows(
	"SELECT user_id, date FROM {TABLE} WHERE parent_id = %d ORDER BY id DESC LIMIT %d, %d",
	array( $user_id, $start, $per ),
	'network'
);

$count_rows	= mlmFire()->db->count_query_rows(
	"SELECT COUNT(id) FROM {TABLE} WHERE parent_id = %d",
	array( $user_id ),
	'network'
);
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Subsets', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_network"><?php _e( 'Get my link', 'mlm' ); ?></a>
	<a href="#" class="btn btn-success btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_parent"><?php _e( 'My reagent', 'mlm' ); ?></a>
</div>

<?php if( ! empty( $results ) ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-refer-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'Image', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'User', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Refer count', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Subsets', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Registered at', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $results as $user ): ?>
					<tr>
						<th scope="row">
							<?php echo get_avatar( $user->user_id, 48, NULL , $user->user_id, array( 'class' => 'd-block rounded' ) ); ?>
						</th>
						<td><?php echo mlm_get_user_name( $user->user_id ); ?></td>
						<td><?php echo mlmFire()->referral->get_refs_count( $user->user_id ); ?></td>
						<td><?php echo mlmFire()->network->get_subs_count( $user->user_id ); ?></td>
						<td><?php echo date_i18n( get_option( 'date_format' ), strtotime( $user->date ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_db_pagination( $count_rows, $subset_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'You have no subsets yet.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_network" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Get my link', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="alert alert-warning text-justify"><?php _e( 'You can start to get subsets by using the code below.', 'mlm' ); ?></p>
				<div class="form-group">
					<label for="mlm_card"><?php _e( 'Your code', 'mlm' ); ?></label>
					<textarea contenteditable="false" onmouseup="this.select()" class="form-control ltr"><?php echo mlmFire()->referral->generate_ref_code( $user_id ); ?></textarea>
				</div>
				<div class="form-group m-0">
					<label for="mlm_card"><?php _e( 'Your link', 'mlm' ); ?></label>
					<textarea contenteditable="false" onmouseup="this.select()" class="form-control ltr"><?php echo mlmFire()->referral->add_ref_to_url(); ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="mlm_parent" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'My reagent', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body mlm-user-panel-widget p-0">
				<?php if( mlm_user_exists( $parent_id ) ): ?>
					
					<?php
					$parent_name	= mlm_get_user_name( $parent_id );
					$verified		= mlmFire()->dashboard->get_account_status( $parent_id );
					?>
					<div class="panel-top mlm-product-vendor-widget p-3 m-0 clearfix">
						<div class="vendor-image mb-2 clearfix">
							<?php echo get_avatar( $parent_id, 128, NULL , $parent_name, array( 'class' => 'rounded-circle d-block bg-white mx-auto' ) ); ?>
						</div>
						<div class="vendor-name text-center mb-3 clearfix">
							<a href="<?php echo esc_url( get_author_posts_url( $parent_id ) ); ?>" target="_blank" class="d-inline-block text-dark bold-300 <?php if( $verified ) echo 'verified'; ?>"><?php echo $parent_name; ?></a>
						</div>
					</div>

				<?php else: ?>
					
					<form id="mlm_submit_parent_form" action="<?php echo $subset_url; ?>" method="post" class="p-3">
						<div class="form-group">
							<label for="mlm_parent"><?php _e( 'Enter your reagent code for start.', 'mlm' ); ?></label>
							<input type="number" name="mlm_parent" id="mlm_parent" class="form-control ltr" step="1" min="1">
						</div>
						<div class="clearfix">
							<?php wp_nonce_field( 'mlm_takarino', 'mlm_security' ); ?>
							<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Save code', 'mlm' ); ?></button>
						</div>
					</form>
					
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>