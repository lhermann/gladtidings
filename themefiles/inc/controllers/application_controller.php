<?php
/*------------------------------------*\
    Application Controller
\*------------------------------------*/

class ApplicationController
{

	protected static function template_redirect( $current_template, $new_template )
	{
		$template = locate_template( $new_template );
		if( $template ) {
			return $template;
		} else {
			return $current_template;
		}
	}

}
