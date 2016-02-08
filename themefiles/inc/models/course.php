<?php
class Course extends Application
{
	// function __construct( $post )
	// {
	// 	parent::__construct( $post );
	// }

	/*=======================*\
		Public Functions
	\*=======================*/

	public function batch_src()
	{
		return h_get_course_batch( $this->ID );
	}

	public function children()
	{
		if( isset($this->children) ) {

			return $this->children;

		} else {

			global $wpdb;

			$query = "SELECT *
					  FROM $wpdb->posts p
					  INNER JOIN $wpdb->gt_relationships r
					  ON r.child_id = p.ID
					  WHERE r.parent_id = {$this->ID}
					  AND p.post_status IN ('publish', 'coming', 'locked')
					  ORDER BY r.position;
					 ";
			$results = $wpdb->get_results( $query, OBJECT );

			$children   = array();
			foreach ( $results as $key => $child ) {

				$children[] = gt_instantiate_object( $child );

			}

			$this->children = $children;
			return $children;

		}
	}
}
