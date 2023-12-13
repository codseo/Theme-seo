<?php

if( ! defined( 'ABSPATH' ) )
{
	die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
	wp_die('You are not allowed here !');
}

$demo			= mlm_selected_demo();
$user_id		= get_current_user_id();
$user_name		= mlm_get_user_name( $user_id );
$verified		= mlmFire()->dashboard->get_account_status( $user_id );
$percent		= mlmFire()->dashboard->get_profile_status( $user_id );
$chart_data		= mlmFire()->dashboard->get_user_chart_data( $user_id );
$top_vendors	= mlmFire()->dashboard->get_top_vendors();
$top_referrers	= mlmFire()->dashboard->get_top_referrers();
$transactions	= mlmFire()->dashboard->get_recent_transactions( $user_id );
$posts_cnt		= count_user_posts( $user_id , 'post' );
$products_cnt	= count_user_posts( $user_id , 'product' );
$panel_url		= trailingslashit( mlm_page_url('panel') );
$blog_query		= new WP_Query( array(
	'post_type' 	=> 'post',
	'post_status'	=> 'publish',
	'posts_per_page'=> 5,
) );
?>

<?php if( $percent < 100 ): ?>
	<div class="alert alert-warning"><?php _e( 'Please complete your profile info.', 'mlm' ); ?></div>
<?php endif; ?>

<?php if( ! empty( $chart_data ) ): ?>
	
	<?php
	$labels	= array();
	$in		= array();
	$out	= array();
	
	foreach( $chart_data as $c => $a )
	{
		$labels[]	= date_i18n( 'Y-m-d', $c );
		$in[]		= $a['in'];
		$out[]		= $a['out'];
	}
	?>
	
	<div class="mlm-chart-wrapper p-0 mb-4 clearfix">
		<canvas id="mlm_chart" height="120"></canvas>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var tdata = {
				labels: <?php echo json_encode( $labels ); ?>,
				datasets: [
					{
						label: "<?php _e( 'withdrawal', 'mlm' ); ?>",
						backgroundColor: "rgba(244, 67, 54,0.4)",
						borderColor: "rgba(244, 67, 54,1)",
						borderWidth: "1",
						data: <?php echo json_encode( $out ); ?>
					},
					{
						label: "<?php _e( 'Income', 'mlm' ); ?>",
						backgroundColor: "rgba(76, 175, 80,0.4)",
						borderColor: "rgba(76, 175, 80,1)",
						borderWidth: "1",
						data: <?php echo json_encode( $in ); ?>
					}
				]
			};
			
			Chart.defaults.global.defaultFontFamily = "iranyekan, tahoma, sans-serif";
			
			var ctx = document.getElementById('mlm_chart').getContext('2d');
			var myLineChart = new Chart(ctx, {
				type: 'bar',
				data: tdata,
				options: {
					responsive: true,
					title: {
						display: false,
						text: "<?php _e( 'withdrawals', 'mlm' ); ?>",
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					},
					scales: {
						xAxes: [{
							display: true,
							scaleLabel: {
								display: false,
								labelString: "<?php _e( 'date', 'mlm' ); ?>",
							},
							gridLines: {
								display: false
							}
						}],
						yAxes: [{
							display: true,
							scaleLabel: {
								display: false,
								labelString: "<?php _e( 'amount', 'mlm' ); ?>",
							},
							gridLines: {
								display: false
							}
							/*
							ticks: {
								min: 0,
								max: 100,

								// forces step size to be 5 units
								stepSize: 5
							}
							*/
						}]
					}
				}
			});
		});
	</script>
<?php endif; ?>

