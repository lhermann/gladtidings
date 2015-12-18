<?php
/**
 * The Glad Tididngs functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @package WordPress
 * @subpackage gladtidings
 * @version 0.2.0-beta.1
 */
define( THEMEVERSION, '0.2.0-beta.1' );

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

require_once ( "inc/functions-course.php" );
// require_once ( "inc/functions-adjust-color.php" );
require_once ( "inc/color-converter/rgb_hsl_converter.inc.php" );
require_once ( "inc/color-converter/custom-functions.inc.php" );

/*------------------------------------*\
	Theme Setup
\*------------------------------------*/
if ( ! function_exists( 'theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function theme_setup() {

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    // add_image_size('large', 700, '', true); // Large Thumbnail
    // add_image_size('medium', 250, '', true); // Medium Thumbnail
    // add_image_size('small', 120, '', true); // Small Thumbnail

    // Add Support for Custom Header - Uncomment below if you're going to use
    $args = array(
    	'default-image'				=> get_template_directory_uri().'/img/header-bg2.jpg',
    	'uploads'       			=> true,
    	'flex-width'    			=> true,
    	'flex-height'   			=> true,
    	'width'         			=> 1800,
    	'height'        			=> 600,
    	'default-text-color'     	=> 'FFF',
    );
    add_theme_support( 'custom-header', $args );

    // Enables post and comment RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Localisation Support
    load_theme_textdomain( 'gladtidings', get_template_directory().'/languages' );
}
endif; // theme_setup
add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function scripts_and_styles() {
	// Add stylesheet
	wp_enqueue_style( 'gladtidings-style', get_template_directory_uri().'/css/main.css', array(), THEMEVERSION );

	// Add javascript
    wp_enqueue_script( 'gladtidings-sript', get_template_directory_uri().'/js/main.js', array(), THEMEVERSION, ture );
}
add_action( 'wp_enqueue_scripts', 'scripts_and_styles' );

/*
 * Admin Scripts and styles
 */
function admin_scripts_and_styles() {
	// Add stylesheet
	wp_enqueue_style( 'custom_admin_css', get_bloginfo('template_directory').'/css/admin.css', array( 'colors' ) );

	// Add javascript
	wp_enqueue_script( 'custom_admin_js', get_bloginfo( 'template_directory' ).'/js/admin.js', array( 'jquery' ), false, true );
};
add_action( 'admin_enqueue_scripts', 'admin_scripts_and_styles' );

/*------------------------------------*\
	Functions
\*------------------------------------*/

/**
 * Admin Menu tweaks
 */
function remove_menus(){
  
//	remove_menu_page( 'index.php' );                  //Dashboard
	remove_menu_page( 'edit.php' );                   //Posts
//	remove_menu_page( 'upload.php' );                 //Media
	remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
//	remove_menu_page( 'themes.php' );                 //Appearance
//	remove_menu_page( 'plugins.php' );                //Plugins
//	remove_menu_page( 'users.php' );                  //Users
//	remove_menu_page( 'tools.php' );                  //Tools
//	remove_menu_page( 'options-general.php' );        //Settings
  
}
add_action( 'admin_menu', 'remove_menus' );

/**
 * Admin Bar tweaks
 * (1) Remove Comments Display
 * (2) Remove 'Add Post' and 'Add Page'
 */
function my_tweaked_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments'); // (1)
    $wp_admin_bar->remove_menu('new-post'); // (2)
    $wp_admin_bar->remove_menu('new-page'); // (2)
}
add_action( 'wp_before_admin_bar_render', 'my_tweaked_admin_bar' );

/**
 * Print classes for html element
 */
function html_class( $value = '' ) {
    $classes = array( 'no-js' );
    $classes = apply_filters( 'html_class', $classes );
    if( $value ) $classes[] = $value;
    print( implode( $classes, ' ' ) );
}

/**
 * Print classes for container div
 */
function container_class( $value = '' ) {
    $classes = array( );
    $classes = apply_filters( 'container_class', $classes );
    if( $value ) $classes[] = $value;
    print( implode( $classes, ' ' ) );
}

/**
 * Print theme inline css
 */
