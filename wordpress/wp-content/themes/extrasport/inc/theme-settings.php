<?php
/**
 * Theme settings — head/body snippets and analytics
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme-level settings (replace with ACF/options page later).
 *
 * @return array<string, string>
 */
function extrasport_get_theme_settings() {
	return apply_filters(
		'extrasport_theme_settings',
		array(
			'code_head'         => '',
			'code_body'         => '',
			'yandex_metrica'    => '',
			'google_analytics'  => '',
			'email_from'        => get_option( 'admin_email' ),
			'email_feedback'    => get_option( 'admin_email' ),
			'email_subscribe'   => get_option( 'admin_email' ),
			'email_timer'       => get_option( 'admin_email' ),
		)
	);
}

/**
 * Output trusted head snippets from settings.
 *
 * @return void
 */
function extrasport_output_code_head() {
	$settings = extrasport_get_theme_settings();
	if ( ! empty( $settings['code_head'] ) ) {
		echo $settings['code_head']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'extrasport_output_code_head', 20 );

/**
 * Output trusted body-open snippets from settings.
 *
 * @return void
 */
function extrasport_output_code_body() {
	$settings = extrasport_get_theme_settings();
	if ( ! empty( $settings['code_body'] ) ) {
		echo $settings['code_body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_body_open', 'extrasport_output_code_body', 5 );

/**
 * Output analytics snippets before </body>.
 *
 * @return void
 */
function extrasport_output_analytics() {
	$settings = extrasport_get_theme_settings();

	if ( ! empty( $settings['yandex_metrica'] ) ) {
		echo $settings['yandex_metrica']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( ! empty( $settings['google_analytics'] ) ) {
		echo $settings['google_analytics']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_footer', 'extrasport_output_analytics', 99 );

/**
 * Enqueue smartbanner assets when app store links are configured.
 *
 * @return void
 */
function extrasport_enqueue_smartbanner() {
	$club = extrasport_get_club();

	if ( empty( $club['url_appstore'] ) && empty( $club['url_googleplay'] ) ) {
		return;
	}

	wp_enqueue_style(
		'smartbanner',
		'https://cdn.jsdelivr.net/npm/smartbanner.js@1.14.0/dist/smartbanner.min.css',
		array(),
		'1.14.0'
	);

	wp_enqueue_script(
		'smartbanner',
		'https://cdn.jsdelivr.net/npm/smartbanner.js@1.14.0/dist/smartbanner.min.js',
		array(),
		'1.14.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'extrasport_enqueue_smartbanner' );

/**
 * Output smartbanner meta tags.
 *
 * @return void
 */
function extrasport_output_smartbanner_meta() {
	$club = extrasport_get_club();

	if ( empty( $club['url_appstore'] ) && empty( $club['url_googleplay'] ) ) {
		return;
	}

	$icon = EXTRASPORT_URI . '/assets/img/app-icon.png';
	?>
	<meta name="smartbanner:title" content="Extra Sport">
	<meta name="smartbanner:author" content="ООО «Extra Sport»">
	<meta name="smartbanner:price" content="Доступно">
	<meta name="smartbanner:price-suffix-apple" content=" в App Store">
	<meta name="smartbanner:price-suffix-google" content=" в Google Play">
	<meta name="smartbanner:icon-apple" content="<?php echo esc_url( $icon ); ?>">
	<meta name="smartbanner:icon-google" content="<?php echo esc_url( $icon ); ?>">
	<meta name="smartbanner:button" content="Загрузить">
	<?php if ( ! empty( $club['url_appstore'] ) ) : ?>
	<meta name="smartbanner:button-url-apple" content="<?php echo esc_url( $club['url_appstore'] ); ?>">
	<?php endif; ?>
	<?php if ( ! empty( $club['url_googleplay'] ) ) : ?>
	<meta name="smartbanner:button-url-google" content="<?php echo esc_url( $club['url_googleplay'] ); ?>">
	<?php endif; ?>
	<meta name="smartbanner:enabled-platforms" content="android,ios">
	<?php
}
add_action( 'wp_head', 'extrasport_output_smartbanner_meta', 5 );
