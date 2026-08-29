<?php
/**
 * Domain-based multisite routing for extrasport.local / devision.local
 *
 * @package ExtraSport
 */

if ( ! defined( 'SUNRISE' ) ) {
	return;
}

global $wpdb;

$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
$host = preg_replace( '/:\d+$/', '', $host );

$domain_blog_map = array(
	'extrasport.local' => 1,
	'devision.local'   => 2,
);

if ( empty( $host ) || ! isset( $domain_blog_map[ $host ] ) ) {
	return;
}

$target_blog_id = (int) $domain_blog_map[ $host ];

if ( $target_blog_id === 1 ) {
	return;
}

$blog = $wpdb->get_row(
	$wpdb->prepare(
		"SELECT * FROM {$wpdb->blogs} WHERE blog_id = %d LIMIT 1",
		$target_blog_id
	)
);

if ( ! $blog ) {
	return;
}

$current_blog            = $blog;
$wpdb->set_blog_id( $target_blog_id );
$blog_id                 = $target_blog_id;
$table_prefix            = $wpdb->get_blog_prefix( $target_blog_id );
$GLOBALS['table_prefix'] = $table_prefix;
