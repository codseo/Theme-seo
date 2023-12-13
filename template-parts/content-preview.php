<?php
$post_id		= get_the_ID();
$button_text	= get_post_meta( $post_id, 'mlm_button_text', true );
$button_link	= get_post_meta( $post_id, 'mlm_button_link', true );
$file_type		= get_post_meta( $post_id, 'mlm_file_type', true );
$types			= mlmFire()->wp_admin->supported_file_types();
$icon			= isset( $types[$file_type]['icon'] ) ? $types[$file_type]['icon'] : 'icon-book-open';
	

if( empty( $button_text ) || empty( $button_link ) )
{
	return;
}
?>
<a target="_blank" href="<?php echo esc_url( $button_link ); ?>" class="mlm-share-btn btn btn-danger btn-block border-0 py-1 px-3 icon <?php echo $icon; ?>"><?php echo $button_text; ?></a>