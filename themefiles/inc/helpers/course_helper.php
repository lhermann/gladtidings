<?php
/*------------------------------------*\
    Course Helper
\*------------------------------------*/


/**
 * Get the course batch src, or the standard placeholder
 */
// function h_get_course_batch( $course_id, $size = 'full' ) {
// 	$src = wp_get_attachment_image_src( get_field( 'img_course_badge', $course_id ), $size );
// 	return $src ? $src[0] : get_template_directory_uri().'/img/course-batch-placeholder.png';
// }

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
	$start_label = $node->type == 'unit' ? __('Start Unit', 'gladtidings') : __('Start Exam', 'gladtidings');

	switch ( $node->status_num ) {
		case 1:  $button = '<span class="label label--theme">'           . __('Coming Soon' , 'gladtidings') . '</span>'; break;
		case 2:  $button = '<span class="label label--theme">'           . __('Locked'      , 'gladtidings') . '</span>'; break;
		case 3:  $button = '<span class="btn btn--theme btn--small">'    . $start_label                      . '</span>'; break;
		case 4:  $button = '<span class="btn btn--success btn--small">'  . __('Continue'    , 'gladtidings') . '</span>'; break;
		case 5:  $button = '<span class="btn btn--unstress btn--small">' . __('Review'      , 'gladtidings') . '</span>'; break;
		default: $button = '';
	}
	return $button;
}

/**
 * Meta States:
 * <span class="color--success">Completed</span>
 * <span class="color--locked">Locked: Complete "%s" first</span>
 * <span class="color--primary">Coming soon: 01/01/2016</span>
 */
function h_node_meta( $node )
{
	switch ( $node->status_num ) {
		case 1:  $output = '&bull; <span class="color--primary t-comp-text">' .          __('Coming soon', 'gladtidings') . ': ' . $node->release_date . '</span>'; break;
		case 2:  $output = '&bull; <span class="color--locked t-comp-text">'  . sprintf( __('Locked: Complete "%s" first', 'gladtidings'), $node->unlock_dependency->title ) . '</span>'; break;
		case 5:  $output = '&bull; <span class="color--success">'             .          __('Completed', 'gladtidings') . '</span>'; break;
		default: $output = '';
	}
	return $output;
}

/**
 * Node footer Paragraph
 * -> get number of videos and lessons
 */
function h_node_footer( $node )
{
	if( $node->status_num < 2 ) return '';

	$return = sprintf( '<span class="fi fi-video"></span> %1$s %2$s',
		$node->num_lessons(),
		_n( 'Lesson', 'Lessons', $node->num_lessons(), 'gladtidings' )
	);

	if( $node->num_quizzes() ) {
		$return .= sprintf( '&nbsp; <span class="fi fi-clipboard-pencil"></span> %1$s %2$s',
			$node->num_quizzes(),
			_n( 'Quizz', 'Quizzes', $node->num_quizzes(), 'gladtidings' )
		);
	}

	return $return;
}
