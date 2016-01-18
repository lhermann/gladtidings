<?php
/*------------------------------------*\
    Single Quizz Controller
\*------------------------------------*/

// require shared functions for lessons and quizzes
require( 'controller.shared.item.php' );


class GTView extends GTItem
{

	function __construct( &$object )
	{
		
		// call parent __contruct
		parent::__construct( $object );

	}

}