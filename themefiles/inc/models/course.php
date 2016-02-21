<?php
/*------------------------------------*\
    Course Module
\*------------------------------------*/

class Course extends Application
{

	/*=======================*\
		Static Functions
	\*=======================*/

	public static function all( $posts )
	{
		$courses = array();

		foreach( $posts as $post ) {

			$courses[] = gt_instantiate_object( $post );

		}

		return $courses;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	public function is_done()
	{
		return $this->progress() == 100;
	}

	public function calculate_progress()
	{
		global $user;

		// get total number of children
		// count how many children are done
		$this->num_lesson = 0;	$this->num_lesson_done = 0;
		$this->num_quizz  = 0;	$this->num_quizz_done  = 0;
		$this->num_exams  = 0;  $this->num_exams_done  = 0;
		foreach ( $this->children() as $child ) {
			switch ( $child->type ) {
				case 'unit':
					$child->calculate_progress();
					$this->num_lesson      += $child->num_lesson;
					$this->num_lesson_done += $child->num_lesson_done;
					$this->num_quizz       += $child->num_quizz;
					$this->num_quizz_done  += $child->num_quizz_done;
					break;
				case 'exam':
					$this->num_exams++;
					if( $child->is_done() ) $this->num_exams_done++;
					break;
			}
		}

		// calculate progress percentage
		$total    = $this->num_lesson      + $this->num_quizz      + $this->num_exams;
		$done     = $this->num_lesson_done + $this->num_quizz_done + $this->num_exams_done;
		$progress = $total > 0 ? (int)round( ( $done / $total ) * 100 ) : 0;

		// save progress
		$this->progress = $progress > 100 ? 100 : $progress;
		$user->update( $this, 'progress', $this->progress );
	}

	/**
	 * Returns the ID of the course
	 */
	public function course_id()
	{
		return $this->ID;
	}

	/**
	 * Retunrs the batch image source
	 */
	public function batch_src( $size = 'full' )
	{
		$src = wp_get_attachment_image_src( get_field( 'img_course_badge', $this->ID ), $size );
		return $src ? $src[0] : get_template_directory_uri().'/img/course-batch-placeholder.png';
	}

	public function num_lessons() { return $this->num_children( 'lesson' ); }
	public function num_quizzes() { return $this->num_children( 'quizz'  ); }
	public function num_exams()   { return $this->num_children( 'exam'   ); }

	public function num_lessons_done() { return $this->num_children_done( 'lesson' ); }
	public function num_quizzes_done() { return $this->num_children_done( 'quizz'  ); }
	public function num_exams_done()   { return $this->num_children_done( 'exam'   ); }

}
