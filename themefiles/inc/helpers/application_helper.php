<?php
/*------------------------------------*\
    Helper Functions
\*------------------------------------*/


/**
 * Converst regular spaces to non-breaking spaces html-entities
 */
function spaces_to_nbsp( $string ) {
	return str_replace( ' ', '&nbsp;', $string);
}


/**
 * Get filesize for a wordpress attachment url and format the display to be human readable
 */
function gt_get_filesize( $url )
{
	// get the filesize in bytes
	$path = realpath( str_replace( get_bloginfo('url'), '.', $attachment['url'] ) );
	if( !is_file($path) ) return false;
	$bytes = filesize( $path );

	// calculate appropriate display
	if ($bytes < 1024) {
		$type = 'KB';
		$filesize = round($bytes / 1024, 2);
	} elseif ($bytes < 1048576) {
		$type = 'KB';
		$filesize = round($bytes / 1024, 0);
	} elseif ($bytes < 1073741824) {
		$type = 'MB';
		$filesize = round($bytes / 1048576, 0);
	} else {
		$type = 'GB';
		$filesize = round($bytes / 1073741824, 0);
	};

	// return
	if( $filesize <= 0 ){
		return $filesize = 0;
	} else {
		return $filesize.' '.$type;
	};
}


/**
 * Instantiate an object for Glad Tidings
 * INPUT: 'WP_Post' object or 'stdClass' object from query
 * OUTPUT: Instantiated object with full Model functionality
 */
function gt_instantiate_object( $post )
{
	// Correction for exam
	$type = $post->post_type === 'quizz' ? 'exam' : $post->post_type;

	// Include Model
	require_once( dirname( __DIR__ ) . '/models/' . $post->post_type . '.php' );

	// Instantiate object
	$class = ucfirst($post->post_type);
	return new $class( $post );
}
