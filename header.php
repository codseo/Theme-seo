<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="author" content="Adanet" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<!--[if lt IE 9]>
		<script src="<?php echo SCRIPTS; ?>/html5.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>
<?php
$body_class		= '';
$notbar_text	= get_option('seo_notbar_text');
$hide_notif		= isset( $_COOKIE['seo_hide_notif'] ) ? true : false;

if( empty( $notbar_text ) $hide_notif )
{
	$body_class		= 'nnf';
}
?>
<body <?php body_class( $body_class ); ?>>
	<!--[if lt IE 9]>
		<div class="alert alert-danger alert-dismissible fade show rounded-0 m-0 p-0 border-0" role="alert">
			<div class="container py-3 position-relative">
				<?php _e( "<strong>Update your browser!</strong> for better experience.", 'seo' ); ?>
				<button type="button" class="close mt-1" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	<![endif]-->
	
	<?php
	$demo = seo_selected_demo();
	
	if( $demo == 'seokar' )
	{
		get_template_part( 'template-parts/seokar/header' );
	}
	else
	{
		get_template_part( 'template-parts/default/header' );
	}
	?>