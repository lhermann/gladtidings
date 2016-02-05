<?php
/*------------------------------------*\
    Course Helper
\*------------------------------------*/


/**
 * Get the course batch src, or the standard placeholder
 */
function gt_get_course_batch( $course_id, $size = 'full' ) {
	$src = wp_get_attachment_image_src( get_field( 'img_course_badge', $course_id ), $size );
	return $src ? $src[0] : get_template_directory_uri().'/img/course-batch-placeholder.png';
}
