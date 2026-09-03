<?php
/**
 * Multisite and per-site club settings
 *
 * Network sites:
 * - extrasport.local → club slug `extrasport` (EXTRASPORT ТК «ПИТЕР»)
 * - devision.local   → club slug `devision` (De-vision ТРЦ «Родео Драйв»)
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
define( 'EXTRASPORT_CLUB_DATA_VERSION', 5 );

/**
 * Legacy Yii2 subdomain slugs mapped to current club slugs.
 *
 * @return array<string, string>
 */
function extrasport_get_legacy_club_slug_map() {
	return array(
		'piter'  => 'extrasport',
		'matros' => 'devision',
	);
}

/**
 * Normalize legacy club or rules slug.
 *
 * @param string $slug Raw slug.
 * @return string
 */
function extrasport_normalize_club_slug( $slug ) {
	$slug = sanitize_key( (string) $slug );
	$map  = extrasport_get_legacy_club_slug_map();

	return $map[ $slug ] ?? $slug;
}

/**
 * Default club profiles keyed by site slug (used when seeding a new blog).
 *
 * @return array<string, array<string, mixed>>
 */
function extrasport_get_club_defaults_registry() {
	return array(
		'extrasport' => array(
			'slug'                => 'extrasport',
			'title'               => 'EXTRASPORT ТК «ПИТЕР»',
			'rules_slug'          => 'extrasport',
			'rules_title_suffix'  => 'ТК «ПИТЕР»',
			'rules_modal_title'   => 'ПРАВИЛА СПОРТИВНОГО КЛУБА «ЭКСТРА СПОРТ» ТК «ПИТЕР»',
			'tel'                 => '+7 812 565 52 49',
			'email'               => 'piter@extrasport.ru',
			'address'             => 'Санкт-Петербург, ул. Типанова, 21, ТК «Питер»',
			'coordinates'         => '59.8533,30.3497',
			'yandex_maps_api_key' => '',
			'metro'               => 'м. Проспект Славы, м. Московская',
			'start_work'          => 'с 8:00 до 22:00',
			'start_work_weekend'  => 'с 9:00 до 22:00',
			'sales_work'          => 'с 10:00 до 22:00',
			'url_appstore'        => '',
			'url_googleplay'      => '',
			'vk'                  => 'http://vk.com/extrasport_ru',
			'youtube'             => 'http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured',
			'whatsapp'            => 'https://wa.me/79669223172',
			'telegram'            => 'https://t.me/extrasport',
			'url_3d_tour'         => 'https://pro3d.pro/D49acmjtV23/',
			'present_video_embed' => '',
			'timer_enabled'       => false,
			'timer_title'         => 'Специальное предложение',
			'timer_intro'         => 'Оставьте заявку до окончания акции',
			'timer_start'         => '',
			'timer_end'           => '',
			'company_name'        => 'ООО «ФИТНЕС ПРО» ИНН 7810743960',
		),
		'devision'   => array(
			'slug'                => 'devision',
			'title'               => 'De-vision ТРЦ «Родео Драйв»',
			'rules_slug'          => 'devision',
			'rules_title_suffix'  => 'De-vision',
			'rules_modal_title'   => 'ПРАВИЛА СПОРТИВНОГО КЛУБА DE-VISION',
			'tel'                 => '+7 (812) 644-02-88',
			'email'               => 'rodeo_manager@de-vision.ru',
			'address'             => 'СПб, пр. Культуры, д. 1, ТРЦ «Родео Драйв»',
			'coordinates'         => '59.984,30.368',
			'yandex_maps_api_key' => '',
			'metro'               => 'м. Озерки, м. Академическая, м. Проспект Просвещения',
			'start_work'          => 'с 8:00 до 22:00',
			'start_work_weekend'  => 'с 9:00 до 22:00',
			'sales_work'          => 'с 9:30 до 22:00',
			'url_appstore'        => 'https://apps.apple.com/ru/app/de-vision/id1469862169',
			'url_googleplay'      => 'https://play.google.com/store/apps/details?id=air.com.devision',
			'vk'                  => 'https://vk.me/club69170005',
			'youtube'             => 'https://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured',
			'whatsapp'            => 'https://wa.me/79219555013',
			'telegram'            => '',
			'url_3d_tour'         => 'https://pro3d.pro/r6moPMPJboE/',
			'present_video_embed' => '',
			'timer_enabled'       => false,
			'timer_title'         => 'Год фитнеса с бассейном 5500. Второй абонемент в ПОДАРОК!',
			'timer_intro'         => 'Оставьте заявку по форме ниже, наш менеджер свяжется с вами и расскажет как внести предоплату, чтобы забронировать за вами абонемент.',
			'timer_start'         => '',
			'timer_end'           => '',
			'company_name'        => '',
		),
	);
}

/**
 * Per-club visual identity (logo, map marker, accent colors).
 *
 * @return array<string, array<string, mixed>>
 */
