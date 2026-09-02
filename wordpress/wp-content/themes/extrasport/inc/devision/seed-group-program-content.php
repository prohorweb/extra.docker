<?php
/**
 * Import De-vision group program page content from production.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_GROUP_PROGRAM_CONTENT_VERSION', 3 );

/**
 * Fetch a De-vision group program page from production.
 *
 * @param string $slug Program slug.
 * @return string HTML or empty string.
 */
function extrasport_fetch_devision_production_group_program_html( $slug ) {
	return extrasport_fetch_production_group_program_html( $slug, 'https://de-vision.ru/services/programs' );
}

/**
 * Extract intro text from a production group program page.
 *
 * @param string $html  Raw HTML.
 * @param string $title Program title.
 * @return string
 */
function extrasport_parse_devision_group_program_intro( $html, $title ) {
	return extrasport_parse_production_group_program_intro( $html, $title );
}

/**
 * Extract main HTML content from a production group program page.
 *
 * @param string $html  Raw HTML.
 * @param string $title Program title.
 * @return string
 */
function extrasport_parse_devision_group_program_content( $html, $title ) {
	return extrasport_parse_production_group_program_content( $html, $title );
}

/**
 * Import production HTML into De-vision group program service posts.
 *
 * @param bool $force Overwrite existing content.
 * @return void
 */
function extrasport_seed_devision_group_program_content( $force = false ) {
	if ( ! extrasport_is_devision_site() || ! function_exists( 'extrasport_get_devision_group_programs_roster' ) ) {
		return;
	}

	extrasport_seed_group_program_content_from_roster(
		'extrasport_get_devision_group_programs_roster',
		'https://de-vision.ru/services/programs',
		'extrasport_devision_group_program_content_version',
		EXTRASPORT_DEVISION_GROUP_PROGRAM_CONTENT_VERSION,
		$force
	);
}
