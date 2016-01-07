<?php
/*------------------------------------*\
    Global Controller
\*------------------------------------*/

/**
 * TODO:
 */


class GladTidingsMasterController
{

	protected $course;
	protected $unit;
	protected $lesson;
	protected $quizz;

	public $this_type;

	protected $first_touch;

	public $user_id;
	public $user_name;
	protected $user_meta;

	function __construct( $post )
	{	
		$this->this_type = $post->post_type;

		$this->first_touch = false;

		$this->user_id = wp_get_current_user() ? (int)wp_get_current_user()->data->ID : false;
		$this->user_name = wp_get_current_user() ? wp_get_current_user()->data->display_name : false;
		$this->user_meta = $this->get_user_meta();

	}

	/*=======================*\
		Private Functions
	\*=======================*/

	/**
	 * OUTPUT: object with all the user meta
	 */
	protected function get_user_meta()
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
			if( !$this->is_done( $type, $ID ) ) return $ID;
		}
		return false;
	}

	// Get number of completed lessons|quizzes for course|unit
	protected function get_num_items_done( $scope, $type = 'all' )
	{

		try {
			if( $this->{$scope} === null ) throw new Exception("\$this->{$scope} is NULL!");

			$ID = $scope == 'course' ? $this->course->ID : $this->unit->term_id;

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
			echo 'Line '.__LINE__.': Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}

	}

	// Get total number of lessons|quizzes
	protected function get_num_items_total( $scope, $type = 'all', $ID = false )
	{
		// var_dump($this->{$scope});
		try {
			if( $this->{$scope} == null ) throw new Exception("\$this->{$scope} is NULL!");

			// prepare variables
			$ID = $ID ? $ID : $this->{$scope}->ID;
			$type = $this->get_singular( $type );

			// see if there is a cached value
			$key = "num_{$type}_total";
			$value = $this->get_value( $scope, $ID, $key );
			if( $value ) return (int)$value;
			
			// do a database query
			global $wpdb;
			$table_name = $wpdb->prefix . "gt_relationships";

			// built query
			$query = "SELECT COUNT(p.ID) AS num";

			switch ( $scope ) {
				case 'course':
					$query .= "
						FROM $table_name c
						INNER JOIN $table_name u
						ON c.child_id = u.parent_id
						INNER JOIN $wpdb->posts p
						ON u.child_id = p.ID
						WHERE c.parent_id = $ID
					";
					break;
				case 'unit':
					$query .= "
						FROM $table_name u
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
			echo 'Line '.__LINE__.': Caught exception: ',  $e->getMessage(), "\n";
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
	 * OUTPUT: true|false
	 *
	 * (1) return false for items that weren't touched before
	 */
	public function is_done( $type, $ID )
	{
		switch ( $type ) {
			case 'lesson':
				if( $this->lesson->ID == $ID && $this->first_touch ) return false;		/* (1) */
				return $this->get_value( 'lesson', $ID, 'touched' ) ? true : false;
				break;

			case 'quizz':
				return $this->get_value( 'quizz', $ID, 'passed' ) ? true : false;
				break;

			default:
				return false;
				break;
		}
	}


	/**
	 * Return the current user specific status
	 *  - 'success' - the object is sucessfully finished
	 *  - 'active'  - the object was started, but is not finished (user specific)
	 *  - otherwise return $object->post_status
	 */
	public function get_status( $object )
	{
		switch ( $object->post_type ) {
			case 'unit':
				switch ( $this->get_progress( $object ) ) {
					case 100: return 'success';            break;
					case 0:   return $object->post_status; break;
					default:  return 'active';             break;
				}
				break;
			
			case 'lesson':
			case 'quizz':
				if( $this->is_done( $object->post_type, $post->ID ) ) return 'success';
				return $object->post_status;
				break;

			default:
				break;
		}
		return $object->post_status;
	}


	/**
	 * Wrapper function for $this->get_num_items_total() in a post object contect
	 * INPUT: post object
	 * OUTPUT: total number of lessons|quizzes for that object
	 */
	protected function num_items( $object, $type )
	{
		$temp = $this->{$object->post_type};
		
		$this->{$object->post_type} = $object;
		$return = $this->get_num_items_total( $object->post_type, $type, $object->ID );
		
		$this->{$object->post_type} = $temp;
		return $return;
	}
	public function num_lessons( $object ) { return $this->num_items( $object, 'lesson' ); }
	public function num_quizzes( $object ) { return $this->num_items( $object, 'quizz' ); }


	/**
	 * 
	 * INPUT: post object
	 * OUTPUT:
	 */
	public function get_progress( $object )
	{
		$temp = $this->{$object->post_type};

		$this->{$object->post_type} = $object;
		$total = $this->get_num_items_total( $object->post_type );
		$done = $this->get_num_items_done( $object->post_type );
		
		$this->{$object->post_type} = $temp;
		return $done == 0 ? $done : round( ( $done / $total ) * 100 );
	}


}