<?php
/**
 * Legacy Yii2 integration flags (disabled by default for production).
 *
 * Set both constants to false for a one-time import, then restore true.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EXTRASPORT_DISABLE_YII_DB' ) ) {
	define( 'EXTRASPORT_DISABLE_YII_DB', true );
}

if ( ! defined( 'EXTRASPORT_DISABLE_LEGACY_IMPORT' ) ) {
	define( 'EXTRASPORT_DISABLE_LEGACY_IMPORT', true );
}

/**
 * Whether read-only Yii DB connections and remote legacy URLs are allowed.
 *
 * @return bool
 */
function extrasport_is_yii_db_enabled() {
	return ! EXTRASPORT_DISABLE_YII_DB;
}

/**
 * Whether automatic seed, migration, and sync hooks may run.
 *
 * @return bool
 */
function extrasport_is_legacy_import_enabled() {
	return ! EXTRASPORT_DISABLE_LEGACY_IMPORT;
}

/**
 * Whether Yii/frontend filesystem paths may be resolved (Docker mounts, frontend/web).
 *
 * Theme-local assets under assets/img/ remain available when this is false.
 *
 * @return bool
 */
function extrasport_allows_legacy_file_paths() {
	return extrasport_is_yii_db_enabled();
}
