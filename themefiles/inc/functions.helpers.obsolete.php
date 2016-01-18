<?php
/*------------------------------------*\
    Helper Functions
\*------------------------------------*/

/**
 * Create a slug for each unit OBSOLETE
 */
function create_unit_slugs( &$unit ) {
	$unit_title = current($unit);
	next($unit); // advance to second index: the slug
	$slug_key = key($unit);
	$unit[$slug_key] = sanitize_title( $unit_title );
}

/**
 * Create a id for each unit
 */
function create_unit_id( &$unit ) {
	$unit_title = current($unit);
	if( next($unit) ) return; // advance to second index (the id) & skip exisiting ones
	$id_key = key($unit);
	$unit[$id_key] = substr( sha1( $unit_title.mt_rand() ), 0, 7 ); // generate unique 7 digit hash
}

/**
 * Create a term if it doesn't exist yet
 * Returns the 'term_id' on success or fetches the 'term_id'
 */
function create_term_if_needed( $term_title, $term_slug, $taxonomy ) {
	if ( !term_exists( $term_title, $taxonomy ) ) {
		$term_id = wp_insert_term(
			$term_title,
			$taxonomy,
			$args = array(
					'slug' => $term_slug
				)
		);
		return $term_id;
	} else {
		$term = get_term_by( 'slug', $term_slug, $taxonomy );
		return (int)$term->term_id;
	}
}

/**
 * Delete a term if it is no longer needed
 */
function delete_term_if_needed( $term_id, $taxonomy ) {
	$term = get_term( $term_id, $taxonomy );
	if( $term->count == 0 ) {
		wp_delete_term( $term_id, $taxonomy );
	}
}

/**
 * Get an array of object id's asociated with a term
 */
function get_object_id_array_by_term( $term_slug, $taxonomy ) {
	// get all objects (lessons and quizzes) already assoziated with the term
	$objects_by_term = get_objects_by_term( $term_slug, $taxonomy, 'object_id' );
	$objects_id_array = array();

	// Get a list with all the object id's
	foreach ( $objects_by_term as $value ) {
		$objects_id_array[] = (int)$value['object_id'];
	}

	return $objects_id_array;
}

/**
 * Helper function
 */
function remove_term_from_object( $object_id, $term_slug, $taxonomy ) {
	$current_terms = wp_get_object_terms( $object_id, $taxonomy, array( 'fields' => 'slugs' ) );
	$temp_key = array_search( $term_slug, $current_terms );
	unset( $current_terms[$temp_key] );
	wp_set_object_terms( $object_id, $current_terms, $taxonomy );
}

/**
 * Get all the objects assoziated with a term
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

/**
 * Converst regular spaces to non-breaking spaces html-entities
 */
function spaces_to_nbsp( $string ) {
	return str_replace( ' ', '&nbsp;', $string);
}

/**
 * Get the course of a lesson/quizz
 * Including the inluding the course object_id
 */
function get_course( $post_id ) {
	global $wpdb;
	$course 			= wp_get_post_terms( $post_id, TAX_COURSE )[0];
	$course->post_id 	= (int)$wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$course->slug'" );
	return $course;
}

/**
 * Get the unit of a lesson/quizz
 * Including the unit_order number
 */
function get_unit( $post_id ) {
	$unit = wp_get_post_terms( $post_id, TAX_UNIT )[0];
	$unit = get_unit_meta( $unit );
	return $unit;
}

/**
 * Helper function to get unit meta, can be called inidividually
 * INPUT: term object of tax-unit
 */
function get_unit_meta( $unit ) {

	$meta = get_term_meta( $unit->term_id );

	foreach ( $meta as $key => $raw_value ) {
		$value = ( $key == 'lesson_order' ? unserialize( $raw_value[0] ) : $raw_value[0] );
		$unit->{$key} = is_int($value) ? (int)$value : $value ;
	}
	$unit->unit_order += 1;

	return $unit;
}

/**
 * This function can be called for a term of type 'tax-unit'
 * Order the post-objects (used for The Loop) in the right order
 * Add the Headlines as a "pseudo" post-object
 */
function sort_objects_inside_unit() {
	global $wp_query, $posts, $unit;

	// create a copy, because $posts is passed by reference
	$posts_copy = $posts;
	$posts = array();

	$order_nr = 1;

	// loop through the item_order array (containing all headlines, videos and quizzes in right order)
	$i = 0;
	foreach ( $unit->lesson_order as $item ) {

		// switch by lesson type (headline, video, quizz)
		switch ( reset($item) ) {
			case 'item_headline':

				$posts[$i] = new stdClass();
				$posts[$i]->post_title = end($item);
				$posts[$i]->post_type = 'headline';

				break;
			case 'item_lesson':
			case 'item_quizz':
			default:

				foreach ( $posts_copy as $object ) {
					if( (int)end($item) == $object->ID ) {
						$posts[$i] = $object;
					}
				}

				// add the index variable
				$posts[$i]->task_number = $order_nr;
				$order_nr++;

				break;

		}
		$i++;

	}

	// update post count
	$wp_query->post_count = count($posts);

}




