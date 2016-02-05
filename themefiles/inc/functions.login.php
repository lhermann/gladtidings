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


/**
 * WP Social Plugin Customization
 */
/*
<a rel="nofollow" href="http://gladtidings:8888/wp-login.php?action=wordpress_social_authenticate&amp;mode=login&amp;provider=Facebook&amp;redirect_to=http%3A%2F%2Fgladtidings%3A8888%3A8888%2Fwp-login.php%3Floggedout%3Dtrue" title="Connect with Facebook" class="wp-social-login-provider wp-social-login-provider-facebook" data-provider="Facebook">
	<img alt="Facebook" title="Connect with Facebook" src="http://gladtidings:8888/wp-content/plugins/wordpress-social-login/assets/img/32x32/wpzoom/facebook.png">
</a>
*/
function wsl_use_foundation_icons( $provider_id, $provider_name, $authenticate_url ) {
	?>
		<a class="wsl wp-social-login-provider wp-social-login-provider-<?= strtolower( $provider_id ) ?>" href="<?= $authenticate_url ?>" rel="nofollow" data-provider="<?= strtolower( $provider_id ) ?>" title="<?= sprintf( __('Login with %s', 'gladtidings'), $provider_name ); ?>">
			<span class="wsl__icon wsl__icon--<?= strtolower( $provider_id ) ?>"></span>
			<span class="wsl__label"><?= $provider_name ?></span>
		</a>
	<?php
}

add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_foundation_icons', 10, 3 );
