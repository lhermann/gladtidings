<?php
/**
 * Register Custom Post Type 'Course' 
 */
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


/**
 * Register Custom Post Type 'Lesson'
 */
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

/**
 * Register Custom Post Type 'Quizz'
 */
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
 * Register Custom Taxonomy 'Course'
 * (1) Remove Tag-cloud
 */
function add_taxonomy_course() {

	$labels = array(
		'name'                       => _x( 'Courses', 'Taxonomy General Name', 'gladtidings' ),
		'singular_name'              => _x( 'Course', 'Taxonomy Singular Name', 'gladtidings' ),
		'menu_name'                  => __( 'Courses', 'gladtidings' ),
		'all_items'                  => __( 'All Courses', 'gladtidings' ),
		'parent_item'                => __( 'Parent Course', 'gladtidings' ),
		'parent_item_colon'          => __( 'Parent Course:', 'gladtidings' ),
		'new_item_name'              => __( 'New Course Name', 'gladtidings' ),
		'add_new_item'               => __( 'Add New Course', 'gladtidings' ),
		'edit_item'                  => __( 'Edit Course', 'gladtidings' ),
		'update_item'                => __( 'Update Course', 'gladtidings' ),
		'view_item'                  => __( 'View Course', 'gladtidings' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'gladtidings' ),
		'add_or_remove_items'        => __( 'Add or remove courses', 'gladtidings' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'gladtidings' ),
		'popular_items'              => null, // (1)
		'search_items'               => __( 'Search Courses', 'gladtidings' ),
		'not_found'                  => __( 'Not Found', 'gladtidings' ),
		'items_list'                 => __( 'Course list', 'gladtidings' ),
		'items_list_navigation'      => __( 'Course list navigation', 'gladtidings' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => false,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'tax-course', array( 'lesson', 'quizz' ), $args );

}
add_action( 'init', 'add_taxonomy_course', 0 );


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


/**
 * This function is triggered whenever a post of the type 'course' is created/updated
 * It checks all the Objects that have been assoziated with the given Course and updates
 *		its terms, i.e. adds a term with the same name as the Course to the Object
 */
function save_lesson_assoziation( $post_id, $post_object ) {
	if( $post_object->post_type !== 'course' ) return;
	
	// if current post_name doesn't exist as term, create it
	$x_term_title = $post_object->post_title; 
	$x_term_name = $post_object->post_name;
	$x_taxonomy = 'tax-'.$post_object->post_type;
	if ( !term_exists( $x_term_title, $x_taxonomy ) ) {
		$return = wp_insert_term( 
			$x_term_title, 
			$x_taxonomy, 
			$args = array(
					'slug' => $x_term_name
				)
		);
	}

	// get all objects (lessons and quizzes) already assoziated with the term
	$objects_by_term = get_objects_by_term( $x_term_name, $x_taxonomy, 'object_id' );
	$objects_array = array();
	foreach ( $objects_by_term as $value ) {
		$objects_array[] = (int)$value['object_id'];
	}

	// loop through the units
	foreach ( reset($_POST['acf']) as $unit ) {
		if ( !isset($unit) || next($unit) == '' ) continue; // prevent running into errors

		// loop through the items
		foreach ( current($unit) as $item ) {
			if ( in_array( reset($item), array( 'lesson_video', 'lesson_quizz' ) ) ) {
				/*
				 * If there is a object ID -> unset the ID in the array
				 * If there is NO object ID -> wp_set_object_terms
				 * If there are object ID's left -> remove term from the list
				 */
				$object_id = (int)end($item);
				if( in_array( $object_id, $objects_array ) ) {
					$temp_key = array_search( $object_id, $objects_array );
					unset( $objects_array[$temp_key] );
				} else {
					wp_set_object_terms( end($item), $x_term_name, $x_taxonomy );
				}
			}
		}
	}

	//remove the term from each object that has been removed from the list
	foreach ( $objects_array as $object_id ) {
		$current_terms = wp_get_object_terms( $object_id, $x_taxonomy, array( 'fields' => 'slugs' ) );
		$temp_key = array_search( $x_term_name, $current_terms );
		unset( $current_terms[$temp_key] );
		wp_set_object_terms( $object_id, $current_terms, $x_taxonomy );
	}
}
add_action( 'save_post', 'save_lesson_assoziation', 9, 2 );



/**
 * Helper function to get all the objects assoziated with a term
 * E.g.: Get all Objects with the term 'bible-basics'
 */
function get_objects_by_term( $term_slug, $taxonomy_slug, $select_value = '*' ) {
	global $wpdb;
	$querystr = "
		SELECT $select_value
		FROM $wpdb->term_relationships
		JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
		JOIN $wpdb->terms ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id
		WHERE $wpdb->terms.slug = '$term_slug'
		AND $wpdb->term_taxonomy.taxonomy = '$taxonomy_slug'
	";
	$result = $wpdb->get_results( $querystr, ARRAY_A );
	return $result;	
}


/*
 * Add Taxonomy Filter to Admin List for Taxonomies
 *
 * Source: https://wordpress.org/support/topic/add-taxonomy-filter-to-admin-list-for-my-custom-post-type
 */
add_action('restrict_manage_posts', 'restrict_lesson_by_course');
add_filter('parse_query', 'convert_id_to_term_in_query');

function restrict_lesson_by_course() {
	global $typenow;
	$post_types = array( 'lesson', 'quizz' ); // change HERE
	$taxonomy = 'tax-course'; // change HERE
	foreach ( $post_types as $post_type ) {
		if ( $typenow == $post_type ) {
			$selected = isset( $_GET[$taxonomy] ) ? $_GET[$taxonomy] : '';
			$info_taxonomy = get_taxonomy( $taxonomy );
			wp_dropdown_categories( array(
				'show_option_all' => __("Show all {$info_taxonomy->label}", "gladtidings"),
				'taxonomy' => $taxonomy,
				'name' => $taxonomy,
				'orderby' => 'name',
				'selected' => $selected,
				'show_count' => true,
				'hide_empty' => true,
			));
		}
	}
}

function convert_id_to_term_in_query( $query ) {
	global $pagenow;
	$post_types = array( 'lesson', 'quizz' ); // change HERE
	$taxonomy = 'tax-course'; // change HERE
	$q_vars = &$query->query_vars;
	foreach ( $post_types as $post_type ) {
		if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
			$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
			$q_vars[$taxonomy] = $term->slug;
		}
	}
}