function extrasport_get_brand_registry() {
	return array(
		'extrasport' => array(
			'logo'        => 'logo.svg',
			'logo_inline' => true,
			'logo_width'  => 216,
			'logo_height' => 86,
			'marker'      => 'marker.png',
			'primary'     => '#ff6600',
			'accent'      => '#dc5800',
		),
		'devision'   => array(
			'logo'        => 'logo-devision.svg',
			'logo_inline' => true,
			'logo_width'  => 300,
			'logo_height' => 73,
			'marker'      => 'marker2.png',
			'primary'     => '#74b72e',
			'accent'      => '#5a9424',
		),
	);
}

/**
 * Brand assets and colors for the current site.
 *
 * @return array<string, mixed>
 */
function extrasport_get_brand() {
	static $cache = array();

	$slug = extrasport_get_current_club_slug();

	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}

	$registry = extrasport_get_brand_registry();
	$defaults = $registry[ $slug ] ?? $registry['extrasport'];

	$cache[ $slug ] = apply_filters(
		'extrasport_brand',
		array(
			'slug'        => $slug,
			'logo'        => $defaults['logo'],
			'logo_url'    => EXTRASPORT_URI . '/assets/img/' . $defaults['logo'],
			'logo_path'   => EXTRASPORT_DIR . '/assets/img/' . $defaults['logo'],
			'logo_inline' => ! empty( $defaults['logo_inline'] ),
			'marker_url'  => EXTRASPORT_URI . '/assets/img/' . $defaults['marker'],
			'primary'     => $defaults['primary'],
			'accent'      => $defaults['accent'],
			'logo_width'  => (int) $defaults['logo_width'],
			'logo_height' => (int) $defaults['logo_height'],
		)
	);

	return $cache[ $slug ];
}

/**
 * Render club logo markup (inline SVG when animation is required).
 *
 * @param array<string, mixed> $attrs Optional HTML attributes.
 * @return string
 */
function extrasport_render_brand_logo( array $attrs = array() ) {
	$brand = extrasport_get_brand();
	$alt   = isset( $attrs['alt'] ) ? (string) $attrs['alt'] : get_bloginfo( 'name' );
	unset( $attrs['alt'] );

	$class = isset( $attrs['class'] ) ? (string) $attrs['class'] : 'site-header__logo-image';
	unset( $attrs['class'] );

	if ( ! empty( $brand['logo_inline'] ) && ! empty( $brand['logo_path'] ) && is_readable( $brand['logo_path'] ) ) {
		$svg = file_get_contents( $brand['logo_path'] );

		if ( false !== $svg && str_contains( $svg, '<svg' ) ) {
			$attr_string = '';
			foreach ( $attrs as $key => $value ) {
				$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( (string) $value ) );
			}

			$svg = preg_replace(
				'/<svg\b/',
				sprintf(
					'<svg class="%s" role="img" aria-label="%s"%s',
					esc_attr( $class ),
					esc_attr( $alt ),
					$attr_string
				),
				$svg,
				1
			);

			return $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme asset.
		}
	}

	$attr_string = '';
	foreach ( $attrs as $key => $value ) {
		$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( (string) $value ) );
	}

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="%3$s" width="%4$d" height="%5$d"%6$s>',
		esc_url( $brand['logo_url'] ),
		esc_attr( $alt ),
		esc_attr( $class ),
		(int) $brand['logo_width'],
		(int) $brand['logo_height'],
		$attr_string
	);
}

/**
 * Short club logo for membership plan cards (inline animated SVG).
 *
 * @return string
 */
function extrasport_render_membership_card_logo() {
	static $instance = 0;

	$slug = extrasport_get_current_club_slug();
	$path = EXTRASPORT_DIR . '/assets/images/' . ( 'devision' === $slug ? 'devision' : 'extrasport' ) . '/logo-short.svg';
	$fallback = EXTRASPORT_URI . '/assets/images/logo-short.svg';

	if ( ! is_readable( $path ) ) {
		return sprintf(
			'<div class="membership-card__logo"><img src="%s" alt=""></div>',
			esc_url( $fallback )
		);
	}

	$svg = file_get_contents( $path );
	if ( false === $svg || ! str_contains( $svg, '<svg' ) ) {
		return sprintf(
			'<div class="membership-card__logo"><img src="%s" alt=""></div>',
			esc_url( $fallback )
		);
	}

	++$instance;
	$suffix = '-mc' . $instance;
	$ids    = array();

	if ( preg_match_all( '/\bid=(["\'])([^"\']+)\1/', $svg, $matches ) ) {
		$ids = array_unique( $matches[2] );
		usort(
			$ids,
			static function ( $a, $b ) {
				return strlen( $b ) - strlen( $a );
			}
		);
	}

	foreach ( $ids as $id ) {
		$svg = str_replace(
			array(
				'id="' . $id . '"',
				"id='" . $id . "'",
				'href="#' . $id . '"',
				"href='#" . $id . "'",
				'url(#' . $id . ')',
			),
			array(
				'id="' . $id . $suffix . '"',
				"id='" . $id . $suffix . "'",
				'href="#' . $id . $suffix . '"',
				"href='#" . $id . $suffix . "'",
				'url(#' . $id . $suffix . ')',
			),
			$svg
		);
	}

	return '<div class="membership-card__logo">' . $svg . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme asset.
}

