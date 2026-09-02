<?php
/**
 * Import Extrasport group program page content from production.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_EXTRASPORT_GROUP_PROGRAM_CONTENT_VERSION', 1 );
define( 'EXTRASPORT_EXTRASPORT_LINKED_SERVICE_CONTENT_VERSION', 1 );

/**
 * Import production HTML into Extrasport group program service posts.
 *
 * @param bool $force Overwrite existing content.
 * @return void
 */
function extrasport_seed_extrasport_group_program_content( $force = false ) {
	if ( ! extrasport_is_extrasport_site() || ! function_exists( 'extrasport_get_extrasport_group_programs_roster' ) ) {
		return;
	}

	extrasport_seed_group_program_content_from_roster(
		'extrasport_get_extrasport_group_programs_roster',
		'https://piter.extrasport.ru/services/programs',
		'extrasport_extrasport_group_program_content_version',
		EXTRASPORT_EXTRASPORT_GROUP_PROGRAM_CONTENT_VERSION,
		$force
	);
}

/**
 * Import production HTML into linked top-level Extrasport services.
 *
 * @param bool $force Overwrite existing content.
 * @return void
 */
function extrasport_seed_extrasport_linked_service_content( $force = false ) {
	if ( ! extrasport_is_extrasport_site() ) {
		return;
	}

	extrasport_seed_production_service_content_from_sources(
		extrasport_get_extrasport_production_service_content_sources(),
		'https://piter.extrasport.ru/services/programs',
		'extrasport_extrasport_linked_service_content_version',
		EXTRASPORT_EXTRASPORT_LINKED_SERVICE_CONTENT_VERSION,
		$force
	);
}
