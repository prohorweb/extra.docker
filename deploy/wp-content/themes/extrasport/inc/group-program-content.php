<?php
/**
 * Shared helpers for importing group program content from production HTML.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allowed HTML for imported service/group program content.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function extrasport_kses_service_content( $html ) {
	$allowed = wp_kses_allowed_html( 'post' );
	$allowed['iframe'] = array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
		'title'           => true,
		'loading'         => true,
	);
	$allowed['img']['style'] = true;

	return wp_kses( (string) $html, $allowed );
}

/**
 * Fetch a group program page from a production site.
 *
 * @param string $slug Program slug.
 * @param string $base_url Production programs base URL.
 * @return string HTML or empty string.
 */
function extrasport_fetch_production_group_program_html( $slug, $base_url ) {
	$slug = sanitize_title( (string) $slug );
	if ( ! $slug ) {
		return '';
	}

	$url = trailingslashit( untrailingslashit( (string) $base_url ) ) . rawurlencode( $slug ) . '/';
	$response = wp_remote_get(
		$url,
		array(
			'timeout'    => 30,
			'user-agent' => 'ExtraSport WordPress Migration',
		)
	);

	if ( is_wp_error( $response ) ) {
		return '';
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return '';
	}

	return (string) wp_remote_retrieve_body( $response );
}

/**
 * Extract intro text from a production group program page.
 *
 * @param string $html  Raw HTML.
 * @param string $title Program title.
 * @return string
 */
function extrasport_parse_production_group_program_intro( $html, $title ) {
	if ( ! $html ) {
		return '';
	}

	$title_pattern = preg_quote( (string) $title, '/' );

	if ( preg_match( '/<h2[^>]*>\s*' . $title_pattern . '\s*<\/h2>\s*<div class="page__desc">(.*?)<\/div>/is', $html, $match ) ) {
		if ( preg_match_all( '/<p[^>]*>(.*?)<\/p>/is', $match[1], $paragraphs ) ) {
			foreach ( $paragraphs[1] as $paragraph ) {
				$text = trim( wp_strip_all_tags( html_entity_decode( $paragraph, ENT_QUOTES, 'UTF-8' ) ) );
				if ( $text && ! preg_match( '/расписание/i', $text ) ) {
					return sanitize_text_field( html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) );
				}
			}
		}

		if ( preg_match_all( '/<h[2-4][^>]*>(.*?)<\/h[2-4]>/is', $match[1], $headings ) ) {
			foreach ( $headings[1] as $heading ) {
				$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( html_entity_decode( $heading, ENT_QUOTES, 'UTF-8' ) ) ) );
				if ( $text && ! preg_match( '/расписание/i', $text ) ) {
					return sanitize_text_field( $text );
				}
			}
		}
	}

	return '';
}

/**
 * Extract main HTML content from a production group program page.
 *
 * @param string $html  Raw HTML.
 * @param string $title Program title.
 * @return string
 */
function extrasport_parse_production_group_program_content( $html, $title ) {
	if ( ! $html || ! $title ) {
		return '';
	}

	$title_pattern = preg_quote( (string) $title, '/' );

	if ( ! preg_match( '/<h2[^>]*>\s*' . $title_pattern . '\s*<\/h2>(.*?)(?:<h2[^>]*>\s*другие|<div class="[^"]*other|<section[^>]*class="[^"]*other|class="page__other|<!--\s*other)/is', $html, $match ) ) {
		return '';
	}

	$content = trim( (string) $match[1] );
	if ( ! $content ) {
		return '';
	}

	$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
	$content = preg_replace( '/\sdata-method="post"/i', '', $content );
	$content = extrasport_clean_service_content_html( $content );

	return extrasport_kses_service_content( $content );
}

/**
 * Import production HTML into group program service posts from a roster.
 *
 * @param callable(): array<int, array<string, mixed>> $roster_callback Roster provider.
 * @param string                                       $production_base Production programs base URL.
 * @param string                                       $version_option  Option key for seed version.
 * @param int                                          $seed_version    Current seed version.
 * @param bool                                         $force           Overwrite existing content.
 * @return void
 */
