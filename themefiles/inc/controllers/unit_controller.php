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

}
