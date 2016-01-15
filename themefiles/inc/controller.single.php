<?php
/*------------------------------------*\
    Single Controller
\*------------------------------------*/

class GTView extends GTGlobal
{

	function __construct( $object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );
	}

	/*=======================*\
		Functions
	\*=======================*/



}