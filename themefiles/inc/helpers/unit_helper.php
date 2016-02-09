<?php
/*------------------------------------*\
    Course Helpers
\*------------------------------------*/

include_once( 'course_helper.php' );

function h_continue_btn( $object )
{

	if( $object->progress() == 100 ) {

		// Determin next object
		$next = $object->find_sibling( array( 'position' => $object->position+1 ) );
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
