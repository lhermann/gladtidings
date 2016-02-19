<?php
/*------------------------------------*\
    Course Controller
\*------------------------------------*/

class CourseController extends ApplicationController
{

	public static function index( $posts )
	{
		$courses = Course::all( $posts );
		return $courses;
	}

	public static function show( $post )
	{
		$course = new Course( $post );
		$course->touch();
		$course->calculate_progress();
		return $course;
	}

	public static function wrapup( $post )
	{
		$course = new Course( $post );
		$course->touch();

		add_filter( 'single_template', array( 'CourseController', 'wrapup_template_redirect' ), 10, 1 );

		return $course;
	}

	public static function wrapup_template_redirect( $single_template )
	{
		return parent::template_redirect( $single_template, "single-course-wrapup.php" );
	}

}
