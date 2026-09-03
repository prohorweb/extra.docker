<?php
/**
 * Read-only connection to the legacy Yii2 database.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Yii2 database name on the shared MariaDB host.
 */
define( 'EXTRASPORT_YII_DB_NAME', 'extra' );

/**
 * Legacy Yii2 database names keyed by club slug.
 *
 * @return array<string, string>
 */
function extrasport_get_yii_db_names_by_club() {
	return array(
		'extrasport' => EXTRASPORT_YII_DB_NAME,
		'devision'   => 'extra_matros',
	);
}

/**
 * Resolve Yii DB name for a club slug.
 *
 * @param string $club_slug extrasport|devision
 * @return string
 */
function extrasport_get_yii_db_name_for_club( $club_slug = '' ) {
	$club_slug = extrasport_normalize_club_slug( $club_slug ?: extrasport_get_current_club_slug() );
	$map       = extrasport_get_yii_db_names_by_club();

	return $map[ $club_slug ] ?? EXTRASPORT_YII_DB_NAME;
}

/**
 * @return wpdb|null
 */
function extrasport_get_yii_db_for_club( $club_slug = '' ) {
	if ( ! extrasport_is_yii_db_enabled() ) {
		return null;
	}

	static $connections = array();

	$db_name = extrasport_get_yii_db_name_for_club( $club_slug );
	if ( isset( $connections[ $db_name ] ) ) {
		return $connections[ $db_name ];
	}

	if ( ! defined( 'DB_USER' ) || ! defined( 'DB_PASSWORD' ) || ! defined( 'DB_HOST' ) ) {
		$connections[ $db_name ] = null;
		return null;
	}

	$yii_db = new wpdb( DB_USER, DB_PASSWORD, $db_name, DB_HOST );

	if ( ! empty( $yii_db->error ) ) {
		$connections[ $db_name ] = null;
		return null;
	}

	$connections[ $db_name ] = $yii_db;

	return $yii_db;
}

/**
 * @return wpdb|null
 */
function extrasport_get_yii_db() {
	return extrasport_get_yii_db_for_club( extrasport_get_current_club_slug() );
}
