<?php
/*------------------------------------*\
    Quizz Module
\*------------------------------------*/

// Include Trait
include_once( 'get_course_trait.php' );
include_once( 'questions_trait.php' );

class Quizz extends Application
{

	function __construct( $post )
	{
		parent::__construct( $post );

		$this->questions_init( $post );
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	// Use trait
	use get_course;
	use questions;

}
