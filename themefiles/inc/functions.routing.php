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
 * 	'view' 			introduction|question|evaluation
 *	'course-name'	slug
 * 	'unit-position'	int
 */
function gladtidings_get_variables() {
	add_rewrite_tag('%view%', '([^&]+)');
	add_rewrite_tag('%course-name%', '([^&]+)');
	add_rewrite_tag('%unit-position%', '([0-9]{1,})');
	// flush_rewrite_rules();
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
		"course/(.?.+?)/unit/([0-9]{1,})/lesson/([^/]+)/?$" => "index.php?lesson=".$wp_rewrite->preg_index(3)."&course-name=".$wp_rewrite->preg_index(1)."&unit-position=".$wp_rewrite->preg_index(2),
		//quizz
		"course/(.?.+?)/unit/([0-9]{1,})/quizz/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(3)."&course-name=".$wp_rewrite->preg_index(1)."&unit-position=".$wp_rewrite->preg_index(2)."&view=".$wp_rewrite->preg_index(4),
		// unit
		"course/(.?.+?)/unit/([0-9]{1,})/?$" => "index.php?unit=".$wp_rewrite->preg_index(2)."&course-name=".$wp_rewrite->preg_index(1),
		// exam
		"course/(.?.+?)/exam/([^/]+)(?:/(introduction|question|evaluation))?/?$" => "index.php?quizz=".$wp_rewrite->preg_index(2)."&course-name=".$wp_rewrite->preg_index(1)."&view=".$wp_rewrite->preg_index(3)
		// course
		// "(.?.+?)(?:/([0-9]+))?/?$" => "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2)
	);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

	// root to:
	// $wp_rewrite->rules['(.?.+?)(?:/([0-9]+))?/?$'] = "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2);
}
add_action( 'generate_rewrite_rules', 'gladtidings_rewrite_rules' );


/**
 * Manipulate WP query before it is executed to interpret the unit routing
 */
function gt_unit_routing( $query ) {
	if( $query->is_singular && $query->query_vars['post_type'] == 'unit' ) {
				
		global $wpdb;
		$sql = $wpdb->prepare( 
			"SELECT u.post_name
			 FROM $wpdb->posts c
			 LEFT JOIN $wpdb->gt_relationships r
			 ON c.ID = r.parent_id
			 LEFT JOIN $wpdb->posts u
			 ON r.child_id = u.ID
			 WHERE c.post_name = %s
			 AND r.order = %d;
			", 
			$query->query['course-name'], 
			$query->query['unit']
		);
		$slug = $wpdb->get_var( $sql );
		$query->query_vars['unit'] = $slug;
		$query->query_vars['name'] = $slug;
		
	}
}
add_action( 'pre_get_posts', 'gt_unit_routing' );


/**
 * My very own get_permalink function!
 */
function gt_get_permalink( $post = 0, $parent_course = null, $parent_unit = null ) {
	
	// get post object
	if( empty($post) ) {
		$post = $GLOBALS['post'];
	} elseif( is_numeric($post) ) {
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
			if( !$parent_course ) $parent_course = gt_get_parent_object( $post );
			$permalink = $url . 'course/' . $parent_course->post_name . '/unit/' . ( $post->order ? $post->order : $parent_course->child_order );
			break;

		case 'exam':
			/* structure: http://gladtidings:8888/course/course-slug/exam/quizz-slug */
			if( !$parent_course ) $parent_course = gt_get_parent_object( $post );
			$permalink = $url . 'course/' . $parent_course->post_name . '/exam/' . $post->post_name;
			break;

		case 'lesson':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1/lesson/lesson-slug */
			if( !$parent_unit ) $parent_unit = gt_get_parent_object( $post );
			if( !$parent_course ) $parent_course = gt_get_parent_object( $parent_unit );
			$permalink = $url . 'course/' . $parent_course->post_name . '/unit/' . ( $parent_unit->order ? $parent_unit->order : $parent_course->child_order ) . '/lesson/' . $post->post_name;
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


/**
 * Returns the parent post object. E.g. Course pertaining to the unit.
 * CAUTON: 'order' and 'position' do not belong to the queried object, but to it's child, 
 *         hence it's renamed 'child_order' and 'child_position'.
 */
function gt_get_parent_object( $post ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "gt_relationships";

	$query = "SELECT p.*, r.order child_order, r.position child_position
		FROM $wpdb->posts p
		INNER JOIN $table_name r
		ON r.parent_id = p.ID
		WHERE r.child_id = $post->ID;
	";

	return $wpdb->get_row( $query );
}






