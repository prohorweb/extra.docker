<?php
/**
 * Russian admin labels for custom post types.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Common hierarchical post labels (services).
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array<string, string>
 */
function extrasport_get_hierarchical_post_labels( $singular, $plural ) {
	return array(
		'name'                     => $plural,
		'singular_name'            => $singular,
		'menu_name'                => $plural,
		'name_admin_bar'           => $singular,
		'add_new'                  => __( 'Добавить', 'extrasport' ),
		'add_new_item'             => sprintf(
			/* translators: %s: singular post type name */
			__( 'Добавить: %s', 'extrasport' ),
			$singular
		),
		'new_item'                 => sprintf(
			/* translators: %s: singular post type name */
			__( 'Новая запись: %s', 'extrasport' ),
			$singular
		),
		'edit_item'                => sprintf(
			/* translators: %s: singular post type name */
			__( 'Редактировать: %s', 'extrasport' ),
			$singular
		),
		'view_item'                => sprintf(
			/* translators: %s: singular post type name */
			__( 'Просмотр: %s', 'extrasport' ),
			$singular
		),
		'all_items'                => sprintf(
			/* translators: %s: plural post type name */
			__( 'Все: %s', 'extrasport' ),
			$plural
		),
		'search_items'             => sprintf(
			/* translators: %s: plural post type name */
			__( 'Искать: %s', 'extrasport' ),
			$plural
		),
		'parent_item_colon'        => sprintf(
			/* translators: %s: singular post type name */
			__( 'Родительская запись: %s', 'extrasport' ),
			$singular
		),
		'not_found'                => __( 'Записи не найдены.', 'extrasport' ),
		'not_found_in_trash'       => __( 'В корзине записей нет.', 'extrasport' ),
		'item_published'           => __( 'Запись опубликована.', 'extrasport' ),
		'item_updated'             => __( 'Запись обновлена.', 'extrasport' ),
		'item_scheduled'           => __( 'Публикация запланирована.', 'extrasport' ),
		'item_reverted_to_draft'   => __( 'Запись переведена в черновик.', 'extrasport' ),
		'featured_image'           => __( 'Миниатюра', 'extrasport' ),
		'set_featured_image'       => __( 'Задать миниатюру', 'extrasport' ),
		'remove_featured_image'    => __( 'Удалить миниатюру', 'extrasport' ),
		'use_featured_image'       => __( 'Использовать как миниатюру', 'extrasport' ),
	);
}

/**
 * Flat post type labels.
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array<string, string>
 */
function extrasport_get_flat_post_labels( $singular, $plural ) {
	$labels = extrasport_get_hierarchical_post_labels( $singular, $plural );
	unset( $labels['parent_item_colon'] );

	return $labels;
}
