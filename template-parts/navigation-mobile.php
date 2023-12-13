<aside id="mlm-mobile-menu" class="mlm-mobile-menu d-block bg-white position-fixed oveflow-hidden transition">
	<div class="slimscroll position-relative m-0 p-0 clearfix">
		<div class="bg-light m-0 p-0 clearfix">
			<a href="#mlm-close-mobile-menu" class="btn btn-secondary text-white float-left rounded-0 icon icon-arrow-right2"></a>
		</div>
		<?php if( is_user_logged_in() ): ?>
			<div class="mlm-user-panel-widget p-0 clearfix">
				<?php mlmFire()->dashboard->print_avatar_box(); ?>
			</div>
			<?php mlmFire()->dashboard->print_mobile_menu(); ?>
			<?php mlmFire()->dashboard->print_social_icons(); ?>
		<?php endif; ?>
		<?php if( has_nav_menu( 'mobile-menu' ) ): ?>
			<div class="menu-title bg-primary text-white m-0 p-2 text-center icon icon-list clearfix">
				<?php _e( 'Main menu', 'mlm' ); ?>
			</div>
			<div class="mlm-mobile-nav clearfix">
				<div class="mlm-drilldown position-relative m-0 p-0">
					<div class="drilldown-container">
						<?php
						wp_nav_menu( array(
							'theme_location'	=> 'mobile-menu',
							'container'			=> false,
							'echo'				=> true,
							'menu_class'		=> 'drilldown-root sliding visible',
							'walker'			=> new MLM_Nav_Menu_Walker(),
						) );
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if( has_nav_menu( 'secondary-menu' ) ): ?>
			<div class="menu-title bg-primary text-white m-0 p-2 text-center icon icon-list clearfix">
				<?php _e( 'Sub menu', 'mlm' ); ?>
			</div>
			<div class="mlm-mobile-nav clearfix">
				<div class="mlm-drilldown position-relative m-0 p-0">
					<div class="drilldown-container">
						<?php
						wp_nav_menu( array(
							'theme_location'	=> 'secondary-menu',
							'container'			=> false,
							'echo'				=> true,
							'menu_class'		=> 'drilldown-root sliding visible',
							'walker'			=> new MLM_Nav_Menu_Walker(),
						) );
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</aside>