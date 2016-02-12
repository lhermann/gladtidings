<?php
/*------------------------------------*\
    Question Helpers
\*------------------------------------*/

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

function h_start_quizz_btn( $object )
{
	if( $object->is_done() ) {

		return $post->type == 'quizz' ? __( 'Repeat Quizz', 'gladtidings' ) : __( 'Repeat Exam', 'gladtidings' );

	} else {

		return $post->type == 'quizz' ? __( 'Start Quizz', 'gladtidings' ) : __( 'Start Exam', 'gladtidings' );

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

function h_related_lesson_btn( $object )
{
	if( !$object || !is_object( $object ) ) return;

	return sprintf( '<a class="btn btn--small btn--theme u-pull--right" href="%1$s" title="%2$s">%3$s</a>',
		gt_get_permalink( $object ),
		$object->title,
		__( 'Review Lesson', 'gladtidings' )
	);
}
