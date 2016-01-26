<?php
/*------------------------------------*\
    Global Controller
\*------------------------------------*/

/**
 * TODO:
 */


class GTGlobal
{

	protected $course;
	protected $unit;
	protected $lesson;
	protected $quizz;
	protected $is_exam = false;

	protected $context;
	protected $siblings;
	protected $children;

	protected $first_touch;

	public $user_id;
	public $user_name;
	protected $user_meta;

	function __construct( &$object )
	{
		// setup user variables
		$this->user_id = wp_get_current_user() ? (int)wp_get_current_user()->data->ID : false;
		$this->user_name = wp_get_current_user() ? wp_get_current_user()->data->display_name : false;
		$this->user_meta = $this->get_user_meta();

		if( $object ) {

			// get object status and relationship
			$object = $this->setup_object( $object );

			// setup context
			if( !$this->context ) $this->context = $object->post_type;

			// touch
			$existed = $this->touch( $object->post_type, $object->ID );
			$this->first_touch = $existed ? false : true;

		} else {

			if( !$this->context ) $this->context = 'home';

		}
	}

	/*===========================*\
		Object Setup Functiuons
	\*===========================*/

	/**
	 * OUTPUT: object with all the user meta
	 */
	private function get_user_meta()
	{
		global $wpdb;
		$query_str = "SELECT meta_key, meta_value
						FROM $wpdb->usermeta
						WHERE user_id = $this->user_id
						AND ( meta_key LIKE 'course_%'
							OR meta_key LIKE 'unit_%'
							OR meta_key LIKE 'lesson_%'
							OR meta_key LIKE 'quizz_%' )";
		$rows = $wpdb->get_results( $query_str, OBJECT );
		$return = new stdClass();
		foreach ( $rows as $row ) {
			$return->{$row->meta_key} = maybe_unserialize( $row->meta_value );
		}
		return $return;
	}

	/**
	 * Get object or complete missing information for a standard wp_post object
	 * INPUT: ID or Post Object
	 */
	protected function get_object( $object )
	{
		if( is_numeric($object) ) {
			$object = get_post( $object );
		}
		if( $object->post_type !== 'course' ) {
			$object = $this->get_object_relationship( $object );
			$object = $this->update_object_status( $object );
		}
		return $object;
	}

	/**
	 * A full object setup
	 * INPUT: ID or Post Object
	 */
	protected function setup_object( $object )
	{
		$object = $this->get_object( $object );
		$this->{$object->post_type} = $object;
		return $object;
	}

	/**
	 * Get the children for a given parent. E.g. get the units of a course
	 * INPUT: ID or Post Object
	 * OUTPUT: Array of post objects
	 */
	protected function get_children( $parent = null, $context = null )
	{
		global $wpdb;
		$parent_id = is_numeric($parent) ? $parent : ( is_object($parent) ? $parent->ID : $this->{$this->context}->ID );
		$context = $context ? $context : $this->context;

		$query = "SELECT *
				  FROM $wpdb->posts p
				  INNER JOIN $wpdb->gt_relationships r
				  ON r.child_id = p.ID
				  WHERE r.parent_id = $parent_id
				  AND p.post_status IN ('publish', 'coming', 'locked')
				  ORDER BY r.position;
				 ";
		$children = $wpdb->get_results( $query, OBJECT );

		// replace post_type 'quizz' with 'exam' in the 'course' context
		if( $context == 'course' ) {
			array_walk( $children, function(&$child) {
				if( $child->post_type == 'quizz' ) $child->post_type = 'exam';
			});
		}

		// update status
		foreach ( $children as $key => &$child) {
			$child = $this->update_object_status( $child, $children );
		}

		return $children;
	}

	/**
	 * Returns the first object that maches all the search parameters, else returns false
	 * INPUT: array with index => value pairs to search for in the object
	 */
	public function find_sibling( $search )
	{
		// $results = array();
		// foreach ( $search as $index => $value ) {
		// 	$results[] = array_keys( array_column( $this->siblings, $index ), $value );
		// }
		// $intersect = reset( array_intersect( $results[0], $results[1] ) );
		// var_dump( $this->siblings[$intersect] );

		foreach ( $this->siblings as $object ) {
			foreach ( $search as $index => $value ) {
				if( $object->{$index} != $value ) continue 2;
			}
			return $object;
		}
		return false;
	}

