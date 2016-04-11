<?php
/*------------------------------------*\
    Module Trait
\*------------------------------------*/

trait init_status {
	/**
	 * Update the status of an object and return the updated object
	 * If the status is ...
	 *  - 'coming'  = IF date has passed
	 *                THEN set status to 'publish' (also update post object and acf field in DB)
	 *                ELSE add a variable with the release date
	 *  - 'locked'  = IF unlock condition has been met
	 *                THEN fall through to 'publish'
	 *                ELSE add a variable with the dependency object
	 */
	protected function init_status( $status )
	{
		switch ( $status ) {
			case 'coming':
				$date = strtotime( get_post_meta( $this->ID, 'release_date', true ) );
				if( $date < time() ) {
					// update post_status to 'publish'
					$status = 'publish';
					wp_publish_post( $this->ID );
					try {
						update_sub_field( array('units_repeater', $this->position + 1, 'status'), 'publish', $this->parrent()->ID );
					} catch (Exception $e) {
						var_dump($e);
					}
				} else {
					$this->release_date = date( "F j, Y", $date );
				}
				break;

			case 'locked':
				$dependency_position = (int)get_post_meta( $this->ID, 'unlock_dependency', true ) - 1;
				if( $dependency_position < $this->position ) {
					$dependency_object = $this->find_sibling( array( 'position' => $dependency_position ) );
					if( !$dependency_object->is_done ) {
						$this->unlock_dependency = $dependency_object;
						break;
					}
				}
				$status = 'publish';
		}

		// var_dump( $status );

		$status = parent::init_status( $status );

		// var_dump( $this->is_done(), $status );

		return $status;
	}
}
