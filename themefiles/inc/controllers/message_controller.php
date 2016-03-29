<?php
/*------------------------------------*\
    Message Controller
\*------------------------------------*/

class MessageController extends ApplicationController
{
	public static function show( $post )
	{
		self::login_check();
		$message = get_query_var( 'message' );
		if($message) {
			add_filter( 'template_include', array( 'MessageController', 'show_single_template_redirect' ), 99 );
		} else {
			add_filter( 'template_include', array( 'MessageController', 'show_archive_template_redirect' ), 99 );
		}
	}

	public static function show_archive_template_redirect( $old_template )
	{
		return parent::template_redirect( locate_template( "404.php" ), "archive-message.php" );
	}

	public static function show_single_template_redirect( $old_template )
	{
		return parent::template_redirect( locate_template( "404.php" ), "single-message.php" );
	}

	protected static function login_check()
	{
		if( !is_user_logged_in() ) {
			wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
			exit();
		}
	}
}
