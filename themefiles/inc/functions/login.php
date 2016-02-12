<?php
/**
 * Login Page customization
 */


/**
 * include the theme footer
 */
// add_action( 'login_footer', 'gt_login_footer' );
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
add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_foundation_icons', 10, 3 );
function wsl_use_foundation_icons( $provider_id, $provider_name, $authenticate_url ) {
	?>
		<a class="wsl wp-social-login-provider wp-social-login-provider-<?= strtolower( $provider_id ) ?>" href="<?= $authenticate_url ?>" rel="nofollow" data-provider="<?= strtolower( $provider_id ) ?>" title="<?= sprintf( __('Login with %s', 'gladtidings'), $provider_name ); ?>">
			<span class="wsl__icon wsl__icon--<?= strtolower( $provider_id ) ?>"></span>
			<span class="wsl__label"><?= $provider_name ?></span>
		</a>
	<?php
}

