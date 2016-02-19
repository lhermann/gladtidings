<?php
/*------------------------------------*\
    Module Trait
\*------------------------------------*/

trait get_course
{

	/**
	 * Returns the course object for lessons and quizzes
	 */
	public function course()
	{
		if( !isset( $this->course) ) {

			global $wpdb;
			$query = "SELECT p.*
				FROM $wpdb->posts p
				INNER JOIN $wpdb->gt_relationships r
				ON p.ID = r.parent_id
				INNER JOIN $wpdb->gt_relationships r2
				ON r.child_id = r2.parent_id
				WHERE r2.child_id = $this->ID;";

			$this->course = gt_instantiate_object( $wpdb->get_row( $query ) );

		}

		return $this->course;

	}

	/**
	 * Returns the ID of the course
	 */
	public function course_id()
	{
		return $this->course()->ID;
	}
}
