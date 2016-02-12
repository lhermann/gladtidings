<?php
/*------------------------------------*\
    Unit Module
\*------------------------------------*/

// Include Trait
include_once( 'init_status_trait.php' );

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

	/**
	 * Returns the ID of the course
	 */
	public function course_id()
	{
		return $this->parent()->ID;
	}

	public function num_lessons() { return $this->num_children( 'lesson' ); }
	public function num_quizzes() { return $this->num_children( 'quizz' ); }

}
