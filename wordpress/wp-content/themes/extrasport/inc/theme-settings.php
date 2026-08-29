<?php
/**
 * Theme settings — network vs site options
 *
 * Network (get_site_option): analytics, shared mail from — Super Admin only for raw HTML.
 * Site (get_option): per-club form email routing only.
 *
 * Analytics snippets are injected client-side after cookie consent.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_NETWORK_OPTION', 'extrasport_network_settings' );
define( 'EXTRASPORT_SITE_SETTINGS_OPTION', 'extrasport_site_settings' );

/**
 * Network-wide theme settings.
 *
 * @return array<string, string>
 */
function extrasport_get_network_settings() {
	$defaults = array(
		'code_head'            => '',
		'code_body'            => '',
		'yandex_metrica'       => '',
		'google_analytics'     => '',
		'yandex_metrica_id'    => '',
		'google_analytics_id'  => '',
		'email_from'           => get_site_option( 'admin_email', get_option( 'admin_email' ) ),
	);

	$saved = get_site_option( EXTRASPORT_NETWORK_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	return apply_filters( 'extrasport_network_settings', wp_parse_args( $saved, $defaults ) );
}

/**
 * Per-site form email settings (blog option, falls back to admin_email).
 *
 * @return array<string, string>
 */
function extrasport_get_theme_settings() {
	$network = extrasport_get_network_settings();
	$club    = extrasport_get_club();
	$admin   = get_option( 'admin_email' );
	$club_email = ! empty( $club['email'] ) ? $club['email'] : $admin;

	$defaults = array(
		'email_from'      => $network['email_from'] ?: $club_email,
		'email_feedback'  => $club_email,
		'email_subscribe' => $club_email,
		'email_timer'     => $club_email,
	);

	$saved = get_option( EXTRASPORT_SITE_SETTINGS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	return apply_filters( 'extrasport_theme_settings', wp_parse_args( $saved, $defaults ) );
}

/**
 * Parse a comma/semicolon/newline-separated recipient list.
 *
 * @param string|array<int, string> $value Raw recipient field.
 * @return string[]
 */
function extrasport_parse_email_list( $value ) {
	if ( is_array( $value ) ) {
		$parts = $value;
	} else {
		$normalized = str_replace( array( ';', "\n", "\r" ), ',', (string) $value );
		$parts      = explode( ',', $normalized );
	}

	$emails = array();

	foreach ( $parts as $part ) {
		$email = sanitize_email( trim( (string) $part ) );
		if ( '' !== $email ) {
			$emails[] = $email;
		}
	}

	return array_values( array_unique( $emails ) );
}

/**
 * Normalize recipient list for storage.
 *
 * @param string|array<int, string> $value Raw recipient field.
 * @return string Comma-separated emails.
 */
function extrasport_sanitize_email_list( $value ) {
	return implode( ',', extrasport_parse_email_list( $value ) );
}

/**
 * Format recipient list for admin inputs.
 *
 * @param string|array<int, string> $value Raw recipient field.
 * @return string
 */
function extrasport_format_email_list( $value ) {
	$emails = extrasport_parse_email_list( $value );

	return implode( ', ', $emails );
}

/**
 * Persist per-site form email routing.
 *
 * @param array<string, string> $input Settings payload.
 * @return bool
 */
function extrasport_update_site_email_settings( array $input ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$current = extrasport_get_theme_settings();

	return update_option(
		EXTRASPORT_SITE_SETTINGS_OPTION,
		wp_parse_args( $input, $current ),
		false
	);
}

/**
 * Build Yandex Metrika snippet from counter ID (safe for non–Super Admin UI).
 *
 * @param string $counter_id Numeric counter ID.
 * @return string
 */
function extrasport_build_yandex_metrica_snippet( $counter_id ) {
	$counter_id = preg_replace( '/\D/', '', (string) $counter_id );
	if ( '' === $counter_id ) {
		return '';
	}

	ob_start();
	?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments);};
	m[i].l=1*new Date();
	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
	ym(<?php echo esc_js( $counter_id ); ?>, "init", {
		clickmap:true,
		trackLinks:true,
		accurateTrackBounce:true,
		webvisor:true
	});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo esc_attr( $counter_id ); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
	<?php
	return (string) ob_get_clean();
}

