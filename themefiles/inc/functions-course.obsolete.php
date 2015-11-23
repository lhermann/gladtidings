<?php
// Register Custom Post Type
function add_post_type_lesson() {

	$labels = array(
		'name'                  => _x( 'Lessons', 'Post Type General Name', 'gladtidings' ),
		'singular_name'         => _x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ),
		'menu_name'             => __( 'Courses', 'gladtidings' ),
		'name_admin_bar'        => __( 'Lesson', 'gladtidings' ),
		'parent_item_colon'     => __( 'Parent Lesson:', 'gladtidings' ),
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
		'description'           => __( 'Individual Lessons (Videos or Quizzes)', 'gladtidings' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'page-attributes' ),
		'taxonomies'            => array( 'course' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-learn-more',
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
		'popular_items'              => null, // (1) __( 'Popular Courses', 'gladtidings' ),
		'search_items'               => __( 'Search Courses', 'gladtidings' ),
		'not_found'                  => __( 'Not Found', 'gladtidings' ),
		'items_list'                 => __( 'Course list', 'gladtidings' ),
		'items_list_navigation'      => __( 'Course list navigation', 'gladtidings' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'course', array( 'lesson' ), $args );

}
add_action( 'init', 'add_taxonomy_course', 0 );

/*
 * Add Taxonomy Filter to Admin List for Taxonomies
 *
 * Source: https://wordpress.org/support/topic/add-taxonomy-filter-to-admin-list-for-my-custom-post-type
 */
add_action('restrict_manage_posts', 'restrict_lesson_by_course');
add_filter('parse_query', 'convert_id_to_term_in_query');

function restrict_lesson_by_course() {
	global $typenow;
	$post_type = 'lesson'; // change HERE
	$taxonomy = 'course'; // change HERE
	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show all {$info_taxonomy->label}", "gladtidings"),
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => true,
			'hide_empty' => true,
		));
	};
}

function convert_id_to_term_in_query( $query ) {
	global $pagenow;
	$post_type = 'lesson'; // change HERE
	$taxonomies = array( 'course' ); // change HERE
	$q_vars = &$query->query_vars;
	foreach ( $taxonomies as $taxonomy ) {
		if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
			$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
			$q_vars[$taxonomy] = $term->slug;
		}
	}
}

/**
 * Remove standard meta box for taxonomy 'course' selection
 */
if (is_admin()) {
	function remove_meta_boxes() {
		remove_meta_box('coursediv', 'lesson', 'side');
	}
	add_action( 'admin_menu', 'remove_meta_boxes' );
}

/**
 * Add/Remove the Post Type Lesson List Columns
 */
function add_remove_lesson_columns( $columns ) {
	$columns = array(
		"cb" 				=> "<input type=\"checkbox\" />",
		"type" 				=> '',
		"title" 			=> __( 'Title' ),
		"l-order" 			=> '<span title="'.__( 'Order', 'gladtidings' ).'" class="dashicons dashicons-editor-ol"></span>',
		"taxonomy-course"	=> __( 'Course', 'gladtidings' ),
		"date" 				=> __( 'Date' )
	);
    return $columns;
}
add_filter('manage_edit-lesson_columns', 'add_remove_lesson_columns', 5);


/**
 * Modify the Post Type Lesson List Columns
 */
function modify_lesson_custom_columns($column) {
	global $post, $wpdb;
	switch ($column) {
		case 'type':
			switch ( get_field( 'type') ) {
				case 'video':
				default:
					print( '<span class="dashicons dashicons-video-alt2"></span>' );
					break;
				case 'quizz':
					print( '<span class="dashicons dashicons-welcome-write-blog"></span>' );
					break;
				case 'headline':
					print( '<span class="dashicons dashicons-editor-paragraph"></span>' );
					break;
			}
			break;
		case 'l-order':
			print( $post->menu_order );
			break;
		case 'taxonomy-course':

			break;
	}
}
add_action("manage_lesson_posts_custom_column", "modify_lesson_custom_columns");

/**
 * Modify the Taxonomy Course List Columns
 * (1) Remove 'Description' column
 */
function add_remove_course_columns( $columns ) {
	$columns = array(
		"cb" 		=> "<input type=\"checkbox\" />",
		"c-order" 	=> '<span title="'.__( 'Order', 'gladtidings' ).'" class="dashicons dashicons-editor-ol"></span>',
		"name" 		=> __( 'Name' ),
		"slug"		=> __( 'Slug' ),
		"posts" 	=> __( 'Count' )
	);
    return $columns;
}
add_filter('manage_edit-course_columns', 'add_remove_course_columns', 5);

/**
 * Modify the Taxonomy Course List Columns
 */
function modify_course_custom_columns( $value, $column_name, $id ) {
	global $taxonomy;
	$term = get_term_by( 'id', $id, $taxonomy );
	switch ( $column_name ) {
		case 'c-order':
			if ( $term->parent == 0 ) {
				print( '<span class="dashicons dashicons-welcome-learn-more"></span>' );
			} else {
				print( 'Unit '.get_field( 'course-order', 'course_'.$id ) );
			}
			break;
	}
}
add_action( "manage_course_custom_column", "modify_course_custom_columns", 5, 3 );


// Register the columns as sortable
function lesson_sortable_columns( $columns ) {
	$var = ( isset( $_GET['taxonomy'] ) ? 'c-order' : 'l-order' );
	$columns[$var] = $var;
	return $columns;
}
add_filter("manage_edit-lesson_sortable_columns", 'lesson_sortable_columns');
add_filter("manage_edit-course_sortable_columns", 'lesson_sortable_columns');

// Make the column "O" (Order) order correctly
function lesson_courses_ordering( $clauses, $wp_query ) {
	global $wpdb;
	//var_dump($wp_query);
 	if ( isset( $wp_query->query['orderby'] ) && $wp_query->query['orderby'] == 'l-order' ) {
 		$clauses['orderby'] = 'wp_posts.menu_order '.strtoupper( $wp_query->get('order') );
	}
	return $clauses;
}
add_filter( 'posts_clauses', 'lesson_courses_ordering', 10, 2 );



function test123( $v1, $v2 = null, $v3 = null ) {
	var_dump( $v1 );
	//return $v1;
	//var_dump( $v1, $v2, $v3, $_POST ); die();
	//var_dump( $v1, $v2, $v3 ); die();
}
//add_action( "edit_terms", "test123", 10, 3 );
//add_filter( "manage_edit-course_columns", "test123", 10, 3 ); //!!!
//add_filter( "edit_terms", "test123", 10, 3 );
//add_action( "before-course-table", 'test123', 5, 3 );
//add_filter( "manage_6_columns", "test123", 10, 3 );
//remove_action( "after-course-table", "course" );




