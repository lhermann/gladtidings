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
		$total = 0;
		$done  = 0;
		foreach ( $this->children() as $child) {
			if( $child->type != 'headline' ) $total++;
			if( $child->is_done() ) $done++;
		}

		// save number of children done
		$user->update( $this, 'children_done', $done );

		// calculate progress percentage
		$progress = (int)round( ( $done / $total ) * 100 );

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
