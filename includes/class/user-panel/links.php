<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$mode		= isset( $_GET['mode'] ) ? esc_attr( $_GET['mode'] ) : 'new';
$vendor		= isset( $_GET['vendor'] ) ? absint( $_GET['vendor'] ) : 0;
$search		= isset( $_GET['search'] ) ? esc_attr( $_GET['search'] ): '';
$links_url	= trailingslashit( mlm_page_url('panel') ) . 'section/links/';
$mode_url	= add_query_arg( 'vendor', $vendor, $links_url );
$vend_url	= add_query_arg( 'mode', $mode, $links_url );

if( ! mlm_user_exists( $vendor ) )
{
	$vendor = 0;
}

if( $mode == 'per' )
{
	$meta_key	= 'mlm_ref_amount';
	$orderby	= 'meta_value_num';
}
elseif( $mode == 'sell' )
{
	$meta_key	= 'total_sales';
	$orderby	= 'meta_value_num';
}
else
{
	$meta_key	= 0;
	$orderby	= 'date';
}

$query = new WP_Query( array(
	'post_type' 	=> 'product',
	'post_status'	=> 'publish',
	'author'		=> $vendor,
	'meta_key'		=> $meta_key,
	'orderby'		=> $orderby,
	'order'			=> 'DESC',
	'paged'			=> $attributes['page'],
	'posts_per_page'=> 10,
	's'				=> $search,
	'meta_query'	=> array(
		array(
			'key'		=> 'mlm_ref_value',
			'value'		=> 0,
			'type'		=> 'numeric',
			'compare'	=> '>',
		)
	),
) );

$vendor_ids	= get_users( array(
	'fields'  => 'ID',
	'orderby' => 'post_count',
	'order'   => 'DESC'
) );
?>

<h3 class="mlm-box-title sm mb-2 py-2"><?php _e( 'Referral links', 'mlm' ); ?></h3>

<div class="mlm-filter-bar mb-3 p-0 clearfix">
	<a href="#" class="btn btn-danger btn-sm float-left mr-1 my-1" data-toggle="modal" data-target="#mlm_search"><?php _e( 'Search product', 'mlm' ); ?></a> 
	<select class="btn btn-success btn-sm float-left mr-1 my-1 font-13 pt-2 simple" onchange="javascript:location.href=this.value;">
		<option value="<?php echo $mode_url; ?>" <?php selected( $mode, 'new' ); ?>><?php _e( 'Most recent', 'mlm' ); ?></option>
		<option value="<?php echo add_query_arg( 'mode', 'sell', $mode_url ); ?>" <?php selected( $mode, 'sell' ); ?>><?php _e( 'Best sale', 'mlm' ); ?></option>
		<option value="<?php echo add_query_arg( 'mode', 'per', $mode_url ); ?>" <?php selected( $mode, 'per' ); ?>><?php _e( 'maximum percents', 'mlm' ); ?></option>
		<option value="<?php echo add_query_arg( 'mode', 'me', $mode_url ); ?>" <?php selected( $mode, 'me' ); ?>><?php _e( 'My products', 'mlm' ); ?></option>
	</select>
	<select class="btn btn-secondary btn-sm float-left mr-1 my-1 font-13 pt-2 simple" onchange="javascript:location.href=this.value;">
		<option value="<?php echo $vend_url; ?>"><?php _e( 'All vendors', 'mlm' ); ?></option>
		<?php 
		foreach( $vendor_ids as $vendor_id )
		{
			$post_count = count_user_posts( $vendor_id, 'product' );
			if( ! $post_count )
			{
				continue;
			}
			?>
			<option value="<?php echo add_query_arg( 'vendor', $vendor_id, $vend_url ); ?>" <?php selected( $vendor, $vendor_id ); ?>>
				<?php echo mlm_get_user_name( $vendor_id ); ?>
			</option>
			<?php
		}
		?>
	</select>
</div>

<?php if( ! empty( $search ) ): ?>
	<h4 class="panel-box-title d-block mb-3 p-2 border-bottom"><?php printf( __( 'search results for %s', 'mlm' ), $search ); ?></h4>
<?php endif; ?>

<?php if( $query->have_posts() ): ?>

	<div class="table-responsive">
		<table class="mlm-table mlm-links-table table table-borderless table-hover border-0">
			<thead>
				<tr>
					<th class="sm" scope="col"><?php _e( 'Image', 'mlm' ); ?></th>
					<th class="lg" scope="col"><?php _e( 'Title', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Price', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Percent', 'mlm' ); ?></th>
					<th class="md" scope="col"><?php _e( 'Link', 'mlm' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					<?php
					$post_id	= get_the_ID();
					$link		= mlmFire()->referral->add_ref_to_url( wp_get_shortlink() );
					$ref_amount	= mlmFire()->wallet->post_ref_amount( $post_id );
					?>
					<tr>
						<th scope="row">
							<img width="64" height="64" src="<?php mlm_image_url( $post_id, 'thumbnail' ); ?>" class="d-block rounded border" alt="post-image">
						</th>
						<td>
							<div class="title d-block mb-1"><?php the_title(); ?></div>
							<?php get_template_part( 'template-parts/links', 'share' ); ?>
						</td>
						<td><?php mlm_product_price(); ?></td>
						<td><?php echo mlm_filter( $ref_amount ); ?></td>
						<td>
							<textarea contenteditable="false" onmouseup="this.select()" class="form-control ltr"><?php echo $link; ?></textarea>
						</td>
					</tr>
				<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
	</div>
	
	<?php mlm_navigation( $query ); ?>
	
<?php else: ?>

	<div class="alert alert-warning"><?php _e( 'No products found.', 'mlm' ); ?></div>
	
<?php endif; ?>

<div class="modal fade" id="mlm_search" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e( 'Search product', 'mlm' ); ?></h5>
				<button type="button" class="close mr-auto ml-0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo $links_url; ?>" method="get">
					<div class="form-group">
						<label for="mlm_keyword"><?php _e( 'Keyword', 'mlm' ); ?></label>
						<input type="text" class="form-control" id="mlm_keyword" name="search" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>">
					</div>
					<div class="clearfix">
						<button type="submit" class="btn btn-primary btn-block"><?php _e( 'Search', 'mlm' ); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>