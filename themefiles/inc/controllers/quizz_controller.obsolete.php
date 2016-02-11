<?php
/*------------------------------------*\
    Single Quizz Controller
\*------------------------------------*/

// require shared functions for lessons and quizzes
require( 'controller.shared.item.php' );


class GTView extends GTItem
{

	public $required_questions;
	public $all_questions;
	public $current_question;
	public $mistakes;

	function __construct( &$object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// identify exam
		if( $object->order < 0 ) $this->is_exam = true;

		// prepare flyout
		if( !$this->is_exam ) {
			add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );
		}

		// set 'passed' to false on first touch
		if( $this->first_touch ) {
			$this->update_value( 'quizz', $this->quizz->ID, 'passed', false );
		}

		// Quizz specific object setup
		$this->required_questions = $i = (int)get_field( 'required_questions', $post->ID );
		$this->all_questions = get_field( 'questions_repeater', $post->ID );

		// savety measure against timing out while loops
		$count = count( $this->all_questions );
		$this->required_questions = $i > $count ? $count : $i;

		// temp value used by $this->quizz_evaluate; and $this->quizz_num_mistakes;
		$this->mistakes = 0;

		/**
		 * Set up view and evaluate $_POST
		 */
		switch ( get_query_var( 'view' ) ) {
			default:
			case 'introduction':
				$this->update_value( 'quizz', $this->quizz->ID, 'step', 0 );
				break;

			case 'question':
				if( isset($_POST['answer']) ) {
					// Increment progress and save answer
					$this->increment_value( 'quizz', $this->quizz->ID, 'step' );
					$this->update_value( 'quizz', $this->quizz->ID, 'answer-'.$this->get_step(), base64_decode($_POST['answer']) );
				} else {
					// First question is shown
					$this->update_value( 'quizz', $this->quizz->ID, 'step', 0 );
					$this->purge_answers();
				};

				if( $this->get_step() < $this->required_questions ) {
					// setup current question
					$this->current_question = $this->setup_current_question();
					break;
				} else {

					set_query_var( 'view', 'evaluation' );
				}

			case 'evaluation':
				$this->update_value( 'quizz', $this->quizz->ID, 'step', $this->required_questions );
				$this->evaluate_answers();
				break;
		}

	}

	/*===========================*\
		Helper Functions
	\*===========================*/

	/**
	 * Returns the current step within in the quizz
	 */
	protected function get_step()
	{
		$step = $this->get_value( 'quizz', $this->quizz->ID, 'step' );
		return $step < 0 ? 0 : $step;
	}

	/**
	 * Delete question/answer cache for a quizz
	 * NOTE: Unused at the moment
	 */
	protected function purge_answers()
	{
		// purge them from the database
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
			for ( $i=1; $i <= $this->required_questions; $i++ ) {
				$key = "quizz_{$this->quizz->ID}_{$name}-{$i}";
				unset( $this->user_meta->{$key} );
			}
		}

		return $return;
	}

	/**
	 * Returns the current question as an array
	 * INPUT: array of questions
	 */
	protected function setup_current_question()
	{
		$questions = $this->all_questions;
		$previous_answers = $this->get_given_answers();

		// unset already used questions
		foreach ( $previous_answers as $key => $answer ) {
			if( $answer['given_answer'] != NULL ) unset( $questions[$answer['key']] );
		}

		// get a randomly one of the remaining questions
		$key = array_rand( $questions );
		$question = $questions[$key];

		// make an array out of the answers
		$question['answers'] = explode( "\r\n", $question['answers'] );

		// safe the current question
		$this->update_value( 'quizz', $this->quizz->ID, "question-".($this->get_step()+1), array( 'key' => $key, 'correct_answer' => reset($question['answers']) ) );

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
	protected function get_given_answers()
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
	 * evaluate the chosen answers
	 * -> save number of mistakes
	 * -> set quizz as 'passed' if no mistakes were made
	 */
	protected function evaluate_answers()
	{
		$answers = $this->get_given_answers();
		$score = 0;
		foreach ( $answers as $key => $answer ) {
			if( $answer['given_answer'] === $answer['correct_answer'] ) $score++;
		}
		$this->mistakes = $this->required_questions - $score;

		/**
		 * If no mistake was found, but the quizz was not yet marked as 'passed'
		 * -> update value quizz_{$ID}_passed = true
		 * -> update current post_status to 'success'
		 */
		if( !$this->mistakes && !$this->is_done() ) {
			$this->update_value( 'quizz', $this->quizz->ID, 'passed', true );
			$this->quizz->post_status = 'success';
		}

	}


	/*===========================*\
		Quizz & Exam Functions
	\*===========================*/

	public function get_answers()
	{
		$answers = $this->current_question['answers'];
		shuffle( $answers );
		return $answers;
	}

	/**
	 * Print hgroup for quizzes
	 * Needs to be translatable strings
	 */
	public function get_subtitle()
	{
		switch ( get_query_var( 'view' ) ) {
			default:
			case 'introduction': return __( 'Introduction', 'gladtidings' );
			case 'question':     return __( 'Question', 'gladtidings' ).' '.($this->get_step()+1);
			case 'evaluation':   return __( 'Evaluation', 'gladtidings' );
		}
	}

	/**
	 * Returns 'true' if the quizz has been completed (all wuestions answered) regardless of passing or not
	 */
	public function is_completed()
	{
		return $this->get_value( 'quizz', $this->quizz->ID, "question-{$this->required_questions}" ) ? true : false;
	}

	/**
	 * Print the Progress bar meter
	 */
	public function print_progress_bar()
	{

		$progress_pills = array();
		for ($i=0; $i < $this->required_questions; $i++) {
			$progress_pills[] = sprintf( '<div class="progress__item %s"></div>',
				$i < $this->get_step() ? 't-comp-bg' : ''
			);
		}

		print( '<h3 class="progress__label"><span class="u-screen-reader-text">' . __( 'Quizz Progress', 'gladtidings' ) . '</span></h3>' );

		printf( '<div class="progress progress--pills" title="%s">%s</div>',
			sprintf( __( '%d/%d Questions done', 'gladtidings' ), $this->get_step(), $this->required_questions ),
			implode( $progress_pills )
		);
	}

	public function print_related_lesson_btn( $answer )
	{
		if( !$answer['related_lesson'] ) return '';

		$object = $answer['related_lesson'];
		printf( '<a class="btn btn--small btn--theme u-pull--right" href="%1$s" title="%2$s">%3$s</a>',
			$this->get_url_to( $object ),
			$object->post_title,
			sprintf( __( 'Review Lesson %s', 'gladtidings' ), $object->order )
		);
	}

	/**
	 * Returns the quizz wrap-up info
	 * INPUT: array of questions
	 */
	public function get_evaluation()
	{
		$questions = $this->all_questions;
		$answers = $this->get_given_answers();

		$return = array();
		foreach ($answers as $key => $answer) {
			$correct = $answer['given_answer'] === $answer['correct_answer'];
			$related_lesson_id = $questions[ $answer['key'] ]['related_lesson'];
			$return[$key] = array(
				'title' 		 => $questions[ $answer['key'] ]['question_text'],
				'given_answer' 	 => $answer['given_answer'],
				'correct'		 => $correct,
				'related_lesson' => $correct ? '' : ( $related_lesson_id ? $this->get_object( $related_lesson_id ) : '' )
			);
		}

		return $return;
	}

	/*===========================*\
		Exam Functions
	\*===========================*/

	/**
	 * Meta States:
	 * <span class="color--success">Completed</span>
	 * <span class="color--locked">Locked: Complete "%s" first</span>
	 * <span class="color--primary">Coming soon: 01/01/2016</span>
	 */
	public function hero_meta()
	{
		switch ( $this->unit->post_status ) {
			case 'coming':  $output = '&bull; <span class="color--primary t-comp-text">' .          __('Coming soon', 'gladtidings') . ': ' . $this->unit->release_date . '</span>'; break;
			case 'locked':  $output = '&bull; <span class="color--locked t-comp-text">'  . sprintf( __('Locked: Complete "%s" first', 'gladtidings'), $this->print_link_to( $this->unit->unlock_dependency ) ) . '</span>'; break;
			case 'success': $output = '&bull; <span class="color--success">'             .          __('Completed', 'gladtidings') . '</span>'; break;
			default: $output = '';
		}
		return $output;
	}

}
