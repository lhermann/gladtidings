<?php
/*-------------------------------------------------*\
    Establish Course - Unit - Item relationships
\*-------------------------------------------------*/

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
add_action( 'save_post', 'gt_establish_relationships', 10, 2 );

function gt_establish_relationships( $post_id, $post_object ) {

	/**
	 * Make sure it only triggers for post objects of type 'course'
	 */
	if( $post_object->post_type !== 'course' ) return; // escape if it is not a course object
	if( $post_object->post_status == 'auto-draft' ) return; // escape if it is an automatic save
	if( !isset($_POST['acf']) || !reset($_POST['acf']) ) return; // escape if there are no units

	/**
	 * ACF field keys
	 */
	$k = new StdClass ();
	$k->units_repeater = 'field_56539b37b0c0b';
	$k->unit_type      = 'field_56956e20f28a2';
	$k->unit_title	   = 'field_56539b37b40ed';
	$k->unit_items     = 'field_56539b37b4115';
	$k->exam_id        = 'field_56956e73f28a3';
	$k->unit_status    = 'field_5653a2b5b8dda';

	/**
	 * Fetch wp_gt_relationships from the DB for this course
	 * They will be used to check if any rows have to be deleted
	 */
	global $wpdb;
	$query = "SELECT c.child_id unit, u.post_type
		FROM $wpdb->gt_relationships c
		LEFT JOIN $wpdb->posts u
		ON c.child_id = u.ID
		WHERE c.parent_id = $post_id;
	";
	$unit_rem = $wpdb->get_results( $query, ARRAY_A );
	$unit_rem = array_column( $unit_rem, 'post_type', 'unit');

	$query = "SELECT u.child_id item, i.post_type
		FROM $wpdb->gt_relationships c
		RIGHT JOIN $wpdb->gt_relationships u
		ON c.child_id = u.parent_id
		RIGHT JOIN $wpdb->posts i
		ON u.child_id = i.ID
		WHERE c.parent_id = $post_id;
	";
	$item_rem = $wpdb->get_results( $query, ARRAY_A );
	$item_rem = array_column( $item_rem, 'post_type', 'item');

	// var_dump( $unit_rem, $query, $item_rem ); die();

	/**
	 * Loop through the units
	 */
	$unit_order = 1;
	$units = array_values( $_POST['acf'][ $k->units_repeater ] ); // new units get really weird array indexes, so we reasign them
	foreach ( $units as $u_key => $unit ) {


		switch ( $unit[ $k->unit_type ] ) {
			case 'exam':

				/**
				 * For each exam:
				 *  (1) get exam_id
				 *  (2) establish the relationships with course
				 */
				// (1)
				$exam_id = $unit[ $k->exam_id ];

				// (2)
				gt_update_relationship( $post_id, $exam_id, -1, $u_key );

				break;
			case 'unit':
			default:

				/**
				 * For each unit:
				 *  (1) create a virtual post object of type 'unit'
				 *  (2) establish the relationships with course
				 *  (3) increase unit order number
				 *  (4) loop through the items (headline, lesson, quizz)
				 */
				// (1)
				$unit_id = gt_update_virtual_object( $post_id, $unit[ $k->unit_title ], 'unit', $unit[ $k->unit_status ] );

				// (3)
				gt_update_relationship( $post_id, $unit_id, $unit_order, $u_key );

				// (4)
				$unit_order++;

				// (5)
				if( !$unit[ $k->unit_items ] ) continue;
				$item_order = 1;
				$items = array_values( $unit[ $k->unit_items ] );
				foreach ( $items as $i_key => $item ) {

					/**
					 * reset indexes (because the acf field keys are relly long)
					 * $item[0] = item type
					 * $item[1] = object_id | headline_title
					 */
					$item = array_values( $item );

					/**
					 * There are 3 types of items: item_headline, item_lesson and item_quizz
					 */
					switch ( explode( '_', $item[0] )[1] ) {
						case 'headline':

							// create a post object of type 'headline'
							$item_id = gt_update_virtual_object( $post_id, $item[1], 'headline' );

							// establish the relationships with unit
							gt_update_relationship( $unit_id, $item_id, -1, $i_key );

							break;

						case 'lesson':
						case 'quizz':

							// establish the relationships with unit
							$item_id = $item[1];
							gt_update_relationship( $unit_id, $item_id, $item_order, $i_key );

							// increase order
							$item_order++;

							break;

					}

					// unset item_id from the $item_rem array
					if( $item_id ) unset( $item_rem[ $item_id ] );

					// reset ids
					$item_id = false;

				} // end item loop

				break;
		}

		// get object_id of unit | exam
		$temp_id = $unit_id ? $unit_id : $exam_id;

		/**
		 * Save Unit | Exam meta
		 */
		switch ( $unit[ $k->unit_status ] ) {
			case 'coming':

				update_post_meta( $temp_id, 'release_date', $unit['field_5653a3a235e26'] );

				break;
			case 'locked':

				update_post_meta( $temp_id, 'unlock_dependency', $unit['field_5695710055ed1'] );

				break;
		}

		// unset unit_id from the $unit_rem array
		if( $temp_id ) unset( $unit_rem[ $temp_id ] );

		// reset ids
		$temp_id = $unit_id = $exam_id = false;


	} // end unit loop

	// remove course-unit-relationships and the unit objects of all units listet in $unit_rem
	array_walk( $unit_rem, 'gt_remove_relationship' );
	array_walk( $unit_rem, function( $unit_type, $unit_id ) {
		if( $unit_type == 'unit' ) gt_remove_virtual_object( $unit_id );
	});

	// remove unit-item-relationships (and the headline objects) of all items listet in $item_rem
	array_walk( $item_rem, 'gt_remove_relationship' );
	array_walk( $item_rem, function( $item_type, $item_id ) {
		if( $item_type == 'headline' ) gt_remove_virtual_object( $item_id );
	});

}


