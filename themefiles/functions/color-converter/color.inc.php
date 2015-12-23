<?php

// Require color manipulation library
require_once ( "rgb_hsl_converter.inc.php" );


/**
 * Some custom addition to make a color text-save
 * That means: the text should have enough contrast to white
 *
 * INPUT:
 * - hex value of the color (eg. $bbbbbb)
 * - threshold (optional) - minimum darkness (0 is black and 1 is white)
 */
function textsave_hex( $hex, $threshold = 0.4 ) { 
	$hsl = hex2hsl( $hex );
	if( $hsl[2] > $threshold ) {
		$hsl[2] = $threshold;
		return hsl2hex( $hsl );
	} else {
		return $hex;
	}
}