<?php
/*------------------------------------*\
    Unit Module
\*------------------------------------*/

// Include Trait
include_once( 'init_status_trait.php' );
include_once( 'questions_trait.php' );

class Exam extends Application
{

	function __construct( $post )
	{
		parent::__construct( $post );

		$this->questions_init( $post );
	}

	/*=======================*\
		Protected Functions
	\*=======================*/

	// Use trait
	use init_status;

	/*=======================*\
		Public Functions
	\*=======================*/

	public function is_done()
	{
		return $this->passed;
	}

	// Use trait
	use questions;

	/**
	 * Returns the ID of the course
	 */
	public function course_id()
	{
		return $this->parent()->ID;
	}

}
