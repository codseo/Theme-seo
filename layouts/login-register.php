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
<body <?php body_class(); ?>>
	<!--[if lt IE 9]>
		<div class="alert alert-danger alert-dismissible fade show rounded-0 m-0 p-0 border-0" role="alert">
			<div class="container py-3 position-relative">
				<?php _e( "<strong>Update your browser!</strong> for better experience.", 'mlm' ); ?>
				<button type="button" class="close mt-1" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	<![endif]-->
	
	<div class="login-page-wrapper h-100 m-0 p-0">
		<div class="row no-gutters align-items-center justify-content-center">
			<div class="col-auto h-100 py-5">
				<div class="text-left mb-3 px-3">
					<a href="<?php echo esc_url( home_url('/') ); ?>" class="go-back-link position-relative d-inline-block"><?php _e( 'Home', 'mlm' ); ?></a>
				</div>
				<div class="auth-form mx-auto bg-white p-3 p-md-5 rounded position-relative overflow-hidden clearfix">
					<?php while( have_posts() ): the_post(); ?>
						<?php the_title( '<h1 class="auth-title ellipsis font-20 bold-900 mb-5">', '</h1>' ); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
					<div class="security-tips position-absolute bg-white p-3 p-md-5 rounded transition clearfix">
						<div class="clearfix">
							<a href="#security-tips-toggle" class="btn btn-light rounded-circle float-left">X</a>
						</div>
						<h4 class="ellipsis font-16 bold-600 text-dark mb-3"><?php _e( 'Security tips', 'mlm' ); ?></h4>
						<div class="dotted text-14 bold-600 text-secondary mb-2">
							<?php _e( 'Please use trusted web browsers like Google Chrome, Mozilla Firefox & etc', 'mlm' ); ?>
						</div>
						<div class="dotted text-14 bold-600 text-secondary mb-2">
							<?php _e( 'Please change your password in short time periods.', 'mlm' ); ?>
						</div>
						<div class="dotted text-14 bold-600 text-secondary mb-2">
							<?php _e( 'We will never ask for your private data through email. Let us know if you asked for it.', 'mlm' ); ?>
						</div>
					</div>
				</div>
				<div class="text-center mt-3 px-3">
					<a href="#security-tips-toggle" class="go-back-link sc position-relative d-inline-block"><?php _e( 'Security tips', 'mlm' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	
	<?php wp_footer(); ?>
</body>
</html>