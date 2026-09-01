<?php
/**
 * Import single service page content from the legacy Yii2 database.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_SERVICES_CONTENT_VERSION', 1 );

/**
 * WP service slug => Yii content source.
 *
 * @return array<string, array<string, mixed>>
 */
function extrasport_get_service_content_sources() {
	return array(
		'personal_training' => array(
			'table' => 'services',
			'alias' => 'personal_training',
		),
		'bassejn'           => array(
			'table' => 'services',
			'alias' => 'bassejn',
		),
		'detskij-klub'      => array(
			'table' => 'services',
			'alias' => 'detskij-klub',
		),
		'boks'              => array(
			'table' => 'program_classes',
			'id'    => 55,
		),
		'kikboksing'        => array(
			'table' => 'program_classes',
			'id'    => 56,
		),
		'muaj-taj'          => array(
			'table' => 'program_classes',
			'id'    => 56,
		),
		'grappling'         => array(
			'table' => 'services',
			'alias' => 'boevye-iskusstva',
		),
	);
}

/**
 * Find a service post by slug at any hierarchy level.
 *
 * @param string $slug Post slug.
 * @return int
 */
function extrasport_find_service_by_slug( $slug ) {
	$post = get_posts(
		array(
			'post_type'      => 'service',
			'name'           => sanitize_title( $slug ),
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	return ! empty( $post ) ? (int) $post[0] : 0;
}

/**
 * Sanitize legacy Yii HTML for WordPress post content.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function extrasport_normalize_yii_html( $html ) {
	$html = html_entity_decode( (string) $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$html = trim( $html );

	if ( ! $html ) {
		return '';
	}

	return wp_kses_post( $html );
}

/**
 * Build HTML for a Yii program class row.
 *
 * @param object $row Program class row.
 * @return string
 */
function extrasport_build_yii_program_class_content( $row ) {
	if ( ! $row ) {
		return '';
	}

	$parts = array();

	if ( ! empty( $row->intro ) ) {
		$parts[] = extrasport_normalize_yii_html( $row->intro );
	}

	if ( ! empty( $row->content ) ) {
		$parts[] = extrasport_normalize_yii_html( $row->content );
	}

	if ( ! empty( $row->duration ) ) {
		$parts[] = '<p>' . esc_html( $row->duration ) . '</p>';
	}

	return implode( "\n\n", array_filter( $parts ) );
}

/**
 * Build HTML for a Yii group program page (category + classes list).
 *
 * @param string $alias Group program alias.
 * @return string
 */
function extrasport_build_yii_group_program_content( $alias ) {
	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return '';
	}

	$row = $yii->get_row(
		$yii->prepare(
			'SELECT id, intro, content FROM group_programs WHERE alias = %s AND status = 10 LIMIT 1',
			$alias
		)
	);

	if ( ! $row ) {
		return '';
	}

	$parts = array();

	if ( ! empty( $row->content ) ) {
		$parts[] = extrasport_normalize_yii_html( $row->content );
	} elseif ( ! empty( $row->intro ) ) {
		$parts[] = extrasport_normalize_yii_html( $row->intro );
	}

	$classes = $yii->get_results(
		$yii->prepare(
			'SELECT title, intro, content, duration FROM program_classes WHERE group_programs_id = %d AND status = 10 ORDER BY position ASC',
			(int) $row->id
		)
	);

	if ( is_array( $classes ) ) {
		foreach ( $classes as $class ) {
			$class_html = extrasport_build_yii_program_class_content( $class );
			if ( ! $class_html ) {
				continue;
			}

			$parts[] = '<h3>' . esc_html( (string) $class->title ) . '</h3>' . $class_html;
		}
	}

	return implode( "\n\n", array_filter( $parts ) );
}

/**
 * Fetch Yii content for a mapped service source.
 *
 * @param array<string, mixed> $source Source config.
 * @return string
 */
function extrasport_fetch_yii_service_content( array $source ) {
	$yii = extrasport_get_yii_db();
	if ( ! $yii || empty( $source['table'] ) ) {
		return '';
	}

	switch ( $source['table'] ) {
		case 'services':
			if ( empty( $source['alias'] ) ) {
				return '';
			}

			$row = $yii->get_row(
				$yii->prepare(
					'SELECT content FROM services WHERE alias = %s AND status = 10 LIMIT 1',
					$source['alias']
				)
			);

			return $row ? extrasport_normalize_yii_html( $row->content ) : '';

		case 'group_programs':
			return ! empty( $source['alias'] ) ? extrasport_build_yii_group_program_content( (string) $source['alias'] ) : '';

		case 'program_classes':
			if ( empty( $source['id'] ) ) {
				return '';
			}

			$row = $yii->get_row(
				$yii->prepare(
					'SELECT title, intro, content, duration FROM program_classes WHERE id = %d AND status = 10 LIMIT 1',
					(int) $source['id']
				)
			);

			return extrasport_build_yii_program_class_content( $row );
	}

	return '';
}

/**
 * Plain-text intro from HTML content.
 *
 * @param string $html Post content HTML.
 * @return string
 */
function extrasport_get_intro_from_html( $html ) {
	$text = trim( wp_strip_all_tags( $html ) );

	if ( ! $text ) {
		return '';
	}

	return wp_html_excerpt( $text, 220, '&hellip;' );
}

/**
 * Import Yii HTML into single service posts.
 *
 * @param bool $force Overwrite existing content.
 * @return void
 */
function extrasport_seed_service_content( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_services_content_version', 0 ) >= EXTRASPORT_SERVICES_CONTENT_VERSION ) {
		return;
	}

	if ( ! extrasport_get_yii_db() ) {
		return;
	}

	foreach ( extrasport_get_service_content_sources() as $slug => $source ) {
		$post_id = extrasport_find_service_by_slug( $slug );
		if ( ! $post_id ) {
			continue;
		}

		$existing = trim( (string) get_post_field( 'post_content', $post_id ) );
		if ( ! $force && $existing && ! str_contains( $existing, 'Контент услуги будет добавлен позже' ) ) {
			continue;
		}

		$content = extrasport_fetch_yii_service_content( $source );
		if ( ! $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $content,
			)
		);

		$intro = extrasport_get_intro_from_html( $content );
		if ( $intro ) {
			update_post_meta( $post_id, '_service_intro', $intro );
		}
	}

	update_option( 'extrasport_services_content_version', EXTRASPORT_SERVICES_CONTENT_VERSION, false );
}

/**
 * Seed content when services admin is opened.
 *
 * @param WP_Screen|null $screen Current screen.
 * @return void
 */
function extrasport_seed_service_content_on_screen( $screen ) {
	if ( ! $screen instanceof WP_Screen || 'edit' !== $screen->base || 'service' !== $screen->post_type ) {
		return;
	}

	extrasport_seed_service_content( false );
}
add_action( 'current_screen', 'extrasport_seed_service_content_on_screen' );

/**
 * Seed content on front-end bootstrap (once per version).
 *
 * @return void
 */
function extrasport_maybe_seed_service_content() {
	if ( is_admin() && ! wp_doing_cron() ) {
		return;
	}

	extrasport_seed_service_content( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_service_content', 36 );
