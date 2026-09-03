<?php
/**
 * HTML sanitization helpers for imported and stored rich content.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a service content link should be removed from imported HTML.
 *
 * @param string $href Link URL.
 * @param string $text Visible link text.
 * @return bool
 */
function extrasport_should_remove_service_content_link( $href, $text ) {
	$href = (string) $href;
	$text = (string) $text;

	if ( preg_match( '#(?:de-vision|extrasport)\.ru#i', $href ) ) {
		return true;
	}

	if ( preg_match( '#piter\.extrasport\.ru#i', $href ) ) {
		return true;
	}

	if ( preg_match( '#/(schedule|raspisanie)(/|$|\?|\#)#i', $href ) ) {
		return true;
	}

	if ( preg_match( '#/(services/programs|services/group-programs)/#i', $href ) ) {
		return true;
	}

	if ( preg_match( '/расписание/ui', $text ) ) {
		return true;
	}

	if ( preg_match( '/подробное описание/ui', $text ) ) {
		return true;
	}

	if ( preg_match( '/посмотреть видео/ui', $text ) ) {
		return true;
	}

	return false;
}

/**
 * Whether an image src belongs to the current site media library.
 *
 * @param string $src Image URL or path.
 * @return bool
 */
function extrasport_is_local_service_content_image( $src ) {
	$src = trim( (string) $src );

	if ( ! $src || str_starts_with( $src, 'data:' ) ) {
		return false;
	}

	if ( preg_match( '#^https?://#i', $src ) ) {
		$host      = wp_parse_url( $src, PHP_URL_HOST );
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );

		return $host && $site_host && strcasecmp( (string) $host, (string) $site_host ) === 0;
	}

	if ( str_starts_with( $src, '/' ) ) {
		return str_contains( $src, '/wp-content/uploads/' );
	}

	return false;
}

/**
 * Remove legacy outbound links and broken external images from service HTML.
 *
 * @param string $html Post content HTML.
 * @return string
 */
function extrasport_clean_service_content_html( $html ) {
	$html = trim( (string) $html );

	if ( ! $html ) {
		return '';
	}

	do {
		$previous = $html;
		$html     = preg_replace_callback(
			'#<a\b[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)</a>#is',
			static function ( $matches ) {
				$href = html_entity_decode( (string) $matches[1], ENT_QUOTES, 'UTF-8' );
				$text = trim( wp_strip_all_tags( html_entity_decode( (string) $matches[2], ENT_QUOTES, 'UTF-8' ) ) );

				if ( extrasport_should_remove_service_content_link( $href, $text ) ) {
					return '';
				}

				return $matches[0];
			},
			$html
		);
	} while ( $html !== $previous );

	$html = preg_replace_callback(
		'#<img\b[^>]*src=["\']([^"\']*)["\'][^>]*/?>#i',
		static function ( $matches ) {
			$src = html_entity_decode( (string) $matches[1], ENT_QUOTES, 'UTF-8' );

			return extrasport_is_local_service_content_image( $src ) ? $matches[0] : '';
		},
		$html
	);

	$html = preg_replace( '#<(p|u|b|strong|em|span|div)(?:\s[^>]*)?>(?:\s|&nbsp;|<br\s*/?>)*</\1>#iu', '', $html );

	return trim( $html );
}

/**
 * Sanitize rich HTML stored in post content and club options.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function extrasport_normalize_rich_content_html( $html ) {
	$html = html_entity_decode( (string) $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$html = trim( $html );

	if ( ! $html ) {
		return '';
	}

	$html = wp_kses_post( $html );

	return extrasport_clean_service_content_html( $html );
}

/**
 * @deprecated Import scripts only — use extrasport_normalize_rich_content_html().
 */
function extrasport_normalize_yii_html( $html ) {
	return extrasport_normalize_rich_content_html( $html );
}
