<?php
/**
 * Admin menu: per-site club settings.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CLUB_SETTINGS_SLUG', 'extrasport-club' );

/**
 * Register top-level admin menu item.
 *
 * @return void
 */
function extrasport_register_club_admin_menu() {
	add_menu_page(
		__( 'Настройки клуба', 'extrasport' ),
		__( 'Клуб', 'extrasport' ),
		'manage_options',
		EXTRASPORT_CLUB_SETTINGS_SLUG,
		'extrasport_render_club_settings_page',
		'dashicons-location-alt',
		58
	);
}
add_action( 'admin_menu', 'extrasport_register_club_admin_menu' );

/**
 * Persist club settings form submission.
 *
 * @return void
 */
function extrasport_handle_club_settings_save() {
	if ( ! isset( $_POST['extrasport_club_settings_nonce'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	check_admin_referer( 'extrasport_club_settings', 'extrasport_club_settings_nonce' );

	$club_data = extrasport_sanitize_club_settings_input( wp_unslash( $_POST ) );
	extrasport_update_club( $club_data );

	$email_data = extrasport_sanitize_site_email_settings_input( wp_unslash( $_POST ) );
	extrasport_update_site_email_settings( $email_data );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => EXTRASPORT_CLUB_SETTINGS_SLUG,
				'updated' => 'true',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'extrasport_handle_club_settings_save' );

/**
 * Sanitize club fields from admin form.
 *
 * @param array<string, mixed> $input Raw POST data.
 * @return array<string, mixed>
 */
function extrasport_sanitize_club_settings_input( array $input ) {
	$current = extrasport_get_club();

	$sanitized = array(
		'title'              => sanitize_text_field( (string) ( $input['club_title'] ?? $current['title'] ) ),
		'tel'                => sanitize_text_field( (string) ( $input['club_tel'] ?? $current['tel'] ) ),
		'email'              => sanitize_email( (string) ( $input['club_email'] ?? $current['email'] ) ),
		'address'            => sanitize_text_field( (string) ( $input['club_address'] ?? $current['address'] ) ),
		'metro'              => sanitize_text_field( (string) ( $input['club_metro'] ?? $current['metro'] ) ),
		'coordinates'        => sanitize_text_field( (string) ( $input['club_coordinates'] ?? $current['coordinates'] ) ),
		'start_work'         => sanitize_text_field( (string) ( $input['club_start_work'] ?? $current['start_work'] ) ),
		'start_work_weekend' => sanitize_text_field( (string) ( $input['club_start_work_weekend'] ?? $current['start_work_weekend'] ) ),
		'sales_work'         => sanitize_text_field( (string) ( $input['club_sales_work'] ?? $current['sales_work'] ) ),
		'url_appstore'       => esc_url_raw( (string) ( $input['club_url_appstore'] ?? $current['url_appstore'] ) ),
		'url_googleplay'     => esc_url_raw( (string) ( $input['club_url_googleplay'] ?? $current['url_googleplay'] ) ),
		'vk'                 => esc_url_raw( (string) ( $input['club_vk'] ?? $current['vk'] ) ),
		'youtube'            => esc_url_raw( (string) ( $input['club_youtube'] ?? $current['youtube'] ) ),
		'whatsapp'           => esc_url_raw( (string) ( $input['club_whatsapp'] ?? $current['whatsapp'] ) ),
		'telegram'           => esc_url_raw( (string) ( $input['club_telegram'] ?? $current['telegram'] ) ),
	);

	return $sanitized;
}

/**
 * Sanitize per-site form email routing fields.
 *
 * @param array<string, mixed> $input Raw POST data.
 * @return array<string, string>
 */
function extrasport_sanitize_site_email_settings_input( array $input ) {
	$current = extrasport_get_theme_settings();

	return array(
		'email_from'      => sanitize_email( (string) ( $input['email_from'] ?? $current['email_from'] ) ),
		'email_feedback'  => extrasport_sanitize_email_list( (string) ( $input['email_feedback'] ?? $current['email_feedback'] ) ),
		'email_subscribe' => extrasport_sanitize_email_list( (string) ( $input['email_subscribe'] ?? $current['email_subscribe'] ) ),
	);
}

/**
 * Render admin settings page.
 *
 * @return void
 */
function extrasport_render_club_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$club     = extrasport_get_club();
	$emails   = extrasport_get_theme_settings();
	$brand    = extrasport_get_brand();
	$site_url = home_url( '/' );

	if ( isset( $_GET['updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Настройки сохранены.', 'extrasport' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<p class="description">
			<?php
			printf(
				/* translators: 1: club title, 2: site URL */
				esc_html__( 'Сайт: %1$s (%2$s)', 'extrasport' ),
				esc_html( $club['title'] ),
				esc_url( $site_url )
			);
			?>
		</p>

		<form method="post" action="">
			<?php wp_nonce_field( 'extrasport_club_settings', 'extrasport_club_settings_nonce' ); ?>

			<h2 class="title"><?php esc_html_e( 'Контакты', 'extrasport' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="club_title"><?php esc_html_e( 'Название клуба', 'extrasport' ); ?></label></th>
					<td><input name="club_title" type="text" id="club_title" value="<?php echo esc_attr( $club['title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_tel"><?php esc_html_e( 'Телефон', 'extrasport' ); ?></label></th>
					<td><input name="club_tel" type="text" id="club_tel" value="<?php echo esc_attr( $club['tel'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_email"><?php esc_html_e( 'Email', 'extrasport' ); ?></label></th>
					<td><input name="club_email" type="email" id="club_email" value="<?php echo esc_attr( $club['email'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_address"><?php esc_html_e( 'Адрес', 'extrasport' ); ?></label></th>
					<td><input name="club_address" type="text" id="club_address" value="<?php echo esc_attr( $club['address'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_metro"><?php esc_html_e( 'Метро', 'extrasport' ); ?></label></th>
					<td><input name="club_metro" type="text" id="club_metro" value="<?php echo esc_attr( $club['metro'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_coordinates"><?php esc_html_e( 'Координаты карты', 'extrasport' ); ?></label></th>
					<td>
						<input name="club_coordinates" type="text" id="club_coordinates" value="<?php echo esc_attr( $club['coordinates'] ); ?>" class="regular-text" placeholder="59.8533,30.3497">
						<p class="description"><?php esc_html_e( 'Широта и долгота через запятую.', 'extrasport' ); ?></p>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Время работы', 'extrasport' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="club_start_work"><?php esc_html_e( 'пн–пт', 'extrasport' ); ?></label></th>
					<td><input name="club_start_work" type="text" id="club_start_work" value="<?php echo esc_attr( $club['start_work'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'с 8:00 до 22:00', 'extrasport' ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_start_work_weekend"><?php esc_html_e( 'сб–вс', 'extrasport' ); ?></label></th>
					<td><input name="club_start_work_weekend" type="text" id="club_start_work_weekend" value="<?php echo esc_attr( $club['start_work_weekend'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'с 9:00 до 22:00', 'extrasport' ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_sales_work"><?php esc_html_e( 'Отдел продаж (пн–вс)', 'extrasport' ); ?></label></th>
					<td><input name="club_sales_work" type="text" id="club_sales_work" value="<?php echo esc_attr( $club['sales_work'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'с 10:00 до 22:00', 'extrasport' ); ?>"></td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Почта для заявок', 'extrasport' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Можно указать несколько адресов через запятую.', 'extrasport' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="email_from"><?php esc_html_e( 'Отправитель', 'extrasport' ); ?></label></th>
					<td><input name="email_from" type="email" id="email_from" value="<?php echo esc_attr( $emails['email_from'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="email_feedback"><?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?></label></th>
					<td>
						<input name="email_feedback" type="text" id="email_feedback" value="<?php echo esc_attr( extrasport_format_email_list( $emails['email_feedback'] ) ); ?>" class="large-text" placeholder="sales@club.ru, manager@club.ru">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="email_subscribe"><?php esc_html_e( 'Test-drive / подписка', 'extrasport' ); ?></label></th>
					<td>
						<input name="email_subscribe" type="text" id="email_subscribe" value="<?php echo esc_attr( extrasport_format_email_list( $emails['email_subscribe'] ) ); ?>" class="large-text" placeholder="sales@club.ru, manager@club.ru">
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Соцсети и приложения', 'extrasport' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="club_vk">VK</label></th>
					<td><input name="club_vk" type="url" id="club_vk" value="<?php echo esc_attr( $club['vk'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_youtube">YouTube</label></th>
					<td><input name="club_youtube" type="url" id="club_youtube" value="<?php echo esc_attr( $club['youtube'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_whatsapp">WhatsApp</label></th>
					<td><input name="club_whatsapp" type="url" id="club_whatsapp" value="<?php echo esc_attr( $club['whatsapp'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_telegram">Telegram</label></th>
					<td><input name="club_telegram" type="url" id="club_telegram" value="<?php echo esc_attr( $club['telegram'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_url_appstore">App Store</label></th>
					<td><input name="club_url_appstore" type="url" id="club_url_appstore" value="<?php echo esc_attr( $club['url_appstore'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="club_url_googleplay">Google Play</label></th>
					<td><input name="club_url_googleplay" type="url" id="club_url_googleplay" value="<?php echo esc_attr( $club['url_googleplay'] ); ?>" class="large-text"></td>
				</tr>
			</table>

			<p class="description">
				<?php esc_html_e( 'Настройки таймер-акции и видео-клуб popup будут добавлены в финальной фазе проекта.', 'extrasport' ); ?>
			</p>

			<p class="description">
				<?php
				printf(
					/* translators: 1: brand primary color, 2: club slug */
					esc_html__( 'Брендинг сайта: %1$s (%2$s). Логотип и цвета задаются в коде темы.', 'extrasport' ),
					esc_html( $brand['primary'] ),
					esc_html( $brand['slug'] )
				);
				?>
			</p>

			<?php submit_button( __( 'Сохранить настройки', 'extrasport' ) ); ?>
		</form>
	</div>
	<?php
}
