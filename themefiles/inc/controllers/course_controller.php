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
		return $course;
	}

}
