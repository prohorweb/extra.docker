<?php
/**
 * Share CPT admin meta boxes.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register share meta box.
 *
 * @return void
 */
function extrasport_register_share_meta_box() {
	add_meta_box(
		'extrasport_share_details',
		__( 'Параметры акции', 'extrasport' ),
		'extrasport_render_share_meta_box',
		'share',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'extrasport_register_share_meta_box' );

/**
 * Render share meta fields.
 *
 * @param WP_Post $post Share post.
 * @return void
 */
function extrasport_render_share_meta_box( WP_Post $post ) {
	wp_nonce_field( 'extrasport_share_meta', 'extrasport_share_meta_nonce' );

	$date        = (string) get_post_meta( $post->ID, '_share_date', true );
	$excerpt     = (string) get_post_meta( $post->ID, '_share_excerpt', true );
	$price       = (string) get_post_meta( $post->ID, '_share_price', true );
	$only_url    = (bool) get_post_meta( $post->ID, '_share_only_url', true );
	$comment     = (string) get_post_meta( $post->ID, '_share_comment', true );
	$meta_title  = (string) get_post_meta( $post->ID, '_share_meta_title', true );
	$meta_keys   = (string) get_post_meta( $post->ID, '_share_meta_keywords', true );
	$meta_desc   = (string) get_post_meta( $post->ID, '_share_meta_description', true );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="share_date"><?php esc_html_e( 'Дата (бейдж на карточке)', 'extrasport' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="share_date" name="share_date" value="<?php echo esc_attr( $date ); ?>" placeholder="<?php esc_attr_e( 'До 29 июня!', 'extrasport' ); ?>">
				<p class="description"><?php esc_html_e( 'Оранжевый бейдж на карточке и на странице акции.', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="share_excerpt"><?php esc_html_e( 'Вступительный текст', 'extrasport' ); ?></label></th>
			<td>
				<textarea class="large-text" rows="3" id="share_excerpt" name="share_excerpt"><?php echo esc_textarea( $excerpt ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Подзаголовок на странице акции и текст под заголовком в карточке.', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="share_price"><?php esc_html_e( 'Цена', 'extrasport' ); ?></label></th>
			<td>
				<input type="number" class="small-text" id="share_price" name="share_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="1">
				<p class="description"><?php esc_html_e( 'Для кнопки «Купить онлайн» (если понадобится).', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Доступ', 'extrasport' ); ?></th>
			<td>
				<label>
					<input type="checkbox" name="share_only_url" value="1" <?php checked( $only_url ); ?>>
					<?php esc_html_e( 'Доступ только по ссылке (noindex)', 'extrasport' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="share_comment"><?php esc_html_e( 'Примечание', 'extrasport' ); ?></label></th>
			<td>
				<input type="text" class="large-text" id="share_comment" name="share_comment" value="<?php echo esc_attr( $comment ); ?>">
				<p class="description"><?php esc_html_e( 'Видно только в админке.', 'extrasport' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="share_meta_title"><?php esc_html_e( 'Meta Title', 'extrasport' ); ?></label></th>
			<td><input type="text" class="large-text" id="share_meta_title" name="share_meta_title" value="<?php echo esc_attr( $meta_title ); ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="share_meta_keywords"><?php esc_html_e( 'Meta Keywords', 'extrasport' ); ?></label></th>
			<td><input type="text" class="large-text" id="share_meta_keywords" name="share_meta_keywords" value="<?php echo esc_attr( $meta_keys ); ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="share_meta_description"><?php esc_html_e( 'Meta Description', 'extrasport' ); ?></label></th>
			<td><textarea class="large-text" rows="2" id="share_meta_description" name="share_meta_description"><?php echo esc_textarea( $meta_desc ); ?></textarea></td>
		</tr>
	</table>
	<?php
}

/**
 * Save share meta fields.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function extrasport_save_share_meta_box( $post_id ) {
	if ( ! isset( $_POST['extrasport_share_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['extrasport_share_meta_nonce'] ) ), 'extrasport_share_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || 'share' !== get_post_type( $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_share_date', sanitize_text_field( wp_unslash( $_POST['share_date'] ?? '' ) ) );
	update_post_meta( $post_id, '_share_excerpt', sanitize_textarea_field( wp_unslash( $_POST['share_excerpt'] ?? '' ) ) );
	update_post_meta( $post_id, '_share_price', absint( $_POST['share_price'] ?? 0 ) );
	update_post_meta( $post_id, '_share_only_url', ! empty( $_POST['share_only_url'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_share_comment', sanitize_text_field( wp_unslash( $_POST['share_comment'] ?? '' ) ) );
	update_post_meta( $post_id, '_share_meta_title', sanitize_text_field( wp_unslash( $_POST['share_meta_title'] ?? '' ) ) );
	update_post_meta( $post_id, '_share_meta_keywords', sanitize_text_field( wp_unslash( $_POST['share_meta_keywords'] ?? '' ) ) );
	update_post_meta( $post_id, '_share_meta_description', sanitize_textarea_field( wp_unslash( $_POST['share_meta_description'] ?? '' ) ) );
}
add_action( 'save_post_share', 'extrasport_save_share_meta_box' );

/**
 * Apply share SEO meta on the front end.
 *
 * @return void
 */
function extrasport_share_document_title( $title ) {
	if ( ! is_singular( 'share' ) ) {
		return $title;
	}

	$custom = (string) get_post_meta( get_queried_object_id(), '_share_meta_title', true );
	return $custom ?: $title;
}
add_filter( 'pre_get_document_title', 'extrasport_share_document_title' );

/**
 * Output share meta tags and noindex flag.
 *
 * @return void
 */
function extrasport_share_head_meta() {
	if ( ! is_singular( 'share' ) ) {
		return;
	}

	$post_id = get_queried_object_id();

	if ( get_post_meta( $post_id, '_share_only_url', true ) ) {
		echo '<meta name="robots" content="noindex, nofollow">' . "\n";
	}

	$keywords = (string) get_post_meta( $post_id, '_share_meta_keywords', true );
	$desc     = (string) get_post_meta( $post_id, '_share_meta_description', true );

	if ( $keywords ) {
		printf( '<meta name="keywords" content="%s">' . "\n", esc_attr( $keywords ) );
	}

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'extrasport_share_head_meta', 1 );
