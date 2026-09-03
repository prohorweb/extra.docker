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

/**
 * Register trainer direction taxonomy (Yii2: trainer_options_type).
 */
function extrasport_register_trainer_direction_taxonomy() {
	$args = array(
		'labels'            => array(
			'name'          => __( 'Направления тренеров', 'extrasport' ),
			'singular_name' => __( 'Направление', 'extrasport' ),
			'menu_name'     => __( 'Направления', 'extrasport' ),
		),
		'public'            => true,
		'publicly_queryable'=> true,
		'show_ui'           => false,
		'show_in_menu'      => false,
		'show_in_nav_menus' => false,
		'show_in_rest'      => false,
		'meta_box_cb'       => false,
		'hierarchical'      => false,
		'rewrite'           => array(
			'slug'         => 'trainers/direction',
			'with_front'   => false,
			'hierarchical' => false,
		),
	);
	register_taxonomy( 'trainer_direction', array( 'trainer' ), $args );
}
add_action( 'init', 'extrasport_register_trainer_direction_taxonomy' );
