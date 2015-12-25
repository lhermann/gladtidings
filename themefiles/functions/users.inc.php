<?php

/*------------------------------------*\
    Save Progress
\*------------------------------------*/

/*
Dummy user meta array

$course = array(
	'ID' => 0,
	'slug' => '',
	'units' => array (
		1 => array (
			'term_id' =>
			'name' =>
			'items' => array(
				0 => array(
					'type' => 'lesson|quizz',
					'ID' => 0,
					'passed' => true|false
				)
			)
		)
	)
);


*/

$test = 'some sentence';

class GladTidings
{
	
	private $course;
	private $unit;
	private $lesson;
	// private $user;

	public $user_id;
	public $user_meta;

	function __construct( $wp )
	{	
		
		$this->user_id = wp_get_current_user() ? (int)wp_get_current_user()->data->ID : false;
		$this->user_meta = $this->get_user_meta();

	}

	/*=======================*\
		Global Functions
	\*=======================*/

	private function get_user_meta()
	{
		global $wpdb;
		$query_str = "SELECT meta_key, meta_value 
						FROM $wpdb->usermeta 
						WHERE user_id = $this->user_id
							AND meta_key LIKE 'course_%'
							OR meta_key LIKE 'unit_%'
							OR meta_key LIKE 'lesson_%'
							OR meta_key LIKE 'quizz_%'";
		$rows = $wpdb->get_results( $query_str, OBJECT );
		$return = new stdClass();
		foreach ( $rows as $row ) {
			$return->{reset($row)} = (int)end($row);
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
	private function get_item_value( $scope, $ID, $name )
	{
		$key = "{$scope}_{$ID}_{$name}";

		if( isset($this->user_meta->{$key}) ) {
			return (int)$this->user_meta->{$key};
		} else {
			return false;
		}
	}

	/**
	 * INPUT: 
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$type = 'lesson'|'quizz'
	 * OUTPUt: DB entry existed true|false
	 */
	private function increase_items_done( $scope, $ID, $type )
	{	
		$key = "{$scope}_{$ID}_{$type}_done";
		$value = isset($this->user_meta->{$key}) ? $this->user_meta->{$key} + 1 : 1;
		update_user_meta( $this->user_id, "{$scope}_{$ID}_{$type}_done", $value );
		$this->user_meta->{$key} = $value;

		return $new_value === 1 ? false : true;
	}

	/**
	 * INPUT: 
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 * OUTPUt: DB entry existed true|false
	 */
	private function touch( $scope, $ID )
	{
		$key = "{$scope}_{$ID}_touched";
		$isset = isset($this->user_meta->{$key});
		update_user_meta( $this->user_id, $key, time() );
		$this->user_meta->{$key} = time();

		return $isset;
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
		Course Functions
	\*=======================*/

	/**
	 * Course User Meta Entries
	 * 
	 * Meta Key 					Meta Value
	 * course_{ID}_touched			timestamp
	 * course_{ID}_lessons_done		int
	 * course_{ID}_quizzes_done		int
	 */

	// Initiate course
	public function course_init( $object )
	{
		// init $course
		$this->course_setup( $object );
		// touch
		$existed = $this->touch( 'course', $this->course->ID );
	}

	// Setup course variable
	public function course_setup( $course )
	{
		$this->course = $course;
	}



	// Retrieve total number of lessons|quizzes
	private function course_item_total( $type )
	{
		try {
			if( $this->course === null ) throw new Exception('Call course_setup() first!');
			return (int)get_post_meta( $this->course->ID, "num_$type", true );
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	public function course_lessons_total() { return $this->course_item_total( 'lessons' ); }
	public function course_quizzes_total() { return $this->course_item_total( 'quizzes' ); }

	// Retrieve number of completed lessons|quizzes
	private function course_item_done( $type ) {
		return (int)get_user_meta( $this->user_id, "course_{$this->course->ID}_{$type}_done", true );
	}
	public function course_lessons_done() { return $this->course_item_done( 'lessons' ); }
	public function course_quizzes_done() {	return $this->course_item_done( 'quizzes' ); }

	// Get course progress percentage
	public function course_progress()
	{
		$total = $this->course_lessons_total() + $this->course_quizzes_total();
		$done = $this->course_lessons_done() + $this->course_quizzes_done();
		return $done == 0 ? $done : round( ( $done / $total ) * 100 );
	}

	public function course_increase_lessons_done()
	{
		$this->increase_items_done( 'course', $this->course->ID, 'lessons' );
	}

	/*=======================*\
		Unit Functions
	\*=======================*/

	/**
	 * Course User Meta Entries
	 * 
	 * Meta Key 					Meta Value
	 * unit_{$term_id}_touched			timestamp
	 * unit_{$term_id}_lessons_done		int
	 * unit_{$term_id}_quizzes_done		int
	 */

	// Initiate unit
	public function unit_init( $term )
	{
		// init $unit
		$unit = get_unit_meta( $term );
		$this->unit_setup( $unit );
		// init $course
		$course = new stdClass();
		$course->ID = (int)$this->unit->course_object_id;
		$this->course_setup( $course );
		// touch
		$existed = $this->touch( 'unit', $this->unit->term_id );
	}

	// Setup unit variable
	public function unit_setup( $unit )
	{
		$this->unit = $unit;
	}

	public function unit_increase_lessons_done()
	{
		$this->increase_items_done( 'unit', $this->unit->term_id, 'lessons' );
	}

	// Retrieve total number of lessons|quizzes
	private function unit_item_total( $type )
	{
		try {
			if( $this->unit === null ) throw new Exception('Call unit_setup() first!');
			return (int)$this->unit->{'num_'.$type};
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function unit_lessons_total() { return $this->unit_item_total( 'lessons' ); }
	public function unit_quizzes_total() { return $this->unit_item_total( 'quizzes' ); }

	public function unit_progress()
	{
		$items_total = $this->unit_lessons_total() + $this->unit_quizzes_total();
		$items_done = $this->get_item_value( 'unit', $this->unit->term_id, 'lessons_done' ) + $this->get_item_value( 'unit', $this->unit->term_id, 'quizzes_done' );
		if( !$items_total || !$items_done ) return 0;
		return (int)round( $items_done / $items_total * 100 );
	}

	/*=======================*\
		Lesson Functions
	\*=======================*/

	/**
	 * Lesson User Meta Entries
	 * 
	 * Meta Key 					Meta Value
	 * lesson_{ID}_touched			timestamp
	 */

	public function lesson_setup( $object )
	{
		// init $lesson
		$this->lesson = $object;
		// init $unit
		$this->unit = get_unit( $object->ID );
		// init $course
		$this->course = new stdClass();
		$this->course->ID = (int)$this->unit->course_object_id;

		// touch
		$existed = $this->touch( 'lesson', $this->lesson->ID );

		/*
		 * if $return is an integer, then the row didn't exist before
		 * --> increase 'course_lessons_done' and 'unit_lessons_done'
		 */
		if( !$existed ) {
			// increase 'course_lessons_done' and 'unit_lessons_done'
			$this->course_increase_lessons_done();
			$this->unit_increase_lessons_done();
		}
	}


}


add_action( 'wp', 'instantiate_GladTidings', 10, 1 );

function instantiate_GladTidings( $wp ) {
	global $_gt;
	$_gt = new GladTidings( $wp );
}


/*------------------------------------*\
    Theme Activation / Deactivation
\*------------------------------------*/

/**
 * Add new user role 'student' on theme activation
 */

function gladtidings_activation_user() {
    // Add new User Role 'student'
    // with custom capability 'study'
    add_role( 
        'student',
        __( 'Student', 'gladtidings' ),
        array(
            'study' => true
        )
    );

    // Set 'student' as new default user role
    update_option( 'default_role', 'student', true );
}
add_action( 'after_switch_theme', 'gladtidings_activation_user' );


function gladtidings_deactivation_user () {
    // Delete user role 'student'
    remove_role( 'student' );
}
add_action('switch_theme', 'gladtidings_deactivation_user');