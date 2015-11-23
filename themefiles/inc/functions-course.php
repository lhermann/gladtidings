<?php

// Register Custom Post Type 'Course'
function add_post_type_course() {

	$labels = array(
		'name'                  => _x( 'Courses', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'gladtidings' ),
		'menu_name'             => __( 'Course', 'gladtidings' ),
		'name_admin_bar'        => __( 'Course', 'gladtidings' ),
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
		'supports'              => array( 'title', 'thumbnail', 'page-attributes', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-learn-more',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'course', $args );

}
add_action( 'init', 'add_post_type_course', 0 );

// Register Custom Post Type 'Lesson'
function add_post_type_lesson() {

	$labels = array(
		'name'                  => _x( 'Lessons', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ),
		'menu_name'             => __( 'Courses', 'gladtidings' ),
		'name_admin_bar'        => __( 'Lesson', 'gladtidings' ),
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
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __( 'Lesson', 'gladtidings' ),
		'description'           => __( 'Individual Videos Lessons', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( 'course' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'course',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'lesson', $args );

}
add_action( 'init', 'add_post_type_lesson', 0 );

// Register Custom Post Type 'Quizz'
function add_post_type_quizz() {

	$labels = array(
		'name'                  => _x( 'Quizzes', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ),
		'menu_name'             => __( 'Courses', 'gladtidings' ),
		'name_admin_bar'        => __( 'Quizz', 'gladtidings' ),
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
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __( 'Quizz', 'gladtidings' ),
		'description'           => __( 'Individual Quizzes', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( 'course' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-write-blog',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'course',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'quizz', $args );

}
add_action( 'init', 'add_post_type_quizz', 0 );

/**
 * Register a custom menu item 'Courses' to handle all three post types 'course', 'lesson' and 'quizz'
 */
function register_custom_menu_page() {

	$slug_base = 'edit.php?post_type=';

	add_menu_page( 'Courses', 'Courses', 'manage_options', $slug_base.'course', false, 'dashicons-welcome-learn-more', 6 );
	add_submenu_page( $slug_base.'course', 'Courses', 'Courses', 'manage_options', $slug_base.'course' );
	//add_submenu_page( $slug_base.'course', 'Add New Course', 'Add New Course', 'manage_options', 'post-new.php?post_type=course' );
	add_submenu_page( $slug_base.'course', 'Lessons', 'Lessons', 'manage_options', $slug_base.'lesson' );
	//add_submenu_page( $slug_base.'course', 'Add New Lesson', 'Add New Lesson', 'manage_options', 'post-new.php?post_type=lesson' );
	add_submenu_page( $slug_base.'course', 'Quizzes', 'Quizzes', 'manage_options', $slug_base.'quizz' );
	//add_submenu_page( $slug_base.'course', 'Add New Quizz', 'Add New Quizz', 'manage_options', 'post-new.php?post_type=quizz' );

}
add_action( 'admin_menu', 'register_custom_menu_page' );




