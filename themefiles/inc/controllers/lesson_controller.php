<?php
/*------------------------------------*\
    Lesson Controller
\*------------------------------------*/

class LessonController extends ApplicationController
{

	public static function show( $post )
	{
		$lesson = new Lesson( $post );
		$lesson->touch();
		return $lesson;
	}

}
