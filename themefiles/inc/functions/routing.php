<?php
/*------------------------------------*\
	Modify rewrite rules and routing
\*------------------------------------*/

/**
 * Permalink Structure for Glad Tidings
 *
 * - course
 *      /course/course-slug
 * - unit
 *      /course/course-slug/unit/1
 * - lesson
 *      /course/course-slug/unit/1/lesson/lesson-slug
 * - quizz
 *      /course/course-slug/unit/1/quizz/quizz-slug/question
 * - quizz
 *      /course/course-slug/unit/1/quizz/quizz-slug/evaluation
 * - exam
 *      /course/course-slug/exam/quizz-slug
 * - messages
 *      /messages
 *      /messages/new
 *      /messages/1
 *      /messages/1/edit
 *      /messages/1/delete
 */


/**
 * Add a query variables
 * 	'view' 			introduction|question|evaluation
 *	'course-name'	slug
 * 	'unit-position'	int
 */
add_action('init', 'gladtidings_get_variables', 10, 0);
function gladtidings_get_variables() {
	add_rewrite_tag('%course-name%', '([^&]+)');
	add_rewrite_tag('%unit-position%', '([0-9]{1,})');
	add_rewrite_tag('%message%', '([^&]+)');
	add_rewrite_tag('%controller%', '([^&]+)');
	add_rewrite_tag('%action%', '([^&]+)');
	add_rewrite_tag('%view%', '([^&]+)');
	// flush_rewrite_rules();
}


/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/
 *
 * NOTE: Standard WP rewrite rules are turned off for each custom post type.
 *       Thus these restful routes here are the only way to reach them
 */
add_action( 'generate_rewrite_rules', 'gladtidings_rewrite_rules' );
function gladtidings_rewrite_rules() {
	global $wp_rewrite;

	// define new rules
	$new_rules = array(
		// lesson
		"course/(.?.+?)/unit/([0-9]{1,})/lesson/([0-9]+)(?:/([^/]+?))?/?$"

				=> "index.php"
						."?p=".$wp_rewrite->preg_index(3)
						."&post_type=lesson"
						."&course-name=".$wp_rewrite->preg_index(1)
						."&unit-position=".$wp_rewrite->preg_index(2)
						."&controller=lesson"
						."&action=".$wp_rewrite->preg_index(4),

		// quizz
		"course/(.?.+?)/unit/([0-9]{1,})/quizz/([^/]+)(?:/([^/]+?))?/?$"

				=> "index.php"
						."?quizz=".$wp_rewrite->preg_index(3)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&unit-position=".$wp_rewrite->preg_index(2)
						."&controller=quizz"
						."&action=".$wp_rewrite->preg_index(4),

		// unit
		"course/(.?.+?)/unit/([0-9]{1,})(?:/([^/]+?))?/?$"

				=> "index.php"
						."?unit=".$wp_rewrite->preg_index(2)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&controller=unit"
						."&action=".$wp_rewrite->preg_index(3),

		// exam
		"course/(.?.+?)/exam/(.?.+?)(?:/([^/]+?))?/?$"

				=> "index.php"
						."?exam=".$wp_rewrite->preg_index(2)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&controller=exam"
						."&action=".$wp_rewrite->preg_index(3),

		// course
		"course/(.?.+?)(?:/([^/]+?))?/?$"

				=> "index.php"
						."?course=".$wp_rewrite->preg_index(1)
						."&controller=course"
						."&action=".$wp_rewrite->preg_index(2),

		// user
		"user(?:/([^/]+?))?/?$"

				=> "index.php"
						."?controller=user"
						."&action=".$wp_rewrite->preg_index(1),

		// message
		"messages(?:/(new))?/?$"

				=> "index.php"
						."?controller=message"
						."&action=".$wp_rewrite->preg_index(1),

		"messages/([0-9]{1,})(?:/([^/]+?))?/?$"

				=> "index.php"
						."?controller=message"
						."&message=".$wp_rewrite->preg_index(1)
						."&action=".$wp_rewrite->preg_index(2),

	);

	// Add new rules to existing rules
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}


/**
 * Instantiate the Controller
 * filename syntax: "inc/controller.single-{post_type}.php"
 * class syntax: "Single{Post_type}"
 */
