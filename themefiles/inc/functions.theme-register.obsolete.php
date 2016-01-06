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
		'publicly_queryable'    => true, //defaults to 'public'
		'rewrite'               => $rewrite,
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
		'rewrite'               => $rewrite,
	);
	register_post_type( 'unit', $args );

	/**
	 * Add custom post status for unit
	 */
	register_post_status( 'coming', array( 'label' => __( 'Coming soon', 'gladtidings' ), 'public' => true ) );
	register_post_status( 'locked', array( 'label' => __( 'Locked', 'gladtidings' ), 'public' => true ) );



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
		'rewrite'               => $rewrite
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
		'rewrite'               => $rewrite
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
	);
	register_post_type( 'headline', $args );





	/**
	 * Register Custom Taxonomy 'Course'
	 */
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
		'popular_items'              => null, // Remove Tag-cloud
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
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		//'query_var'                  => true,
		//'rewrite'                    => array( 'slug' => 'course' ),
	);
	// register_taxonomy( TAX_COURSE, array( 'lesson', 'quizz' ), $args );

	/**
	 * Register Custom Taxonomy 'Unit'
	 */
	$labels = array(
		'name'                       => _x( 'Units', 'Taxonomy General Name', 'gladtidings' ),
		'singular_name'              => _x( 'Unit', 'Taxonomy Singular Name', 'gladtidings' ),
		'menu_name'                  => __( 'Units', 'gladtidings' ),
		'all_items'                  => __( 'All Units', 'gladtidings' ),
		'parent_item'                => __( 'Parent Unit', 'gladtidings' ),
		'parent_item_colon'          => __( 'Parent Unit:', 'gladtidings' ),
		'new_item_name'              => __( 'New Unit Name', 'gladtidings' ),
		'add_new_item'               => __( 'Add New Unit', 'gladtidings' ),
		'edit_item'                  => __( 'Edit Unit', 'gladtidings' ),
		'update_item'                => __( 'Update Unit', 'gladtidings' ),
		'view_item'                  => __( 'View Unit', 'gladtidings' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'gladtidings' ),
		'add_or_remove_items'        => __( 'Add or remove courses', 'gladtidings' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'gladtidings' ),
		'popular_items'              => null, // Remove Tag-cloud
		'search_items'               => __( 'Search Units', 'gladtidings' ),
		'not_found'                  => __( 'Not Found', 'gladtidings' ),
		'items_list'                 => __( 'Unit list', 'gladtidings' ),
		'items_list_navigation'      => __( 'Unit list navigation', 'gladtidings' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'query_var'                  => true,
		// 'rewrite'                    => array( 'slug' => 'unit' ),
	);
	// register_taxonomy( TAX_UNIT, array( 'lesson', 'quizz' ), $args );

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
// add_action( 'admin_menu', 'register_custom_menu_page' );


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
	$taxonomies = array( TAX_COURSE, TAX_UNIT ); // change HERE
	foreach ( $post_types as $post_type ) {
		foreach ( $taxonomies as $taxonomy ) {
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
}

function convert_id_to_term_in_query( $query ) {
	global $pagenow;
	$post_types = array( 'lesson', 'quizz' ); // change HERE
	$taxonomies = array( TAX_COURSE, TAX_UNIT ); // change HERE
	$q_vars = &$query->query_vars;
	foreach ( $post_types as $post_type ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
				$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
				$q_vars[$taxonomy] = $term->slug;
			}
		}
	}
}


/**
 * This function is triggered whenever a post of the type 'course' is created/updated
 * It checks all the Objects that have been assoziated with the given Course and updates
 *		its terms, i.e. adds a term with the same name as the Course to the Object
 * Objectives:
 * (1) Create a slug for each unit
 * (2) Set up a term to represent the Course
 * (3) Set up a counter for videos and quizzes
 * (4) Loop through the Units
 * (5) Set up a term to represent each Unit
 * (6) Loop through the Items in each Unit
 * (7) Count
 * (8) Add/remove terms to the item (object)
 * (9) Save count as term meta
 */
