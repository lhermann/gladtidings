<?php
class CourseController extends ApplicationController
{
	public function show( $post )
	{
		$course = new Course( $post );
		$course->touch();
		return $course;
	}
}