/**
 * Add club slug to body classes for theme overrides.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function extrasport_body_class( $classes ) {
	$classes[] = 'club-' . extrasport_get_current_club_slug();
	return $classes;
}
add_filter( 'body_class', 'extrasport_body_class' );

/**
 * Resolve club slug for the current blog.
 *
 * @return string extrasport|devision
 */
function extrasport_get_current_club_slug() {
	if ( is_multisite() ) {
		return 2 === (int) get_current_blog_id() ? 'devision' : 'extrasport';
	}

	$host = extrasport_get_request_host();
	return str_contains( $host, 'devision' ) ? 'devision' : 'extrasport';
}

/**
 * Basename for service clubs hero/about video (service_extra | service_devision).
 *
 * @return string
 */
function extrasport_get_service_clubs_video_basename() {
	return 'devision' === extrasport_get_current_club_slug() ? 'service_devision' : 'service_extra';
}

/**
 * Map blog ID to club slug when seeding network sites.
 *
 * @param int $blog_id Blog ID.
 * @return string
 */
function extrasport_get_club_slug_for_blog( $blog_id ) {
	return 2 === (int) $blog_id ? 'devision' : 'extrasport';
}

/**
 * Seed club option on a new site if empty.
 *
 * @param int $blog_id Blog ID.
 * @return void
 */
function extrasport_seed_club_option( $blog_id ) {
	$registry = extrasport_get_club_defaults_registry();
	$slug     = extrasport_get_club_slug_for_blog( $blog_id );
	$defaults = $registry[ $slug ] ?? $registry['extrasport'];

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
 * Normalize legacy Yii2 slugs in stored club data.
 *
 * @param array<string, mixed> $club Club data.
 * @return array<string, mixed>
 */
function extrasport_normalize_legacy_club_data( $club ) {
	if ( ! empty( $club['slug'] ) ) {
		$club['slug'] = extrasport_normalize_club_slug( $club['slug'] );
	}

	if ( ! empty( $club['rules_slug'] ) ) {
		$club['rules_slug'] = extrasport_normalize_club_slug( $club['rules_slug'] );
	}

	return $club;
}
add_filter( 'extrasport_club', 'extrasport_normalize_legacy_club_data', 5 );

/**
 * Get club data for the current site (blog options + defaults).
 *
 * @return array<string, mixed>
 */
function extrasport_get_club() {
	static $cache = array();

	$blog_id = get_current_blog_id();

	if ( isset( $cache[ $blog_id ] ) ) {
		return $cache[ $blog_id ];
	}

	$registry = extrasport_get_club_defaults_registry();
	$slug     = extrasport_get_current_club_slug();
	$defaults = $registry[ $slug ] ?? $registry['extrasport'];
	$saved    = get_option( EXTRASPORT_CLUB_OPTION, array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$club = wp_parse_args( $saved, $defaults );
	$club['domain'] = extrasport_get_request_host();

	$cache[ $blog_id ] = apply_filters( 'extrasport_club', $club );

	return $cache[ $blog_id ];
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
				'slug'    => extrasport_get_current_club_slug(),
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
			'slug'    => extrasport_get_current_club_slug(),
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
		update_option( EXTRASPORT_CLUB_OPTION, $registry[ $slug ] ?? $registry['extrasport'], false );
	}
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_current_club', 20 );

/**
 * Apply canonical club contact data when defaults are updated.
 *
 * Preserves imported content fields like about_content.
 *
 * @return void
 */
function extrasport_maybe_sync_club_defaults() {
	$version = (int) get_option( 'extrasport_club_data_version', 0 );
	if ( $version >= EXTRASPORT_CLUB_DATA_VERSION ) {
		return;
	}

	$registry = extrasport_get_club_defaults_registry();
	$slug     = extrasport_get_current_club_slug();
	$defaults = $registry[ $slug ] ?? $registry['extrasport'];
	$current  = get_option( EXTRASPORT_CLUB_OPTION, array() );

	if ( ! is_array( $current ) ) {
		$current = array();
	}

	$preserve_keys = array( 'about_content', 'about_intro', 'url_3d_tour' );
	$merged        = wp_parse_args( $defaults, $current );

	foreach ( $preserve_keys as $key ) {
		if ( ! empty( $current[ $key ] ) ) {
			$merged[ $key ] = $current[ $key ];
		}
	}

	update_option( EXTRASPORT_CLUB_OPTION, $merged, false );
	update_option( 'extrasport_club_data_version', EXTRASPORT_CLUB_DATA_VERSION, false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_sync_club_defaults', 25 );

/**
 * Whether the current site is EXTRASPORT Piter.
 *
 * @return bool
 */
function extrasport_is_extrasport_site() {
	return 'extrasport' === extrasport_get_current_club_slug();
}

/**
 * Whether the current site is De-vision.
 *
 * @return bool
 */
function extrasport_is_devision_site() {
	return 'devision' === extrasport_get_current_club_slug();
}

/**
 * Legacy Yii2 subdomain for the current club.
 *
 * @return string piter|matros
 */
function extrasport_get_legacy_yii_subdomain() {
	return extrasport_is_devision_site() ? 'matros' : 'piter';
}
