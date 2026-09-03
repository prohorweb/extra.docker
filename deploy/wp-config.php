<?php
/**
 * WordPress config for the official Docker image (FPM).
 *
 * Core files come from the image volume; only theme/plugins/uploads are mounted.
 * Database credentials — from container environment (see docker-compose.yml).
 *
 * @package ExtraSport
 */

if ( ! function_exists( 'getenv_docker' ) ) {
	/**
	 * @param string $env     Environment variable name.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	function getenv_docker( $env, $default ) {
		if ( $file_env = getenv( $env . '_FILE' ) ) {
			return rtrim( file_get_contents( $file_env ), "\r\n" );
		}

		if ( ( $val = getenv( $env ) ) !== false ) {
			return $val;
		}

		return $default;
	}
}

define( 'DB_NAME', getenv_docker( 'WORDPRESS_DB_NAME', 'wordpress' ) );
define( 'DB_USER', getenv_docker( 'WORDPRESS_DB_USER', 'wordpress' ) );
define( 'DB_PASSWORD', getenv_docker( 'WORDPRESS_DB_PASSWORD', 'wordpress123' ) );
define( 'DB_HOST', getenv_docker( 'WORDPRESS_DB_HOST', 'db' ) );
define( 'DB_CHARSET', getenv_docker( 'WORDPRESS_DB_CHARSET', 'utf8mb4' ) );
define( 'DB_COLLATE', getenv_docker( 'WORDPRESS_DB_COLLATE', '' ) );

define( 'AUTH_KEY', getenv_docker( 'WORDPRESS_AUTH_KEY', 'change-me-auth-key' ) );
define( 'SECURE_AUTH_KEY', getenv_docker( 'WORDPRESS_SECURE_AUTH_KEY', 'change-me-secure-auth-key' ) );
define( 'LOGGED_IN_KEY', getenv_docker( 'WORDPRESS_LOGGED_IN_KEY', 'change-me-logged-in-key' ) );
define( 'NONCE_KEY', getenv_docker( 'WORDPRESS_NONCE_KEY', 'change-me-nonce-key' ) );
define( 'AUTH_SALT', getenv_docker( 'WORDPRESS_AUTH_SALT', 'change-me-auth-salt' ) );
define( 'SECURE_AUTH_SALT', getenv_docker( 'WORDPRESS_SECURE_AUTH_SALT', 'change-me-secure-auth-salt' ) );
define( 'LOGGED_IN_SALT', getenv_docker( 'WORDPRESS_LOGGED_IN_SALT', 'change-me-logged-in-salt' ) );
define( 'NONCE_SALT', getenv_docker( 'WORDPRESS_NONCE_SALT', 'change-me-nonce-salt' ) );

$table_prefix = getenv_docker( 'WORDPRESS_TABLE_PREFIX', 'wp_' );

/** Multisite (domain-based): extrasport.local + devision.local */
define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', getenv_docker( 'WORDPRESS_DOMAIN_CURRENT_SITE', 'extrasport.local' ) );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define( 'COOKIEDOMAIN', getenv_docker( 'WORDPRESS_COOKIE_DOMAIN', '.local' ) );

define( 'WP_DEBUG', !! getenv_docker( 'WORDPRESS_DEBUG', '' ) );
define( 'WP_MEMORY_LIMIT', getenv_docker( 'WORDPRESS_MEMORY_LIMIT', '256M' ) );
define( 'WP_MAX_MEMORY_LIMIT', getenv_docker( 'WORDPRESS_MAX_MEMORY_LIMIT', '512M' ) );
define( 'WPLANG', getenv_docker( 'WORDPRESS_LANG', 'ru_RU' ) );
define( 'DISALLOW_FILE_EDIT', true );

if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && str_contains( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) ) {
	$_SERVER['HTTPS'] = 'on';
}

$config_extra = getenv_docker( 'WORDPRESS_CONFIG_EXTRA', '' );
if ( $config_extra ) {
	eval( $config_extra );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
