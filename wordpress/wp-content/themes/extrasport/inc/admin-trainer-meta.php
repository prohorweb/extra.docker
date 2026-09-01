<?php
/**
 * Trainer CPT admin meta boxes.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove any default taxonomy UI for trainer directions.
 *
 * @return void
 */
function extrasport_hide_trainer_direction_default_metabox() {
	remove_meta_box( 'tagsdiv-trainer_direction', 'trainer', 'side' );
	remove_meta_box( 'trainer_directiondiv', 'trainer', 'side' );
}
add_action( 'add_meta_boxes', 'extrasport_hide_trainer_direction_default_metabox', 99 );
add_action( 'add_meta_boxes_trainer', 'extrasport_hide_trainer_direction_default_metabox', 99 );

/**
 * Register trainer meta boxes.
 *
 * @return void
 */
function extrasport_register_trainer_meta_box() {
	add_meta_box(
		'extrasport_trainer_directions',
		__( 'Направления', 'extrasport' ),
		'extrasport_render_trainer_directions_meta_box',
		'trainer',
		'side',
		'high'
	);

	add_meta_box(
		'extrasport_trainer_details',
		__( 'Параметры тренера', 'extrasport' ),
		'extrasport_render_trainer_meta_box',
		'trainer',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'extrasport_register_trainer_meta_box' );

/**
 * Render trainer direction checkboxes.
 *
 * @param WP_Post $post Trainer post.
 * @return void
 */
function extrasport_render_trainer_directions_meta_box( WP_Post $post ) {
	$directions         = extrasport_get_trainer_directions();
	$selected_direction = extrasport_get_trainer_direction_term_ids( $post->ID );

	if ( ! $directions ) {
		echo '<p>' . esc_html__( 'Направления ещё не импортированы.', 'extrasport' ) . '</p>';
		return;
	}
	?>
	<fieldset class="extrasport-trainer-directions">
		<legend class="screen-reader-text"><?php esc_html_e( 'Направления', 'extrasport' ); ?></legend>
		<?php foreach ( $directions as $term ) : ?>
			<label class="extrasport-trainer-directions__item">
				<input
					type="checkbox"
					name="trainer_direction_ids[]"
					value="<?php echo esc_attr( (string) $term->term_id ); ?>"
					<?php checked( in_array( (int) $term->term_id, $selected_direction, true ) ); ?>
				>
				<?php echo esc_html( $term->name ); ?>
			</label>
		<?php endforeach; ?>
	</fieldset>
	<p class="description">
		<?php esc_html_e( 'Можно выбрать несколько. Без отметок тренер показывается только в «Все направления».', 'extrasport' ); ?>
	</p>
	<?php
}

/**
 * Render trainer meta fields.
 *
 * @param WP_Post $post Trainer post.
 * @return void
 */
function extrasport_render_trainer_meta_box( WP_Post $post ) {
	wp_nonce_field( 'extrasport_trainer_meta', 'extrasport_trainer_meta_nonce' );

	$position   = (string) get_post_meta( $post->ID, EXTRASPORT_TRAINER_POST_META, true );
	$meta_title = (string) get_post_meta( $post->ID, EXTRASPORT_TRAINER_META_TITLE, true );
	$meta_keys  = (string) get_post_meta( $post->ID, EXTRASPORT_TRAINER_META_KEYWORDS, true );
	$meta_desc  = (string) get_post_meta( $post->ID, EXTRASPORT_TRAINER_META_DESCRIPTION, true );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="trainer_post"><?php esc_html_e( 'Должность', 'extrasport' ); ?></label></th>
			<td><input type="text" class="regular-text" id="trainer_post" name="trainer_post" value="<?php echo esc_attr( $position ); ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="trainer_meta_title"><?php esc_html_e( 'Meta Title', 'extrasport' ); ?></label></th>
			<td><input type="text" class="large-text" id="trainer_meta_title" name="trainer_meta_title" value="<?php echo esc_attr( $meta_title ); ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="trainer_meta_keywords"><?php esc_html_e( 'Meta Keywords', 'extrasport' ); ?></label></th>
			<td><input type="text" class="large-text" id="trainer_meta_keywords" name="trainer_meta_keywords" value="<?php echo esc_attr( $meta_keys ); ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="trainer_meta_description"><?php esc_html_e( 'Meta Description', 'extrasport' ); ?></label></th>
			<td><textarea class="large-text" rows="3" id="trainer_meta_description" name="trainer_meta_description"><?php echo esc_textarea( $meta_desc ); ?></textarea></td>
		</tr>
	</table>
	<?php
}

/**
 * Save trainer meta fields.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function extrasport_save_trainer_meta( $post_id ) {
	if ( ! isset( $_POST['extrasport_trainer_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['extrasport_trainer_meta_nonce'] ) ), 'extrasport_trainer_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || 'trainer' !== get_post_type( $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, EXTRASPORT_TRAINER_POST_META, sanitize_text_field( wp_unslash( $_POST['trainer_post'] ?? '' ) ) );
	update_post_meta( $post_id, EXTRASPORT_TRAINER_META_TITLE, sanitize_text_field( wp_unslash( $_POST['trainer_meta_title'] ?? '' ) ) );
	update_post_meta( $post_id, EXTRASPORT_TRAINER_META_KEYWORDS, sanitize_text_field( wp_unslash( $_POST['trainer_meta_keywords'] ?? '' ) ) );
	update_post_meta( $post_id, EXTRASPORT_TRAINER_META_DESCRIPTION, sanitize_textarea_field( wp_unslash( $_POST['trainer_meta_description'] ?? '' ) ) );

	$direction_ids = array();
	if ( isset( $_POST['trainer_direction_ids'] ) && is_array( $_POST['trainer_direction_ids'] ) ) {
		$allowed_ids   = wp_list_pluck( extrasport_get_trainer_directions(), 'term_id' );
		$direction_ids = array_map( 'intval', wp_unslash( $_POST['trainer_direction_ids'] ) );
		$direction_ids = array_values( array_intersect( $direction_ids, array_map( 'intval', $allowed_ids ) ) );
	}

	extrasport_set_trainer_directions( $post_id, $direction_ids );
}
add_action( 'save_post_trainer', 'extrasport_save_trainer_meta' );

/**
 * Admin styles for trainer direction checkboxes.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function extrasport_trainer_admin_styles( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'trainer' !== $screen->post_type ) {
		return;
	}

	wp_add_inline_style(
		'wp-admin',
		'.extrasport-trainer-directions__item{display:flex;align-items:flex-start;gap:8px;margin:0 0 10px;}.extrasport-trainer-directions__item input{margin-top:2px;}'
	);
}
add_action( 'admin_enqueue_scripts', 'extrasport_trainer_admin_styles' );
