<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

/**
 * Pass a post object or an ID
 */

class Application
{
	public $ID, $type, $title, $slug, $status, $status_num, $date, $date_gmt;

	function __construct( $post )
	{
		$post = is_object( $post ) ? $post : get_post( $post );

		$this->ID         = (int)$post->ID;
		$this->type       = strtolower( get_class ( $this ) );
		$this->date       = $post->post_date;
		$this->date_gmt   = $post->post_date_gmt;
		$this->title      = $post->post_title;
		$this->slug       = $post->post_name;
		$this->status     = $this->init_status( $post->post_status );
		$this->status_num = $this->init_status_number( $this->status );

		return $post;
	}

	/*=======================*\
		Private Functions
	\*=======================*/

	/**
	 * Update the status of an object and return the updated object
	 * If the status is ...
	 *  - 'coming'  = date has passed ? set to 'publish' (also update post object and acf field)
	 *                note: change from 'coming' to 'publish' should only happen once
	 *  - 'locked'  = unlock condition has been met ? fall through to 'publish' : don't change
	 *  - 'publish' = user has started ? (active) or finished ? (success) the object : don't change
	 *
	 * Possible Status Flags
	 *  - 'success' - the unit is sucessfully finished (user specific)
	 *  - 'active'  - the unit was started, but is not finished (user specific)
	 *  - 'publish' - the unit is open, but not yet started (wp builtin)
	 *  - 'locked'  - the unit is visible, but not accessible
	 *  - 'coming'  - the unit is anounced for a future date, but visible (other than builtin 'future')
	 *  - 'draft'   - the unit is not visible (wp builtin)
	 */
	private function init_status( $status )
	{
		global $user;

		switch ( $status ) {
			case 'coming':
				$date = strtotime( get_post_meta( $this, 'release_date', true ) );
				if( $date < time() ) {
					// update status to 'publish' and fall through
					$status = 'publish';
					wp_publish_post( $this->ID );
				} else {
					$this->release_date = date( "F j, Y", $date );
					break;
				}

			case 'publish':
				$progress = $user->get_progress( $this );
				if( $progress == 100 ) {
					$status = 'success';
				} elseif( $progress > 0 ) {
					$status = 'active';
					$this->progress = $progress;
				}
		}
		return $status;
	}

	/**
	 * Returns the status as an integer
	 */
	private function init_status_number( $status )
	{
		$number = 0;
		switch ( $status ) {
			case 'success': $number++; // 5
			case 'active':  $number++; // 4
			case 'publish': $number++; // 3
			case 'locked':  $number++; // 2
			case 'coming':  $number++; // 1
			case 'draft':              // 0
			default:                   // 0
		}
		return $number;
	}

	/*=======================*\
		Protected Functions
	\*=======================*/



	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 *
	 */
	public function is_done()
	{
		return $this->status === 'passed' ? true : false;
	}

	/**
	 * The Progress as an integer from 0 to 100
	 */
	public function progress()
	{
		global $user;
		return $user->get_progress( $this );
	}

	/**
	 * Get the parent object
	 * [1] Return chached value if existing
	 * [2] Check if the global $post happens to be the parent to save a db query
	 * [3] Query for the parent object
	 * [4] Instantiate the queried object
	 * [5] Chache parent
	 */
	public function parent()
	{
		global $post;

		if( isset( $this->parent) ) {

			/* [1] */
			return $this->parent;

		} elseif( $this->parent_id == $post->ID ) {

			/* [2] */
			return $post;

		} else {

			/* [3] */
			global $wpdb;

			$query = "SELECT *
				FROM `wp_gt_relationships` r
				INNER JOIN `wp_posts` p
				ON p.ID = r.parent_id
				WHERE r.child_id = $this->ID
			";

			/* [4] & [5] */
			$this->parent = gt_instantiate_object( $wpdb->get_row( $query ) );
			return $this->parent;

		}
	}

	/**
	 * Get the objects chidren as an array
	 * [1] Return cached array if existing
	 * [2] Query for the children
	 * [3] Instantiate the queried objects
	 * [4] Cache array
	 */
	public function children()
	{
		if( isset($this->children) ) {

			/* [1] */
			return $this->children;

		} else {

			/* [2] */
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

			/* [3] */
			$children   = array();
			foreach ( $results as $key => $child ) {

				$children[] = gt_instantiate_object( $child );

			}

			/* [4] */
			$this->children = $children;
			return $children;

		}
	}

	/**
	 * INPUT:
	 *   %args  -> possible arguments:
	 *              'class'     = css class
	 *              'title'     = link title="" attribute
	 *              'attribute' = any attribute, eg. disabled
	 *              'display'   = the link text or label (should be renamed label)
	 */
	public function link_to( $args = array() )
	{
		return sprintf( '<a class="%1$s" href="%2$s" title="%3$s" %4$s>%5$s</a>',
			isset($args['class']) ? $args['class'] : 'a--bodycolor',
			gt_get_permalink( $this ),
			isset($args['title']) ? $args['title'] : __('Permalink to:', 'gladtidings') . ' ' . $this->title,
			isset($args['attribute']) ? $args['attribute'] : '',
			isset($args['display']) ? $args['display'] : $this->title
		);
	}


	/**
	 * Wrapper to print get_link_to()
	 */
	// public function print_link_to( $object = null, $args = array() )
	// {
	// 	print( $this->get_link_to( $object, $args ) );
	// }
}
