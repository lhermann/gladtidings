<?php
/*------------------------------------*\
	Modify rewrite rules and routing
\*------------------------------------*/

/**
 * Permalink Structure for Glad Tidings
 * 
 * - course - http://gladtidings:8888/course/course-slug
 * - unit   - http://gladtidings:8888/course/course-slug/unit/1
 * - lesson - http://gladtidings:8888/course/course-slug/unit/1/lesson/lesson-slug
 * - quizz  - http://gladtidings:8888/course/course-slug/unit/1/quizz/quizz-slug/question
 * - quizz  - http://gladtidings:8888/course/course-slug/unit/1/quizz/quizz-slug/evaluation
 * - exam   - http://gladtidings:8888/course/course-slug/exam/quizz-slug
 */

// http://gladtidings:8888/bible-basics/lesson/1

// global $wp_rewrite;
// var_dump( $wp_rewrite );

/**
 * Add a query variables
 * 	'view' 		introduction|question|evaluation
 *	'of-course'	slug
 * 	'of-unit'	int
 */
function gladtidings_get_variables() {
	add_rewrite_tag('%view%', '([^&]+)');
	add_rewrite_tag('%of-course%', '([^&]+)');
	add_rewrite_tag('%of-unit%', '([0-9]{1,})');
}
add_action('init', 'gladtidings_get_variables', 10, 0);



/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/ 
 */
function gladtidings_rewrite_rules() {
	global $wp_rewrite;

	// add rules
	$new_rules = array(
		// lesson
		"course/(.?.+?)/unit/([0-9]{1,})/lesson/([^/]+)/?$" => "index.php?lesson=".$wp_rewrite->preg_index(3)."&of-course=".$wp_rewrite->preg_index(1)."&of-unit=".$wp_rewrite->preg_index(2),
		//quizz
		"course/(.?.+?)/unit/([0-9]{1,})/quizz/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(3)."&of-course=".$wp_rewrite->preg_index(1)."&of-unit=".$wp_rewrite->preg_index(2)."&view=".$wp_rewrite->preg_index(4),
		// unit
		"course/(.?.+?)/unit/([0-9]{1,})/?$" => "index.php?unit=".$wp_rewrite->preg_index(2)."&of-course=".$wp_rewrite->preg_index(1),
		// exam
		"course/(.?.+?)/exam/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(2)."&of-course=".$wp_rewrite->preg_index(1)."&view=".$wp_rewrite->preg_index(3)
		// course
		// "(.?.+?)(?:/([0-9]+))?/?$" => "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2)
	);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

	// root to:
	// $wp_rewrite->rules['(.?.+?)(?:/([0-9]+))?/?$'] = "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2);
}
add_action( 'generate_rewrite_rules', 'gladtidings_rewrite_rules' );


/**
 * Manipulate query before it is executed
 */
function your_function_name( $query ) {
	// var_dump( $query ); die();
	// b23d68cc
	$slug = 'my-first-unit';
	$query->query_vars['unit'] = $slug;
	$query->query_vars['name'] = $slug;
}
// add_action( 'pre_get_posts', 'your_function_name' );


/**
 * My very own get_permalink function!
 */
function gt_get_permalink( $post = 0 ) {
	
	// get post object
	if( empty($post) ) {
		$post = $GLOBALS['post'];
	} elseif( is_numerical($post) ) {
		$post = get_post( $post );
	} elseif( !is_object($post) ) {
		return false;
	}

	$url = esc_url( home_url( '/' ) );

	switch ( $post->post_type ) {
		case 'course':
			$permalink = get_permalink( $post );
			break;

		case 'unit':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1 */
			$parent = gt_get_parent_object( $post );
			$permalink = $url . 'course/' . $parent->post_name . '/unit/' . $parent->order;
			break;

		case 'lesson':
			$permalink = get_permalink( $post );
			break;

		case 'quizz':
			$permalink = get_permalink( $post );
			break;

		default:
			$permalink = get_permalink( $post );
			break;
	}

	return $permalink;
}


function gt_get_parent_object( $post ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "gt_relationships";

	$query = "SELECT *
		FROM $wpdb->posts p
		INNER JOIN $table_name r
		ON r.parent_id = p.ID
		WHERE r.child_id = $post->ID;
	";

	return $wpdb->get_row( $query );
}






