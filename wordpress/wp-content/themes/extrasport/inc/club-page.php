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
				'num'  => '2007',
				'text' => '2007 год — год<br> открытия клуба',
			),
			array(
				'num'  => '2020',
				'text' => '2020 м2 — общая<br> площадь клуба',
			),
			array(
				'num'  => '450',
				'text' => '450 м2 — <br>тренажерный зал',
			),
			array(
				'num'  => '136',
				'text' => '136 м2 —<br>аэробные залы',
			),
			array(
				'num'  => '49',
				'text' => '49 м2 — <br>Cycle-студия',
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
 * Resolve a legacy main banner image on disk.
 *
 * @param string $filename Banner filename from Yii main_banners.img.
 * @return string
 */
function extrasport_resolve_yii_main_banner_path( $filename ) {
	$filename = sanitize_file_name( (string) $filename );

	if ( ! $filename ) {
		return '';
	}

	foreach ( array( 'image/banners', 'banners' ) as $subdir ) {
		$path = extrasport_resolve_yii_upload_image_path( $subdir, $filename );
		if ( $path ) {
			return $path;
		}
	}

	return '';
}

/**
 * Rewrite legacy Yii links inside club about HTML.
 *
 * @param string $html Club about HTML.
 * @return string
 */
function extrasport_normalize_club_content_html( $html ) {
	$html = extrasport_normalize_yii_html( $html );

	if ( ! $html ) {
		return '';
	}

	$replacements = array(
		'https://extrasport.ru/piter/services/' => home_url( '/services/' ),
		'http://extrasport.ru/piter/services/'  => home_url( '/services/' ),
		'https://piter.extrasport.ru/es/services/' => home_url( '/services/' ),
		'http://piter.extrasport.ru/es/services/'  => home_url( '/services/' ),
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

	$yii = extrasport_get_yii_db();
	if ( $yii ) {
		$row = $yii->get_row( 'SELECT content FROM club LIMIT 1' );
		if ( $row && ! empty( $row->content ) ) {
			$cache = extrasport_normalize_club_content_html( $row->content );
			return $cache;
		}
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

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		$cache = array();
		return $cache;
	}

	$rows = $yii->get_results(
		'SELECT img FROM main_banners WHERE status = 10 ORDER BY position ASC'
	);

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			if ( empty( $row->img ) ) {
				continue;
			}

			$filename = sanitize_file_name( (string) $row->img );
			$path     = extrasport_resolve_yii_main_banner_path( $filename );

			if ( $path ) {
				$slides[] = array(
					'url' => extrasport_get_yii_uploads_public_url( 'image/banners/' . $filename, 'banners/' . $filename ),
					'alt' => '',
				);
				continue;
			}

			$remote = extrasport_get_legacy_club_public_base_url() . '/uploads/image/banners/' . rawurlencode( $filename );
			$slides[] = array(
				'url' => $remote,
				'alt' => '',
			);
		}
	}

	$cache = array_filter( $slides );
	return $cache;
}

/**
 * Legacy public site base URL for the current club.
 *
 * @return string
 */
function extrasport_get_legacy_club_public_base_url() {
	if ( 'devision' === extrasport_get_current_club_slug() ) {
		return 'https://de-vision.ru';
	}

	return 'https://piter.extrasport.ru';
}

/**
 * Public URL for a Yii upload file when nginx serves /uploads/.
 *
 * @param string ...$relative_paths Candidate relative paths under uploads/.
 * @return string
 */
function extrasport_get_yii_uploads_public_url( ...$relative_paths ) {
	foreach ( $relative_paths as $relative_path ) {
		$relative_path = ltrim( (string) $relative_path, '/' );
		$parts         = explode( '/', $relative_path );
		$filename      = array_pop( $parts );
		$subdir        = implode( '/', $parts );

		if ( extrasport_resolve_yii_upload_image_path( $subdir, $filename ) ) {
			return home_url( '/uploads/' . $relative_path );
		}
	}

	$first = (string) ( $relative_paths[0] ?? '' );

	return extrasport_get_legacy_club_public_base_url() . '/uploads/' . ltrim( $first, '/' );
}
