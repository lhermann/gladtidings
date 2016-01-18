<?php
/*------------------------------------*\
    Shared Controller: Item
\*------------------------------------*/

/**
 * Shared functions for Lessons and Quizzes
 */
class GTItem extends GTGlobal
{

	function __construct( &$object )
	{
		
		// call parent __contruct
		parent::__construct( $object );

		// set up unit
		$this->unit = $this->setup_object( $object->parent_id );

		// set up course
		$this->course = $this->setup_object( $this->unit->parent_id );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );
	}

}