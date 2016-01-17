<?php
/*------------------------------------*\
    Helper Functions
\*------------------------------------*/


/**
 * Get the course batch src, or the standard placeholder
 */
function gt_get_course_batch( $post, $size = 'full' ) {
	$src = wp_get_attachment_image_src( get_field( 'img_course_badge', $post->ID ), $size );
	return $src ? $src[0] : get_template_directory_uri().'/img/course-batch-placeholder.png';
}


/**
 * Converst regular spaces to non-breaking spaces html-entities
 */
function spaces_to_nbsp( $string ) {
	return str_replace( ' ', '&nbsp;', $string);
}