/**
 * Helper function to update a virtual post object
 * or create it, if it doesn't exist
 */
function gt_update_virtual_object( $parent_id, $title, $post_type, $status = '', $content = '' ) {

	// get ID of object with slug and post-type
	global $wpdb;
	$slug = sanitize_title( $parent_id.'-'.$title );
	$ID = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$slug' AND post_type = '$post_type'" );


	if( $ID ) { // got an object returned? update that object

		$object = array( 'ID' => $ID );
		if( $status ) $object['post_status'] = $status;
		if( $content ) $object['post_content'] = $content;

		wp_update_post( $object );

	} else { // got no object returned? insert object

		$object = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => $status ? $status : 'publish',
			'post_type'    => $post_type
		);
		$ID = wp_insert_post( $object );

	}

	return (int)$ID;
}

/**
 * Helper function to remove a virtual post object
 */
function gt_remove_virtual_object( $object_id ) {
	wp_delete_post( $object_id, true );
}


/**
 * Help Function to update the acf field of unit_id
 */
function gt_update_field_unit_id( $unit_id, $unit_key, $post_id ) {
	global $wpdb;

	$wpdb->update(
		$wpdb->postmeta,
		array(
			'meta_value' => $unit_id
		),
		array(
			'post_id' => $post_id,
			'meta_key' => "units_repeater_{$unit_key}_unit_id"
		),
		array(
			'%d'
		),
		array(
			'%d',
			'%s'
		)
	);

}


/**
 * Helper Function to update the relationship between two objects,
 * or insert it, if it doesn't exist
 */
function gt_update_relationship( $parent_id, $child_id, $order, $position = 0 ) {

	global $wpdb;

	$relationship = $wpdb->get_row( "SELECT parent_id, child_id FROM $wpdb->gt_relationships WHERE child_id = $child_id" );

	if ( $relationship ) {

		$wpdb->update(
			$wpdb->gt_relationships,
			array(
				'parent_id' => $parent_id,
				'order'     => $order,
				'position'	=> $position
			),
			array(
				'child_id'  => $child_id
			),
			array(
				'%d',
				'%d',
				'%d'
			),
			array(
				'%d',
				'%d'
			)
		);

	} else {

		$wpdb->insert(
			$wpdb->gt_relationships,
			array(
				'parent_id' => $parent_id,
				'child_id'  => $child_id,
				'order'     => $order,
				'position'  => $position
			),
			array(
				'%d',
				'%d',
				'%d',
				'%d'
			)
		);

	}

}

/**
 * Helper Function to remove the relationship between two objects
 * INPUT:
 *  - $type is not necessary, but is passed as first argument by array_walk and the id as the second
 *    because the id is the index of the individual array vars
 */
function gt_remove_relationship( $type, $ID ) {
	global $wpdb;
	switch ($type) {
		case 'unit':
			$wpdb->delete( $wpdb->gt_relationships, array( 'parent_id' => $ID ), array( '%d' ) );
		default:
			$wpdb->delete( $wpdb->gt_relationships, array( 'child_id' => $ID ), array( '%d' ) );
	}
}


