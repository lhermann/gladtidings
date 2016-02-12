<?php
/*------------------------------------*\
    Lesson Module
\*------------------------------------*/

// Include Trait
include_once( 'get_course_trait.php' );

class Lesson extends Application
{

	/*=======================*\
		Public Functions
	\*=======================*/

	public function is_done()
	{
		return $this->touched ? true : false;
	}

	// Use trait
	use get_course;

}
