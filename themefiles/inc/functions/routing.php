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
	add_rewrite_tag('%course-name%', '([^&]+)');
	add_rewrite_tag('%unit-position%', '([0-9]{1,})');
	add_rewrite_tag('%controller%', '([^&]+)');
	add_rewrite_tag('%action%', '([^&]+)');
	add_rewrite_tag('%view%', '([^&]+)');
	flush_rewrite_rules();
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
		"course/(.?.+?)/unit/([0-9]{1,})/lesson/([^/]+)/?$"

				=> "index.php"
						."?lesson=".$wp_rewrite->preg_index(3)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&unit-position=".$wp_rewrite->preg_index(2)
						."&controller=lesson"
						."&action=show",

		// quizz
		"course/(.?.+?)/unit/([0-9]{1,})/quizz/([^/]+)(?:/(introduction|question|evaluation))?/?$"

				=> "index.php"
						."?quizz=".$wp_rewrite->preg_index(3)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&unit-position=".$wp_rewrite->preg_index(2)
						."&controller=quizz"
						."&action=".$wp_rewrite->preg_index(4),

		// unit
		"course/(.?.+?)/unit/([0-9]{1,})/?$"

				=> "index.php"
						."?unit=".$wp_rewrite->preg_index(2)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&controller=unit"
						."&action=show",

		// exam
		"course/(.?.+?)/exam/([^/]+)(?:/(introduction|question|evaluation))?/?$"

				=> "index.php"
						."?quizz=".$wp_rewrite->preg_index(2)
						."&course-name=".$wp_rewrite->preg_index(1)
						."&controller=exam"
						."&action=".$wp_rewrite->preg_index(3),

		// course
		"course/(.?.+?)/?$"

				=> "index.php"
						."?course=".$wp_rewrite->preg_index(1)
						."&controller=course"
						."&action=show",

	);
	// var_dump($wp_rewrite->rules);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	// $wp_rewrite->rules = array_merge ( $wp_rewrite->rules, $new_rules );
	// var_dump($wp_rewrite->rules);

	// root to:
	// $wp_rewrite->rules['(.?.+?)(?:/([0-9]+))?/?$'] = "index.php?course=".$wp_rewrite->preg_index(1)."&page=".$wp_rewrite->preg_index(2);
}


/**
 * Alter the main wp_query on the home page to fetch 'course' instead of 'post'
 */
function alter_query_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', 'course' );
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
 * Pass in the object
 */
function gt_get_permalink( $object = 0 ) {

	// get object
	$classes = array( 'Course', 'Unit', 'Exam', 'Lesson', 'Quizz' );
	if( !in_array( get_class( $object ), $classes ) ) {

		if( is_numeric( $object ) ) {
			$post = get_post( $object );
		} elseif( get_class( $object ) === 'WP_Post' ) {
			$post = $object;
		} else {
			return false;
		}

		if( $post->post_type === 'quizz' ) $post->post_type = 'exam';

		require_once( dirname( __DIR__ ) . $post->post_type . '.php' );
		$class = ucfirst($post->post_type);
		$object = new $class( $post );

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
			$permalink = get_permalink( $object->ID );
			break;
	}

	return $permalink;
}


/**
 * INPUT:
 *   $input -> Object || ID
 *   %args  -> possible arguments:
 *              'class'     = css class
 *              'title'     = link title="" attribute
 *              'attribute' = any attribute, eg. disabled
 *              'display'   = the link text or label (should be renamed label)
 */
function get_link_to( $object = null, $args = array() )
{
	return sprintf( '<a class="%1$s" href="%2$s" title="%3$s" %4$s>%5$s</a>',
		isset($args['class']) ? $args['class'] : 'a--bodycolor',
		gt_get_permalink( $object ),
		isset($args['title']) ? $args['title'] : __('Permalink to:', 'gladtidings') . ' ' . $object->title,
		isset($args['attribute']) ? $args['attribute'] : '',
		isset($args['display']) ? $args['display'] : $object->title
	);
}


/**
 * Wrapper to print get_link_to()
 */
function print_link_to( $object = null, $args = array() )
{
	print( $this->get_link_to( $object, $args ) );
}


/**
 * Returns the parent post object. E.g. Course pertaining to the unit.
 * CAUTON: 'order' and 'position' belong to the queried object,
 *         while 'child_order' and 'child_position' belong to the child.
 */
// function gt_get_parent_object( $post ) {
// 	global $wpdb;

// 	$query = "SELECT p.*, r2.order, r2.position, r1.order child_order, r1.position child_position
// 		FROM $wpdb->posts p
// 		INNER JOIN $wpdb->gt_relationships r1
// 		ON r1.parent_id = p.ID
// 		INNER JOIN $wpdb->gt_relationships r2
// 		ON r2.child_id = p.ID
// 		WHERE r1.child_id = $post->ID;
// 	";

// 	return $wpdb->get_row( $query );
// }


/**
 * returns the post object with 'order' and 'position'
 */
// function gt_get_object_order( $post ) {
// 	global $wpdb;

// 	$query = "SELECT r.order, r.position
// 		FROM $wpdb->gt_relationships r
// 		WHERE r.child_id = $post->ID;
// 	";

// 	$result = $wpdb->get_row( $query );

// 	$post->order = $result->order;
// 	$post->position = $result->position;

// 	return $post;
// }


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
	if( in_array( $object->post_type, array( 'lesson', 'quizz' ) ) ) {

		wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
		exit();

	}
}

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
