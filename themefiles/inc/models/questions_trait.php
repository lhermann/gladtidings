<?php
/*------------------------------------*\
    Module Trait
\*------------------------------------*/

trait questions
{

	public $required_questions, $all_questions, $current_question, $mistakes;
	private $step;

	private function questions_init( $post )
	{
		// Quizz specific object setup
		$this->all_questions = get_field( 'questions_repeater', $post->ID );

		$i = (int)get_field( 'required_questions', $post->ID );
		$count = count( $this->all_questions );
		$this->required_questions = $i > $count ? $count : $i;

		global $user;
		$this->step = (int)$user->get_value( $this->type, $this->ID, 'step' );

		$this->mistakes = 0;
	}

	/*=======================*\
		Protected Functions
	\*=======================*/

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
		global $user;
		$answers = array();
		for ( $i = 1; $i <= $this->required_questions; $i++ ) {
			$temp = $user->get_value( $this->type, $this->ID, "question-$i" );
			if( !$temp ) continue;
			$answers[$i] = $temp;
			$answers[$i]['given_answer'] = $user->get_value( $this->type, $this->ID, "answer-$i" );
		}
		return $answers;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 * Returns the current step within in the quizz
	 */
	public function get_step()
	{
		return $this->step < 0 ? 0 : $this->step;
	}

	public function set_step( $value = 0 )
	{
		global $user;
		$this->step = $value;
		return $user->update_value( $this->type, $this->ID, 'step', $value );
	}

	/**
	 * Increments the step
	 * OUTPUT: new value
	 */
	public function increment_step()
	{
		global $user;
		$value = (int)$user->increment_value( $this->type, $this->ID, 'step' );
		$this->step = $value;
		return $value;
	}

	/**
	 * Returns true if the last step was reached,
	 * otherwise returns false
	 */
	public function final_step()
	{
		return $this->step > $this->required_questions;
	}

	/**
	 * Returns the current question as an array
	 * INPUT: array of questions
	 */
	public function setup_current_question()
	{
		$questions = $this->all_questions;
		$previous_answers = $this->get_given_answers();

		// unset already used questions
		foreach ( $previous_answers as $answer ) {
			if( $answer['given_answer'] != NULL ) unset( $questions[$answer['key']] );
		}

		// get a randomly one of the remaining questions
		$key = array_rand( $questions );
		$question = $questions[ $key ];

		// make an array out of the answers
		$question['answers'] = explode( "\r\n", $question['answers'] );

		// safe the current question
		global $user;
		$user->update_value( $this->type, $this->ID, "question-".($this->step), array( 'key' => $key, 'correct_answer' => $question['answers'][0] ) );

		$this->current_question = $question;
	}

	/**
	 * Get the answers of the current question
	 */
	public function get_answers()
	{
		$answers = $this->current_question['answers'];
		shuffle( $answers );
		return $answers;
	}

	/**
	 * Save the given answer
	 * INPUT: Answer as string
	 */
	public function save_answer( $answer )
	{
		global $user;
		return $user->update_value( $this->type, $this->ID, 'answer-'.$this->step, $answer );
	}

	/**
	 * Delete question/answer cache for this quizz
	 */
	public function purge_answers()
	{
		global $user;
		return $user->purge_answers( $this );
	}

	/**
	 * evaluate the chosen answers
	 * -> save number of mistakes
	 * -> set quizz as 'passed' if no mistakes were made
	 */
	public function evaluate_answers()
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
			global $user;
			$user->update_value( $this->type, $this->ID, 'passed', true );
			$this->status = 'success';
		}

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
				'related_lesson' => $correct ? null : ( $related_lesson_id ?? null )
			);
		}

		return $return;
	}

}
