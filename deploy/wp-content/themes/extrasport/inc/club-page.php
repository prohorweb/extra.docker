<?php
/**
 * Club overview page helpers (/club/).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Technical club stats keyed by site slug.
 *
 * @return array<string, array<int, array{num: string, text: string}>>
 */
function extrasport_get_club_stats_registry() {
	return array(
		'extrasport' => array(
			array(
				'num'  => '2008',
				'text' => '2008 год — год<br> открытия клуба',
			),
			array(
				'num'  => '2240',
				'text' => '2240 м2 — общая<br> площадь клуба',
			),
			array(
				'num'  => '450',
				'text' => '450 м2 — <br>тренажерный зал',
			),
			array(
				'num'  => '290',
				'text' => '290 м2 —<br> 2 аэробных зала',
			),
			array(
				'num'  => '96',
				'text' => '96 м2 — <br>зал единоборств',
			),
			array(
				'num'  => '35',
				'text' => '35 м2 — <br>Cycle-студия',
			),
			array(
				'num'  => '20',
				'text' => '20 метров — бассейн <br>на 3 дорожки',
			),
		),
		'devision'   => array(
			array(
				'num'  => '2014',
				'text' => '2014 год — год<br> открытия клуба',
			),
			array(
				'num'  => '3200',
				'text' => '3200 м2 — общая<br> площадь клуба',
			),
			array(
				'num'  => '1000',
				'text' => '1000 м2 — <br>тренажерный зал',
			),
			array(
				'num'  => '240',
				'text' => '240 м2 —<br> 2 аэробных зала',
			),
			array(
				'num'  => '42',
				'text' => '42 м2 — <br>зал единоборств',
			),
			array(
				'num'  => '104',
				'text' => '104 м2 — <br>Cycle-студия',
			),
			array(
				'num'  => '20',
				'text' => '20 метров — бассейн <br>на 3 дорожки',
			),
		),
	);
}

/**
 * Stats for the current club.
 *
 * @return array<int, array{num: string, text: string}>
 */
function extrasport_get_club_stats() {
	$slug     = extrasport_get_current_club_slug();
	$registry = extrasport_get_club_stats_registry();

	return $registry[ $slug ] ?? array();
}

/**
 * Whether the VR tour banner should render on the club page.
 *
 * @return bool
 */
function extrasport_club_has_vr_banner() {
	return (bool) extrasport_get_club_vr_tour_url();
}

/**
 * VR tour URL for the current club.
 *
 * @return string
 */
function extrasport_get_club_vr_tour_url() {
	$club = extrasport_get_club();

	return trim( (string) ( $club['url_3d_tour'] ?? '' ) );
}

/**
 * VR banner image filename for the current club.
 *
 * @return string
 */
function extrasport_get_club_vr_banner_image() {
	return 'devision' === extrasport_get_current_club_slug()
		? 'vr-full-img.jpg'
		: 'vr-full-piter-img.jpg';
}

/**
 * Breadcrumbs for the club overview page.
 *
 * @return array<int, array{label: string, url?: string}>
 */
function extrasport_get_club_breadcrumbs() {
	$club = extrasport_get_club();

	return array(
		array(
			'label' => $club['title'],
			'url'   => home_url( '/' ),
		),
		array(
			'label' => __( 'О клубе', 'extrasport' ),
			'url'   => extrasport_get_about_page_url( 'club' ),
		),
		array(
			'label' => extrasport_get_about_page_label( 'club' ),
			'url'   => '',
		),
	);
}

/**
 * Rewrite old internal links inside club about HTML.
 *
 * @param string $html Club about HTML.
 * @return string
 */
function extrasport_normalize_club_content_html( $html ) {
	$html = extrasport_normalize_rich_content_html( $html );

	if ( ! $html ) {
		return '';
	}

	$replacements = array(
		'https://extrasport.ru/piter/services/'     => home_url( '/services/' ),
		'http://extrasport.ru/piter/services/'      => home_url( '/services/' ),
		'https://piter.extrasport.ru/es/services/'  => home_url( '/services/' ),
		'http://piter.extrasport.ru/es/services/'   => home_url( '/services/' ),
		'https://de-vision.ru/dv/services/'         => home_url( '/services/' ),
		'http://de-vision.ru/dv/services/'          => home_url( '/services/' ),
		'https://de-vision.ru/services/'            => home_url( '/services/' ),
		'http://de-vision.ru/services/'             => home_url( '/services/' ),
	);

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $html );
}

/**
 * Club overview HTML content.
 *
 * @return string
 */
function extrasport_get_club_page_content() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$club = extrasport_get_club();
	$html = trim( (string) ( $club['about_content'] ?? '' ) );

	if ( $html ) {
		$cache = extrasport_normalize_club_content_html( $html );
		return $cache;
	}

	$cache = '<p>' . esc_html( extrasport_get_club_about_intro() ) . '</p>';
	return $cache;
}

/**
 * Gallery slides for the club overview carousel.
 *
 * @return array<int, array{url: string, alt: string}>
 */
function extrasport_get_club_gallery_slides() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$slides = array();

	$stored_ids = get_option( 'extrasport_club_gallery_ids', array() );
	if ( is_array( $stored_ids ) ) {
		foreach ( $stored_ids as $attachment_id ) {
			$attachment_id = (int) $attachment_id;
			$url           = wp_get_attachment_image_url( $attachment_id, 'full' );
			if ( ! $url ) {
				continue;
			}

			$slides[] = array(
				'url' => $url,
				'alt' => trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ),
			);
		}
	}

	if ( $slides ) {
		$cache = $slides;
		return $cache;
	}

	$cache = array();
	return $cache;
}
