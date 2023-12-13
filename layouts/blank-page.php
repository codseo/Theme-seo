<?php
/* Template Name: Blank page for page builders */
?>

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
	
	<section id="primary" class="content-area container">
		<div class="row">
	
			<main id="main" class="site-main col-12">
				
				<?php while( have_posts() ): the_post(); ?>
				
					<?php the_content(); ?>
					
				<?php endwhile; ?>
				
			</main>
	
		</div>
	</section>
	
	<?php wp_footer(); ?>
</body>
</html>