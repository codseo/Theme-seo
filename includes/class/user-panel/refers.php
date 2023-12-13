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
$links_url	= trailingslashit( mlm_page_url('panel') ) . 'section/links/';
$refers_url	= trailingslashit( mlm_page_url('panel') ) . 'section/refers/';
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$results	= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE ref_user_id = %d ORDER BY id DESC LIMIT %d, %d",
	array( $user_id, $start, $per ),
	'referral'
);

$count_rows	= mlmFire()->db->count_query_rows(
	"SELECT COUNT(id) FROM {TABLE} WHERE ref_user_id = %d",
	array( $user_id ),
	'referral'
);

mlmFire()->referral->add_ref_to_url();
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Referrals', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="<?php echo $links_url; ?>" class="btn btn-danger btn-sm float-left mr-1 my-1"><?php _e( 'Product links', 'mlm' ); ?></a>
</div>

<?php if( ! empty( $results ) ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-refer-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'No.', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Source', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Landing page', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'IP', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Date', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $results as $ref ): ?>
					<?php
					$class = '';
					if( $ref->invalid )
					{
						$class = 'table-danger';
					}
					?>
					<tr class="<?php echo $class; ?>">
						<th scope="row">#<?php echo $ref->id; ?></th>
						<td><?php echo empty( $ref->user_host ) ? __( 'direct link', 'mlm' ) : $ref->user_host; ?></td>
						<td>
							<a target="_blank" href="<?php echo $ref->user_url; ?>">
								<?php echo urldecode( $ref->user_url ); ?>
							</a>
						</td>
						<td><?php echo $ref->user_ip; ?></td>
						<td>
							<?php echo date_i18n( 'j F Y', strtotime( $ref->date ) ); ?><br />
							<?php echo date_i18n( 'H:i', strtotime( $ref->date ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_db_pagination( $count_rows, $refers_url, $per, $attributes['page'] ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No referrals found.', 'mlm' ); ?></div>
	
<?php endif; ?>