function update_course_and_unit_tax( $post_id, $post_object ) {
	if( $post_object->post_type !== 'course' ) return; // escape if it is not a course object
	if( $post_object->post_status == 'auto-draft' ) return; // escape if it is an automatic save
	if( !isset($_POST['acf']) || !reset($_POST['acf']) ) return; // escape if there are no units

	//get the repeater field key and populate the unit slug field					/* 1 */
	$r_key = key( $_POST['acf'] );
	array_walk( $_POST['acf'][$r_key], 'create_unit_id' );
	//var_dump( $_POST['acf'][$r_key] ); die();

	// set up a term to represent the course object									/* 2 */
	$course_term_title = $post_object->post_title;
	$course_term_slug = $post_object->post_name;
	$course_term_id = create_term_if_needed( $course_term_title, $course_term_slug, TAX_COURSE );
	// the $course_objects_array is used to determine if an object has been removed from the list
	$course_objects_array = get_object_id_array_by_term( $course_term_slug, TAX_COURSE );


	/*
	 * loop through the units
	 */
	$course_counter = array( 'item_lesson' => 0, 'item_quizz' => 0 ); 				/* 3 */
	foreach ( reset($_POST['acf']) as $u_key => $unit ) { 							/* 4 */
		$unit_title = reset($unit); // first index: the title
		$unit_id = next($unit); // second index: the id
		$objects = next($unit); // third index: unit array
		if ( !isset($objects) || empty($objects) ) $objects = array(); // prevent running into errors if unit has no Lessons

		// For each Unit: Set up a term to represent the unit						/* 5 */
		$unit_term_id = create_term_if_needed( $unit_title, $unit_id, TAX_UNIT );

		// save the array index as 'unit_order' inside the tax-unit term
		update_term_meta( $unit_term_id, 'unit_order', $u_key );

		// get an array with all the id's associated with the term
		$unit_objects_array = get_object_id_array_by_term( $unit_id, TAX_UNIT );


		/*
		 * loop through the objects (headline, lesson, quizz)
		 */
		$unit_counter = array( 'item_lesson' => 0, 'item_quizz' => 0 );			/* 3 */
		foreach ( $objects as $object ) {												/* 6 */
			if ( !in_array( reset($object), array( 'item_lesson', 'item_quizz' ) ) ) continue;
			$unit_counter[reset($object)]++; //count									/* 7 */

			/*
			 * If there is an object ID -> unset the ID in the ${term}_objects_array
			 * If there is NO object ID -> wp_set_object_terms
			 */
			$object_id = (int)end($object);

			// Check the Course Object Ids array									/* 8 */
			if( in_array( $object_id, $course_objects_array ) ) {
				$temp_key = array_search( $object_id, $course_objects_array );
				unset( $course_objects_array[$temp_key] );
			} else {
				wp_add_object_terms( end($object), $course_term_slug, TAX_COURSE );
			}

			// Check the Unit Object Ids array										/* 8 */
			if( in_array( $object_id, $unit_objects_array ) ) {
				$temp_key = array_search( $object_id, $unit_objects_array );
				unset( $unit_objects_array[$temp_key] );
			} else {
				wp_add_object_terms( end($object), $unit_id, TAX_UNIT );
			}

			// Update Object with the meta value order_nr
			$order_nr = $unit_counter['item_lesson'] + $unit_counter['item_quizz'];
			update_post_meta( $object_id, 'order_nr', $order_nr );

		} // end item loop


		// update the tax-unit term meta with the numbers							/* 9 */
		update_term_meta( $unit_term_id, 'num_lessons', $unit_counter['item_lesson'] );
		update_term_meta( $unit_term_id, 'num_quizzes', $unit_counter['item_quizz'] );

		// save the lesson order as tax-unit term meta
		update_term_meta( $unit_term_id, 'lesson_order', $objects );
		// save IDs of post object and term of the course
		update_term_meta( $unit_term_id, 'course_title', $post_object->post_title );
		update_term_meta( $unit_term_id, 'course_term_id', $course_term_id );
		update_term_meta( $unit_term_id, 'course_object_id', $post_id );

		// add numbers to the course counter
		$course_counter['item_lesson'] += $unit_counter['item_lesson'];
		$course_counter['item_quizz'] += $unit_counter['item_quizz'];

		// remove the term from each object that has been removed from the unit list
		foreach ( $unit_objects_array as $object_id ) {								/* 8 */
			remove_term_from_object( $object_id, $unit_id, TAX_UNIT );
			delete_term_if_needed( $unit_term_id, TAX_UNIT );
		}

	} // end unit loop


	// update the tax-course term meta with the numbers								/* 9 */
	// update_term_meta( $course_term_id, 'num_lessons', $course_counter['item_lesson'] );
	// update_term_meta( $course_term_id, 'num_quizzes', $course_counter['item_quizz'] );

	// update the course object meta with the numbers								/* 9 */
	update_post_meta( $post_id, 'num_lessons', $course_counter['item_lesson'] );
	update_post_meta( $post_id, 'num_quizzes', $course_counter['item_quizz'] );

	//remove the term from each object that has been removed from the course list
	foreach ( $course_objects_array as $object_id ) {								/* 8 */
		remove_term_from_object( $object_id, $course_term_slug, TAX_COURSE );
		delete_term_if_needed( $course_term_id, TAX_COURSE );
	}
	// die();
}
// add_action( 'save_post', 'update_course_and_unit_tax', 9, 2 );



/**
 * Hooks when the repeater fields (units) for the edit course screen are prepared
 * The purpose of this filter is to retreive the 'unit_id' and save it in a global variable for later use
 */
function acf_get_unit_ids( $field ) {
	global $temp_unit_slugs;
	if( !$field['value'] ) return $field; // Bail early

	foreach ( $field['value'] as $i => $unit ) {
		$key = "acf-{$field['key']}-$i-".key($unit);
		$temp_unit_slugs[$key] = next($unit);
	}
	return $field;
}
add_filter('acf/prepare_field/name=units_repeater', 'acf_get_unit_ids');

/**
 * Hooks into the creation of each unit tile
 * The purpose of this filter is to add some helper text after the title to show the video and quizz count
 */
function acf_extend_unit_title( $field ) {
	global $temp_unit_slugs;
	if( !$temp_unit_slugs ) return $field; // Bail early

	// get term
	$term_slug = $temp_unit_slugs[$field['id']];
	$term = get_term_by( 'slug', $term_slug, TAX_UNIT );

	// get term meta
	$num_lessons = get_term_meta( $term->term_id, 'num_lessons' )[0];
	$num_quizzes = get_term_meta( $term->term_id, 'num_quizzes' )[0];

	// generate helper text output
	if( $num_lessons || $num_quizzes ) {
		$field['label'] .= sprintf( '<small class="acf-unit-title-helper-text">%s%s%s</small>',
			( $num_lessons ? '<span class="dashicons dashicons-video-alt2"></span> '.$num_lessons.' '.( $num_lessons == 1 ? 'Lesson' : 'Lessons' ) : '' ),
			( $num_lessons && $num_quizzes ? ', ' : '' ),
			( $num_quizzes ? '<span class="dashicons dashicons-welcome-write-blog"></span> '.$num_quizzes.' '.( $num_quizzes == 1 ? 'Quizz' : 'Quizzes' ) : '' )
		);
	}

	return $field;
}
add_filter('acf/prepare_field/name=unit_title', 'acf_extend_unit_title');



