<?php
/*------------------------------------*\
    Continue Button Helpers
\*------------------------------------*/

function h_continue_btn( $object, $attribute = null )
{
	$function = "h_{$object->type}_continue_btn";
	return $function( $object, $attribute );
}

function h_lesson_continue_btn( $object, $attr = null )
{
	return h_child_of_unit_continue_btn( $object, $attr = null );
}

function h_quizz_continue_btn( $object, $attr = null )
{
	return h_child_of_unit_continue_btn( $object, $attr = null );
}


function h_exam_continue_btn( $object, $attr = null )
{
	return h_child_of_course_continue_btn( $object, $attr = null );
}

function h_unit_continue_btn( $object, $attr = null )
{
	return h_child_of_course_continue_btn( $object, $attr = null );
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
function h_child_of_course_continue_btn( $object, $attr = null )
{
	// Determin next object
	if( $object->type == 'exam' || $object->is_done() ) {

		// Find next sibling or get parent course
		$next = $object->find_sibling( array( 'position' => $object->position + 1 ) );
		if( !$next ) $next = $object->parent();


	} else {

		// Find the first item that is not done
		foreach ( $object->children() as $child ) {
			if( $child->type == 'headline' ) continue;
			if( !$child->is_done() ) {
				$next = $child;
				break;
			}
		}

	}

	// Labeling
	switch ( $next->type ) {
		case 'lesson':
		case 'quizz' : $label = $object->progress() ? __( 'Continue learning', 'gladtidings' ) : __( 'Start learning', 'gladtidings' ); break;
		case 'unit'  : $label = sprintf( __( 'Advance to Unit %d', 'gladtidings' ), $next->order ) . ' <span class="fi fi-arrow-right"></span>'; break;
		case 'exam'  : $label = ( $object->type == 'exam' ? __( 'Take the next Exam', 'gladtidings' ) : __( 'Take the Exam', 'gladtidings' ) ) . ' <span class="fi fi-arrow-right"></span>'; break;
		case 'course': $label = __( 'Return to Course Overview', 'gladtidings' ); break;
	}

	// Define arguments
	$args = array(
		'display' => $label
	);
	switch( $object->type ) {
		case 'exam': $args['class'] = 'btn btn--success'; break;
		case 'unit': $args['class'] = 'layout__item u-pull--right btn btn--success'; break;
	}

	// Return link
	if( !$next ) return;
	return $next->link_to( $args );
}


/**
 * Returns the continue button for lessons and quizzes
 *
 * Labeling:
 *   sibling lesson|quizz -> 'Next'
 *   parent unit          -> 'Return to Unit Overview'
 */
function h_child_of_unit_continue_btn( $object, $attr = null )
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

	// Define arguments
	$args = array(
		'class'   => 'btn btn--success',
		'display' => $label
	);
	if( $attr ) $args['attribute'] = $attr;

	// Return link
	if( !$next ) return;
	return $next->link_to( $args );

}







