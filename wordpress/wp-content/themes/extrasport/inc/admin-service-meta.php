<?php
/**
 * Service CPT admin meta boxes.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register service meta box.
 *
 * @return void
 */
function extrasport_register_service_meta_box() {
	add_meta_box(
		'extrasport_service_details',
		__( 'Параметры услуги', 'extrasport' ),
		'extrasport_render_service_meta_box',
		'service',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'extrasport_register_service_meta_box' );

/**
 * Render service meta fields.
 *
 * @param WP_Post $post Service post.
 * @return void
 */
function extrasport_render_service_meta_box( WP_Post $post ) {
	wp_nonce_field( 'extrasport_service_meta', 'extrasport_service_meta_nonce' );

	$card_mode    = extrasport_get_service_card_mode( $post->ID );
	$intro        = (string) get_post_meta( $post->ID, '_service_intro', true );
	$is_top_level = 0 === (int) $post->post_parent;
	$is_child     = ! $is_top_level;
	?>
	<table class="form-table" role="presentation">
		<?php if ( $is_top_level ) : ?>
			<tr>
				<th scope="row"><label for="service_card_mode"><?php esc_html_e( 'Тип услуги', 'extrasport' ); ?></label></th>
				<td>
					<select name="service_card_mode" id="service_card_mode">
						<option value="page" <?php selected( $card_mode, 'page' ); ?>><?php esc_html_e( 'Одиночная — страница услуги', 'extrasport' ); ?></option>
						<option value="group" <?php selected( $card_mode, 'group' ); ?>><?php esc_html_e( 'Групповая — список дочерних услуг', 'extrasport' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Групповая услуга открывает список дочерних записей с тем же родителем в атрибутах страницы.', 'extrasport' ); ?></p>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( $is_child ) : ?>
			<tr>
				<th scope="row"><?php esc_html_e( 'Родитель', 'extrasport' ); ?></th>
				<td>
					<?php
					$parent = get_post( (int) $post->post_parent );
					if ( $parent instanceof WP_Post ) {
						echo esc_html( $parent->post_title );
					}
					?>
					<p class="description"><?php esc_html_e( 'Измените родителя в блоке «Атрибуты страницы» справа.', 'extrasport' ); ?></p>
				</td>
			</tr>
		<?php endif; ?>

		<tr>
			<th scope="row"><label for="service_intro"><?php esc_html_e( 'Вступительный текст', 'extrasport' ); ?></label></th>
			<td>
				<textarea class="large-text" rows="3" id="service_intro" name="service_intro"><?php echo esc_textarea( $intro ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Подзаголовок на странице услуги и текст на карточке.', 'extrasport' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save service meta fields.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function extrasport_save_service_meta_box( $post_id ) {
	if ( ! isset( $_POST['extrasport_service_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['extrasport_service_meta_nonce'] ) ), 'extrasport_service_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'service' !== get_post_type( $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );

	if ( $post instanceof WP_Post && 0 === (int) $post->post_parent ) {
		$mode = isset( $_POST['service_card_mode'] ) ? sanitize_key( wp_unslash( $_POST['service_card_mode'] ) ) : 'page';
		update_post_meta( $post_id, '_service_card_mode', in_array( $mode, array( 'page', 'group' ), true ) ? $mode : 'page' );
	}

	if ( isset( $_POST['service_intro'] ) ) {
		update_post_meta( $post_id, '_service_intro', sanitize_textarea_field( wp_unslash( $_POST['service_intro'] ) ) );
	}
}
add_action( 'save_post_service', 'extrasport_save_service_meta_box' );

/**
 * Admin notice when a service has no featured image.
 *
 * @return void
 */
function extrasport_service_thumbnail_admin_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'service' !== $screen->post_type || 'post' !== $screen->base ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $post_id || has_post_thumbnail( $post_id ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p>' . esc_html__( 'Добавьте миниатюру — она используется на карточке услуги и в списках.', 'extrasport' ) . '</p></div>';
}
add_action( 'admin_notices', 'extrasport_service_thumbnail_admin_notice' );
