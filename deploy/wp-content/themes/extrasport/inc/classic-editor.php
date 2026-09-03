<?php
/**
 * Force Classic Editor for all post types.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable the block editor for every post.
 *
 * @return bool
 */
function extrasport_disable_block_editor_for_post() {
	return false;
}
add_filter( 'use_block_editor_for_post', 'extrasport_disable_block_editor_for_post', 100 );

/**
 * Disable the block editor for every post type.
 *
 * @return bool
 */
function extrasport_disable_block_editor_for_post_type() {
	return false;
}
add_filter( 'use_block_editor_for_post_type', 'extrasport_disable_block_editor_for_post_type', 100 );

/**
 * Keep classic widgets screen instead of the block widgets editor.
 *
 * @return bool
 */
function extrasport_disable_widgets_block_editor() {
	return false;
}
add_filter( 'use_widgets_block_editor', 'extrasport_disable_widgets_block_editor' );
