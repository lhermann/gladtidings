<?php
class Course extends Application
{
	// function __construct( $post )
	// {
	// 	parent::__construct( $post );
	// }

	/*=======================*\
		Public Functions
	\*=======================*/

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
		// return h_get_course_batch( $this->ID );
	}

	public function num_lessons() { return $this->num_children( 'lesson' ); }
	public function num_quizzes() { return $this->num_children( 'quizz' ); }

}
