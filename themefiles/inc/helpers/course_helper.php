<?php
/*------------------------------------*\
    Course Helper
\*------------------------------------*/


/**
 * Get the course batch src, or the standard placeholder
 */
function h_get_course_batch( $course_id, $size = 'full' ) {
	$src = wp_get_attachment_image_src( get_field( 'img_course_badge', $course_id ), $size );
	return $src ? $src[0] : get_template_directory_uri().'/img/course-batch-placeholder.png';
}

/**
 * Link coresponding to the node
 */
function h_node_link( $node )
{
	return $node->status_num >= 3 ? '<a href="' . gt_get_permalink( $node ) . '" title="' . __('Permalink to:', 'gladtidings') . ' ' . $node->title . '">' : '';
}

/**
 * Button
 */
function h_node_button( $node )
{
	$start_label = $node->post_type == 'unit' ? __('Start Unit', 'gladtidings') : __('Start Exam', 'gladtidings');

	$button = '';
	switch ( $node_status_num ) {
		case 1: $button = '<span class="label label--theme">'           . __('Coming Soon' , 'gladtidings') . '</span>'; break;
		case 2: $button = '<span class="label label--theme">'           . __('Locked'      , 'gladtidings') . '</span>'; break;
		case 3: $button = '<span class="btn btn--theme btn--small">'    . $start_label	                    . '</span>'; break;
		case 4: $button = '<span class="btn btn--success btn--small">'  . __('Continue'    , 'gladtidings') . '</span>'; break;
		case 5: $button = '<span class="btn btn--unstress btn--small">' . __('Review'      , 'gladtidings') . '</span>'; break;
	}
	return $button;
}