<?php if( $demo == 'zhaket' ): ?>
	<div class="total-stats-widget clearfix">
		<div class="row">
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-products.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1">
						<?php printf( _nx( '%d item', '%d items', $products_cnt, 'items count', 'mlm' ), $products_cnt ); ?>
					</div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'your products count', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-balance.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1"><?php echo mlm_filter( mlmFire()->wallet->get_balance( $user_id ) ); ?></div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'your balance', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-withdraw.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1"><?php echo mlm_filter( mlmFire()->wallet->total_withdraw_amount( $user_id ) ); ?></div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'total withdrawals', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-posts.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1">
						<?php printf( _nx( '%d item', '%d items', $posts_cnt, 'items count', 'mlm' ), $posts_cnt ); ?>
					</div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'your posts count', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-transaction.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1"><?php echo mlm_filter( mlmFire()->wallet->recent_withdraw_amount( $user_id ) ); ?></div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'last withdrawal amount', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-income.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1"><?php echo mlm_filter( mlmFire()->wallet->total_income_amount( $user_id ) ); ?></div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'total income', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-subs.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1">
						<?php
						$subs_count	= mlmFire()->network->get_subs_count( $user_id );
						printf( _nx( '%d person', '%d people', $subs_count, 'subsets count', 'mlm' ), $subs_count );
						?>
					</div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'your subsets count', 'mlm' ); ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="stat-item px-3 py-4 mb-4 rounded text-center transition clearfix">
					<div class="icon mb-4 mx-auto">
						<img src="<?php echo IMAGES; ?>/panel-sales.svg" class="d-block mx-auto" alt="stat" />
					</div>
					<div class="v font-18 bold-600 ellipsis text-warning mb-1">
						<?php
						$sale_count	= mlmFire()->wallet->get_user_sales_count( $user_id );
						printf( _nx( '%d item', '%d items', $sale_count, 'items count', 'mlm' ), $sale_count );
						?>
					</div>
					<div class="t font-14 bold-600 ellipsis text-secondary"><?php _e( 'total sales count', 'mlm' ); ?></div>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
	<div class="mlm-user-stats mb-4 clearfix">
		<div class="row">
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/products-all/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-barcode d-block"></span>
					<span class="v d-block bold-600">
						<?php printf( _nx( '%d item', '%d items', $products_cnt, 'items count', 'mlm' ), $products_cnt ); ?>
					</span>
					<span class="t d-block bold-300"><?php _e( 'your products count', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/wallet/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-credit-card d-block"></span>
					<span class="v d-block bold-600"><?php echo mlm_filter( mlmFire()->wallet->get_balance( $user_id ) ); ?></span>
					<span class="t d-block bold-300"><?php _e( 'your balance', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/withdrawals/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-credit-card d-block"></span>
					<span class="v d-block bold-600"><?php echo mlm_filter( mlmFire()->wallet->total_withdraw_amount( $user_id ) ); ?></span>
					<span class="t d-block bold-300"><?php _e( 'total withdrawals', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/posts-all/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-file-text d-block"></span>
					<span class="v d-block bold-600">
						<?php printf( _nx( '%d item', '%d items', $posts_cnt, 'items count', 'mlm' ), $posts_cnt ); ?>
					</span>
					<span class="t d-block bold-300"><?php _e( 'your posts count', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/withdrawals/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-file-text d-block"></span>
					<span class="v d-block bold-600"><?php echo mlm_filter( mlmFire()->wallet->recent_withdraw_amount( $user_id ) ); ?></span>
					<span class="t d-block bold-300"><?php _e( 'last withdrawal amount', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/sales/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-file-text d-block"></span>
					<span class="v d-block bold-600"><?php echo mlm_filter( mlmFire()->wallet->total_income_amount( $user_id ) ); ?></span>
					<span class="t d-block bold-300"><?php _e( 'total income', 'mlm' ); ?></span>
				</a>
			</div>
		</div>
	</div>

	<div class="row">
		<?php if( ! empty( $top_vendors ) ): ?>
			<div class="col-12 col-md-8">
				<div class="mlm-vendors-slider-wrapper mlm-category-widget mb-4 clearfix">
					<h3 class="mlm-box-title sm my-3"><?php _e( 'Top vendors', 'mlm' ); ?></h3>
					<div class="mlm-vendor-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php foreach( $top_vendors as $vendor ): ?>
								<?php
								$vendor_name	= mlm_get_user_name( $vendor->user_id );
								?>
								<div class="swiper-slide">
									<div class="mlm-category-box text-center p-2 m-0 rounded transition clearfix">
										<?php echo get_avatar( $vendor->user_id, 80, NULL , $vendor_name, array( 'class' => 'item-image rounded-circle d-block mx-auto' ) ); ?>
										<h5 class="item-title my-2 bold-600"><?php echo $vendor_name; ?></h5>
										<a href="<?php echo get_author_posts_url( $vendor->user_id ); ?>" class="btn btn-light py-0 rounded-pill" title="<?php echo $vendor_name; ?>" rel="bookmark"><?php _e( 'View', 'mlm' ); ?></a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if( ! empty( $transactions ) ): ?>
			<div class="col-12 col-md-4">
				<h3 class="mlm-box-title sm my-3"><?php _e( 'Your financial balance', 'mlm' ); ?></h3>
				<div class="mlm-wallet-changes slimscroll mb-4 p-0 clearfix">
					<div class="table-responsive-sm">
						<table class="mlm-table mlm-changes-table table table-sm table-borderless table-hover border-0 m-0">
							<tbody>
								<?php foreach( $transactions as $ta ): ?>
									<?php
									$tp_class = mlmFire()->wallet->get_type_class( $ta->type );
									// $tp_sign = mlmFire()->wallet->get_type_sign( $ta->type );
									$tp_sign = mlmFire()->wallet->get_type_text( $ta->type );
									?>
									<tr>
										<th scope="row">#<?php echo $ta->id; ?></th>
										<td class="text-<?php echo $tp_class; ?>"><?php echo $ta->amount . ' ('.$tp_sign.')'; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if( $blog_query->have_posts() ): ?>
			<div class="col-12 col-md-4">
				<div class="mlm-products-slider-wrapper mlm-archive m-0 clearfix">
					<h3 class="mlm-box-title my-3 sm"><?php _e( 'Blog', 'mlm' ); ?></h3>
					<div class="mlm-vendor-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php while( $blog_query->have_posts() ): $blog_query->the_post(); ?>
								<div class="swiper-slide">
									<?php get_template_part( 'template-parts/content', 'post' ); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if( ! empty( $top_referrers ) ): ?>
			<div class="col-12 col-md-8">
				<div class="mlm-vendors-slider-wrapper mlm-category-widget mb-4 clearfix">
					<h3 class="mlm-box-title sm my-3"><?php _e( 'Top referrers', 'mlm' ); ?></h3>
					<div class="mlm-vendor-products-slider swiper-container">
						<div class="swiper-wrapper">
							<?php foreach( $top_referrers as $referrer ): ?>
								<?php
								$vendor_name	= mlm_get_user_name( $referrer->ref_user_id );
								?>
								<div class="swiper-slide">
									<div class="mlm-category-box text-center p-2 m-0 rounded transition clearfix">
										<?php echo get_avatar( $referrer->ref_user_id, 80, NULL , $vendor_name, array( 'class' => 'item-image rounded-circle d-block mx-auto' ) ); ?>
										<h5 class="item-title my-2 bold-600"><?php echo $vendor_name; ?></h5>
										<a href="<?php echo get_author_posts_url( $referrer->ref_user_id ); ?>" class="btn btn-light py-0 rounded-pill" title="<?php echo $vendor_name; ?>" rel="bookmark"><?php _e( 'View', 'mlm' ); ?></a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<!-- STATS -->
	<div class="mlm-user-stats mb-4 clearfix">
		<div class="row">
			<?php if( mlmFire()->dashboard->is_menu_item_visible( 'referral' ) ): ?>
				<div class="col-6 col-md-4">
					<a href="<?php echo $panel_url.'section/network/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
						<span class="icon icon-users d-block"></span>
						<span class="v d-block bold-600">
							<?php
							$subs_count	= mlmFire()->network->get_subs_count( $user_id );
							printf( _nx( '%d person', '%d people', $subs_count, 'subsets count', 'mlm' ), $subs_count );
							?>
						</span>
						<span class="t d-block bold-300"><?php _e( 'your subsets count', 'mlm' ); ?></span>
					</a>
				</div>
			<?php endif; ?>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/sales/'; ?>" class="mlm-user-meta d-block bg-light text-dark p-3 mb-3 text-center transition clearfix">
					<span class="icon icon-cloud-download d-block"></span>
					<span class="v d-block bold-600">
						<?php
						$sale_count	= mlmFire()->wallet->get_user_sales_count( $user_id );
						printf( _nx( '%d item', '%d items', $sale_count, 'items count', 'mlm' ), $sale_count );
						?>
					</span>
					<span class="t d-block bold-300"><?php _e( 'total sales count', 'mlm' ); ?></span>
				</a>
			</div>
			<div class="col-6 col-md-4">
				<a href="<?php echo $panel_url.'section/products-new/'; ?>" class="btn btn-primary btn-block mb-1 py-1"><?php _e( 'Add new product', 'mlm' ); ?></a>
				<a href="<?php echo $panel_url.'section/tickets-new/'; ?>" class="btn btn-danger btn-block mb-1 py-1"><?php _e( 'Add new ticket', 'mlm' ); ?></a>
				<a href="<?php echo $panel_url.'section/posts-new/'; ?>" class="btn btn-secondary btn-block mb-3 py-1"><?php _e( 'Add new post', 'mlm' ); ?></a>
			</div>
		</div>
	</div>

<?php endif; ?>