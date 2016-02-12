<?php
/*------------------------------------*\
    Continue Button Helpers
\*------------------------------------*/

/**
 * Returns the continue button for lessons and quizzes
 *
 * Labeling:
 *   sibling lesson|quizz -> 'Next'
 *   parent unit          -> 'Return to Unit Overview'
 */
function h_lesson_continue_btn( $object )
{

	// Determin next object
	$next = $object->find_sibling( array( 'order' => $object->order + 1 ) );
	if( !$next ) $next = $object->parent();

	// Labeling
	switch ( $next->type ) {
		case 'lesson':
		case 'quizz' : $label = __( 'Next', 'gladtidings' ) . ' <span class="fi fi-arrow-right"></span>'; break;
		case 'unit'  : $label = __( 'Return to Unit Overview', 'gladtidings' ); break;
	}

	$args = array(
		'class'   => 'btn btn--success',
		'display' => $label
	);

	return $next->link_to( $args );
}

/**
 * Returns the continue button for lessons and quizzes
 *
 * Labeling:
 *   sibling lesson|quizz -> 'Next'
 *   parent unit          -> 'Return to Unit Overview'
 */
function h_quizz_continue_btn( $object, $attr = null )
{

	// Determin next object
	$next = $object->find_sibling( array( 'order' => $object->order + 1 ) );
	if( !$next ) $next = $object->parent();

	// Labeling
	switch ( $next->type ) {
		case 'lesson':
		case 'quizz' : $label = __( 'Next', 'gladtidings' ) . ' <span class="fi fi-arrow-right"></span>'; break;
		case 'unit'  : $label = __( 'Return to Unit Overview', 'gladtidings' ); break;
	}

	$args = array(
		'class'   => 'btn btn--success',
		'display' => $label
	);
	if( $attr ) $args['attribute'] = $attr;

	return $next->link_to( $args );
}

function h_exam_continue_btn( $object )
{
	return h_unit_continue_btn( $object );
}

/**
 * Returns the continue button for units
 *
 * Labeling:
 *   sibling unit       -> 'Advance to Unit %d'
 *   sibling exam       -> 'Take the Exam'
 *   parent course      -> 'Return to Course Overview'
 *   child lesson|quizz -> 'Continue learning' || 'Start learning'
 */
function h_unit_continue_btn( $object )
{

	if( $object->progress() == 100 ) {

		// Determin next object
		$next = $object->find_sibling( array( 'position' => $object->position + 1 ) );
		if( !$next ) $next = $object->parent();

		// Labeling
		switch ( $next->type ) {
			case 'unit'  : $label = sprintf( __( 'Advance to Unit %d', 'gladtidings' ), $next->order ); break;
			case 'exam'  : $label =          __( 'Take the Exam', 'gladtidings' ); break;
			case 'course': $label =          __( 'Return to Course Overview', 'gladtidings' ); break;
		}

	} else {

		// find the first item that is not done
		foreach ( $object->children() as $child ) {
			if( $child->type == 'headline' ) continue;
			if( $child->status == 'publish' ) {
				$next = $child;
				break;
			}
		}

		// Labeling
		$label = $object->progress() ? __( 'Continue learning', 'gladtidings' ) : __( 'Start learning', 'gladtidings' );
	}

	if( !isset($next) ) return;

	$args = array(
		'class'   => 'layout__item u-pull--right btn btn--success',
		'display' => $label
	);

	return $next->link_to( $args );
}
