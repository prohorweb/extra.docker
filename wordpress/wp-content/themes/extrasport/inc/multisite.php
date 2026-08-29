<?php
/**
 * Multisite and per-site club settings
 *
 * Site options (get_option) — club-specific data on each blog.
 * Network options (get_site_option) — shared infrastructure via theme-settings.php.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CLUB_OPTION', 'extrasport_club' );

/**
 * Default club profiles keyed by site slug (used when seeding a new blog).
 *
 * @return array<string, array<string, mixed>>
 */
function extrasport_get_club_defaults_registry() {
	return array(
		'piter'    => array(
			'slug'                => 'piter',
			'title'               => 'EXTRASPORT ТК «ПИТЕР»',
			'rules_slug'          => 'piter',
			'rules_title_suffix'  => 'ТК «ПИТЕР»',
			'tel'                 => '+7 (812) 123-45-67',
			'email'               => 'info@extrasport.local',
			'address'             => 'Санкт-Петербург, ул. Типанова, 21',
			'coordinates'         => '59.8533,30.3497',
			'metro'               => 'м. Площадь Мужества, м. Политехническая',
			'start_work'          => '06:00 – 23:00',
			'start_work_weekend'  => '08:00 – 22:00',
			'url_appstore'        => '',
			'url_googleplay'      => '',
			'vk'                  => 'http://vk.com/extrasport_ru',
			'youtube'             => 'http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured',
			'whatsapp'            => 'https://wa.me/79669223172',
			'telegram'            => 'https://t.me/extrasport',
			'present_video_embed' => '',
			'timer_enabled'       => false,
			'timer_title'         => 'Специальное предложение',
			'timer_intro'         => 'Оставьте заявку до окончания акции',
			'timer_end'           => '',
		),
		'devision' => array(
			'slug'                => 'devision',
			'title'               => 'De-vision ТРК «РОДЕО ДРАЙВ»',
			'rules_slug'          => 'matros',
			'rules_title_suffix'  => 'De-vision',
			'tel'                 => '+7 (812) 000-00-00',
			'email'               => 'info@devision.local',
			'address'             => 'Санкт-Петербург, пр. Культуры, 1',
			'coordinates'         => '59.984,30.368',
			'metro'               => '',
			'start_work'          => '07:00 – 23:00',
			'start_work_weekend'  => '09:00 – 22:00',
			'url_appstore'        => '',
			'url_googleplay'      => '',
			'vk'                  => '',
			'youtube'             => '',
			'whatsapp'            => '',
			'telegram'            => '',
			'present_video_embed' => '',
			'timer_enabled'       => false,
			'timer_title'         => 'Специальное предложение',
			'timer_intro'         => 'Оставьте заявку до окончания акции',
			'timer_end'           => '',
		),
	);
}

/**
 * Resolve default profile slug for the current blog.
 *
 * @return string
 */
function extrasport_get_current_club_slug() {
	if ( is_multisite() ) {
		return 2 === (int) get_current_blog_id() ? 'devision' : 'piter';
	}

	$host = extrasport_get_request_host();
	return str_contains( $host, 'devision' ) ? 'devision' : 'piter';
}

/**
 * Seed club option on a new site if empty.
 *
 * @param int $blog_id Blog ID.
 * @return void
 */
function extrasport_seed_club_option( $blog_id ) {
	$registry = extrasport_get_club_defaults_registry();
	$slug     = 2 === (int) $blog_id ? 'devision' : 'piter';
	$defaults = $registry[ $slug ] ?? $registry['piter'];

	switch_to_blog( $blog_id );
	if ( ! get_option( EXTRASPORT_CLUB_OPTION ) ) {
		update_option( EXTRASPORT_CLUB_OPTION, $defaults, false );
	}
	restore_current_blog();
}

/**
 * Resolve current request host without port.
 *
 * @return string
 */
function extrasport_get_request_host() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	return preg_replace( '/:\d+$/', '', $host );
}

/**
 * Get club data for the current site (blog options + defaults).
 *
 * @return array<string, mixed>
 */
function extrasport_get_club() {
	static $club = null;

	if ( null !== $club ) {
		return $club;
	}

	$registry = extrasport_get_club_defaults_registry();
	$slug     = extrasport_get_current_club_slug();
	$defaults = $registry[ $slug ] ?? $registry['piter'];
	$saved    = get_option( EXTRASPORT_CLUB_OPTION, array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$club = wp_parse_args( $saved, $defaults );
	$club['domain'] = extrasport_get_request_host();

	return apply_filters( 'extrasport_club', $club );
}

/**
 * Persist club settings for the current site.
 *
 * @param array<string, mixed> $data Club fields to merge and save.
 * @return bool
 */
function extrasport_update_club( array $data ) {
	$current = extrasport_get_club();
	$merged  = wp_parse_args( $data, $current );
	return update_option( EXTRASPORT_CLUB_OPTION, $merged, false );
}

/**
 * Normalize dynamic URLs for the active site.
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
 * Club switcher cards from all public network sites.
 *
 * @return array<int, array<string, string>>
 */
function extrasport_get_clubs() {
	if ( ! is_multisite() ) {
		$club = extrasport_get_club();
		return array(
			array(
				'title'   => $club['title'],
				'address' => $club['address'],
				'url'     => home_url( '/' ),
			),
		);
	}

	$clubs = array();

	foreach ( get_sites( array( 'public' => 1, 'deleted' => 0 ) ) as $site ) {
		switch_to_blog( (int) $site->blog_id );
		$club    = extrasport_get_club();
		$clubs[] = array(
			'title'   => $club['title'],
			'address' => $club['address'],
			'url'     => home_url( '/' ),
		);
		restore_current_blog();
	}

	return $clubs;
}

/**
 * Seed options when a site is added to the network.
 *
 * @param WP_Site|object $new_site New site object.
 * @param array          $args     Site arguments.
 * @return void
 */
function extrasport_on_site_created( $new_site, $args = array() ) {
	unset( $args );
	extrasport_seed_club_option( (int) $new_site->blog_id );
}
add_action( 'wp_initialize_site', 'extrasport_on_site_created', 10, 2 );

/**
 * Seed blog #1 on theme activation if option is missing.
 *
 * @return void
 */
function extrasport_maybe_seed_current_club() {
	if ( ! get_option( EXTRASPORT_CLUB_OPTION ) ) {
		$registry = extrasport_get_club_defaults_registry();
		$slug     = extrasport_get_current_club_slug();
		update_option( EXTRASPORT_CLUB_OPTION, $registry[ $slug ] ?? $registry['piter'], false );
	}
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_current_club', 20 );
