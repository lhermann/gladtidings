<?php
/*------------------------------------*\
    Register Posts and Taxonomies
\*------------------------------------*/

/**
 * Register Custom Post Types 'Course', 'Lessson', 'Quizz' and 'Exam'
 * As well as the virtual post types 'Unit' and 'Headline'
 */
add_action( 'init', 'add_post_types_and_taxonomies', 0 );
function add_post_types_and_taxonomies()
{

	/**
	 * Register Custom Post Types
	 * These are the ones that will have a admin menu
	 */

	/* Common Labels */
	$labels = array(
		'add_new'               => __( 'Add New', 'gladtidings' ),
		'not_found'             => __( 'Not found', 'gladtidings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gladtidings' ),
	);

	/* Common Arguments */
	$args = array(
		'supports'              => array( 'title' ),
		'public'                => false,
		'show_ui'               => true, //defaults to 'public'
		'show_in_menu'          => true, //defaults to 'show_ui'
		'show_in_admin_bar'     => true, // defaults to 'show_in_menu'
		'can_export'            => true,
		'has_archive'           => true,
		'publicly_queryable'    => true,
		'rewrite'               => false,
	);

	/* Course */
	$course_labels = $labels + array(
		'name'                  => _x( 'Courses', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'gladtidings' ),
		'all_items'             => __( 'All Courses', 'gladtidings' ),
		'add_new_item'          => __( 'Add New Course', 'gladtidings' ),
		'new_item'              => __( 'New Course', 'gladtidings' ),
		'edit_item'             => __( 'Edit Course', 'gladtidings' ),
		'update_item'           => __( 'Update Course', 'gladtidings' ),
		'view_item'             => __( 'View Course', 'gladtidings' ),
		'search_items'          => __( 'Search Courses', 'gladtidings' ),
		'items_list'            => __( 'Course list', 'gladtidings' ),
		'items_list_navigation' => __( 'Course list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter course list', 'gladtidings' ),
	);
	$course_args = $args + array(
		'label'                 => __( 'Course', 'gladtidings' ),
		'description'           => __( 'Courses consisting of separate Units with Videos and Quizzes', 'gladtidings' ),
		'labels'                => $course_labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
		// 'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-learn-more',
	);
	register_post_type( 'course', $course_args );

	/* Lesson */
	$lesson_labels = $labels + array(
		'name'                  => _x( 'Lessons', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ),
		'all_items'             => __( 'All Lessons', 'gladtidings' ),
		'add_new_item'          => __( 'Add Lesson', 'gladtidings' ),
		'new_item'              => __( 'New Lesson', 'gladtidings' ),
		'edit_item'             => __( 'Edit Lesson', 'gladtidings' ),
		'update_item'           => __( 'Update Lesson', 'gladtidings' ),
		'view_item'             => __( 'View Lesson', 'gladtidings' ),
		'search_items'          => __( 'Search Lesson', 'gladtidings' ),
		'items_list'            => __( 'Lessons list', 'gladtidings' ),
		'items_list_navigation' => __( 'Lessons list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter Lessons list', 'gladtidings' ),
	);
	$lesson_args = $args + array(
		'label'                 => __( 'Lesson', 'gladtidings' ),
		'description'           => __( 'Individual Videos Lessons', 'gladtidings' ),
		'labels'                => $lesson_labels,
		// 'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt2',
	);
	register_post_type( 'lesson', $lesson_args );

	/* Quizzes */
	$quizz_labels = $labels + array(
		'name'                  => _x( 'Quizzes', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ),
		'all_items'             => __( 'All Quizzes', 'gladtidings' ),
		'add_new_item'          => __( 'Add Quizz', 'gladtidings' ),
		'new_item'              => __( 'New Quizz', 'gladtidings' ),
		'edit_item'             => __( 'Edit Quizz', 'gladtidings' ),
		'update_item'           => __( 'Update Quizz', 'gladtidings' ),
		'view_item'             => __( 'View Quizz', 'gladtidings' ),
		'search_items'          => __( 'Search Quizz', 'gladtidings' ),
		'items_list'            => __( 'Quizzes list', 'gladtidings' ),
		'items_list_navigation' => __( 'Quizzes list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter Quizzes list', 'gladtidings' ),
	);
	$quizz_args = $args + array(
		'label'                 => __( 'Quizz', 'gladtidings' ),
		'description'           => __( 'Individual Quizzes', 'gladtidings' ),
		'labels'                => $quizz_labels,
		// 'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-write-blog',
	);
	register_post_type( 'quizz', $quizz_args );

	/* Exams */
	$exam_labels = $labels + array(
		'name'                  => _x( 'Exams', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Exam', 'Post Type Singular Name', 'gladtidings' ),
		'all_items'             => __( 'All Exams', 'gladtidings' ),
		'add_new_item'          => __( 'Add Exam', 'gladtidings' ),
		'new_item'              => __( 'New Exam', 'gladtidings' ),
		'edit_item'             => __( 'Edit Exam', 'gladtidings' ),
		'update_item'           => __( 'Update Exam', 'gladtidings' ),
		'view_item'             => __( 'View Exam', 'gladtidings' ),
		'search_items'          => __( 'Search Exam', 'gladtidings' ),
		'items_list'            => __( 'Exams list', 'gladtidings' ),
		'items_list_navigation' => __( 'Exams list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter Exams list', 'gladtidings' ),
	);
	$exam_args = $args + array(
		'label'                 => __( 'Exam', 'gladtidings' ),
		'description'           => __( 'Individual Exams', 'gladtidings' ),
		'labels'                => $exam_labels,
		// 'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-write-blog',
	);
	register_post_type( 'exam', $exam_args );


	/**
	 * Register Virtual Custom Post Types
	 * These are just for internal reference, they have no admin ui and are created in gt_relationships
	 */

	/* Common Arguments */
	$args = array(
		'supports'              => array( 'title' ),
		'public'                => false,
		'show_ui'               => false,
		'can_export'            => false,
		'has_archive'           => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
	);

	/* Units */
	$unit_labels = array(
		'name'                  => _x( 'Units', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Unit', 'Post Type Singular Name', 'gladtidings' ),
	);
	$unit_args = $args + array(
		'label'                 => __( 'Unit', 'gladtidings' ),
		'description'           => __( 'Units consisting of Videos and Quizzes', 'gladtidings' ),
		'labels'                => $unit_labels,
	);
	register_post_type( 'unit', $unit_args );


	/* Headline */
	$headline_labels = array(
		'name'                  => _x( 'Headlines', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Headline', 'Post Type Singular Name', 'gladtidings' ),
	);
	$headline_args = $args + array(
		'label'                 => __( 'Headline', 'gladtidings' ),
		'description'           => __( 'Individual Headlines', 'gladtidings' ),
		'labels'                => $headline_labels,
	);
	register_post_type( 'headline', $headline_args );


	/**
	 * Add custom post status for unit
	 *  - 'success' - the unit is sucessfully finished (user specific)
	 *  - 'active'  - the unit was started, but is not finished (user specific)
	 *  - 'publish' - the unit is open, but not yet started (wp builtin)
	 *  - 'locked'  - the unit is visible, but not accessible
	 *  - 'coming'  - the unit is anounced for a future date, but visible (other than builtin 'future')
	 *  - 'draft'   - the unit is not visible (wp builtin)
	 */
	register_post_status( 'locked',  array( 'public' => true, 'label' => __( 'Locked', 'gladtidings' ) ) );
	register_post_status( 'coming',  array( 'public' => true, 'label' => __( 'Coming soon', 'gladtidings' ) ) );


	/**
	 * Create a variable in $wpdb for the wp_gt_relationships table
	 */
	global $wpdb;
	$wpdb->gt_relationships = $wpdb->prefix . "gt_relationships";


}
