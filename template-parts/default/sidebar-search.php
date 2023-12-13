<?php
$min_price		= get_query_var( 'mlm_min_price', '' );
$max_price		= get_query_var( 'mlm_max_price', '' );
$category		= get_query_var( 'mlm_category', array() );
$tag			= get_query_var( 'mlm_tag', array() );
$medal			= get_query_var( 'mlm_medal', array() );

$medals_obj		= mlmFire()->medal->product_medals();
$vendor_obj		= wp_dropdown_users( array(
	'show_option_none'	=> __( 'All vendors', 'mlm' ),
	'option_none_value'	=> '',
	'selected'			=> get_query_var( 'mlm_vendor', 0 ),
	'name'				=> 'mlm_vendor',
	'class'				=> 'form-control no-shadow',
	'role'				=> 'mlm_vendor',
	'echo'				=> 0,
) );
$cats_obj		= get_terms( array(
	'taxonomy'		=> 'product_cat',
	'hide_empty'	=> true,
	'parent'		=> 0,
) );
$tags_obj		= get_terms( array(
	'taxonomy'		=> 'product_tag',
	'hide_empty'	=> true,
	'orderby'		=> 'count',
	'order'			=> 'DESC',
	'number'		=> 100
) );
?>

<div class="mlm-filters-widget mb-3 clearfix">
	<h6 class="widget-title font-14 bold-600 mb-2 px-3 py-2 bg-light border rounded">
		<span class="icon">
			<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
		</span>
		<?php _e( 'By price range', 'mlm' ); ?>
	</h6>
	<div class="form-group row align-items-center">
		<label for="mlm_min_price" class="col-sm-2 col-form-label"><?php _e( 'From', 'mlm' ); ?></label>
		<div class="col-sm-10">
			<div class="input-group m-0">
				<input type="number" class="form-control no-shadow" name="mlm_min_price" value="<?php echo $min_price; ?>" min="0">
				<div class="input-group-append">
					<span class="input-group-text font-10"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group row align-items-center">
		<label for="mlm_max_price" class="col-sm-2 col-form-label"><?php _e( 'To', 'mlm' ); ?></label>
		<div class="col-sm-10">
			<div class="input-group m-0">
				<input type="number" class="form-control no-shadow" name="mlm_max_price" value="<?php echo $max_price; ?>" min="0">
				<div class="input-group-append">
					<span class="input-group-text font-10"><?php if( function_exists('get_woocommerce_currency_symbol') ) echo get_woocommerce_currency_symbol(); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if( ! empty( $vendor_obj ) ): ?>
	<div class="mlm-filters-widget mb-3 clearfix">
		<h6 class="widget-title font-14 bold-600 mb-2 px-3 py-2 bg-light border rounded">
			<span class="icon">
				<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
			</span>
			<?php _e( 'Product vendor', 'mlm' ); ?>
		</h6>
		<?php echo $vendor_obj; ?>
	</div>
<?php endif; ?>

