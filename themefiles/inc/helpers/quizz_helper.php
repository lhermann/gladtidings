<?php
/*------------------------------------*\
    Quizz Helpers
\*------------------------------------*/

include_once( 'continue_button_helper.php' );
include_once( 'questions_helper.php' );

function h_node_label( $node )
{
	switch ( $node->type ) {
		case 'lesson': return __( 'Lesson', 'gladtidings' );
		case 'quizz' : return __( 'Quizz', 'gladtidings' );
	}
}
