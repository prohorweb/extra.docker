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
		'vk'                 => 'http://vk.com/extrasport_ru',
		'youtube'            => 'http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured',
		'whatsapp'           => 'https://wa.me/79669223172',
		'telegram'           => 'https://t.me/extrasport',
		'legal_url'          => home_url( '/legal/' ),
		'privacy_url'        => home_url( '/privacy/' ),
		'metro'              => 'м. Площадь Мужества, м. Политехническая',
	);
}

/**
 * Multisite club switcher cards (domain-based).
 *
 * @return array<int, array<string, string>>
 */
function extrasport_get_clubs() {
	return array(
		array(
			'title'   => 'EXTRASPORT ТК «ПИТЕР»',
			'address' => 'Санкт-Петербург, ул. Типанова, 21',
			'url'     => 'https://extrasport.local/',
		),
		array(
			'title'   => 'EXTRASPORT «МАТРОСА ЖЕЛЕЗНЯКА»',
			'address' => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
			'url'     => 'https://extrasport.local/',
		),
		array(
			'title'   => 'De-vision ТРК "РОДЕО ДРАЙВ"',
			'address' => 'Санкт-Петербург, пр. Культуры, 1',
			'url'     => 'https://devision.local/',
		),
	);
}