<?php if( ! empty( $cats_obj ) && ! is_wp_error( $cats_obj ) ): ?>
	<div class="mlm-filters-widget mb-3 clearfix">
		<h6 class="widget-title font-14 bold-600 mb-2 px-3 py-2 bg-light border rounded">
			<span class="icon">
				<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
			</span>
			<?php _e( 'Product categories', 'mlm' ); ?>
		</h6>
		<?php foreach( $cats_obj as $ct ): ?>
			<?php
			$childs_obj	= get_terms( array(
				'taxonomy'		=> 'product_cat',
				'hide_empty'	=> true,
				'parent'		=> $ct->term_id,
			) );
			$child_ids	= get_term_children( $ct->term_id, 'product_cat' );
			$ct_class	= '';

			if( ! empty( $child_ids ) && ! is_wp_error( $child_ids ) )
			{
				foreach( $child_ids as $child_id )
				{
					if( in_array( $child_id, $category ) )
					{
						$ct_class	= 'open';
						break;
					}
				}
			}
			?>
			<div class="category-item position-relative py-1 clearfix <?php echo $ct_class; ?>">
				<div class="form-check">
					<input class="form-check-input" name="mlm_category[]" type="checkbox" value="<?php echo $ct->term_id; ?>" id="mlm_category_<?php echo $ct->term_id; ?>" <?php if( in_array( $ct->term_id, $category ) ) echo 'checked="checked"'; ?>>
					<label class="form-check-label" for="mlm_category_<?php echo $ct->term_id; ?>"><?php echo $ct->name; ?></label>
				</div>
				<?php if( ! empty( $childs_obj ) && ! is_wp_error( $childs_obj ) ): ?>
					<a href="#" class="toggle btn border-0 bg-transparent position-absolute p-1 no-shadow">
						<?php echo ( empty( $ct_class ) ) ? '+' : '-'; ?>
					</a>
					<div class="childs mt-1 clearfix">
						<?php foreach( $childs_obj as $ch ): ?>
							<div class="form-check">
								<input class="form-check-input" name="mlm_category[]" type="checkbox" value="<?php echo $ch->term_id; ?>" id="mlm_category_<?php echo $ch->term_id; ?>" <?php if( in_array( $ch->term_id, $category ) ) echo 'checked="checked"'; ?>>
								<label class="form-check-label" for="mlm_category_<?php echo $ch->term_id; ?>"><?php echo $ch->name; ?></label>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if( ! empty( $tags_obj ) && ! is_wp_error( $tags_obj ) ): ?>
	<div class="mlm-filters-widget mb-3 clearfix">
		<h6 class="widget-title font-14 bold-600 mb-2 px-3 py-2 bg-light border rounded">
			<span class="icon">
				<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
			</span>
			<?php _e( 'Product tags', 'mlm' ); ?>
		</h6>
		<select name="mlm_tag[]" class="form-control" id="mlm_search_tags" multiple="multiple">
			<?php foreach( $tags_obj as $tg ): ?>
				<option value="<?php echo $tg->term_id; ?>" <?php if( in_array( $tg->term_id, $tag ) ) echo 'selected="selected"'; ?>><?php echo $tg->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>

<div class="mlm-filters-widget mb-3 clearfix">
	<h6 class="widget-title font-14 bold-600 mb-2 px-3 py-2 bg-light border rounded">
		<span class="icon">
			<svg width="24px" height="24px" viewBox="0 0 24 24"><path fill="#95989A" d="M10.2 24c-.7 0-1.3-.3-1.6-1-.1-.3-.2-.6-.2-.9V13L.4 3C-.2 2.2-.1 1 .7.4 1 .2 1.4 0 1.8 0h20.3c1 0 1.8.9 1.8 1.9 0 .4-.2.8-.4 1.1l-7.9 10v7.1c0 .7-.4 1.3-.9 1.6L11 23.8c-.2.1-.5.2-.8.2zm-8-22l7.8 9.8c.3.3.4.7.4 1.2v8.9l3.4-1.9v-7c0-.4.1-.8.4-1.2L21.9 2H2.2zm11.5 18.1zm2-7.1zm0-.1zM2 1.8z"></path></svg>
		</span>
		<?php _e( 'Product medals', 'mlm' ); ?>
	</h6>
	<?php foreach( $medals_obj as $md ): ?>
		<div class="form-check">
			<input class="form-check-input" name="mlm_medal[]" type="checkbox" value="<?php echo $md; ?>" id="mlm_medal_<?php echo $md; ?>" <?php if( in_array( $md, $medal ) ) echo 'checked="checked"'; ?>>
			<label class="form-check-label" for="mlm_medal_<?php echo $md; ?>"><?php echo mlmFire()->medal->get_product_medal_title( $md ); ?></label>
		</div>
	<?php endforeach; ?>
</div>