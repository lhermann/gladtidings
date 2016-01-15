<?php
/*------------------------------------*\
    Single Unit Controller
\*------------------------------------*/

class GTView extends GTGlobal
{

	function __construct( &$object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// set up course
		$this->course = get_posts( array( 'name' => get_query_var( 'course-name' ), 'post_type' => 'course' ) )[0];

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );
	}

	/*=======================*\
		Unit Functions
	\*=======================*/


	/*=======================*\
		Hero Functions
	\*=======================*/

	


}