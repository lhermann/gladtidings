<?php
/*------------------------------------*\
    User Controller
\*------------------------------------*/

class UserController extends ApplicationController
{

	public static function show( $post )
	{
		self::login_check();
		add_filter( 'template_include', array( 'UserController', 'show_template_redirect' ), 99 );
	}

	public static function settings( $post )
	{
		self::login_check();
		add_filter( 'template_include', array( 'UserController', 'settings_template_redirect' ), 99 );
	}

	public static function contact( $post )
	{
		self::login_check();
		add_filter( 'template_include', array( 'UserController', 'contact_template_redirect' ), 99 );
	}

	public static function show_template_redirect( $old_template )
	{
		return parent::template_redirect( locate_template( "404.php" ), "single-user.php" );
	}

	public static function settings_template_redirect( $old_template )
	{
		return parent::template_redirect( locate_template( "single-user.php" ), "single-user-settings.php" );
	}

	public static function contact_template_redirect( $old_template )
	{
		return parent::template_redirect( locate_template( "single-user.php" ), "single-user-contact.php" );
	}

	protected function login_check()
	{
		if( !is_user_logged_in() ) {
			wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
			exit();
		}
	}

}
