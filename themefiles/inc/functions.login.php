<?php

/**
 * Login Page customization
 */

/**
 * Instantiate the Controller
 * filename syntax: "inc/controller.single-{post_type}.php"
 * class syntax: "Single{Post_type}"
 */
add_action( 'login_init', 'instantiate_GladTidings_login', 10, 1 );
function instantiate_GladTidings_login( $wp ) {

	global $_gt;

	$_gt = new GTGlobal( get_queried_object() );

}


/**
 * include the theme footer
 */
add_action( 'login_footer', 'gt_login_footer' );
function gt_login_footer()
{
	get_footer();
}


/**
 * Enqueue Styles
 */
add_action( 'login_enqueue_scripts', 'gt_login_stylesheet' );
function gt_login_stylesheet() {

    wp_enqueue_style( 'gt-login', get_template_directory_uri() . '/css/login.css', array(), THEMEVERSION );

}


/**
 * Add some custom markup above the ligin field
 */
add_filter( 'login_message', 'gt_login_message', 1, 10 );
function gt_login_message( $value )
{
	?>
		<div class="login-title">
			<a class="login-title__link" href="<?= esc_url( home_url( '/' ) ); ?>" rel="bookmark" title="<?php bloginfo( 'name' ); ?>">
				<img src="<?= get_template_directory_uri() . '/img/gt-logo.svg' ?>" alt="Site Logo">
				<h1><?php bloginfo( 'name' ); ?></h1>
			</a>
		</div>
	<?php
}