add_action( 'wp', 'instantiate_the_controller', 10, 1 );
function instantiate_the_controller( $wp ) {

	// Bail for admin area
	if( is_admin() ) return;

	// get paths
	$model_path      = dirname( __DIR__ ).'/models/';
	$controller_path = dirname( __DIR__ ).'/controllers/';
	$helper_path     = dirname( __DIR__ ).'/helpers/';

	// get route
	$controller = get_query_var( 'controller', false );
	$action = get_query_var( 'action' ) ? get_query_var( 'action' ) : 'show';
	$model = $controller;

	// include model and controller and helper
	             require_once( $model_path . 'user.php' );
	             require_once( $model_path . 'application.php' );
	if( $model ) require_once( $model_path . $model . '.php' );

	                  require( $controller_path . 'application_controller.php' );
	if( $controller ) require( $controller_path . $controller . '_controller.php' );

	                  // require( $helper_path . 'application_helper.php' ); // This one is now always included
	if( $controller ) require( $helper_path . $controller . '_helper.php' );

	// Instantiate the user
	global $user;
	$user = new User;

	// call controller action statically and pass get_queried_object() as argument
	if( $controller ) {

		global $post, $posts, $wp_query;

		$controller_class = ucfirst($controller).'Controller';

		// save some debug information
		$wp_query->debug = [
			'model'      => ucfirst($controller),
			'controller' => $controller_class,
			'action'     => $action
		];

		// var_dump( $wp_query, method_exists( $controller_class, $action ) );

		if( method_exists( $controller_class, $action ) && ( $post || $posts ) ) {

			// if action exists: call the action as static method
			switch ( $action ) {
				case 'index': $posts = $controller_class::$action( $posts ); break;
				default:      $post  = $controller_class::$action( $post  ); break;
			}

		} else {


			// if action doesn't exist: show 404 page
			$wp_query->init();
			$wp_query->set_404();

			// wp_redirect( gt_get_permalink( $post ) );
			// exit;

		}

	}

}


/**
 * Add routes to body class like so:
 * 'model-user action-settings'
 */
add_filter( 'body_class', 'add_routes_to_body_class' );
function add_routes_to_body_class( $classes ) {
	global $wp_query;

	$classes[] = 'model-' . strtolower( $wp_query->debug['model'] );
	$classes[] = 'action-' . strtolower( $wp_query->debug['action'] );

	return $classes;
}


/**
 * Alter the main wp_query on the home page to fetch 'course' instead of 'post'
 */
function alter_query_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() && !isset($query->query['controller']) ) {
		$query->set( 'post_type', 'course' );
		$query->set( 'controller', 'course' );
		$query->set( 'action', 'index' );
	}
}
add_action( 'pre_get_posts', 'alter_query_home' );


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
			$query->get('course-name'),
			$query->get('unit')
		);
		$slug = $wpdb->get_var( $sql );

		if( $slug ) {
			$query->set( 'unit', $slug );
			$query->set( 'name', $slug );
		}

	}
}


/**
 * My very own get_permalink function!
 * INPUT: Object || ID to which to link
 */
function gt_get_permalink( $object = 0, $after_url = '' ) {

	// get object
	if( is_object( $object ) ) {

		$classes = array( 'Course', 'Unit', 'Exam', 'Lesson', 'Quizz' );
		$class = get_class( $object );
		if( !in_array( $class, $classes ) ) {
			if( $class === 'WP_Post' ) {
				$object = gt_instantiate_object( $object );
			} else {
				return false;
			}
		}

	} else {

		if( is_numeric( $object ) ) {
			$object = gt_instantiate_object( get_post( $object ) );
		} else {
			return false;
		}

	}

	// get url base
	$url = esc_url( home_url( '/' ) );

	switch ( $object->type ) {
		case 'course':
			/* structure: http://gladtidings:8888/course/course-slug */
			$permalink = $url . 'course/' . $object->slug;
			break;

		case 'unit':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1 */
			$permalink = $url . 'course/' . $object->parent()->slug . '/unit/' . $object->order;
			break;

		case 'exam':
			/* structure: http://gladtidings:8888/course/course-slug/exam/quizz-slug */
			$permalink = $url . 'course/' . $object->parent()->slug . '/exam/' . $object->slug;
			break;

		case 'lesson':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1/lesson/1 */
			$permalink = $url . 'course/' . $object->course()->slug . '/unit/' . $object->parent()->order . '/' . $object->type . '/' . $object->ID;
			break;

		case 'quizz':
			/* structure: http://gladtidings:8888/course/course-slug/unit/1/quizz/quizz-slug */
			$permalink = $url . 'course/' . $object->course()->slug . '/unit/' . $object->parent()->order . '/' . $object->type . '/' . $object->slug;
			break;

		default:
			$permalink = get_permalink( $object );
			break;
	}

	return esc_url( $permalink . '/' . $after_url );
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
// add_action( 'template_redirect', 'gt_login_redirect' );
// function gt_login_redirect()
// {

// 	if( is_user_logged_in() ) return;

// 	$object = get_queried_object();
// 	if( in_array( $object->post_type, array( 'lesson', 'quizz' ) ) ) {

// 		wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
// 		exit();

// 	}
// }

/**
 * Home Redirect
 * evaluating the constant GT_HOME and redirect the landing page accordingly
 * constant GT_HOME syntax: 'course:slug'
 */
if( defined( 'GT_HOME' ) ) {
	add_action( 'parse_query', 'gt_home_redirect' );
	function gt_home_redirect()
	{
		if( is_front_page() ) {
			$redirect = explode( ':', GT_HOME );
			wp_redirect( esc_url( home_url( '/' ) ) . $redirect[0] . '/' .  $redirect[1] );
		}
	}
}
