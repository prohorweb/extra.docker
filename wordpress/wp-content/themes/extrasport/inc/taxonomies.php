<?php
/**
 * Register Custom Taxonomies
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Service Category Taxonomy (reserved, hidden from admin UI).
 */
function extrasport_register_service_category_taxonomy() {
	$args = array(
		'labels'             => array(
			'name'          => __( 'Категории услуг', 'extrasport' ),
			'singular_name' => __( 'Категория услуги', 'extrasport' ),
			'menu_name'     => __( 'Категории услуг', 'extrasport' ),
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => false,
		'show_in_menu'       => false,
		'show_in_nav_menus'  => false,
		'show_in_rest'       => false,
		'hierarchical'       => true,
	);
	register_taxonomy( 'service_category', array( 'service' ), $args );
}
add_action( 'init', 'extrasport_register_service_category_taxonomy' );
