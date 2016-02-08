<?php
/*------------------------------------*\
    Unit Module
\*------------------------------------*/

// Include Trait
include_once( 'unit_exam_trait.php' );

class Unit extends Application
{

	// function __construct( $post )
	// {

	// 	parent::__construct( $post );

	// }

	/*=======================*\
		Protected Functions
	\*=======================*/

	// Use trait
	use init_status;

	/*=======================*\
		Public Functions
	\*=======================*/

	public function num_lessons() { return $this->num_children( 'lesson' ); }
	public function num_quizzes() { return $this->num_children( 'quizz' ); }

}