/**
 * Build Google Analytics 4 snippet from measurement ID.
 *
 * @param string $measurement_id GA4 ID (G-XXXXXXXX).
 * @return string
 */
function extrasport_build_google_analytics_snippet( $measurement_id ) {
	$measurement_id = preg_replace( '/[^A-Z0-9-]/i', '', (string) $measurement_id );
	if ( '' === $measurement_id ) {
		return '';
	}

	ob_start();
	?>
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $measurement_id ); ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo esc_js( $measurement_id ); ?>');
</script>
<!-- /Google Analytics -->
	<?php
	return (string) ob_get_clean();
}

/**
 * Raw analytics snippets for deferred client-side injection.
 *
 * Raw HTML fields (code_head, code_body) are network-only and require Super Admin.
 * Counter IDs are sanitized and rendered via theme templates (Multisite-safe).
 *
 * @return array<string, string>
 */
function extrasport_get_analytics_snippets() {
	$settings = extrasport_get_network_settings();

	$yandex = ! empty( $settings['yandex_metrica'] )
		? (string) $settings['yandex_metrica']
		: extrasport_build_yandex_metrica_snippet( $settings['yandex_metrica_id'] ?? '' );

	$google = ! empty( $settings['google_analytics'] )
		? (string) $settings['google_analytics']
		: extrasport_build_google_analytics_snippet( $settings['google_analytics_id'] ?? '' );

	return array(
		'head'   => (string) ( $settings['code_head'] ?? '' ),
		'body'   => (string) ( $settings['code_body'] ?? '' ),
		'yandex' => $yandex,
		'google' => $google,
	);
}

/**
 * Persist network settings (Super Admin). Raw HTML only with unfiltered_html.
 *
 * @param array<string, mixed> $input Settings payload.
 * @return bool
 */
function extrasport_update_network_settings( array $input ) {
	if ( ! current_user_can( 'manage_network' ) ) {
		return false;
	}

	$current  = extrasport_get_network_settings();
	$sanitized = array(
		'yandex_metrica_id'   => preg_replace( '/\D/', '', (string) ( $input['yandex_metrica_id'] ?? $current['yandex_metrica_id'] ) ),
		'google_analytics_id' => preg_replace( '/[^A-Z0-9-]/i', '', (string) ( $input['google_analytics_id'] ?? $current['google_analytics_id'] ) ),
		'email_from'          => sanitize_email( (string) ( $input['email_from'] ?? $current['email_from'] ) ),
	);

	if ( current_user_can( 'unfiltered_html' ) ) {
		$sanitized['code_head']        = (string) ( $input['code_head'] ?? $current['code_head'] );
		$sanitized['code_body']        = (string) ( $input['code_body'] ?? $current['code_body'] );
		$sanitized['yandex_metrica']   = (string) ( $input['yandex_metrica'] ?? $current['yandex_metrica'] );
		$sanitized['google_analytics'] = (string) ( $input['google_analytics'] ?? $current['google_analytics'] );
	} else {
		$sanitized['code_head']        = $current['code_head'];
		$sanitized['code_body']        = $current['code_body'];
		$sanitized['yandex_metrica']   = $current['yandex_metrica'];
		$sanitized['google_analytics'] = $current['google_analytics'];
	}

	return update_site_option( EXTRASPORT_NETWORK_OPTION, wp_parse_args( $sanitized, $current ) );
}

/**
 * Enqueue smartbanner assets when app store links are configured on this site.
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
 * Output smartbanner meta tags for the current site's club profile.
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
