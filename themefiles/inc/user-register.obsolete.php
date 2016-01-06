<?php
/*------------------------------------*\
    Theme Activation / Deactivation
\*------------------------------------*/

/**
 * Add new user role 'student' on theme activation
 */

function gladtidings_activation_user() {
    // Add new User Role 'student'
    // with custom capability 'study'
    add_role( 
        'student',
        __( 'Student', 'gladtidings' ),
        array(
            'study' => true
        )
    );

    // Set 'student' as new default user role
    update_option( 'default_role', 'student', true );
}
add_action( 'after_switch_theme', 'gladtidings_activation_user' );


function gladtidings_deactivation_user () {
    // Delete user role 'student'
    remove_role( 'student' );
}
add_action('switch_theme', 'gladtidings_deactivation_user');