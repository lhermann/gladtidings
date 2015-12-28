<?php

/*------------------------------------*\
    Save Progress
\*------------------------------------*/

class GladTidings
{
	
	private $course;
	private $unit;
	private $lesson;
	private $quizz;
	// private $user;

	private $first_touch;

	public $user_id;
	public $user_name;
	public $user_meta;

	function __construct( $wp )
	{	
		$this->first_touch = false;

		$this->user_id = wp_get_current_user() ? (int)wp_get_current_user()->data->ID : false;
		$this->user_name = wp_get_current_user() ? wp_get_current_user()->data->display_name : false;
		$this->user_meta = $this->get_user_meta();

	}

	/*=======================*\
		Private Functions
	\*=======================*/

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

	// Get number of completed lessons|quizzes for course|unit
	private function get_num_items_done( $scope, $type = 'all' )
	{

		try {
			if( $this->{$scope} === null ) throw new Exception("Call {$scope}_setup() first!");

			$ID = $scope == 'course' ? $this->course->ID : $this->unit->term_id;

			switch ($type) {
				case 'lessons':
				case 'quizzes':
					return (int)$this->get_item_value( $scope, $ID, "{$type}_done" );
					break;
				case 'all':
				default:
					return (int)$this->get_item_value( $scope, $ID, "lessons_done" ) + (int)$this->get_item_value( $scope, $ID, "quizzes_done" );
					break;
			}

		} catch (Exception $e) {
			echo 'Line '.__LINE__.': Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}

	}

	// Get total number of lessons|quizzes
	private function get_num_items_total( $scope, $type = 'all' )
	{
		try {
			if( $this->{$scope} === null ) throw new Exception("Call {$scope}_setup() first!");

			$ID = $scope == 'course' ? $this->course->ID : $this->unit->term_id;

			switch ($type) {
				case 'lessons':
				case 'quizzes':
					return (int)$this->{$scope}->{"num_{$type}"};
					break;
				case 'all':
				default:
					return (int)$this->{$scope}->{"num_lessons"} + (int)$this->{$scope}->{"num_quizzes"};
					break;
			}

			// return (int)get_post_meta( $this->course->ID, "num_$type", true );

		} catch (Exception $e) {
			echo 'Line '.__LINE__.': Caught exception: ',  $e->getMessage(), "\n";
			return false;
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
	private function update_value( $scope, $ID, $name, $value )
	{
		$key = "{$scope}_{$ID}_{$name}";
		$isset = isset($this->user_meta->{$key});
		$this->user_meta->{$key} = $value;
		update_user_meta( $this->user_id, $key, (int)$value );
		return $isset;
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

		return $this->update_value( $scope, $ID, "{$type}_done", $value );
	}

	/**
	 * INPUT: 
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 * OUTPUt: DB entry existed true|false
	 */
	private function touch( $scope, $ID )
	{
		return $this->update_value( $scope, $ID, 'touched', time() );
	}

	/**
	 * OUTPUT: first item (lesson|quizz) of a unit that has not yet been touched
	 */
	private function find_first_undone_item()
	{
		foreach ( $this->unit->lesson_order as $key => $item ) {
			$type = explode( '_', reset($item) )[1];
			switch ($type) {
				case 'lesson':
					if( $this->get_item_value( 'lesson', (int)end($item), 'touched' ) ) continue;
					break;
				case 'quizz':
					if( $this->get_item_value( 'quizz', (int)end($item), 'passed' ) ) continue;
					break;
				case 'headline':
				default:
					continue;
					break;
			}
			return (int)end($item);
		}
		return false;
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
		// setup $course
		$this->course_setup( $object );
		// touch
		$existed = $this->touch( 'course', $this->course->ID );
	}

	// Setup course variable
	public function course_setup( $course )
	{
		$this->course = $course;
	}

	// Get total number of lessons|quizzes
	public function course_lessons_total() { return $this->get_num_items_total( 'course', 'lessons' ); }
	public function course_quizzes_total() { return $this->get_num_items_total( 'course', 'quizzes' ); }

	// Get number of completed lessons|quizzes
	public function course_lessons_done() { return $this->get_num_items_done( 'course', 'lessons' ); }
	public function course_quizzes_done() { return $this->get_num_items_done( 'course', 'quizzes' ); }

	// Get course progress percentage
	public function course_progress()
	{
		$total = $this->get_num_items_total( 'course' );
		$done = $this->get_num_items_done( 'course' );
		return $done == 0 ? $done : round( ( $done / $total ) * 100 );
	}

	// public function course_increase_lessons_done() { $this->increase_items_done( 'course', $this->course->ID, 'lessons' ); }

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
		// setup $unit
		$unit = get_unit_meta( $term );
		$this->unit_setup( $unit );
		// setup $course
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

	// public function unit_increase_lessons_done() { $this->increase_items_done( 'unit', $this->unit->term_id, 'lessons' ); }

	// Get total number of lessons|quizzes
	// public function unit_lessons_total() { return $this->get_num_items_total( 'unit', 'lessons' ); }
	// public function unit_quizzes_total() { return $this->get_num_items_total( 'unit', 'quizzes' ); }

	// Get number of completed lessons|quizzes
	// public function unit_lessons_done() { return $this->get_num_items_done( 'unit', 'lessons' ); }
	// public function unit_quizzes_done() { return $this->get_num_items_done( 'unit', 'quizzes' ); }

	/**
	 * OUTPUT: (int) Unit Progress in percentage
	 */
	public function unit_progress()
	{
		$items_total = $this->get_num_items_total( 'unit' );
		$items_done = $this->get_num_items_done( 'unit' );

		if( !$items_total || !$items_done ) return 0;
		return (int)round( $items_done / $items_total * 100 );
	}

	/**
	 * OUTPUT: (string) html to print the continue button, empty string if unit is finidhed
	 */
	public function unit_continue_btn()
	{
		$items_total = $this->get_num_items_total( 'unit' );
		$items_done = $this->get_num_items_done( 'unit' );

		// bail earlu
		if( $items_total === $items_done ) return '';

		$btn_label = $items_done ? __( 'Continue learning', 'gladtidings' ) : __( 'Start learning', 'gladtidings' );

		$item_id = $this->find_first_undone_item();

		print( '<a class="layout__item u-pull--right btn btn--success" href="'.get_permalink($item_id).'">'.$btn_label.'</a>' );
		return;
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


	// Initiate lesson
	public function lesson_init( $post )
	{
		// setup $lesson
		$this->lesson_setup( $post );
		// setup $unit
		$unit = get_unit( $post->ID );
		$this->unit_setup( $unit );
		// setup $course
		$course = new stdClass();
		$course->ID = (int)$this->unit->course_object_id;
		$this->course_setup( $course );

		// touch
		$this->first_touch = !$this->touch( 'lesson', $this->lesson->ID );

		/*
		 * if $return is an integer, then the row didn't exist before
		 * --> increase 'course_lessons_done' and 'unit_lessons_done'
		 */
		if( $this->first_touch ) {
			// increase 'course_lessons_done' and 'unit_lessons_done'
			$this->increase_items_done( 'course', $this->course->ID, 'lessons' );
			$this->increase_items_done( 'unit', $this->unit->term_id, 'lessons' );

		}
	}

	// Setup lesson variable
	public function lesson_setup( $lesson )
	{
		$this->lesson = $lesson;
	}

	/**
	 * OUTPUT: true|false
	 */
	// public function lesson_done()
	// {
	// 	try {
	// 		if( $this->lesson === null ) throw new Exception('Call lesson_setup() first!');
	// 		return $this->get_item_value( 'lesson', $this->lesson->ID, 'touched' ) ? true : false;
	// 	} catch (Exception $e) {
	// 		echo 'Caught exception: ',  $e->getMessage(), "\n";
	// 	}
	// }

	/*=======================*\
		Quizz Functions
	\*=======================*/

	/**
	 * Lesson User Meta Entries
	 * 
	 * Meta Key 					Meta Value
	 * quizz_{ID}_touched			timestamp
	 * quizz_{ID}_passed			bool as int (1|0)
	 */


	// Initiate quizz
	public function quizz_init( $post )
	{
		// setup $quizz
		$this->quizz_setup( $post );
		// setup $unit
		$unit = get_unit( $post->ID );
		$this->unit_setup( $unit );
		// setup $course
		$course = new stdClass();
		$course->ID = (int)$this->unit->course_object_id;
		$this->course_setup( $course );

		// touch
		$this->first_touch = !$this->touch( 'quizz', $this->quizz->ID );

		/*
		 * if $return is an integer, then the row didn't exist before
		 * --> increase 'course_quizzes_done' and 'unit_quizzes_done'
		 */
		if( $this->first_touch ) {
			// set 'passed' to false
			$this->update_value( 'quizz', $this->quizz->ID, 'passed', false );
		}
	}

	// Setup quizz variable
	public function quizz_setup( $quizz )
	{
		$this->quizz = $quizz;
	}

	public function quizz_passed( $quizz )
	{
		$this->update_value( 'quizz', $this->quizz->ID, 'passed', true );
		// increase 'course_quizzes_done' and 'unit_quizzes_done'
		$this->increase_items_done( 'course', $this->course->ID, 'quizzes' );
		$this->increase_items_done( 'unit', $this->unit->term_id, 'quizzes' );
	}

	/*=======================*\
		Global Functions
	\*=======================*/

	/**
	 * OUTPUT: true|false
	 *
	 * (1) return false for items that weren't touched before
	 */
	public function item_done( $type, $ID )
	{
		switch ($type) {
			case 'lesson':
				if( $this->lesson->ID == $ID && $this->first_touch ) return false;		/* (1) */
				return $this->get_item_value( $type, $ID, 'touched' ) ? true : false;
				break;

			case 'quizz':
				return $this->get_item_value( $type, $ID, 'passed' ) ? true : false;
				break;

			default:
				return false;
				break;
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