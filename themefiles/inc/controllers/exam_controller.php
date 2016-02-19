<?php
/*------------------------------------*\
    Exam Controller
\*------------------------------------*/

class ExamController extends ApplicationController
{

	public static function show( $post )
	{
		$exam = new Exam( $post );
		$exam->touch();
		return $exam;
	}

	public static function question( $post )
	{
		$exam = new Exam( $post );

		if( !isset($_POST['answer']) ) {

			$exam->purge_answers();
			$exam->set_step(1);

		} else {

			$exam->save_answer( base64_decode($_POST['answer']) );
			$exam->increment_step();

			if( $exam->final_step() ) {
				wp_redirect( gt_get_permalink( $exam, 'evaluation' ) );
				exit;
			}

		}

		$exam->setup_current_question();
		$exam->touch();
		return $exam;
	}

	public static function evaluation( $post )
	{
		$exam = new Exam( $post );
		$exam->evaluate_answers();
		$exam->touch();
		return $exam;
	}

}
