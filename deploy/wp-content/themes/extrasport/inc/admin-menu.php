<?php
/**
 * Trim unused default wp-admin menu items.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove default post types and comments from the admin menu.
 *
 * @return void
 */
function extrasport_trim_admin_menu() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit.php?post_type=page' );
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'extrasport_trim_admin_menu', 999 );

/**
 * Remove the same items from the admin bar quick links.
 *
 * @param WP_Admin_Bar $admin_bar Admin bar instance.
 * @return void
 */
function extrasport_trim_admin_bar( $admin_bar ) {
	if ( ! $admin_bar instanceof WP_Admin_Bar ) {
		return;
	}

	$admin_bar->remove_node( 'new-post' );
	$admin_bar->remove_node( 'new-page' );
	$admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'extrasport_trim_admin_bar', 999 );
