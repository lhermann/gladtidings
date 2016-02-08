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
	public function num_quizzes() { return $this->num_children( 'quizz' ); }

}
