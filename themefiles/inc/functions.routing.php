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


/**
 * Add a query variables
 * 	'view' 			introduction|question|evaluation
 *	'course-name'	slug
 * 	'unit-position'	int
 */
add_action('init', 'gladtidings_get_variables', 10, 0);
function gladtidings_get_variables() {
	add_rewrite_tag('%view%', '([^&]+)');
	add_rewrite_tag('%course-name%', '([^&]+)');
	add_rewrite_tag('%unit-position%', '([0-9]{1,})');
	// flush_rewrite_rules();
}


/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/
 */
add_action( 'generate_rewrite_rules', 'gladtidings_rewrite_rules' );
function gladtidings_rewrite_rules() {
	global $wp_rewrite;

	// add rules
	$new_rules = array(
		// lesson
		"course/(.?.+?)/unit/([0-9]{1,})/lesson/([^/]+)/?$" => "index.php?lesson=".$wp_rewrite->preg_index(3)."&course-name=".$wp_rewrite->preg_index(1)."&unit-position=".$wp_rewrite->preg_index(2),
		// quizz
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


/**
 * Manipulate WP query before it is executed to interpret the unit routing
 */
add_action( 'pre_get_posts', 'gt_unit_routing' );
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

	// exam correction
	if( $post->post_type == 'quizz' && $post->order < 0 ) $post->post_type = 'exam';

	switch ( $post->post_type ) {
		case 'course':
			$permalink = get_permalink( $post );
			break;

		case 'unit':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1 */
			if( !isset($post->order) ) gt_get_object_order( $post );
			if( !$parent_course ) $parent_course = gt_get_parent_object( $post );
			$permalink = $url . 'course/' . $parent_course->post_name . '/unit/' . ( $post->order ? $post->order : $parent_course->child_order );
			break;

		case 'exam':
			/* structure: http://gladtidings:8888/course/course-slug/exam/quizz-slug */
			if( !$parent_course ) $parent_course = gt_get_parent_object( $post );
			$permalink = $url . 'course/' . $parent_course->post_name . '/exam/' . $post->post_name;
			break;

		case 'lesson':
		case 'quizz':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1/lesson/lesson-slug */
			/* structure: http://gladtidings:8888/course/course-slug/unit/1/quizz/quizz-slug */
			if( !isset($parent_unit->order) ) $parent_unit = gt_get_parent_object( $post );
			if( !$parent_course ) $parent_course = gt_get_parent_object( $parent_unit );
			$permalink = $url . "course/{$parent_course->post_name}/unit/{$parent_unit->order}/{$post->post_type}/{$post->post_name}";
			break;

		default:
			$permalink = get_permalink( $post );
			break;
	}

	return $permalink;
}


/**
 * Returns the parent post object. E.g. Course pertaining to the unit.
 * CAUTON: 'order' and 'position' belong to the queried object,
 *         while 'child_order' and 'child_position' belong to the child.
 */
function gt_get_parent_object( $post ) {
	global $wpdb;

	$query = "SELECT p.*, r2.order, r2.position, r1.order child_order, r1.position child_position
		FROM $wpdb->posts p
		INNER JOIN $wpdb->gt_relationships r1
		ON r1.parent_id = p.ID
		INNER JOIN $wpdb->gt_relationships r2
		ON r2.child_id = p.ID
		WHERE r1.child_id = $post->ID;
	";

	return $wpdb->get_row( $query );
}


/**
 * returns the post object with 'order' and 'position'
 */
function gt_get_object_order( $post ) {
	global $wpdb;

	$query = "SELECT r.order, r.position
		FROM $wpdb->gt_relationships r
		WHERE r.child_id = $post->ID;
	";

	$result = $wpdb->get_row( $query );

	$post->order = $result->order;
	$post->position = $result->position;

	return $post;
}


/**
 * Builtin Login Redirect
 */
// add_filter( 'single_template', 'gt_login_redirect', 9, 1 );
// function gt_login_redirect( $single_template )
// {
// 	if( is_user_logged_in() ) return $single_template;

// 	$object = get_queried_object();
// 	if( in_array( $object->post_type, array( 'course', 'unit', 'lesson', 'quizz' ) ) ) {

// 		// redirect to the login.php template if existing
// 		$login_template = locate_template("login.php");
// 		if( $login_template ) return $login_template;

// 	}
// 	return $single_template;
// }


/**
 * Exam Template redirect
 * 'exam' is just an artificial name since it is really a quizz, but it still needs to load a different template
 */
add_filter( 'single_template', 'exam_template_redirect', 10, 1 );
function exam_template_redirect( $single_template )
{
	$object = get_queried_object();

	// bail early: skipp everything but quizzes that have no order
	if( !( $object->post_type == 'quizz' && $object->order < 0 ) ) return $single_template;

	// redirect to the single-exam.php template if existing
	$exam_template = locate_template("single-exam.php");
	if( $exam_template ) {
		return $exam_template;
	} else {
		return $single_template;
	}
}


/**
 * Login Redirect
 */
add_action( 'template_redirect', 'gt_login_redirect' );
function gt_login_redirect()
{

	if( is_user_logged_in() ) return;

	$object = get_queried_object();
	if( in_array( $object->post_type, array( 'course', 'unit', 'lesson', 'quizz' ) ) ) {

		wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
		exit();

	}
}


add_action( 'login_footer', 'gt_login_footer' );
function gt_login_footer()
{
	get_footer();
}

function gt_login_stylesheet() {

    wp_enqueue_style( 'gt-login', get_template_directory_uri() . '/css/login.css', array(), THEMEVERSION );

}
add_action( 'login_enqueue_scripts', 'gt_login_stylesheet' );
