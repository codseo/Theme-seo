<?php
$post_id		= get_the_ID();
$chapters_obj	= mlmFire()->db->query_rows(
	"SELECT * FROM {TABLE} WHERE post_id = %d AND parent_id = %d ORDER BY priority ASC",
	array( $post_id, 0 ),
	'course'
);

if( ! mlm_check_course( $post_id ) || empty( $chapters_obj ) )
{
	return;
}

$permission = false;

if( is_user_logged_in() )
{
	$user_id	= get_current_user_id();
	$user_obj	= get_userdata( $user_id );
	$user_email	= $user_obj->user_email;
	$access		= mlmFire()->plan->check_user_access( $post_id, $user_id );

	if( $access || ( function_exists('wc_customer_bought_product') && wc_customer_bought_product( $user_email, $user_id, $post_id ) ) )
	{
		$permission	= true;
	}
}
?>

<div id="mlm-scroll-to-course" class="mlm-course-chapters-widget mb-4 clearfix">
	<h3 class="mlm-box-title icon icon-notebook sm mb-2"><?php _e( 'Course articles', 'mlm' ); ?></h3>
	<div class="collapse show multi-collapse" id="mlm-course-chapters-collapse">
		<div class="mlm-chapters accordion clearfix" id="mlm-course-chapters-accordion">
			<?php foreach( $chapters_obj as $chapter ): ?>
				<?php
				$chapter_data	= maybe_unserialize( $chapter->course_data );
				$image_atts		= wp_get_attachment_image_src( $chapter_data['image_id'], 'thumbnail' );
				$lesson_obj		= mlmFire()->db->query_rows(
					"SELECT * FROM {TABLE} WHERE parent_id = %d ORDER BY priority ASC",
					array( $chapter->id ),
					'course'
				);

				if( ! $image_atts )
				{
					$image_url		= IMAGES . '/no-thumbnail.png';
				}
				else
				{
					$image_url		= $image_atts[0];
				}
				?>
				<div class="chapter-item mb-1 clearfix">
					<div class="chapter-header bg-light position-relative clearfix" id="mlm-chapter-heading-<?php echo $chapter->id; ?>">
						<a href="#" class="d-block p-2 text-dark font-12" data-toggle="collapse" data-target="#mlm-chapter-collapse-<?php echo $chapter->id; ?>" aria-expanded="false" aria-controls="mlm-chapter-collapse-<?php echo $chapter->id; ?>">
							<img width="48" height="48" src="<?php echo $image_url; ?>" class="chapter-image float-right ml-2" alt="<?php echo $chapter_data['title']; ?>">
							<span class="chapter-title d-block font-14 bold-600"><?php echo $chapter_data['title']; ?></span>
							<span class="chapter-text d-block font-12 bold-300"><?php echo $chapter_data['text']; ?></span>
							<span class="icon icon-arrow-down2 position-absolute font-18"></span>
						</a>
					</div>
					<div id="mlm-chapter-collapse-<?php echo $chapter->id; ?>" class="collapse" aria-labelledby="mlm-chapter-heading-<?php echo $chapter->id; ?>" data-parent="#mlm-course-chapters-accordion">
						<div class="mlm-lessons-accordion my-2 mx-3 pr-1 border-right clearfix">
							<?php if( ! empty( $lesson_obj ) ): ?>
								<div class="collapse show multi-collapse" id="mlm-course-chapter<?php echo $chapter->id; ?>-collapse">
									<div class="mlm-lessons accordion clearfix" id="mlm-course-chapter<?php echo $chapter->id; ?>-accordion">
										<?php foreach( $lesson_obj as $lesson ): ?>
											<?php
											$lesson_data	= maybe_unserialize( $lesson->course_data );
											$content		= html_entity_decode( $lesson_data['content'] );
											$content		= stripslashes( $content );
											$lesson_links	= isset( $lesson_data['links'] ) ? $lesson_data['links'] : array();
											?>
											<div class="lesson-item my-1 clearfix">
												<div class="lesson-header clearfix" id="mlm-lesson-heading-<?php echo $lesson->id; ?>">
													<a href="#" class="d-block p-1 text-dark font-12" data-toggle="collapse" data-target="#mlm-lesson-collapse-<?php echo $lesson->id; ?>" aria-expanded="false" aria-controls="mlm-lesson-collapse-<?php echo $lesson->id; ?>">
														<span class="lesson-title d-block font-12 bold-600">
															<?php if( $permission || $lesson_data['status'] == 'free' ): ?>
																<span class="icon icon-arrow-down2 float-right ml-1"></span>
															<?php else: ?>
																<span class="icon icon-lock float-right ml-1"></span>
															<?php endif; ?>
															<?php echo $lesson_data['title']; ?>
														</span>
														<span class="lesson-text d-block font-10 bold-300"><?php echo $lesson_data['text']; ?></span>
													</a>
												</div>
												<div id="mlm-lesson-collapse-<?php echo $lesson->id; ?>" class="collapse" aria-labelledby="mlm-lesson-heading-<?php echo $lesson->id; ?>" data-parent="#mlm-course-chapter<?php echo $chapter->id; ?>-accordion">
													<div class="p-2 text-justify clearfix">
														<?php if( $permission || $lesson_data['status'] == 'free' ): ?>

															<?php if( ! empty( $content ) ): ?>
																<div class="mb-3 clearfix">
																	<?php echo apply_filters( 'the_content', $content ); ?>
																</div>
															<?php endif; ?>

															<?php if( ! empty( $lesson_links ) ): ?>
																<ul class="list-group list-group-flush m-0 p-0">
																	<?php foreach( $lesson_links as $lk ): ?>
																		<li class="list-group-item p-1">
																			<a target="_blank" href="<?php echo $lk['file']; ?>" class="text-dark font-12">
																				<?php echo $lk['name']; ?>
																			</a>
																		</li>
																	<?php endforeach; ?>
																</ul>
															<?php endif; ?>

														<?php else: ?>
															<div class="alert alert-danger font-12 m-0"><?php _e( 'You need to purchase the course to view this content.', 'mlm' ); ?></div>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php else: ?>
								<div class="alert alert-warning m-0"><?php _e( 'This article has no lessons yet.', 'mlm' ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>