<?php
/*------------------------------------*\
    Register Posts and Taxonomies
\*------------------------------------*/

/**
 * Define Constants
 */
define( TAX_COURSE, 'tax-course' );
define( TAX_UNIT, 'tax-unit' );

/**
 * Register Custom Post Types 'Course', 'Lessson' and 'Quizz'
 * Register Taxonomies 'tax-course' and 'tax-unit'
 */
function add_post_types_and_taxonomies() {


	/**
	 * Register Custom Post Type 'Course'
	 */
	$labels = array(
		'name'                  => _x( 'Courses', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'gladtidings' ),
		// 'menu_name'             => __( 'Course', 'gladtidings' ),
		// 'name_admin_bar'        => __( 'Course', 'gladtidings' ),
		'all_items'             => __( 'All Courses', 'gladtidings' ),
		'add_new_item'          => __( 'Add New Course', 'gladtidings' ),
		'add_new'               => __( 'Add New', 'gladtidings' ),
		'new_item'              => __( 'New Course', 'gladtidings' ),
		'edit_item'             => __( 'Edit Course', 'gladtidings' ),
		'update_item'           => __( 'Update Course', 'gladtidings' ),
		'view_item'             => __( 'View Course', 'gladtidings' ),
		'search_items'          => __( 'Search Courses', 'gladtidings' ),
		'not_found'             => __( 'Not found', 'gladtidings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gladtidings' ),
		'items_list'            => __( 'Course list', 'gladtidings' ),
		'items_list_navigation' => __( 'Course list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter course list', 'gladtidings' ),
	);
	$rewrite = array(
		'slug'                  => 'course',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __( 'Course', 'gladtidings' ),
		'description'           => __( 'Courses consisting of separate Units with Videos and Quizzes', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'page-attributes', ),
		//'taxonomies'            => array( TAX_UNIT ),
		// 'hierarchical'          => false, // defaults to false
		'public'                => false,
		'show_ui'               => true, //defaults to 'public'
		// 'show_in_menu'          => true, //defaults to 'show_ui'
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-learn-more',
		'show_in_admin_bar'     => true, // defaults to 'show_in_menu'
		// 'show_in_nav_menus'     => true, //defaults to 'public'
		'can_export'            => true,
		'has_archive'           => true,
		// 'exclude_from_search'   => false, //defaults to 'public'
		'publicly_queryable'    => true,
		'rewrite'               => false,
		// 'capability_type'       => 'post', // defaults to 'post'
	);
	register_post_type( 'course', $args );

	/**
	 * Register Virtual Custom Post Type 'Unit'
	 */
	$labels = array(
		'name'                  => _x( 'Units', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Unit', 'Post Type Singular Name', 'gladtidings' ),
	);
	$rewrite = array(
		'slug'                  => 'unit',
		'with_front'            => true,
		'pages'                 => false,
	);
	$args = array(
		'label'                 => __( 'Unit', 'gladtidings' ),
		'description'           => __( 'Units consisting of Videos and Quizzes', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'public'                => false,
		'show_ui'               => false,
		'can_export'            => false,
		'has_archive'           => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
	);
	register_post_type( 'unit', $args );

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
	 * Register Virtual Custom Post Type 'Headline'
	 */
	$labels = array(
		'name'                  => _x( 'Exams', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Exam', 'Post Type Singular Name', 'gladtidings' ),
	);
	$args = array(
		'label'                 => __( 'Exam', 'gladtidings' ),
		'description'           => __( 'Individual Exams', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'public'                => false,
		'show_ui'               => false,
		'can_export'            => false,
		'has_archive'           => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
	);
	register_post_type( 'exam', $args );


	/**
	 * Register Custom Post Type 'Lesson'
	 */
	$labels = array(
		'name'                  => _x( 'Lessons', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ),
		// 'menu_name'             => __( 'Courses', 'gladtidings' ),
		// 'name_admin_bar'        => __( 'Lesson', 'gladtidings' ),
		'all_items'             => __( 'All Lessons', 'gladtidings' ),
		'add_new_item'          => __( 'Add Lesson', 'gladtidings' ),
		'add_new'               => __( 'Add New', 'gladtidings' ),
		'new_item'              => __( 'New Lesson', 'gladtidings' ),
		'edit_item'             => __( 'Edit Lesson', 'gladtidings' ),
		'update_item'           => __( 'Update Lesson', 'gladtidings' ),
		'view_item'             => __( 'View Lesson', 'gladtidings' ),
		'search_items'          => __( 'Search Lesson', 'gladtidings' ),
		'not_found'             => __( 'Not found', 'gladtidings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gladtidings' ),
		'items_list'            => __( 'Lessons list', 'gladtidings' ),
		'items_list_navigation' => __( 'Lessons list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter Lessons list', 'gladtidings' ),
	);
	$rewrite = array(
		'slug'                  => 'lesson',
		'with_front'            => true,
		'pages'                 => false
	);
	$args = array(
		'label'                 => __( 'Lesson', 'gladtidings' ),
		'description'           => __( 'Individual Videos Lessons', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		// 'taxonomies'            => array( TAX_COURSE, TAX_UNIT ),
		'public'                => false,
		'show_ui'               => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt2',
		'can_export'            => true,
		'has_archive'           => false,
		'publicly_queryable'    => true,
		'rewrite'               => false
	);
	register_post_type( 'lesson', $args );


	/**
	 * Register Custom Post Type 'Quizz'
	 */
	$labels = array(
		'name'                  => _x( 'Quizzes', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ),
		// 'menu_name'             => __( 'Courses', 'gladtidings' ),
		// 'name_admin_bar'        => __( 'Quizz', 'gladtidings' ),
		'all_items'             => __( 'All Quizzes', 'gladtidings' ),
		'add_new_item'          => __( 'Add Quizz', 'gladtidings' ),
		'add_new'               => __( 'Add New', 'gladtidings' ),
		'new_item'              => __( 'New Quizz', 'gladtidings' ),
		'edit_item'             => __( 'Edit Quizz', 'gladtidings' ),
		'update_item'           => __( 'Update Quizz', 'gladtidings' ),
		'view_item'             => __( 'View Quizz', 'gladtidings' ),
		'search_items'          => __( 'Search Quizz', 'gladtidings' ),
		'not_found'             => __( 'Not found', 'gladtidings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gladtidings' ),
		'items_list'            => __( 'Quizzes list', 'gladtidings' ),
		'items_list_navigation' => __( 'Quizzes list navigation', 'gladtidings' ),
		'filter_items_list'     => __( 'Filter Quizzes list', 'gladtidings' ),
	);
	$rewrite = array(
		'slug'                  => 'quizz',
		'with_front'            => true,
		'pages'                 => false
	);
	$args = array(
		'label'                 => __( 'Quizz', 'gladtidings' ),
		'description'           => __( 'Individual Quizzes', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		// 'taxonomies'            => array( TAX_COURSE, TAX_UNIT ),
		'public'                => false,
		'show_ui'               => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-write-blog',
		'can_export'            => true,
		'has_archive'           => false,
		'publicly_queryable'    => true,
		'rewrite'               => false
	);
	register_post_type( 'quizz', $args );


	/**
	 * Register Virtual Custom Post Type 'Headline'
	 */
	$labels = array(
		'name'                  => _x( 'Headlines', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Headline', 'Post Type Singular Name', 'gladtidings' ),
	);
	$args = array(
		'label'                 => __( 'Headline', 'gladtidings' ),
		'description'           => __( 'Individual Headlines', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'public'                => false,
		'show_ui'               => false,
		'can_export'            => false,
		'has_archive'           => false,
		'rewrite'               => false,
	);
	register_post_type( 'headline', $args );

	/**
	 * Create a variable in $wpdb fpr the wp_gt_relationships table
	 */
	global $wpdb;
	$wpdb->gt_relationships = $wpdb->prefix . "gt_relationships";


}
add_action( 'init', 'add_post_types_and_taxonomies', 0 );

/**
 * Remove the meta box on the admin post edit screen for taxonomy input
 */
if (is_admin()) :
	function remove_edit_screen_meta_boxes() {
		remove_meta_box('tax-coursediv', 'lesson', 'side');
		remove_meta_box('tax-unitdiv', 'lesson', 'side');
		remove_meta_box('tax-coursediv', 'quizz', 'side');
		remove_meta_box('tax-unitdiv', 'quizz', 'side');
	}
	add_action( 'admin_menu', 'remove_edit_screen_meta_boxes' );
endif;
