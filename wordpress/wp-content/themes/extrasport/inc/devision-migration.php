<?php
/**
 * De-vision content migration orchestrator.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_MIGRATION_VERSION', 11 );

/**
 * Run De-vision content migration tasks.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_migrate_devision_content( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_migration_version', 0 ) >= EXTRASPORT_DEVISION_MIGRATION_VERSION ) {
		return;
	}

	extrasport_seed_devision_club_page( false );
	extrasport_sync_devision_trainers_roster( false );
	extrasport_seed_devision_news( false );
	extrasport_seed_devision_jobs( false );
	extrasport_seed_devision_group_programs( true );
	extrasport_seed_devision_group_program_content( true );
	extrasport_cleanup_service_posts_content_html();
	extrasport_cleanup_excluded_services_for_current_site();

	update_option( 'extrasport_devision_migration_version', EXTRASPORT_DEVISION_MIGRATION_VERSION, false );
}

/**
 * Bootstrap De-vision migration on front-end requests.
 *
 * @return void
 */
function extrasport_maybe_migrate_devision_content() {
	if ( is_admin() || ! extrasport_is_devision_site() ) {
		return;
	}

	extrasport_migrate_devision_content( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_migrate_devision_content', 45 );
