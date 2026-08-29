<?php
/**
 * Club settings helper
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get club contact/settings data.
 *
 * @return array<string, string>
 */
function extrasport_get_club() {
	return array(
		'title'              => get_bloginfo( 'name' ),
		'tel'                => '+7 (812) 123-45-67',
		'email'              => 'info@extrasport.local',
		'address'            => 'Санкт-Петербург, ул. Типанова, 21',
		'coordinates'        => '59.8533,30.3497',
		'start_work'         => '06:00 – 23:00',
		'start_work_weekend' => '08:00 – 22:00',
		'url_appstore'       => '',
		'url_googleplay'     => '',
	);
}