function extrasport_seed_group_program_content_from_roster( callable $roster_callback, $production_base, $version_option, $seed_version, $force = false ) {
	if ( ! $force && (int) get_option( $version_option, 0 ) >= $seed_version ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( 'group-programs' );
	if ( ! $parent_id ) {
		return;
	}

	foreach ( $roster_callback() as $item ) {
		if ( ! empty( $item['link_to'] ) ) {
			continue;
		}

		$slug  = sanitize_title( (string) ( $item['slug'] ?? '' ) );
		$title = sanitize_text_field( (string) ( $item['title'] ?? '' ) );
		if ( ! $slug || ! $title ) {
			continue;
		}

		$post_id = extrasport_find_service_child_by_slug( $parent_id, $slug );
		if ( ! $post_id ) {
			continue;
		}

		$existing = trim( (string) get_post_field( 'post_content', $post_id ) );
		if ( ! $force && $existing && ! str_contains( $existing, 'Контент услуги будет добавлен позже' ) ) {
			continue;
		}

		$html = extrasport_fetch_production_group_program_html( $slug, $production_base );
		if ( ! $html ) {
			continue;
		}

		$content = extrasport_parse_production_group_program_content( $html, $title );
		if ( ! $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $content,
			)
		);

		$intro = extrasport_parse_production_group_program_intro( $html, $title );
		if ( $intro ) {
			update_post_meta( $post_id, '_service_intro', $intro );
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_excerpt' => $intro,
				)
			);
		}
	}

	update_option( $version_option, $seed_version, false );
}

/**
 * Top-level services imported from production program pages.
 *
 * @return array<int, array{post_slug: string, production_slug: string, title: string}>
 */
function extrasport_get_extrasport_production_service_content_sources() {
	return array(
		array(
			'post_slug'       => 'detskij-klub',
			'production_slug' => 'detskij-klub',
			'title'           => 'Детский клуб',
		),
		array(
			'post_slug'       => 'bassejn',
			'production_slug' => 'bassejn',
			'title'           => 'Бассейн',
		),
	);
}

/**
 * Import production HTML into top-level service posts.
 *
 * @param array<int, array{post_slug: string, production_slug?: string, title: string}> $sources Source map.
 * @param string                                                                       $production_base Production programs base URL.
 * @param string                                                                       $version_option Option key for seed version.
 * @param int                                                                          $seed_version Current seed version.
 * @param bool                                                                         $force Overwrite existing content.
 * @return void
 */
function extrasport_seed_production_service_content_from_sources( array $sources, $production_base, $version_option, $seed_version, $force = false ) {
	if ( ! $force && (int) get_option( $version_option, 0 ) >= $seed_version ) {
		return;
	}

	foreach ( $sources as $item ) {
		$post_slug       = sanitize_title( (string) ( $item['post_slug'] ?? '' ) );
		$production_slug = sanitize_title( (string) ( $item['production_slug'] ?? $post_slug ) );
		$title           = sanitize_text_field( (string) ( $item['title'] ?? '' ) );

		if ( ! $post_slug || ! $production_slug || ! $title ) {
			continue;
		}

		$post_id = extrasport_find_top_level_service_by_slug( $post_slug );
		if ( ! $post_id && function_exists( 'extrasport_find_service_by_slug' ) ) {
			$post_id = extrasport_find_service_by_slug( $post_slug );
		}

		if ( ! $post_id ) {
			continue;
		}

		$html = extrasport_fetch_production_group_program_html( $production_slug, $production_base );
		if ( ! $html ) {
			continue;
		}

		$content = extrasport_parse_production_group_program_content( $html, $title );
		if ( ! $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $content,
			)
		);

		$intro = extrasport_parse_production_group_program_intro( $html, $title );
		if ( $intro ) {
			update_post_meta( $post_id, '_service_intro', $intro );
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_excerpt' => $intro,
				)
			);
		}
	}

	update_option( $version_option, $seed_version, false );
}
