<?php
/**
 * Extrasport content migration orchestrator.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_EXTRASPORT_MIGRATION_VERSION', 2 );

/**
 * Run Extrasport content migration tasks.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_migrate_extrasport_content( $force = false ) {
	if ( ! extrasport_is_extrasport_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_extrasport_migration_version', 0 ) >= EXTRASPORT_EXTRASPORT_MIGRATION_VERSION ) {
		return;
	}

	extrasport_seed_extrasport_group_program_content( true );
	extrasport_seed_extrasport_linked_service_content( true );
	extrasport_cleanup_service_posts_content_html();

	update_option( 'extrasport_extrasport_migration_version', EXTRASPORT_EXTRASPORT_MIGRATION_VERSION, false );
}

/**
 * Bootstrap Extrasport migration on front-end requests.
 *
 * @return void
 */
function extrasport_maybe_migrate_extrasport_content() {
	if ( is_admin() || ! extrasport_is_extrasport_site() ) {
		return;
	}

	extrasport_migrate_extrasport_content( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_migrate_extrasport_content', 45 );
