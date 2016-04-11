<?php
/*------------------------------------*\
    Node Helpers
\*------------------------------------*/

/**
 * Link coresponding to the node
 */
function h_node_link( $node )
{
	return $node->status_num >= 3 ? '<a href="' . gt_get_permalink( $node ) . '" title="' . __('Permalink to:', 'gladtidings') . ' ' . $node->title . '">' : '';
}

function h_node_label( $node )
{
	return $node->title;
}

/**
 * Button
 */
function h_node_button( $node, $class = "" )
{
	$start_label = $node->type == 'unit' ? __('Start Unit', 'gladtidings') : __('Start Exam', 'gladtidings');

	switch ( $node->status_num ) {
		case 1:  $class .= ' label label--theme';           $label = __('Coming Soon' , 'gladtidings'); break;
		case 2:  $class .= ' label label--theme';           $label = __('Locked'      , 'gladtidings'); break;
		case 3:  $class .= ' btn btn--theme btn--small';    $label = $start_label;                      break;
		case 4:  $class .= ' btn btn--success btn--small';  $label = __('Continue'    , 'gladtidings'); break;
		case 5:  $class .= ' btn btn--unstress btn--small'; $label = __('Review'      , 'gladtidings'); break;
		default: return;
	}
	return '<span class="' . $class . '">' . $label . '</span>';
}

/**
 * Meta States:
 * <span class="color--success">Completed</span>
 * <span class="color--locked">Locked: Complete "%s" first</span>
 * <span class="color--primary">Coming soon: 01/01/2016</span>
 */
function h_node_meta( $node, $bull = false )
{
	switch ( $node->status_num ) {
		case 1:  $output = '<span class="color--primary t-comp-text">' .          __('Coming soon', 'gladtidings') . ': ' . $node->release_date . '</span>'; break;
		case 2:  $output = '<span class="color--locked t-comp-text">'  . sprintf( __('Locked: Complete "%s" first', 'gladtidings'), $node->unlock_dependency->title ) . '</span>'; break;
		case 5:  $output = '<span class="color--success">'             .          __('Completed', 'gladtidings') . '</span>'; break;
		default: return '';
	}
	return ( $bull ? '&bull; ' : '' ) . $output;
}

/**
 * Node footer Paragraph
 * -> get number of videos and lessons
 */
function h_node_footer( $node, $bull = false )
{
	if( $node->type != 'unit' ) return;

	if( $node->status_num < 2 ) return '';

	$return = sprintf( '<span class="fi fi-video"></span> %1$s %2$s',
		$node->num_lessons(),
		_n( 'Lesson', 'Lessons', $node->num_lessons(), 'gladtidings' )
	);

	if( $node->num_quizzes() ) {
		$return .= sprintf( ' &bull; <span class="fi fi-clipboard-pencil"></span> %1$s %2$s',
			$node->num_quizzes(),
			_n( 'Quizz', 'Quizzes', $node->num_quizzes(), 'gladtidings' )
		);
	}

	return $return . ( $bull ? '&bull; ' : '' );
}
