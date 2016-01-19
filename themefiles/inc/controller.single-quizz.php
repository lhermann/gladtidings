<?php
/*------------------------------------*\
    Single Quizz Controller
\*------------------------------------*/

// require shared functions for lessons and quizzes
require( 'controller.shared.item.php' );


class GTView extends GTItem
{

	function __construct( &$object )
	{
		
		// call parent __contruct
		parent::__construct( $object );

		// prepare flyout
		add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );

		// get siblings
		$this->siblings = $this->get_children( $this->unit );

		// set 'passed' to false on first touch
		if( $this->first_touch ) {
			$this->update_value( 'quizz', $this->quizz->ID, 'passed', false );
		}

		// Quizz specific object setup
		$this->quizz->required_questions = $i = (int)get_field( 'required_questions', $post->ID );
		$this->quizz->questions_repeater = get_field( 'questions_repeater', $post->ID );
		
		// savety measure against timing out while loops
		$count = count( $this->quizz->questions_repeater );
		$this->quizz->required_questions = $i > $count ? $count : $i;

		// temp value used by $this->quizz_evaluate; and $this->quizz_num_mistakes;
		$this->quizz->mistakes = 0;

		/**
		 * Set up view and evaluate $_POST
		 */
		switch ( get_query_var( 'view' ) ) {
			default:
			case 'introduction':
				$this->update_value( 'quizz', $this->quizz->ID, 'step', 0 );
				break;
			case 'question':
				var_dump('POST',$_POST['answer']);
				if( isset($_POST['answer']) ) {
					// Increment progress and save answer
					$this->increment_value( 'quizz', $this->quizz->ID, 'step' );
					$this->update_value( 'quizz', $this->quizz->ID, 'answer-'.$this->get_step(), base64_decode($_POST['answer']) );
				} else {
					// First question is shown
					$this->update_value( 'quizz', $this->quizz->ID, 'step', 0 );
					$this->quizz_purge_answers();
				};
				break;
			case 'evaluation':
				$this->quizz_evaluate();
				$this->update_value( 'quizz', $this->quizz->ID, 'step', $this->quizz->required_questions );
				break;
		}

		if( $this->get_step() >= $this->quizz->required_questions ) {
			$this->update_value( 'quizz', $this->quizz->ID, 'step', $this->quizz->required_questions );
			set_query_var( 'view', 'evaluation' );
			$this->quizz_evaluate();
		}

	}

	/*===========================*\
		Helper Functions
	\*===========================*/

	/**
	 * Returns the current step within in the quizz
	 */
	public function get_step()
	{
		$step = $this->get_value( 'quizz', $this->quizz->ID, 'progress' );
		return $step < 0 ? 0 : $step;
	}


	/*===========================*\
		Quizz Functions
	\*===========================*/

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
	 * wrapper for $this->quizz->required_questions
	 */
	public function num_questions() { return $this->quizz->required_questions; }

	/**
	 * Returns 'true' if the quizz has been completed (all wuestions answered) regardless of passing or not
	 */
	public function is_completed()
	{
		return $this->get_value( 'quizz', $this->quizz->ID, "question-{$this->quizz->required_questions}" ) ? true : false;
	}

	/**
	 * Print out the continue button
	 */
	public function quizz_print_continue_btn()
	{
		$attr = !$this->quizz_is_passed() ? 'disabled' : '';
		print( $this->print_continue_btn( $this->quizz->ID, $attr ) );
	}

}