<?php
$post_id		= get_the_ID();
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
	$title	= isset( $types[$mlm_file_type]['title'] ) ? $types[$mlm_file_type]['title'] : __( 'Pages count', 'mlm' );
}
?>

<div class="product-meta-widget mb-4 p-3 border rounded clearfix">
	<div class="row align-items-center">
		<?php if( $fields_type == 'custom' ): ?>
			<?php foreach( $custom_fields as $k => $v ): ?>
				<?php if( isset( $saved_fields[$v['id']] ) && ! empty( $saved_fields[$v['id']] ) ): ?>
					<div class="col-auto my-3">
						<span class="d-block font-12 bold-400 text-secondary">
							<?php echo $v['text']; ?>: <?php echo $saved_fields[$v['id']]; ?>
						</span>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php else: ?>
			<?php if( ! empty( $mlm_page_count ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php echo $title; ?>: <?php echo $mlm_page_count; ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_part_count ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php _e( 'Parts count', 'mlm' ); ?>: <?php echo $mlm_part_count; ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_file_size ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php _e( 'Size', 'mlm' ); ?>: <?php echo $mlm_file_size; ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_file_format ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary" itemprop="brand" itemscope itemtype="http://schema.org/Brand">
						<span itemprop="description"><?php _e( 'Format', 'mlm' ); ?></span>: <span itemprop="name"><?php echo $mlm_file_format; ?></span>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_file_language ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php _e( 'Language', 'mlm' ); ?>: <?php echo $mlm_file_language; ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_file_step ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php _e( 'Step', 'mlm' ); ?>: <?php echo $mlm_file_step; ?>
					</span>
				</div>
			<?php endif; ?>
			<?php if( ! empty( $mlm_file_author ) ): ?>
				<div class="col-auto my-3">
					<span class="d-block font-12 bold-400 text-secondary">
						<?php _e( 'Organizer', 'mlm' ); ?>: <?php echo $mlm_file_author; ?>
					</span>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<div class="col-auto my-3">
			<span class="d-block font-12 bold-400 text-secondary">
				<?php _e( 'Updated at', 'mlm' ); ?>: <time class="entry-date published updated"><?php the_modified_date('j F Y'); ?></time>
			</span>
		</div>
	</div>
</div>

<div class="sr-only">
	<meta itemprop="sku" content="download-<?php echo $post_id; ?>">
	<meta itemprop="productID" content="<?php echo $post_id; ?>">
</div>