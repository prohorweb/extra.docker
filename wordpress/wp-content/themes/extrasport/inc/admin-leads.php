<?php
/**
 * Lead CPT admin list and detail view.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lead list table columns.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function extrasport_lead_admin_columns( $columns ) {
	$new = array(
		'cb'          => $columns['cb'] ?? '',
		'title'       => __( 'Заявка', 'extrasport' ),
		'lead_type'   => __( 'Тип', 'extrasport' ),
		'form_type'   => __( 'Страница', 'extrasport' ),
		'lead_name'   => __( 'Имя', 'extrasport' ),
		'lead_tel'    => __( 'Телефон', 'extrasport' ),
		'source_url'  => __( 'URL', 'extrasport' ),
		'email_sent'  => __( 'Email', 'extrasport' ),
		'date'        => $columns['date'] ?? __( 'Дата', 'extrasport' ),
	);

	return $new;
}
add_filter( 'manage_lead_posts_columns', 'extrasport_lead_admin_columns' );

/**
 * Render custom lead list columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Lead post ID.
 * @return void
 */
function extrasport_lead_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'lead_type':
			echo esc_html( (string) get_post_meta( $post_id, 'lead_type', true ) );
			break;
		case 'form_type':
			echo esc_html( (string) get_post_meta( $post_id, 'form_type', true ) );
			break;
		case 'lead_name':
			echo esc_html( (string) get_post_meta( $post_id, 'name', true ) );
			break;
		case 'lead_tel':
			echo esc_html( (string) get_post_meta( $post_id, 'tel', true ) );
			break;
		case 'source_url':
			$url = (string) get_post_meta( $post_id, 'source_url', true );
			if ( $url ) {
				printf( '<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>', esc_url( $url ), esc_html( wp_parse_url( $url, PHP_URL_PATH ) ?: $url ) );
			}
			break;
		case 'email_sent':
			echo get_post_meta( $post_id, 'email_sent', true ) ? '✓' : '—';
			break;
	}
}
add_action( 'manage_lead_posts_custom_column', 'extrasport_lead_admin_column_content', 10, 2 );

/**
 * Register lead details meta box.
 *
 * @return void
 */
function extrasport_register_lead_meta_box() {
	add_meta_box(
		'extrasport_lead_details',
		__( 'Данные заявки', 'extrasport' ),
		'extrasport_render_lead_meta_box',
		'lead',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'extrasport_register_lead_meta_box' );

/**
 * Render lead meta box (read-only).
 *
 * @param WP_Post $post Lead post.
 * @return void
 */
function extrasport_render_lead_meta_box( WP_Post $post ) {
	$fields = array(
		__( 'Тип заявки', 'extrasport' ) => get_post_meta( $post->ID, 'lead_type', true ),
		__( 'Контекст формы', 'extrasport' ) => get_post_meta( $post->ID, 'form_type', true ),
		__( 'Имя', 'extrasport' ) => get_post_meta( $post->ID, 'name', true ),
		__( 'Телефон', 'extrasport' ) => get_post_meta( $post->ID, 'tel', true ),
		__( 'Вакансия', 'extrasport' ) => get_post_meta( $post->ID, 'job_title', true ),
		__( 'Согласие', 'extrasport' ) => get_post_meta( $post->ID, 'accept', true ) ? __( 'Да', 'extrasport' ) : __( 'Нет', 'extrasport' ),
		__( 'Страница', 'extrasport' ) => get_post_meta( $post->ID, 'source_url', true ),
		__( 'Клуб', 'extrasport' ) => get_post_meta( $post->ID, 'club', true ),
		__( 'Email отправлен', 'extrasport' ) => get_post_meta( $post->ID, 'email_sent', true ) ? __( 'Да', 'extrasport' ) : __( 'Нет', 'extrasport' ),
	);
	?>
	<table class="form-table" role="presentation">
		<?php foreach ( $fields as $label => $value ) : ?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td>
					<?php if ( str_contains( (string) $value, 'http' ) ) : ?>
						<a href="<?php echo esc_url( (string) $value ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( (string) $value ); ?></a>
					<?php else : ?>
						<?php echo esc_html( (string) $value ); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Hide lead title editor — title is generated automatically.
 *
 * @return void
 */
function extrasport_lead_admin_hide_title() {
	$screen = get_current_screen();
	if ( $screen && 'lead' === $screen->post_type ) {
		echo '<style>#titlediv{display:none;}</style>';
	}
}
add_action( 'admin_head', 'extrasport_lead_admin_hide_title' );
