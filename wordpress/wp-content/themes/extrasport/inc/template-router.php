<?php
/**
 * Template routing — single entry point for all front-end views.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map CPT slug to view folder / card component name.
 *
 * @return array<string, string>
 */
function extrasport_get_view_type_map() {
	return array(
		'post'    => 'post',
		'service' => 'service',
		'share'   => 'share',
	);
}

/**
 * Map CPT slug to card component filename.
 *
 * @param string $post_type Post type slug.
 * @return string
 */
function extrasport_get_card_component( $post_type ) {
	$map = extrasport_get_view_type_map();
	return $map[ $post_type ] ?? $post_type;
}

/**
 * Resolve view template slug for the current query.
 *
 * @return string Path slug for get_template_part(), e.g. views/service/show.
 */
function extrasport_resolve_view() {
	if ( is_404() ) {
		return 'views/errors/404';
	}

	if ( extrasport_is_card_type_page() ) {
		return 'views/card-type/index';
	}

	if ( is_front_page() ) {
		return 'views/home/index';
	}

	if ( is_singular() ) {
		$post_type = get_post_type();
		$folder    = extrasport_get_view_type_map()[ $post_type ] ?? null;
		if ( 'service' === $post_type && extrasport_is_service_group( get_queried_object_id() ) ) {
			return 'views/service/group';
		}
		if ( $folder && 'post' !== $folder ) {
			return 'views/' . $folder . '/show';
		}
		return 'views/page/show';
	}

	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		$folder = extrasport_get_view_type_map()[ $post_type ] ?? null;
		if ( $folder && 'post' !== $folder ) {
			return 'views/' . $folder . '/index';
		}
	}

	if ( is_archive() || is_home() || is_search() ) {
		return 'views/post/index';
	}

	return 'views/post/index';
}

/**
 * Route all front-end templates through index.php.
 *
 * @param string $template Path to the template file.
 * @return string
 */
function extrasport_template_router( $template ) {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return $template;
	}

	return EXTRASPORT_DIR . '/index.php';
}

add_filter( 'template_include', 'extrasport_template_router', 99 );

/**
 * Render the resolved view inside the theme shell.
 *
 * @return void
 */
function extrasport_render_view() {
	get_template_part( extrasport_resolve_view() );
}
