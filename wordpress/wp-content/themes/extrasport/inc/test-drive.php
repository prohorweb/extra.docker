<?php
/**
 * Test-drive form context — page-specific API metadata.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve test-drive form context for the current request.
 *
 * @param array<string, string> $override Optional overrides (form_id, form_type, source_url).
 * @return array{form_id: string, form_type: string, source_url: string}
 */
function extrasport_get_test_drive_form_context( array $override = array() ) {
	$context = array(
		'form_id'     => 'test-drive',
		'form_type'   => 'test_drive',
		'source_url'  => '',
	);

	if ( is_front_page() ) {
		$context['form_id']    = 'test-drive-home';
		$context['form_type']    = 'test_drive';
		$context['source_url']   = home_url( '/' );
	} elseif ( extrasport_is_card_type_page() ) {
		$context['form_id']    = 'test-drive-membership';
		$context['form_type']    = 'membership_cards';
		$context['source_url']   = extrasport_get_card_type_url();
	} elseif ( is_singular( 'share' ) ) {
		$context['form_id']    = 'test-drive-share';
		$context['form_type']    = 'share';
		$context['source_url']   = get_permalink();
	} elseif ( is_post_type_archive( 'share' ) ) {
		$context['form_id']    = 'test-drive-shares';
		$context['form_type']    = 'shares';
		$context['source_url']   = get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' );
	} elseif ( is_singular( 'service' ) ) {
		$context['form_id']    = 'test-drive-service';
		$context['form_type']    = 'service';
		$context['source_url']   = get_permalink();
	} elseif ( is_post_type_archive( 'service' ) ) {
		$context['form_id']    = 'test-drive-services';
		$context['form_type']    = 'services';
		$context['source_url']   = get_post_type_archive_link( 'service' ) ?: home_url( '/services/' );
	} elseif ( is_singular( 'group_program' ) ) {
		$context['form_id']    = 'test-drive-program';
		$context['form_type']    = 'group_program';
		$context['source_url']   = get_permalink();
	} elseif ( is_post_type_archive( 'group_program' ) ) {
		$context['form_id']    = 'test-drive-programs';
		$context['form_type']    = 'group_programs';
		$context['source_url']   = get_post_type_archive_link( 'group_program' ) ?: home_url( '/services/programs/' );
	} elseif ( is_singular() ) {
		$context['form_id']    = 'test-drive-page';
		$context['form_type']    = get_post_type();
		$context['source_url']   = get_permalink();
	} elseif ( is_archive() ) {
		$context['form_id']    = 'test-drive-archive';
		$context['form_type']    = 'archive';
		$context['source_url']   = get_post_type_archive_link( get_post_type() ) ?: '';
	}

	if ( empty( $context['source_url'] ) && isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {
		$context['source_url'] = esc_url_raw(
			( is_ssl() ? 'https://' : 'http://' ) . wp_unslash( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] )
		);
	}

	return array_merge( $context, $override );
}

/**
 * Render unified test-drive section.
 *
 * @param array<string, string> $override Optional form context overrides.
 * @return void
 */
function extrasport_render_test_drive_section( array $override = array() ) {
	get_template_part(
		'sections/test-drive',
		null,
		array(
			'club'    => extrasport_get_club(),
			'uri'     => EXTRASPORT_URI,
			'context' => extrasport_get_test_drive_form_context( $override ),
		)
	);
}
