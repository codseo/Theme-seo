<?php
if (post_password_required()) {
	return;
}
?>

<div class="seo-comments-box comments-area" id="comments">
		
	<?php if (have_comments()): ?>
		<ol class="comment-list d-block p-0 mt-0 mx-0 mb-4">
			<?php
				wp_list_comments(array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 64,
				));
			?>
		</ol>
		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
			<nav class="comment-nav bold-600 clearfix" role="navigation">
				<p class="comment-nav-prev">
					<?php previous_comments_link(__('&larr; Older comments', 'seo')); ?>
				</p>
				<p class="comment-nav-next">
					<?php next_comments_link(__('Recent comments &rarr;', 'seo')); ?>
				</p>
			</nav>
		<?php endif; ?>
	<?php endif; // have_comments() ?>

	<?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
		<p class="alert alert-warning rounded-0 border-0 mb-3"><?php _e('Comments are closed.', 'seo'); ?></p>
	<?php endif; ?>

	<?php
	comment_form(array(
		'title_reply'          => '',
		'cancel_reply_link'    => __('Cancel Reply', 'seo'),
		'comment_notes_after'  => '',
		'comment_notes_before' => ''
	));
	?>
	
</div>