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
	private function get_value( $scope, $ID, $name )
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
	private function update_value( $scope, $ID, $name, $value )
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
	private function increment_value( $scope, $ID, $name )
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
	private function increment_items_done( $scope, $ID, $type )
	{	
		return $this->increment_value( $scope, $ID, "{$type}_done" );
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
			if( $type === 'headline' ) continue;
			$ID = (int)end($item);
			if( !$this->item_done( $type, $ID ) ) return $ID;
			// switch ($type) {
			// 	case 'lesson':
			// 		if( $this->get_value( 'lesson', (int)end($item), 'touched' ) ) continue;
			// 		break;
			// 	case 'quizz':
			// 		if( $this->get_value( 'quizz', (int)end($item), 'passed' ) ) continue;
			// 		break;
			// 	case 'headline':
			// 	default:
			// 		continue;
			// 		break;
			// }
			// return (int)end($item);
		}
		return false;
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

	// public function course_increase_lessons_done() { $this->increment_items_done( 'course', $this->course->ID, 'lessons' ); }

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

	// public function unit_increase_lessons_done() { $this->increment_items_done( 'unit', $this->unit->term_id, 'lessons' ); }

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
	public function unit_print_continue_btn()
	{
		$items_total = $this->get_num_items_total( 'unit' );
		$items_done = $this->get_num_items_done( 'unit' );

		// bail early
		if( $items_total === $items_done ) return;

		$btn_label = $items_done ? __( 'Continue learning', 'gladtidings' ) : __( 'Start learning', 'gladtidings' );

		$item_id = $this->find_first_undone_item();

		print( '<a class="layout__item u-pull--right btn btn--success" href="'.get_permalink($item_id).'">'.$btn_label.'</a>' );
		return;
	}


	/*====================================*\
		Leeson & Quizz Helper Functions
	\*====================================*/

	/**
	 * Return the continue button
	 */
	public function item_continue_btn( $ID, $attr = '' )
	{
		// get id of the next item
		$item_ids = array();
		foreach ($this->unit->lesson_order as $n) {
			if( (int)end($n) > 0 ) $item_ids[] = (int)end($n);
		}
		$next_item_id = $item_ids[ array_search( $ID, $item_ids ) + 1 ];

		// print button
		return sprintf ( '<a class="btn btn--success btn--success" href="%1$s" title="%2$s" %3$s>%2$s <i class="fi fi-arrow-right"></i></a>',
			esc_url( $next_item_id ? get_permalink( $next_item_id ) : get_term_link( $this->unit ).'?origin=continue' ),
			__( 'Next', 'gladtidings' ),
			$attr
		);
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
			$this->increment_items_done( 'course', $this->course->ID, 'lessons' );
			$this->increment_items_done( 'unit', $this->unit->term_id, 'lessons' );

		}
	}

	// Setup lesson variable
	public function lesson_setup( $lesson )
	{
		$this->lesson = $lesson;
	}

	/**
	 * Print out the continue button
	 */
	public function lesson_print_continue_btn()
	{
		print( $this->item_continue_btn( $this->lesson->ID ) );
	}

	/*=======================*\
		Quizz Functions
	\*=======================*/

	/**
	 * Lesson User Meta Entries
	 * 
	 * Meta Key 					Meta Value
	 * quizz_{ID}_touched			timestamp
	 * quizz_{ID}_passed			bool as int (1|0)
	 * quizz_{ID}_progress			int
	 * quizz_{ID}_question-x		array( 'key' => int, 'correct_answer' => string )
	 * quizz_{ID}_answer-x			string
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

		/**
		 * Set up view and evaluate $_POST
		 */
		switch ( get_query_var( 'view' ) ) {
			default:
			case 'introduction':
				$this->update_value( 'quizz', $this->quizz->ID, 'progress', 0 );
				break;
			case 'question':
				var_dump('POST',$_POST['answer']);
				if( isset($_POST['answer']) ) {
					// Increment progress and save answer
					$this->increment_value( 'quizz', $this->quizz->ID, 'progress' );
					$this->update_value( 'quizz', $this->quizz->ID, 'answer-'.$this->quizz_get_progress(), base64_decode($_POST['answer']) );
				} else {
					// First question is shown
					$this->update_value( 'quizz', $this->quizz->ID, 'progress', 0 );
					$this->quizz_purge_answers();
				};
				break;
			case 'evaluation':
				$this->quizz_evaluate();
				$this->update_value( 'quizz', $this->quizz->ID, 'progress', $this->quizz->required_questions );
				break;
		}

		if( $this->quizz_get_progress() >= $this->quizz->required_questions ) {
			$this->update_value( 'quizz', $this->quizz->ID, 'progress', $this->quizz->required_questions );
			set_query_var( 'view', 'evaluation' );
			$this->quizz_evaluate();
		}
	}

	// Setup quizz variable
	public function quizz_setup( $post )
	{
		$this->quizz = new stdClass();
		$this->quizz->ID = $post->ID;
		$this->quizz->required_questions = (int)get_field( 'required_questions', $post->ID );
		$this->quizz->questions_repeater = get_field( 'questions_repeater', $post->ID );
		// savety measure against timing out while loops
		if( $this->quizz->required_questions > count( $this->quizz->questions_repeater ) ) $this->quizz->required_questions = count( $this->quizz->questions_repeater );
		$this->quizz->mistakes = 0; // used by $this->quizz_evaluate; and $this->quizz_num_mistakes;
	}

	/**
	 * Mark a quizz as passed
	 */
	public function quizz_is_passed()
	{
		return (bool)$this->get_value( 'quizz', $this->quizz->ID, 'passed' );
	}

	/**
	 * evaluate the chosen answers
	 * -> save number of mistakes
	 * -> set quizz as 'passed' if no mistakes were made
	 */
	public function quizz_evaluate()
	{
		$answers = $this->quizz_get_answers();
		$score = 0;
		foreach ( $answers as $key => $answer ) {
			if( $answer['given_answer'] === $answer['correct_answer'] ) $score++;
		}
		$this->quizz->mistakes = $this->quizz->required_questions - $score;
		if( !$this->quizz->mistakes && !$this->quizz_is_passed() ) $this->quizz_set_passed();
	}

	/**
	 * wrapper for $this->quizz->mistakes
	 */
	public function quizz_num_mistakes() { return $this->quizz->mistakes; }

	/**
	 * wrapper for $this->quizz->required_questions
	 */
	public function quizz_num_questions() { return $this->quizz->required_questions; }

	/**
	 * Mark a quizz as passed
	 */
	public function quizz_set_passed()
	{
		$this->update_value( 'quizz', $this->quizz->ID, 'passed', true );
		// increase 'course_quizzes_done' and 'unit_quizzes_done'
		$this->increment_items_done( 'course', $this->course->ID, 'quizzes' );
		$this->increment_items_done( 'unit', $this->unit->term_id, 'quizzes' );
	}

	/**
	 * Returns the current progress within in the quizz
	 */
	public function quizz_get_progress()
	{
		$progress = $this->get_value( 'quizz', $this->quizz->ID, 'progress' );
		return $progress < 0 ? 0 : $progress;
	}

	/**
	 * Returns 'true' if the quizz has been completed (all wuestions answered) regardless of passing or not
	 */
	public function quizz_is_completed()
	{
		return $this->get_value( 'quizz', $this->quizz->ID, "question-{$this->quizz->required_questions}" ) ? true : false;
	}

	/**
	 * Returns the next question as an array
	 * INPUT: array of questions
	 */
	public function quizz_get_question()
	{
		$questions = $this->quizz->questions_repeater;
		$previous_answers = $this->quizz_get_answers();

		// unset all used keys
		foreach ( $previous_answers as $key => $answer ) {
			if( $answer['given_answer'] != NULL ) unset( $questions[$answer['key']] );
		}

		// get random key of remaining questions
		$key = array_rand( $questions );
		$question = $questions[$key];

		$question['answers'] = explode( "\r\n", $question['answers'] );

		$progress = $this->get_value( 'quizz', $this->quizz->ID, 'progress' ) + 1;

		$this->update_value( 'quizz', $this->quizz->ID, "question-{$progress}", array( 'key' => $key, 'correct_answer' => reset($question['answers']) ) );

		shuffle( $question['answers'] );
		
		return $question;
	}

	/**
	 * Get an array with the shown questions and given answers
	 * OUTPUT:  array(
	 *				'key' 			 => int
	 *				'correct_answer' => string
	 *				'given_answer'   => string
	 * 			)
	 */
	public function quizz_get_answers()
	{
		$answers = array();
		for ( $i = 1; $i <= $this->quizz->required_questions; $i++ ) { 
			$temp = $this->get_value( 'quizz', $this->quizz->ID, "question-$i" );
			if( !$temp ) continue;
			$answers[$i] = $temp;
			$answers[$i]['given_answer'] = $this->get_value( 'quizz', $this->quizz->ID, "answer-$i" );
		}
		return $answers;
	}

	/**
	 * Delete question/answer cache for a quizz
	 * NOTE: Unused at the moment
	 */
	public function quizz_purge_answers()
	{
		global $wpdb;
		$query_str = "DELETE FROM $wpdb->usermeta 
						WHERE user_id = $this->user_id
						AND ( 
							meta_key LIKE '%s'
						 OR meta_key LIKE '%s'
						)";
		$return = $wpdb->query( 
			$wpdb->prepare( $query_str, 
					"quizz_{$this->quizz->ID}_question-%", 
					"quizz_{$this->quizz->ID}_answer-%"
				)
		);
		// purge them also in $this->user_meta
		foreach ( array('question', 'answer') as $name ) {
			for ( $i=1; $i <= $this->quizz->required_questions; $i++ ) { 
				$key = "quizz_{$this->quizz->ID}_{$name}-{$i}";
				unset( $this->user_meta->{$key} );
			}
		}
		return $return;
	}

	/**
	 * Returns the quizz wrap-up info
	 * INPUT: array of questions
	 */
	public function quizz_get_evaluation()
	{
		$questions = $this->quizz->questions_repeater;
		$answers = $this->quizz_get_answers();

		$return = array();
		foreach ($answers as $key => $answer) {
			$return[] = array(
				'title' 		 => $questions[$answer['key']]['question_text'],
				'given_answer' 	 => $answer['given_answer'],
				'correct'		 => $answer['given_answer'] === $answer['correct_answer'],
				'related_lesson' => $questions[$answer['key']]['related_lesson']
			);
		}

		return $return;
	}

	/**
	 * Print hgroup for quizzes
	 * Needs to be translatable strings
	 */
	public function quizz_print_hgroup()
	{
		switch ( get_query_var( 'view' ) ) {
			default:
			case 'introduction':
				$subtitle = __( 'Introduction', 'gladtidings' );
				break;
			case 'question':
				$subtitle = __( 'Question', 'gladtidings' ).' '.($this->quizz_get_progress()+1);
				break;
			case 'evaluation':
				$subtitle = __( 'Evaluation', 'gladtidings' );
				break;
		}

		$label_order = sprintf( '<span class="label label--small label--theme">%s</span>', get_post_meta( $this->quizz->ID, 'order_nr', true ) );	

		printf( '<header class="hgroup">%s %s</header>',
			'<h1 class="hgroup__title">'.$label_order.' '.get_the_title().'</h1>',
			'<h2 class="hgroup__subtitle">'.$subtitle.'</h2>'
		);	
	}

	/**
	 * Print the Progress bar meter
	 */
	public function quizz_print_progress_bar()
	{
		// printf( '<div class="progress"><span class="progress__meter t-comp-bg" style="width: %s">%s</span></div>',
		// 	floor( $this->quizz_get_progress() / $this->quizz->required_questions * 100 ).'%',
		// 	$this->quizz_get_progress().'/'.$this->quizz->required_questions
		// );

		$progress_pills = array();
		for ($i=0; $i < $this->quizz->required_questions; $i++) { 
			$progress_pills[] = sprintf( '<div class="progress__item %s"></div>',
				$i < $this->quizz_get_progress() ? 't-comp-bg' : ''
			);
		}

		// <h3 class="progress__label">%s<span class="u-screen-reader-text">%s</span></h3>

		printf( '<div class="progress progress--pills" title="%s">%s</div>',
			// __( 'Progress:', 'gladtidings' ),
			sprintf( __( 'Progress: %d/%d Questions', 'gladtidings' ), $this->quizz_get_progress(), $this->quizz->required_questions ),
			implode( $progress_pills )
		);
	}

	/**
	 * Print out the continue button
	 */
	public function quizz_print_continue_btn()
	{
		$attr = !$this->quizz_is_passed() ? 'disabled' : '';
		print( $this->item_continue_btn( $this->quizz->ID, $attr ) );
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
				return $this->get_value( $type, $ID, 'touched' ) ? true : false;
				break;

			case 'quizz':
				return $this->get_value( $type, $ID, 'passed' ) ? true : false;
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