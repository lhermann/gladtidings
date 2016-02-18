<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

/**
 * Pass a post object or an ID
 */
class Application
{
	public $ID, $type, $title, $slug, $status, $status_num, $date, $date_gmt, $touched, $order, $position, $parent_id;

	function __construct( $post )
	{
		$post = is_object( $post ) ? $post : get_post( $post );

		global $user;

		$this->ID         = (int)$post->ID;
		$this->type       = strtolower( get_class ( $this ) );
		$this->title      = $post->post_title;
		$this->slug       = $post->post_name;

		$this->date       = $post->post_date;
		$this->date_gmt   = $post->post_date_gmt;
		$this->touched    = (int)$user->get( $this, 'touched' );

		$this->gt_relationships( $post );

		$this->init_status( $post->post_status );

		return $post;
	}

	/*=======================*\
		Private Functions
	\*=======================*/

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

	/**
	 * Assign the object's order, position and parent_id
	 * Fetch the values from the DB if they are not existent in the $post object
	 */
	protected function gt_relationships( $post )
	{
		if( isset( $post->order ) ) {

			$this->order     = (int)$post->order;
			$this->position  = (int)$post->position;
			$this->parent_id = (int)$post->parent_id;

		} elseif( in_array( $this->type, array('unit', 'exam', 'lesson', 'quizz') ) ) {

			global $wpdb;
			$query = "SELECT r.order, r.position, r.parent_id
				FROM $wpdb->gt_relationships r
				INNER JOIN $wpdb->posts p
				ON p.ID = r.child_id
				WHERE p.ID = $post->ID;";
			$result = $wpdb->get_row( $query );

			$this->order     = (int)$result->order;
			$this->position  = (int)$result->position;
			$this->parent_id = (int)$result->parent_id;

		}

	}

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
	protected function init_status( $status )
	{
		switch ( $status ) {
			case 'publish':
			case 'active' :
			case 'success':
				if( $this->is_done() ) {
					$status = 'success';
				} elseif( $this->progress() > 0 ) {
					$status = 'active';
				}
		}

		$this->status_num = $this->init_status_number( $status );
		$this->status     = $status;

		return $status;
	}

	/*=======================*\
		Public Functions
	\*=======================*/


	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 * OUTPUt: DB entry existed true|false
	 */
	public function touch()
	{
		global $user;
		return $user->update( $this, 'touched', time() );
	}

	/**
	 *
	 */
	public function is_done()
	{
		return $this->status === 'success' ? true : false;
	}

	/**
	 * The Progress as an integer from 0 to 100
	 */
	public function progress()
	{
		if( !isset( $this->progress ) ) {

			global $user;
			$this->progress = $user->get( $this, 'progress' );
		}

		return $this->progress;
	}

	/**
	 * Returns the ID of the course
	 * false by default
	 * Should be overwritten within the appropriate models
	 */
	public function course_id()
	{
		return false;
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
		/* [1] */
		if( !isset( $this->parent) ) {

			global $post;
			if( $this->parent_id == $post->ID ) {

				/* [2] */
				$this->parent = $post;

			} else {

				/* [3] */
				global $wpdb;

				$query = "SELECT *
					FROM $wpdb->posts p
					LEFT OUTER JOIN $wpdb->gt_relationships r
					ON p.ID = r.child_id
					WHERE p.ID = $this->parent_id;
				";

				/* [4] & [5] */
				$this->parent = gt_instantiate_object( $wpdb->get_row( $query ) );

			}

		}

		return $this->parent;
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
		/* [1] */
		if( !isset($this->children) ) {

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

			/* [3] & [4] */
			$this->children = array();
			foreach ( $results as $key => $child ) {

				$this->children[] = gt_instantiate_object( $child );

			}

		}

		return $this->children;
	}

	/**
	 * Get an object's siblings as array
	 */
	public function siblings()
	{
		$parent = $this->parent();
		return $parent->children();
	}

	/**
	 * Perform a search for a specific sibling
	 * INPUT: array with index => value pairs to search for in the object
	 *
	 * [1] Use cached siblings if available
	 * [2] Otherwise do a db query
	 */
	public function find_sibling( $search = array() )
	{
		if( empty($search) ) return;

		/* [1] */
		if( isset($this->siblings) ) {

			foreach ( $this->siblings as $object ) {
				foreach ( $search as $index => $value ) {
					if( $object->{$index} != $value ) continue 2;
				}
				return $object;
			}

		/* [2] */
		} else {

			global $wpdb;
			$query = "SELECT *
				FROM $wpdb->posts p
				INNER JOIN $wpdb->gt_relationships r
				ON p.ID = r.child_id
				WHERE r.parent_id = $this->parent_id
				AND p.post_type <> 'headline'";

			foreach ( $search as $key => $value) {
				$key = in_array( $key, array( 'order', 'position' ) ) ? 'r.'.$key : 'p.'.$key;
				$query .= "\nAND $key = '$value'";
			}

			return gt_instantiate_object( $wpdb->get_row( $query ) );

		}
	}

	/**
	 * Get number of children, optionally of one type only
	 * [1] Check if cached number exists
	 * [2] Check if children array is already cached
	 * [3] Count from hte existing children array
	 * [4] Cache number
	 * [5] Build the query
	 *
	 * INPUT: $type to limit by
	 * OUTPUT: (int) number of children
	 */
	public function num_children( $type = 'children' )
	{
		/* [1] */
		if( !isset( $this->{"num_{$type}"} ) ) {

			/* [2] */
			if( isset( $this->children ) ) {

				/* [3] */
				if( $type != 'children' ) {

					$count = 0;
					foreach( $this->children as $child ) {
						if( $child->type == $type ) $count++;
					}

				} else {

					$count = count( $this->children );

				}

				/* [4] */
				$this->{"num_{$type}"} = $count;

			} else {

				/* [5] */
				global $wpdb;

				$query = "SELECT COUNT(p.ID) AS num
					FROM $wpdb->gt_relationships r
					INNER JOIN $wpdb->posts p
					ON r.child_id = p.ID\n";

				if( $this->type == 'course' ) {

					$query .= "INNER JOIN $wpdb->gt_relationships r2
						ON r2.child_id = r.parent_id
						WHERE r2.parent_id = $this->ID\n";

				} else {

					$query .= "WHERE r.parent_id = $this->ID\n";

				}

				if( $type != 'children' ) {

					$query .= "AND p.post_type = '$type'";

				}

				/* [4] */
				$this->{"num_{$type}"} = (int)$wpdb->get_var( $query );

			}
		}

		return $this->{"num_{$type}"};
	}

	/**
	 * Get number of children, optionally of one type only
	 * [1] Check if cached number exists
	 * [2] Count completed children
	 * [3] Cache number
	 *
	 * INPUT: $type to limit by
	 * OUTPUT: (int) number of children
	 */
	public function num_children_done( $type = 'children' )
	{

		/* [1] */
		if( !isset( $this->{"num_{$type}_done"} ) ) {

			/* [2] */
			$count = 0;
			foreach( $this->children() as $child ) {
				if( $child->type != $type && $type != 'children' ) continue;
				if( $child->is_done() ) $count++;
			}

			/* [3] */
			$this->{"num_{$type}_done"} = $count;
		}

		return $this->{"num_{$type}_done"};
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
			gt_get_permalink( $this, ( isset($args['after_url']) ? $args['after_url'] : null ) ),
			isset($args['title']) ? $args['title'] : __('Permalink to:', 'gladtidings') . ' ' . $this->title,
			isset($args['attribute']) ? $args['attribute'] : '',
			isset($args['display']) ? $args['display'] : $this->title
		);
	}

}
