<?php
/*------------------------------------*\
    Unit Module
\*------------------------------------*/

// Include Trait
include_once( 'init_status_trait.php' );

class Unit extends Application
{

	// function __construct( $post )
	// {

	// 	parent::__construct( $post );

	// }

	/*=======================*\
		Protected Functions
	\*=======================*/

	// Use trait
	use init_status;

	/*=======================*\
		Public Functions
	\*=======================*/

	public function calculate_progress()
	{
		global $user;

		// get total number of children
		// count how many children are done
		$this->num_lesson = 0;	$this->num_lesson_done = 0;
		$this->num_quizz  = 0;	$this->num_quizz_done  = 0;

		foreach ( $this->children() as $child ) {
			switch ( $child->type ) {
				case 'lesson': $this->num_lesson++; break;
				case 'quizz' : $this->num_quizz++; break;
			}
			if( $child->is_done() ) $this->{"num_{$child->type}_done"}++;
		}

		// calculate progress percentage
		$total = $this->num_lesson + $this->num_quizz;
		$done  = $this->num_lesson_done + $this->num_quizz_done;
		$progress = $total > 0 ? (int)round( ( $done / $total ) * 100 ) : 0;

		// save progress
		$this->progress = $progress > 100 ? 100 : $progress;
		$user->update( $this, 'progress', $this->progress );

		// update status
		$this->status = parent::init_status( $this->status );
	}

	public function is_done()
	{
		return $this->progress() === 100 ? true : false ;
	}

	/**
	 * Returns the ID of the course
	 */
	public function course_id()
	{
		return $this->parent()->ID;
	}

	public function num_lessons() { return $this->num_children( 'lesson' ); }
	public function num_quizzes() { return $this->num_children( 'quizz' ); }

}
