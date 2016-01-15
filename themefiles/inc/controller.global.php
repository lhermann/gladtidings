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
	protected $exam;
	protected $lesson;
	protected $quizz;

	protected $context;

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

		// update object status
		$object = $this->object_status( $object );
		$object = $this->object_relationship( $object );

		// setupt context
		$this->setup_context( $object );

		// touch
		$existed = $this->touch( $object->post_type, $object->ID );
		$this->first_touch = $existed ? false : true;
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
	 * Update the status of an object and return the updated object
	 * If the status is ...
	 *  - 'coming'  = date has passed ? set to 'publish' (also update post object and acf field)
	 *                note: change from 'coming' to 'publish' should only happen once
	 *  - 'locked'  = unlock condition has been met ? fall through to 'publish' : don't change
	 *  - 'publish' = user has started ? (active) or finished ? (success) the object : don't change
	 */
	protected function object_status( $object, $object_array = null )
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
				$dependency_object = $object_array[ (int)get_post_meta( $object->ID, 'unlock_dependency', true ) - 1 ];
				if( $this->is_done( $dependency_object ) ) {
					$object->post_status = 'publish'; // fall through
				} else {
					$object->unlock_dependency_title = $dependency_object->post_title;
					break;
				}

			case 'publish':
				if( $this->is_done( $object ) ) {
					$object->post_status = 'success';
				} elseif( $this->get_progress( $object ) > 0 ) {
					$object->post_status = 'active';
				}
				break;
		}
		return $object;
	}

	protected function object_relationship( $object )
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
		Protexted Functions
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
	 * OUTPUT: first item (lesson|quizz) of a unit that has not yet been touched
	 */
	protected function find_first_undone_item()
	{
		foreach ( $this->unit->lesson_order as $key => $item ) {
			$type = explode( '_', reset($item) )[1];
			if( $type === 'headline' ) continue;
			$ID = (int)end($item);
			if( !$this->is_done( $this->unit ) ) return $ID;
		}
		return false;
	}

	// Get number of completed lessons|quizzes for course|unit
	protected function get_num_items_done( $object, $type = 'all' )
	{

		try {
			if( $object === null ) throw new Exception("\$object is NULL!");

			// prepare variables
			$scope = $object->post_type;
			$ID    = $object->ID;

			switch ($type) {
				case 'lessons':
				case 'quizzes':
					return (int)$this->get_value( $scope, $ID, "{$type}_done" );
					break;
				case 'all':
				default:
					return (int)$this->get_value( $scope, $ID, "lessons_done" ) + (int)$this->get_value( $scope, $ID, "quizzes_done" );
					break;
			}

		} catch (Exception $e) {
			echo 'Line '.__LINE__.': Caught exception: ', $e->getMessage(), "\n";
			return false;
		}

	}

	// Get total number of lessons|quizzes
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


	/*=======================*\
		User Functions
	\*=======================*/

	/**
	 * Current user is allowed to access the study area
	 */
	public function user_can_study()
	{
		return is_user_logged_in() && ( current_user_can( 'study' ) || current_user_can( 'edit_post' ) ) ? true : fale;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 * Set up a context
	 * INPUT: a post object
	 */
	public function setup_context( $object )
	{
		try {

			if( !is_object($object) || !isset($object->ID) ) throw new Exception("Input has to be a post object.");
			if( $object->post_type == $this->parent_context ) throw new Exception("Cannot overwrite context.");

			$this->context = $object->post_type;
			$this->{$object->post_type} = $object;
			
		} catch (Exception $e) {
			echo 'GTGlobal '.__LINE__.' - Caught exception: ', $e->getMessage(), "\n";
		}
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
	public function is_done( $object )
	{
		switch ( $object->post_type ) {
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
	 * Return the current post status
	 * wrapper for $this->{context}->post_status
	 * OBSOLETE: should be accessed through $post->post_status
	 */
	// public function get_status( $context = null )
	// {
	// 	if( !$context ) $context = $this->context;
	// 	return $this->{$context}->post_status;
	// }


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
	 * 
	 * INPUT: post object
	 * OUTPUT:
	 */
	public function get_progress( $object = null )
	{
		$object = $object ? $object : $this->{$this->context};

		$total = $this->get_num_items_total( $object );
		$done = $this->get_num_items_done( $object );

		if( !$total || !$done ) return 0;
		
		return (int)round( ( $done / $total ) * 100 );
	}

}