<?php
/**
 * Thumbnail column in admin post list tables.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post types that support a list thumbnail column.
 *
 * @return array<int, string>
 */
function extrasport_get_thumbnail_admin_post_types() {
	return array( 'service', 'share', 'event', 'banner', 'trainer' );
}

/**
 * Add thumbnail column after the checkbox.
 *
 * @param array<string, string> $columns List table columns.
 * @return array<string, string>
 */
function extrasport_add_thumbnail_admin_column( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'cb' === $key ) {
			$new['thumbnail'] = __( 'Миниатюра', 'extrasport' );
		}
	}

	if ( ! isset( $new['thumbnail'] ) ) {
		$new = array_merge(
			array( 'thumbnail' => __( 'Миниатюра', 'extrasport' ) ),
			$new
		);
	}

	return $new;
}

/**
 * Keep the thumbnail column visible by default.
 *
 * @param array<string> $hidden  Hidden columns.
 * @param WP_Screen     $screen  Current screen.
 * @return array<string>
 */
function extrasport_show_thumbnail_admin_column( $hidden, $screen ) {
	if ( $screen instanceof WP_Screen && in_array( $screen->post_type, extrasport_get_thumbnail_admin_post_types(), true ) ) {
		return array_values( array_diff( $hidden, array( 'thumbnail' ) ) );
	}

	return $hidden;
}

/**
 * Get the featured image attachment ID for a post.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function extrasport_get_post_thumbnail_attachment_id( $post_id ) {
	$attachment_id = get_post_thumbnail_id( (int) $post_id );

	return $attachment_id ? (int) $attachment_id : 0;
}

/**
 * Admin URL to edit a media attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function extrasport_get_attachment_edit_url( $attachment_id ) {
	$attachment_id = (int) $attachment_id;

	if ( ! $attachment_id ) {
		return '';
	}

	$edit_link = get_edit_post_link( $attachment_id, 'raw' );
	if ( is_string( $edit_link ) && $edit_link ) {
		return $edit_link;
	}

	return admin_url( 'post.php?post=' . $attachment_id . '&action=edit' );
}

/**
 * Render admin list-table thumbnail markup.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_admin_list_thumbnail_html( $post_id ) {
	$post_id       = (int) $post_id;
	$attachment_id = extrasport_get_post_thumbnail_attachment_id( $post_id );

	if ( $attachment_id ) {
		$image_url = wp_get_attachment_image_url( $attachment_id, 'extrasport-admin-square' );
		if ( ! $image_url ) {
			$image_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
		}
		$edit_url  = extrasport_get_attachment_edit_url( $attachment_id );

		if ( $image_url && $edit_url ) {
			return sprintf(
				'<a href="%1$s" class="extrasport-admin-thumb-wrap extrasport-admin-thumb-link" aria-label="%2$s"><img src="%3$s" class="extrasport-admin-thumb" width="56" height="56" alt="" loading="lazy" decoding="async" /></a>',
				esc_url( $edit_url ),
				esc_attr__( 'Открыть медиафайл', 'extrasport' ),
				esc_url( $image_url )
			);
		}
	}

	$post_edit_url = get_edit_post_link( $post_id, 'raw' );
	if ( ! is_string( $post_edit_url ) || ! $post_edit_url ) {
		$post_edit_url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
	}

	return sprintf(
		'<a href="%1$s" class="extrasport-admin-thumb-wrap extrasport-admin-thumb-link extrasport-admin-thumb-link--empty" aria-label="%2$s"><span class="extrasport-admin-thumb extrasport-admin-thumb--empty" aria-hidden="true"></span></a>',
		esc_url( $post_edit_url ),
		esc_attr__( 'Задать миниатюру', 'extrasport' )
	);
}

/**
 * Render thumbnail column cell.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function extrasport_render_thumbnail_admin_column( $column, $post_id ) {
	static $rendered = array();

	if ( 'thumbnail' !== $column ) {
		return;
	}

	$key = $column . ':' . (int) $post_id;
	if ( isset( $rendered[ $key ] ) ) {
		return;
	}

	$rendered[ $key ] = true;

	echo extrasport_get_admin_list_thumbnail_html( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Fallback renderer for hierarchical CPT list tables (service).
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function extrasport_render_hierarchical_thumbnail_admin_column( $column, $post_id ) {
	$post = get_post( (int) $post_id );
	if ( ! $post instanceof WP_Post || 'service' !== $post->post_type ) {
		return;
	}

	extrasport_render_thumbnail_admin_column( $column, $post_id );
}

foreach ( extrasport_get_thumbnail_admin_post_types() as $post_type ) {
	add_filter( "manage_{$post_type}_posts_columns", 'extrasport_add_thumbnail_admin_column' );
	add_action( "manage_{$post_type}_posts_custom_column", 'extrasport_render_thumbnail_admin_column', 10, 2 );
}

add_action( 'manage_pages_custom_column', 'extrasport_render_hierarchical_thumbnail_admin_column', 10, 2 );

add_filter( 'default_hidden_columns', 'extrasport_show_thumbnail_admin_column', 10, 2 );

/**
 * Russian labels for featured image UI in the block/classic editor.
 *
 * @param string $translated Translated text.
 * @param string $text       Original text.
 * @param string $domain     Text domain.
 * @return string
 */
function extrasport_admin_featured_image_labels( $translated, $text, $domain ) {
	if ( ! is_admin() || 'default' !== $domain ) {
		return $translated;
	}

	$labels = array(
		'Featured image'           => 'Миниатюра',
		'Set featured image'       => 'Задать миниатюру',
		'Remove featured image'    => 'Удалить миниатюру',
		'Use as featured image'    => 'Использовать как миниатюру',
		'Featured image this post' => 'Миниатюра записи',
	);

	return $labels[ $text ] ?? $translated;
}
add_filter( 'gettext', 'extrasport_admin_featured_image_labels', 10, 3 );
