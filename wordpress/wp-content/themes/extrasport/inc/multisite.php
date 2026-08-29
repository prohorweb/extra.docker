<?php
/**
 * Multisite and domain-based club resolution
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registry of clubs keyed by domain.
 *
 * @return array<string, array<string, mixed>>
 */
function extrasport_get_clubs_registry() {
	return array(
		'extrasport.local' => array(
			'slug'               => 'piter',
			'domain'             => 'extrasport.local',
			'title'              => 'EXTRASPORT ТК «ПИТЕР»',
			'rules_slug'         => 'piter',
			'rules_title_suffix' => 'ТК «ПИТЕР»',
			'tel'                => '+7 (812) 123-45-67',
			'email'              => 'info@extrasport.local',
			'address'            => 'Санкт-Петербург, ул. Типанова, 21',
			'coordinates'        => '59.8533,30.3497',
			'metro'              => 'м. Площадь Мужества, м. Политехническая',
			'start_work'         => '06:00 – 23:00',
			'start_work_weekend' => '08:00 – 22:00',
			'url_appstore'       => '',
			'url_googleplay'     => '',
			'vk'                 => 'http://vk.com/extrasport_ru',
			'youtube'            => 'http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured',
			'whatsapp'           => 'https://wa.me/79669223172',
			'telegram'           => 'https://t.me/extrasport',
			'legal_url'          => 'https://extrasport.local/legal/',
			'privacy_url'        => 'https://extrasport.local/privacy/',
			'present_video_embed'=> '',
			'timer_enabled'      => false,
			'timer_title'        => 'Специальное предложение',
			'timer_intro'        => 'Оставьте заявку до окончания акции',
			'timer_end'          => '',
		),
		'devision.local'   => array(
			'slug'               => 'devision',
			'domain'             => 'devision.local',
			'title'              => 'De-vision ТРК «РОДЕО ДРАЙВ»',
			'rules_slug'         => 'matros',
			'rules_title_suffix' => 'De-vision',
			'tel'                => '+7 (812) 000-00-00',
			'email'              => 'info@devision.local',
			'address'            => 'Санкт-Петербург, пр. Культуры, 1',
			'coordinates'        => '59.984,30.368',
			'metro'              => '',
			'start_work'         => '07:00 – 23:00',
			'start_work_weekend' => '09:00 – 22:00',
			'url_appstore'       => '',
			'url_googleplay'     => '',
			'vk'                 => '',
			'youtube'            => '',
			'whatsapp'           => '',
			'telegram'           => '',
			'legal_url'          => 'https://devision.local/legal/',
			'privacy_url'        => 'https://devision.local/privacy/',
			'present_video_embed'=> '',
			'timer_enabled'      => false,
			'timer_title'        => 'Специальное предложение',
			'timer_intro'        => 'Оставьте заявку до окончания акции',
			'timer_end'          => '',
		),
	);
}

/**
 * Resolve current request host without port.
 *
 * @return string
 */
function extrasport_get_request_host() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	return preg_replace('/:\d+$/', '', $host);
}

/**
 * Map domain to blog ID when multisite is active.
 *
 * @return array<string, int>
 */
function extrasport_get_domain_blog_map() {
	return apply_filters(
		'extrasport_domain_blog_map',
		array(
			'extrasport.local' => 1,
			'devision.local'   => 2,
		)
	);
}

/**
 * Switch to the blog matching current domain on multisite.
 *
 * @return void
 */
function extrasport_maybe_switch_blog_for_domain() {
	if ( ! is_multisite() ) {
		return;
	}

	$host   = extrasport_get_request_host();
	$map    = extrasport_get_domain_blog_map();
	$blog_id = $map[ $host ] ?? 0;

	if ( $blog_id && (int) get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( (int) $blog_id );
	}
}
add_action( 'plugins_loaded', 'extrasport_maybe_switch_blog_for_domain', 1 );

/**
 * Get club data for current domain.
 *
 * @return array<string, mixed>
 */
function extrasport_get_club() {
	static $club = null;

	if ( null !== $club ) {
		return $club;
	}

	$host     = extrasport_get_request_host();
	$registry = extrasport_get_clubs_registry();
	$club     = $registry[ $host ] ?? reset( $registry );

	return apply_filters( 'extrasport_club', $club );
}

/**
 * Normalize club URLs for current domain.
 *
 * @param array<string, mixed> $club Club data.
 * @return array<string, mixed>
 */
function extrasport_normalize_club_urls( $club ) {
	$club['privacy_url'] = home_url( '/privacy/' );
	$club['legal_url']   = home_url( '/legal/' );
	return $club;
}
add_filter( 'extrasport_club', 'extrasport_normalize_club_urls' );

/**
 * Multisite club switcher cards.
 *
 * @return array<int, array<string, string>>
 */
function extrasport_get_clubs() {
	$registry = extrasport_get_clubs_registry();

	return array_values(
		array_map(
			static function ( $club ) {
				return array(
					'title'   => $club['title'],
					'address' => $club['address'],
					'url'     => 'https://' . $club['domain'] . '/',
				);
			},
			$registry
		)
	);
}
