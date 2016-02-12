<?php
/*------------------------------------*\
    Quizz Helpers
\*------------------------------------*/

include_once( 'continue_button_helper.php' );

/**
 * Returns a properly formated subtitle
 * Needed because of translatable strings
 */
function h_subtitle( $object )
{
	switch ( get_query_var( 'action' ) ) {
		default:           return __( 'Introduction', 'gladtidings' );
		case 'question':   return __( 'Question', 'gladtidings' ).' '.($object->get_step()+1);
		case 'evaluation': return __( 'Evaluation', 'gladtidings' );
	}
}

/**
 * Returns the Progress bar
 */
function h_stepper_bar( $object )
{

	$progress_pills = array();
	for ($i=1; $i <= $object->required_questions; $i++) {
		$progress_pills[] = sprintf( '<div class="progress__item %s"></div>',
			$i < $object->get_step() ? 't-comp-bg' : ''
		);
	}

	return sprintf( '<div class="progress progress--pills" title="%s">%s</div>',
		sprintf( __( '%d/%d Questions done', 'gladtidings' ), $object->get_step(), $object->required_questions ),
		implode( $progress_pills )
	);
}

function h_related_lesson_btn( $id )
{
	if( !$id ) return;

	global $post;
	$object = $post->find_sibling( array( 'ID' => $id ) );

	if( !$object ) return;

	return sprintf( '<a class="btn btn--small btn--theme u-pull--right" href="%1$s" title="%2$s">%3$s</a>',
		gt_get_permalink( $object ),
		$object->title,
		sprintf( __( 'Review Lesson %s', 'gladtidings' ), $object->order )
	);
}
