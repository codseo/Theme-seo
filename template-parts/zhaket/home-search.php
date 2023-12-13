<?php
$site_logo		= get_option('mlm_logo');
$search_title	= get_option('mlm_search_title');
$search_sub		= get_option('mlm_search_subtitle');
?>

<section class="app-home-search mb-5 mx-auto clearfix">
	<?php if( ! empty( $site_logo ) ): ?>
		<div class="home-logo mb-4 text-center">
			<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
			<img src="<?php echo esc_url( $site_logo ); ?>" class="app-logo img-fluid d-block mx-auto" alt="<?php bloginfo( 'name' ); ?>" />
		</div>
	<?php endif; ?>
	
	<div class="search-form mb-4 clearfix">
		<form class="mlm-ajax-search position-relative m-0" action="<?php echo esc_url( home_url('/') ); ?>" method="get">
			<div class="search-input position-relative">
				<input type="text" name="s" class="input transition rounded-pill font-16" placeholder="<?php _e( 'Search for ...', 'mlm' ); ?>" data-verify="<?php echo wp_create_nonce('mlm_farolmokr'); ?>" />
				<button type="submit" class="search-btn btn">
					<svg viewBox="-4.615 -5.948 39.083 39.417"><path d="M33.207 30.77L25.6 23c-.064-.065-.143-.104-.218-.148 2.669-2.955 4.31-6.856 4.31-11.143 0-9.189-7.476-16.665-16.665-16.665S-3.638 2.52-3.638 11.709s7.476 16.665 16.665 16.665c4.221 0 8.067-1.59 11.007-4.186.042.072.076.148.137.211l7.607 7.77a.998.998 0 0 0 1.414.016 1.002 1.002 0 0 0 .015-1.415zm-20.18-4.397c-8.086 0-14.665-6.578-14.665-14.665S4.94-2.956 13.027-2.956c8.086 0 14.665 6.579 14.665 14.665s-6.579 14.664-14.665 14.664z"></path></svg>
				</button>
			</div>
			<div class="mlm-search-results mlm-widget bg-white position-absolute text-justify m-0 p-0 rounded clearfix"></div>
		</form>
	</div>
	<div class="home-title text-center clearfix">
		<?php if( ! empty( $search_title ) ): ?>
			<h1 class="main bold-600 font-24 mb-2">
				<?php echo $search_title; ?>
			</h1>
		<?php endif; ?>
		<?php if( ! empty( $search_sub ) ): ?>
			<p class="sub m-0 font-16">
				<?php echo $search_sub; ?>
			</p>
		<?php endif; ?>
	</div>
</section>