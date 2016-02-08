<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

class Unit extends Application
{

	function __construct( $post )
	{

		parent::__construct( $post );

		$this->order     = (int)$post->order;
		$this->position  = (int)$post->position;
		$this->parent_id = (int)$post->parent_id;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	public function parent()
	{
		if( isset( $this->parent) ) return $this->parent;

		global $post;
		if( $this->parent_id == $post->ID ) {

			return $post;

		} else {

			global $wpdb;

			$query = "SELECT *
				FROM `wp_gt_relationships` r
				INNER JOIN `wp_posts` p
				ON p.ID = r.parent_id
				WHERE r.child_id = $this->ID
			";

			$this->parent = gt_instantiate_object( $wpdb->get_row( $query ) );
			return $this->parent;

		}
	}
}
