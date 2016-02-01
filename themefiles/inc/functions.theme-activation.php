<?php
/*------------------------------------*\
	Theme Activation / Deactivation
\*------------------------------------*/

/**
 * When Theme is activated
 */
add_action( 'after_switch_theme', 'gladtidings_theme_activation' );

function gladtidings_theme_activation() {

	/**
	 * Create a database table for course - unit - item assosiation
	 * table name: {prefix}gt_relationships
	 * Tutorial: http://codex.wordpress.org/Creating_Tables_with_Plugins
	 */
	global $wpdb;
	$table_name = $wpdb->prefix . "gt_relationships";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE `$table_name` (
	  `parent_id` bigint(20) unsigned NOT NULL,
	  `child_id` bigint(20) unsigned NOT NULL,
	  `order` int(11) NOT NULL DEFAULT '0',
	  `position` int(11) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`parent_id`, `child_id`),
	  KEY `parent` (`parent_id`)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'gt_db_version', THEMEVERSION );


	/**
	 * Add new user role 'student'
	 * with custom capability 'study'
	 */
	add_role(
		'student',
		__( 'Student', 'gladtidings' ),
		array(
			'study' => true
		)
	);
	// Set 'student' as new default user role
	update_option( 'default_role', 'student', true );


	/**
	 * Flush rewrite rules
	 */
	add_post_types_and_taxonomies();
	flush_rewrite_rules();

}




/**
 * When Theme is deactivated
 */
add_action('switch_theme', 'gladtidings_theme_deactivation');

function gladtidings_theme_deactivation () {

	/**
	 * Drop database table {prefix}gt_relationships
	 */
	global $wpdb;
	$table_name = $wpdb->prefix . "gt_relationships";
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($sql);

	delete_option( 'gt_db_version' );

	/**
	 * Delete user role 'student'
	 */
	remove_role( 'student' );
}
