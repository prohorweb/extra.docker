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
 * @return wpdb|null
 */
function extrasport_get_yii_db() {
	static $yii_db = null;
	static $checked = false;

	if ( $checked ) {
		return $yii_db;
	}

	$checked = true;

	if ( ! defined( 'DB_USER' ) || ! defined( 'DB_PASSWORD' ) || ! defined( 'DB_HOST' ) ) {
		return null;
	}

	$yii_db = new wpdb( DB_USER, DB_PASSWORD, EXTRASPORT_YII_DB_NAME, DB_HOST );

	if ( ! empty( $yii_db->error ) ) {
		$yii_db = null;
	}

	return $yii_db;
}
