<?php
/**
 * Per-site wp-admin and login branding (multisite clubs).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline CSS variables and login logo for the current club brand.
 *
 * @return string
 */
function extrasport_get_admin_brand_inline_css() {
	$brand = extrasport_get_brand();
	$club  = extrasport_get_club();

	$primary = esc_attr( $brand['primary'] );
	$accent  = esc_attr( $brand['accent'] );
	$logo    = esc_url( $brand['logo_url'] );
	$width   = max( 160, min( (int) $brand['logo_width'], 280 ) );
	$height  = max( 48, min( (int) $brand['logo_height'], 96 ) );

	$css  = ":root {\n";
	$css .= "\t--extrasport-brand-primary: {$primary};\n";
	$css .= "\t--extrasport-brand-accent: {$accent};\n";
	$css .= "}\n\n";

	$css .= ".login h1 a {\n";
	$css .= "\tbackground-image: url('{$logo}');\n";
	$css .= "\tbackground-size: contain;\n";
	$css .= "\tbackground-position: center;\n";
	$css .= "\tbackground-repeat: no-repeat;\n";
	$css .= "\twidth: {$width}px;\n";
	$css .= "\theight: {$height}px;\n";
	$css .= "\tmargin: 0 auto 1.5rem;\n";
	$css .= "}\n\n";

	$css .= ".login h1 {\n";
	$css .= "\tpadding-bottom: 0;\n";
	$css .= "}\n\n";

	$css .= "body.login::before {\n";
	$css .= "\tcontent: " . wp_json_encode( $club['title'] ) . ";\n";
	$css .= "\tdisplay: block;\n";
	$css .= "\ttext-align: center;\n";
	$css .= "\tcolor: rgb(255 255 255 / 72%);\n";
	$css .= "\tfont-size: 0.875rem;\n";
	$css .= "\tmargin-bottom: 1rem;\n";
	$css .= "}\n";

	return $css;
}

/**
 * Enqueue admin/login branding assets.
 *
 * @return void
 */
function extrasport_enqueue_admin_brand_assets() {
	$css_path = EXTRASPORT_DIR . '/assets/css/admin-brand.css';

	if ( ! file_exists( $css_path ) ) {
		return;
	}

	wp_enqueue_style(
		'extrasport-admin-brand',
		EXTRASPORT_URI . '/assets/css/admin-brand.css',
		array(),
		filemtime( $css_path )
	);

	wp_add_inline_style( 'extrasport-admin-brand', extrasport_get_admin_brand_inline_css() );
}
add_action( 'admin_enqueue_scripts', 'extrasport_enqueue_admin_brand_assets' );
add_action( 'login_enqueue_scripts', 'extrasport_enqueue_admin_brand_assets' );

/**
 * Login logo link target.
 *
 * @return string
 */
function extrasport_login_header_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'extrasport_login_header_url' );

/**
 * Login logo link title.
 *
 * @return string
 */
function extrasport_login_header_text() {
	return extrasport_get_club()['title'];
}
add_filter( 'login_headertext', 'extrasport_login_header_text' );

/**
 * Club slug on admin body for optional overrides.
 *
 * @param string $classes Admin body classes.
 * @return string
 */
function extrasport_admin_body_class( $classes ) {
	return $classes . ' club-' . extrasport_get_current_club_slug();
}
add_filter( 'admin_body_class', 'extrasport_admin_body_class' );

/**
 * Footer text with current club name.
 *
 * @return string
 */
function extrasport_admin_footer_text() {
	$club = extrasport_get_club();

	return sprintf(
		/* translators: %s: club title */
		esc_html__( 'Управление сайтом %s', 'extrasport' ),
		esc_html( $club['title'] )
	);
}
add_filter( 'admin_footer_text', 'extrasport_admin_footer_text' );

/**
 * Register club-specific admin color schemes (Profile → Admin Color Scheme).
 *
 * @return void
 */
function extrasport_register_admin_color_schemes() {
	$registry = extrasport_get_brand_registry();

	foreach ( $registry as $slug => $brand ) {
		$label = 'piter' === $slug ? 'ExtraSport' : 'De-vision';

		wp_admin_css_color(
			'extrasport-' . $slug,
			$label,
			EXTRASPORT_URI . '/assets/css/admin-brand.css',
			array( $brand['primary'], '#ffffff', $brand['accent'], '#141416' ),
			array(
				'base'    => $brand['accent'],
				'focus'   => '#ffffff',
				'current' => $brand['primary'],
			)
		);
	}
}
add_action( 'admin_init', 'extrasport_register_admin_color_schemes' );

/**
 * Default admin color scheme for the current site.
 *
 * @param string $color_scheme User color scheme.
 * @return string
 */
function extrasport_default_admin_color_scheme( $color_scheme ) {
	if ( '' !== $color_scheme && 'fresh' !== $color_scheme ) {
		return $color_scheme;
	}

	return 'extrasport-' . extrasport_get_current_club_slug();
}
add_filter( 'get_user_option_admin_color', 'extrasport_default_admin_color_scheme' );
