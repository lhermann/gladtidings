<?php
/*------------------------------------*\
    Quizz Controller
\*------------------------------------*/

class QuizzController extends ApplicationController
{

	public static function show( $post )
	{
		$quizz = new Quizz( $post );
		$quizz->touch();
		return $quizz;
	}

	public static function question( $post )
	{
		$quizz = new Quizz( $post );

		if( !isset($_POST['answer']) ) {

			$quizz->purge_answers();
			$quizz->set_step(1);

		} else {

			$quizz->save_answer( base64_decode($_POST['answer']) );
			$quizz->increment_step();

			if( $quizz->final_step() ) {
				wp_redirect( gt_get_permalink( $quizz, 'evaluation' ) );
				exit;
			}

		}

		$quizz->setup_current_question();
		$quizz->touch();
		return $quizz;
	}

	public static function evaluation( $post )
	{
		$quizz = new Quizz( $post );
		$quizz->evaluate_answers();
		$quizz->touch();
		return $quizz;
	}

}
