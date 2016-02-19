<?php
/*------------------------------------*\
    Unit Controller
\*------------------------------------*/

class UnitController extends ApplicationController
{

	public static function show( $post )
	{
		$unit = new Unit( $post );
		$unit->touch();
		$unit->calculate_progress();
		return $unit;
	}

	public static function wrapup( $post )
	{
		$unit = new Unit( $post );
		$unit->touch();

		add_filter( 'single_template', array( 'UnitController', 'wrapup_template_redirect' ), 10, 1 );

		return $unit;
	}

	public static function wrapup_template_redirect( $single_template )
	{
		return parent::template_redirect( $single_template, "single-unit-wrapup.php" );
	}

}
