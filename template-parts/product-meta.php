<?php
$post_id			= get_the_ID();
$fields_type	= mlm_custom_fields_type();

if( $fields_type == 'custom' )
{
	$custom_fields	= mlmFire()->wp_admin->get_fields();
	$saved_fields	= get_post_meta( $post_id, 'mlm_saved_fields', true );
}
else
{
	$mlm_file_type		= get_post_meta( $post_id, 'mlm_file_type', true );
	$mlm_page_count		= get_post_meta( $post_id, 'mlm_page_count', true );
	$mlm_part_count		= get_post_meta( $post_id, 'mlm_part_count', true );
	$mlm_file_author	= get_post_meta( $post_id, 'mlm_file_author', true );
	$mlm_file_size		= get_post_meta( $post_id, 'mlm_file_size', true );
	$mlm_file_format	= get_post_meta( $post_id, 'mlm_file_format', true );
	$mlm_file_language	= get_post_meta( $post_id, 'mlm_file_language', true );
	$mlm_file_step		= get_post_meta( $post_id, 'mlm_file_step', true );
	
	$types	= mlmFire()->wp_admin->supported_file_types();
	$icon	= isset( $types[$mlm_file_type]['icon'] ) ? $types[$mlm_file_type]['icon'] : 'icon-book-open';
	$title	= isset( $types[$mlm_file_type]['title'] ) ? $types[$mlm_file_type]['title'] : __( 'Pages count', 'mlm' );
}
?>

<div class="mlm-product-meta-box m-0 p-0 clearfix">
	<h3 class="mlm-box-title icon icon-presentation sm mb-2">
		<?php _e( 'Product info', 'mlm' ); ?>
	</h3>
	<div class="row">
		<?php if( $fields_type == 'custom' ): ?>
		
			<?php foreach( $custom_fields as $k => $v ): ?>
				<?php if( isset( $saved_fields[$v['id']] ) && ! empty( $saved_fields[$v['id']] ) ): ?>
					<div class="col-6 col-md-3">
						<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
							<span class="t d-block"><?php echo $v['text']; ?></span>
							<span class="v d-block bold-600"><?php echo $saved_fields[$v['id']]; ?></span>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			
			<div class="col-6 col-md-3">
				<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
					<span class="t d-block"><?php _e( 'Updated at', 'mlm' ); ?></span>
					<time class="v d-block bold-600 entry-date published updated"><?php the_modified_date('j F Y'); ?></time>
				</div>
			</div>
			
		<?php else: ?>
			<?php if( ! empty( $mlm_page_count ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon d-block <?php echo $icon; ?>"></span>
						<span class="t d-block"><?php echo $title; ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_page_count; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_part_count ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon icon-drawer d-block"></span>
						<span class="t d-block"><?php _e( 'Parts count', 'mlm' ); ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_part_count; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_file_size ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon icon-floppy-disk d-block"></span>
						<span class="t d-block"><?php _e( 'Size', 'mlm' ); ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_file_size; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_file_format ) ): ?>
				<div class="col-6 col-md-3" itemprop="brand" itemscope itemtype="http://schema.org/Brand">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon d-block <?php echo $icon; ?>"></span>
						<span class="t d-block" itemprop="description"><?php _e( 'Format', 'mlm' ); ?></span>
						<span class="v d-block bold-600" itemprop="name"><?php echo $mlm_file_format; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_file_language ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon icon-list2 d-block"></span>
						<span class="t d-block"><?php _e( 'Language', 'mlm' ); ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_file_language; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_file_step ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon icon-key2 d-block"></span>
						<span class="t d-block"><?php _e( 'Step', 'mlm' ); ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_file_step; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if( ! empty( $mlm_file_author ) ): ?>
				<div class="col-6 col-md-3">
					<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
						<span class="icon icon-user-tie d-block"></span>
						<span class="t d-block"><?php _e( 'Organizer', 'mlm' ); ?></span>
						<span class="v d-block bold-600"><?php echo $mlm_file_author; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="col-6 col-md-3">
				<div class="mlm-product-meta bg-light p-3 mb-4 text-center clearfix">
					<span class="icon icon-calendar d-block"></span>
					<span class="t d-block"><?php _e( 'Updated at', 'mlm' ); ?></span>
					<time class="v d-block bold-600 entry-date published updated"><?php the_modified_date('j F Y'); ?></time>
				</div>
			</div>
		<?php endif; ?>
		
	</div>
</div>

<div class="sr-only">
	<meta itemprop="sku" content="download-<?php echo $post_id; ?>">
	<meta itemprop="productID" content="<?php echo $post_id; ?>">
</div>