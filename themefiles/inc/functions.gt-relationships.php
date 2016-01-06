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
function gt_establish_relationships( $post_id, $post_object ) {
	

	/**
	 * Make sure it only triggers for post objects of type 'course'
	 */
	if( $post_object->post_type !== 'course' ) return; // escape if it is not a course object
	if( $post_object->post_status == 'auto-draft' ) return; // escape if it is an automatic save
	if( !isset($_POST['acf']) || !reset($_POST['acf']) ) return; // escape if there are no units


	/**
	 * Loop through the units
	 */
	$unit_order = 0;
	$units = array_values( $_POST['acf']['field_56539b37b0c0b'] );
	foreach ( $units as $u_key => $unit ) {

		$unit_order++;

		/**
		 * For each unit:
		 *  (1) create a post object of type 'unit'
		 *  (2) update unit_id field
		 *  (3) establish the relationships with course
		 */
		// (1)
		$unit_id = $unit['field_568c2e3389878'];
		$unit_id = gt_update_virtual_object( $unit_id, $unit['field_56539b37b40ed'], 'unit', $unit['field_5653a2b5b8dda'] );

		// (2)
		gt_update_field_unit_id( $unit_id, $u_key, $post_id );
		
		// (3)
		gt_update_relationship( $post_id, $unit_id, $unit_order );


		/**
		 * Loop through the items (headline, lesson, quizz)
		 */
		if( !$unit['field_56539b37b4115'] ) continue;
		$item_order = 1;
		$items = array_values( $unit['field_56539b37b4115'] );
		foreach ( $items as $i_key => $item ) {
			
			$item = array_values( $item );

			/**
			 * For each item:
			 */
			switch ( explode( '_', $item[0] )[1] ) {
				case 'headline':

					// create a post meta field for the headline
					update_post_meta( $unit_id, "headline_before_{$item_order}", $item[1] );

					break;

				case 'lesson':
				case 'quizz':

					// establish the relationships with unit
					gt_update_relationship( $unit_id, $item[1], $item_order );

					$item_order++;

					break;

			}


		} // end item loop


	} // end unit loop


}
add_action( 'save_post', 'gt_establish_relationships', 10, 2 );


/**
 * Helper function to update a virtual post object
 * or create it, if it doesn't exist
 */
function gt_update_virtual_object( $ID, $title, $post_type, $status = '', $content = '' ) {

	if( $ID ) {

		$object = array(
			'ID'           => $ID,
			'post_title'   => $title,
			'post_name'	   => sanitize_title( $title )
		);
		if( $status ) $object['post_status'] = $status;
		if( $content ) $object['post_content'] = $content;

		wp_update_post( $object );

	} else {

		$object = array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => $status ? $status : 'publish',
			'post_type'    => $post_type
		);  
		$ID = wp_insert_post( $object );

	}

	return $ID;
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
function gt_update_relationship( $parent_id, $child_id, $order ) {
	
	global $wpdb;
	$table_name = $wpdb->prefix . "gt_relationships";

	$relationship = $wpdb->get_row( "SELECT parent_id, child_id FROM $table_name WHERE parent_id = $parent_id AND child_id = $child_id" );
	// var_dump( $relationship ); die();

	if ( $relationship ) {

		$wpdb->update( 
			$table_name,
			array( 
				'order'     => $order
			), 
			array(
				'parent_id' => $parent_id, 
				'child_id'  => $child_id
			), 
			array( 
				'%d'
			), 
			array(
				'%d',
				'%d'
			) 
		);

	} else {

		$wpdb->insert( 
			$table_name,
			array( 
				'parent_id' => $parent_id, 
				'child_id'  => $child_id,
				'order'     => $order
			), 
			array( 
				'%d', 
				'%d',
				'%d' 
			) 
		);

	}

}


