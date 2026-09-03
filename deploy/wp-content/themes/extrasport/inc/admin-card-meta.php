<?php
/**
 * Membership card CPT admin fields.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register membership card meta box.
 *
 * @return void
 */
function extrasport_register_membership_card_meta_box() {
	add_meta_box(
		'extrasport_membership_card_details',
		__( 'Параметры карты', 'extrasport' ),
		'extrasport_render_membership_card_meta_box',
		'membership_card',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'extrasport_register_membership_card_meta_box' );

/**
 * Render membership card fields.
 *
 * @param WP_Post $post Card post.
 * @return void
 */
function extrasport_render_membership_card_meta_box( WP_Post $post ) {
	wp_nonce_field( 'extrasport_membership_card_meta', 'extrasport_membership_card_meta_nonce' );

	$price = (string) get_post_meta( $post->ID, EXTRASPORT_CARD_PRICE_META, true );
	$video = (int) get_post_meta( $post->ID, EXTRASPORT_CARD_VIDEO_META, true );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="card_term"><?php esc_html_e( 'Срок', 'extrasport' ); ?></label></th>
			<td>
				<p class="description"><?php esc_html_e( 'Указывается в заголовке записи, например «1 месяц» или «12 месяцев».', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="card_price"><?php esc_html_e( 'Цена', 'extrasport' ); ?></label></th>
			<td>
				<input type="number" class="regular-text" id="card_price" name="card_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="1">
				<p class="description"><?php esc_html_e( 'Отображается на карточке и в форме заказа звонка.', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="card_video"><?php esc_html_e( 'Видео фона', 'extrasport' ); ?></label></th>
			<td>
				<select id="card_video" name="card_video">
					<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
						<option value="<?php echo esc_attr( (string) $i ); ?>" <?php selected( $video, $i ); ?>>
							<?php echo esc_html( sprintf( __( 'Вариант %d', 'extrasport' ), $i ) ); ?>
						</option>
					<?php endfor; ?>
				</select>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save membership card meta.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function extrasport_save_membership_card_meta_box( $post_id ) {
	if ( ! isset( $_POST['extrasport_membership_card_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['extrasport_membership_card_meta_nonce'] ) ), 'extrasport_membership_card_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, EXTRASPORT_CARD_PRICE_META, sanitize_text_field( wp_unslash( $_POST['card_price'] ?? '' ) ) );
	update_post_meta( $post_id, EXTRASPORT_CARD_VIDEO_META, max( 1, min( 4, (int) ( $_POST['card_video'] ?? 1 ) ) ) );
}
add_action( 'save_post_membership_card', 'extrasport_save_membership_card_meta_box' );

/**
 * Admin list columns for membership cards.
 *
 * @param array<string, string> $columns Default columns.
 * @return array<string, string>
 */
function extrasport_membership_card_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['card_price'] = __( 'Цена', 'extrasport' );
			$new['card_video'] = __( 'Видео', 'extrasport' );
		}
	}

	return $new;
}
add_filter( 'manage_membership_card_posts_columns', 'extrasport_membership_card_columns' );

/**
 * Render custom admin list column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function extrasport_membership_card_column_content( $column, $post_id ) {
	if ( 'card_price' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, EXTRASPORT_CARD_PRICE_META, true ) );
		return;
	}

	if ( 'card_video' === $column ) {
		echo esc_html( (string) (int) get_post_meta( $post_id, EXTRASPORT_CARD_VIDEO_META, true ) );
	}
}
add_action( 'manage_membership_card_posts_custom_column', 'extrasport_membership_card_column_content', 10, 2 );