	/**
	 * Update the status of an object and return the updated object
	 * If the status is ...
	 *  - 'coming'  = date has passed ? set to 'publish' (also update post object and acf field)
	 *                note: change from 'coming' to 'publish' should only happen once
	 *  - 'locked'  = unlock condition has been met ? fall through to 'publish' : don't change
	 *  - 'publish' = user has started ? (active) or finished ? (success) the object : don't change
	 */
	protected function update_object_status( $object, $siblings = null )
	{
		switch ( $object->post_status ) {
			case 'coming':
				$date = strtotime( get_post_meta( $object->ID, 'release_date', true ) );
				if( $date < time() ) {
					// update post_status to 'publish'
					$object->post_status = 'publish';
					wp_publish_post( $object->ID );
					update_sub_field( array('units_repeater', $object->position + 1, 'status'), 'publish', $this->course->ID );
				} else {
					$object->release_date = date( "F j, Y", $date );
				}
				break;

			case 'locked':
				if( !$siblings ) $siblings = $this->get_children( $object->parent_id, 'course' );
				$dependency_object = $siblings[ (int)get_post_meta( $object->ID, 'unlock_dependency', true ) - 1 ];
				if( $this->is_done( $dependency_object ) ) {
					$object->post_status = 'publish'; // fall through
				} else {
					$object->unlock_dependency = $dependency_object;
					break;
				}

			case 'publish':
				if( $this->is_done( $object ) ) {
					$object->post_status = 'success';
				} elseif( $this->get_progress( $object ) > 0 ) {
					$object->post_status = 'active';
					$object->progress = $this->get_progress( $object );
				}
				break;
		}
		return $object;
	}

	protected function get_object_relationship( $object )
	{
		global $wpdb;
		$query = "SELECT r.parent_id, r.order, r.position
				  FROM $wpdb->gt_relationships r
				  WHERE r.child_id = $object->ID;";
		$result = $wpdb->get_row( $query );
		if( $result ) {
			$object->parent_id = (int)$result->parent_id;
			$object->order     = $result->order;
			$object->position  = $result->position;
		}
		return $object;
	}

	/*=======================*\
		Protected Functions
	\*=======================*/

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID = object ID or term_id
	 * 	$name =	name of the key
	 * OUTPUt:
	 *	if DB entry exists = int
	 * 	else = false
	 */
	protected function get_value( $scope, $ID, $name )
	{
		$key = "{$scope}_{$ID}_{$name}";

		if( isset($this->user_meta->{$key}) ) {
			return $this->user_meta->{$key};
		} else {
			return NULL;
		}
	}

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$name
	 * 	$value = new value
	 * OUTPUt: DB entry existed true|false
	 */
	protected function update_value( $scope, $ID, $name, $value )
	{
		$key = "{$scope}_{$ID}_{$name}";
		$isset = isset($this->user_meta->{$key});
		$this->user_meta->{$key} = $value;
		update_user_meta( $this->user_id, $key, is_bool($value) ? (int)$value : $value );
		return $isset;
	}

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$name
	 * OUTPUt: DB entry existed true|false
	 */
	protected function increment_value( $scope, $ID, $name )
	{
		$key = "{$scope}_{$ID}_{$name}";
		$value = isset($this->user_meta->{$key}) ? $this->user_meta->{$key} + 1 : 1;

		return $this->update_value( $scope, $ID, $name, $value );
	}

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$type = 'lesson'|'quizz'
	 * OUTPUt: DB entry existed true|false
	 */
	protected function increment_items_done( $scope, $ID, $type )
	{
		return $this->increment_value( $scope, $ID, "{$type}_done" );
	}

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 * OUTPUt: DB entry existed true|false
	 */
	protected function touch( $scope, $ID )
	{
		return $this->update_value( $scope, $ID, 'touched', time() );
	}