function theme_css() {
    $css = array();

    // Add WP theme header on the home page
    if( is_home() ) {
        $css['t-header-image'] = array( 'background-image' => 'url('.get_header_image().')' );
    } else {
        $css['t-header-image'] = array( 'background-image' => 'url('.get_template_directory_uri().'/img/course-header-placeholder.jpg'.')' );
    }

    // Apply the filter so css can be added via teh 'theme_css' hook
    $css = apply_filters( 'theme_css', $css );

    // Print the nested array as inline css
    print( '<style type="text/css" media="screen">' );
    foreach( $css as $class => $values ) {
        print( '.'.$class.' { ' );
        foreach ( $values as $option => $value) {
            print( $option.': '.$value.' !important;' );
        }
        print( " }\n" );
    }
    print( '</style>' );

    return;
}

/**
 * Add the course colors to the theme_css filter
 */
function add_theme_color( $css ) {
    global $fields, $unit;

    // var_dump( get_field( 'img_course_header', $unit->course_object_id ) );

    // get and cache variables
    $header     = isset( $fields['img_course_header'] ) ? $fields['img_course_header'] : get_field( 'img_course_header', $unit->course_object_id );
    $main_hex   = isset( $fields['color_main'] )        ? $fields['color_main']        : get_field( 'color_main', $unit->course_object_id );
    $second_hex = isset( $fields['color_secondary'] )   ? $fields['color_secondary']   : get_field( 'color_secondary', $unit->course_object_id );
    $comp_hex   = isset( $fields['color_comp'] )        ? $fields['color_comp']        : get_field( 'color_comp', $unit->course_object_id );

    // create hues
    $main_hsl = hex2hsl( $main_hex );
    $main_dark_hex = hsl2hex( array( $main_hsl[0], $main_hsl[1], $main_hsl[2]*0.60 ) ); // darker version of the main color
    $second_hsl = hex2hsl( $second_hex );
    $second_light_hex = hsl2hex( array( $second_hsl[0], ($second_hsl[1] > 0.4 ? 0.4 : $second_hsl[1]), 0.96 ) ); // very light version fo the secondary color

    // modify and add css
    if( $header ) $css['t-header-image']['background-image'] = 'url('.$header.')';
    $temp_css = array(
        't-main-text'       => array( 'color'            => textsave_hex( $main_hex )   ),
        't-main-border'     => array( 'border-color'     => $main_hex                   ),
        't-main-bg'         => array( 'background-color' => $main_hex                   ),
        't-second-text'     => array( 'color'            => textsave_hex( $second_hex ) ),
        't-second-border'   => array( 'border-color'     => $second_hex                 ),
        't-second-bg'       => array( 'background-color' => $second_hex                 ),
        't-comp-text'       => array( 'color'            => textsave_hex( $comp_hex )   ),
        't-comp-border'     => array( 'border-color'     => $comp_hex                   ),
        't-comp-bg'         => array( 'background-color' => $comp_hex                   ),
        't-light-bg'        => array( 'background-color' => $second_light_hex           ),
        'btn--theme'        => array( 'background-color' => $main_hex,
                                          'border-color' => $main_dark_hex              ),
        'panel'             => array( 'background-color' => $second_light_hex,
                                          'border-color' => $second_hex                 )
    );
    return array_merge( $css, $temp_css );
}

/*------------------------------------*\
	Actions + Filters
\*------------------------------------*/
// Remove Actions
// remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
// remove_action('wp_head', 'index_rel_link'); // Index link
// remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
// remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
// remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
// remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
// remove_action('wp_head', 'rel_canonical');
// remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // javascript that detects emoji support
remove_action( 'wp_print_styles', 'print_emoji_styles' ); // emoji styles printed in the head section

// Remove Filters
// remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Remove Admin bar
function remove_admin_bar() {
    // Show Admin Bar only for privileged Users
    if( current_user_can( 'edit_pages' ) ) {
        return true;
    } else {
        return false;
    }
}
add_filter( 'show_admin_bar', 'remove_admin_bar' ); // Remove Admin bar


/**
 * Alter the main wp_query on the home page to fetch 'course' instead of 'post'
 */
function alter_query_home( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'post_type', 'course' );
    }
}
add_action( 'pre_get_posts', 'alter_query_home' );


/*------------------------------------*\
    Theme Activation / Deactivation
\*------------------------------------*/

function gladtidings_activation() {
    // Add new User Role 'student'
    // with custom capability 'study'
    add_role( 
        'student',
        __( 'Student', 'gladtidings' ),
        array(
            'study' => true
        )
    );

    // Set 'studnet' as new default user role
    update_option( 'default_role', 'student', true );
}
add_action( 'after_switch_theme', 'gladtidings_activation' );


function gladtidings_deactivation () {
    // Delete user role 'student'
    remove_role( 'student' );
}
add_action('switch_theme', 'gladtidings_deactivation');




