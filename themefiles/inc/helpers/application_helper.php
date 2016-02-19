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
	$path = realpath( str_replace( get_bloginfo('url'), '.', $url ) );
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
	if( is_numeric( $post ) ) {
		$post = get_post( $post );
	} elseif( !is_object( $post ) ) {
		return NULL;
	} elseif( get_class($post) === 'WP_Post' ) {
		return NULL;
	}

	// Include Model
	require_once( dirname( __DIR__ ) . '/models/' . $post->post_type . '.php' );

	// Instantiate object
	$class = ucfirst( $post->post_type );
	return new $class( $post );
}

/**
 * Get an array with all the breadcrumbs for the current site
 */
function gt_get_breadcrumbs( $post )
{
	// evaluate home redirect constant
	$gt_home = defined( 'GT_HOME' ) ? explode( ':', GT_HOME )[0] : false;

	$return = array();

	switch ( $post->type ) {
		case 'lesson': $return[] = $post->course();
		case 'unit':   $return[] = $post->parent();
		case 'course': $return[] = $post;
		default:
			array_unshift( $return , 'home' );
	}

	return $return;

}

/**
 * Print the Link for one breadcrumb
 * Basically a fancy wrapper for print_link_to()
 */
function gt_crumb_link( $crumb )
{
	$args = array();

	switch ( $crumb->type ) {
		case 'lesson':
			$args['display'] = sprintf( __('Lesson %d', 'gladtidings'),  $crumb->order );
			break;
		case 'unit':
			$args['display'] = sprintf( __('Unit %d', 'gladtidings'),  $crumb->order );
			break;
	}

	return $crumb->link_to( $args );
}