	/**
	 * Get number of completed items for a course|unit
	 * INPUT: $object (for which to find the stats), $type to limit for only 'lessons'|'quizzes'
	 * OUTPUT: (int) number of items done
	 */
	protected function get_num_items_done( $object, $type = 'all' )
	{

		// prepare variables
		$counter = 0;
		$iterator = $object->post_type == 'course' ? $this->children : array( $object );

		foreach ( $iterator as $i) {
			$counter += $type !== 'quizzes' ? (int)$this->get_value( $i->post_type, $i->ID, "lessons_done" ) : 0;
			$counter += $type !== 'lessons' ? (int)$this->get_value( $i->post_type, $i->ID, "quizzes_done" ) : 0;
		}

		return $counter;
	}

	/**
	 * Get total number of items for a course|unit
	 * INPUT: $object (for which to find the stats), $type to limit for only 'lessons'|'quizzes'
	 * OUTPUT: (int) number of total items
	 */
	protected function get_num_items_total( $object, $type = 'all' )
	{
		try {
			if( $object == null ) throw new Exception("\$object is NULL!");

			// prepare variables
			$scope = $object->post_type;
			$ID    = $object->ID;
			$type  = $this->get_singular( $type );

			// see if there is a cached value
			$key = "num_{$type}_total";
			$value = $this->get_value( $scope, $ID, $key );
			if( $value ) return (int)$value;

			// do a database query
			global $wpdb;

			// built query
			$query = "SELECT COUNT(p.ID) AS num";

			switch ( $scope ) {
				case 'course':
					$query .= "
						FROM $wpdb->gt_relationships c
						INNER JOIN $wpdb->gt_relationships u
						ON c.child_id = u.parent_id
						INNER JOIN $wpdb->posts p
						ON u.child_id = p.ID
						WHERE c.parent_id = $ID
					";
					break;
				case 'unit':
					$query .= "
						FROM $wpdb->gt_relationships u
						INNER JOIN $wpdb->posts p
						ON u.child_id = p.ID
						WHERE u.parent_id = $ID
					";
					break;
			}

			switch ($type) {
				case 'lesson':
				case 'quizz':
					$query .= "AND p.post_type = '$type';";
					break;
				case 'all':
				default:
					$query .= "AND p.post_type IN ('lesson', 'quizz');";
					break;
			}

			// get value
			$result = $wpdb->get_var( $query );

			// chache value
			$this->user_meta->{$key} = (int)$result;

			// return value
			return (int)$result;

		} catch (Exception $e) {
			echo 'Global Controller Line '.__LINE__.': Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}
	}

	/**
	 * Get the singular of one of these often used terms
	 */
	protected function get_singular( $type )
	{
		switch ( $type ) {
			case 'lessons': return 'lesson'; break;
			case 'quizzes': return 'quizz'; break;
			default: return $type;
		}
	}

	/**
	 * Specifically calculate the progress percentage, to not use cached values,
	 * if you call this outside the constructer consider using $this->get_progress instead
	 * INPUT: post object
	 * OUTPUT: (int) 0-100
	 */
	public function calculate_progress( $object = null )
	{
		$object = $object ? $object : $this->{$this->context};

		$total = $this->get_num_items_total( $object );
		$done = $this->get_num_items_done( $object );

		if( !$total || !$done ) return 0;

		return (int)round( ( $done / $total ) * 100 );
	}


	/*=======================*\
		User Functions
	\*=======================*/

	/**
	 * Current user is allowed to access the study area
	 */
	public function user_can_study()
	{
		return is_user_logged_in() && ( current_user_can( 'study' ) || current_user_can( 'edit_post' ) ) ? true : false;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 * Return any protected or public variable
	 */
	public function get_var( $var, $sub_var = null )
	{
		if( !isset($this->{$var}) ) return false;
		$return = $this->{$var};

		if( $sub_var ) {
			if( !isset($return->{$sub_var}) ) return false;
			$return = $return->{$sub_var};
		}

		return $return;
	}


	/**
	 * Wrapper for $this->context;
	 */
	public function get_context()
	{
		return $this->context;
	}


	/**
	 * Wrapper for $this->course->ID
	 */
	public function get_course_id()
	{
		return $this->course->ID;
	}


	/**
	 * OUTPUT: true|false
	 *
	 * (1) return false for items that weren't touched before
	 */
	public function is_done( $object = null )
	{
		$object = $object ? $object : $this->{$this->context};

		switch ( $object->post_type ) {
			case 'course':
			case 'unit':
				return $this->get_progress( $object ) === 100 ? true : false;

			case 'lesson':
				if( $this->lesson->ID == $object->ID && $this->first_touch ) return false;		/* (1) */
				return $this->get_value( 'lesson', $object->ID, 'touched' ) ? true : false;

			case 'exam':
			case 'quizz':
				return $this->get_value( 'quizz', $object->ID, 'passed' ) ? true : false;

			default:
				return false;
		}
	}


	/**
	 * Wrapper function for $this->get_num_items_total() in a post object contect
	 * INPUT: post object
	 * OUTPUT: total number of lessons|quizzes for that object
	 */
	protected function num_items( $object = null, $type )
	{

		$object = $object ? $object : $this->{$this->context};
		$return = $this->get_num_items_total( $object, $type );

		return $return;
	}
	public function num_lessons( $object = null ) { return $this->num_items( $object, 'lesson' ); }
	public function num_quizzes( $object = null ) { return $this->num_items( $object, 'quizz' ); }


	/**
	 * Get the progress (to completion) of a course or unit in percent
	 * INPUT: post object
	 * OUTPUT: (int) 0-100
	 */
	public function get_progress( $object = null )
	{
		return (int)$this->get_value( $object->post_type, $object->ID, 'progress' );
	}


	/**
	 * INPUT: Type string (e.g. 'unit') or Post Object
	 */
	public function get_url_to( $input = null )
	{
		$object = is_object($input) ? $input : ( is_string($input) ? $this->{$input} : $this->{$this->context} );
		return gt_get_permalink( $object, $this->course, $this->unit );
	}


	/**
	 * INPUT:
	 *   $input -> Type string (e.g. 'unit') or Post Object
	 *   %args  -> possible arguments:
	 *              'class'     = css class
	 *              'title'     = link title="" attribute
	 *              'attribute' = any attribute, eg. disabled
	 *              'display'   = the link text or label (should be renamed label)
	 */
	public function get_link_to( $input = null, $args = array() )
	{
		$object = is_object($input) ? $input : ( is_string($input) ? $this->{$input} : $this->{$this->context} );
		return sprintf( '<a class="%1$s" href="%2$s" title="%3$s" %4$s>%5$s</a>',
			isset($args['class']) ? $args['class'] : 'a--bodycolor',
			$this->get_url_to( $object ),
			isset($args['title']) ? $args['title'] : the_title_attribute( array( 'before' => __('Permalink to: ', 'gladtidings'), 'echo' => false, 'post' => $object ) ),
			isset($args['attribute']) ? $args['attribute'] : '',
			isset($args['display']) ? $args['display'] : $object->post_title
		);
	}


	/**
	 * Wrapper to print get_link_to()
	 */
	public function print_link_to( $input = null, $args = array() )
	{
		print( $this->get_link_to( $input, $args ) );
	}


	/*=======================*\
		Breadcrumb Functions
	\*=======================*/


	/**
	 * Get an array with all the breadcrumbs for the current site
	 */
	public function get_breadcrumbs()
	{
		$return = array();

		switch ( $this->context ) {
			case 'lesson':
			case 'quizz':
				$return[] = $this->{$this->context};
			case 'unit':
				if( !$this->is_exam ) $return[] = $this->unit;
			case 'course':
				$return[] = $this->course;
			default:
				$return[] = 'home';
		}

		return array_reverse( $return );

	}


	/**
	 * Print the Link for one breadcrumb
	 * Basically a fancy wrapper for print_link_to()
	 */
	public function print_crumb_link( $crumb )
	{
		$args = array();

		switch ( $crumb->post_type ) {
			case 'lesson':
				$args['display'] = sprintf( __('Lesson %d', 'gladtidings'),  $crumb->order );
				break;
			case 'unit':
				$args['display'] = sprintf( __('Unit %d', 'gladtidings'),  $crumb->order );
				break;
		}

		$this->print_link_to( $crumb, $args );
	}

}
