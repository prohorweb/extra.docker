<?php
/**
 * Admin menu: promo timer popup settings.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_TIMER_SETTINGS_SLUG', 'extrasport-timer' );

/**
 * Register top-level timer admin menu item.
 *
 * @return void
 */
function extrasport_register_timer_admin_menu() {
	add_menu_page(
		__( 'Таймер-акция', 'extrasport' ),
		__( 'Таймер', 'extrasport' ),
		'manage_options',
		EXTRASPORT_TIMER_SETTINGS_SLUG,
		'extrasport_render_timer_settings_page',
		'dashicons-clock',
		59
	);
}
add_action( 'admin_menu', 'extrasport_register_timer_admin_menu' );

/**
 * Persist timer settings form submission.
 *
 * @return void
 */
function extrasport_handle_timer_settings_save() {
	if ( ! isset( $_POST['extrasport_timer_settings_nonce'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	check_admin_referer( 'extrasport_timer_settings', 'extrasport_timer_settings_nonce' );

	$timer_data = extrasport_sanitize_timer_settings_input( wp_unslash( $_POST ) );
	extrasport_update_club( $timer_data );

	extrasport_update_site_email_settings(
		array(
			'email_timer' => extrasport_sanitize_email_list( (string) ( wp_unslash( $_POST['email_timer'] ?? '' ) ) ),
		)
	);

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => EXTRASPORT_TIMER_SETTINGS_SLUG,
				'updated' => 'true',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'extrasport_handle_timer_settings_save' );

/**
 * Sanitize timer popup fields from admin form.
 *
 * @param array<string, mixed> $input Raw POST data.
 * @return array<string, mixed>
 */
function extrasport_sanitize_timer_settings_input( array $input ) {
	$current = extrasport_get_club();

	return array(
		'timer_enabled' => ! empty( $input['timer_enabled'] ),
		'timer_title'   => sanitize_text_field( (string) ( $input['timer_title'] ?? $current['timer_title'] ) ),
		'timer_intro'   => sanitize_textarea_field( (string) ( $input['timer_intro'] ?? $current['timer_intro'] ) ),
		'timer_start'   => extrasport_sanitize_timer_datetime( (string) ( $input['timer_start'] ?? $current['timer_start'] ) ),
		'timer_end'     => extrasport_sanitize_timer_datetime( (string) ( $input['timer_end'] ?? $current['timer_end'] ) ),
	);
}

/**
 * Render timer settings admin page.
 *
 * @return void
 */
function extrasport_render_timer_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$club     = extrasport_get_club();
	$emails   = extrasport_get_theme_settings();
	$brand    = extrasport_get_brand();
	$site_url = home_url( '/' );
	$active   = extrasport_is_timer_in_window();
	$end_ms   = extrasport_get_timer_end_ms();

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

		<?php if ( $active ) : ?>
			<div class="notice notice-info inline">
				<p><?php esc_html_e( 'Акция активна — popup показывается посетителям сайта.', 'extrasport' ); ?></p>
			</div>
		<?php elseif ( ! empty( $club['timer_enabled'] ) ) : ?>
			<div class="notice notice-warning inline">
				<p><?php esc_html_e( 'Акция включена, но сейчас вне периода показа (проверьте даты начала и окончания).', 'extrasport' ); ?></p>
			</div>
		<?php else : ?>
			<div class="notice notice-warning inline">
				<p><?php esc_html_e( 'Popup выключен.', 'extrasport' ); ?></p>
			</div>
		<?php endif; ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'extrasport_timer_settings', 'extrasport_timer_settings_nonce' ); ?>

			<h2 class="title"><?php esc_html_e( 'Popup с обратным отсчётом', 'extrasport' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Всплывающее окно с flip-clock и формой заявки. После закрытия скрывается на 12 часов (cookie popup-timer).', 'extrasport' ); ?>
				<br><?php esc_html_e( 'Даты и время указываются по Москве (UTC+3).', 'extrasport' ); ?>
			</p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Включить', 'extrasport' ); ?></th>
					<td>
						<label for="timer_enabled">
							<input name="timer_enabled" type="checkbox" id="timer_enabled" value="1" <?php checked( ! empty( $club['timer_enabled'] ) ); ?>>
							<?php esc_html_e( 'Показывать popup в период акции', 'extrasport' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="timer_title"><?php esc_html_e( 'Заголовок', 'extrasport' ); ?></label></th>
					<td><input name="timer_title" type="text" id="timer_title" value="<?php echo esc_attr( $club['timer_title'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="timer_intro"><?php esc_html_e( 'Описание', 'extrasport' ); ?></label></th>
					<td><textarea name="timer_intro" id="timer_intro" rows="4" class="large-text"><?php echo esc_textarea( $club['timer_intro'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="timer_start"><?php esc_html_e( 'Начало акции', 'extrasport' ); ?></label></th>
					<td>
						<input name="timer_start" type="datetime-local" id="timer_start" value="<?php echo esc_attr( extrasport_format_timer_datetime_local( $club['timer_start'] ?? '' ) ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="timer_end"><?php esc_html_e( 'Окончание акции', 'extrasport' ); ?></label></th>
					<td>
						<input name="timer_end" type="datetime-local" id="timer_end" value="<?php echo esc_attr( extrasport_format_timer_datetime_local( $club['timer_end'] ?? '' ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'После этой даты popup не показывается.', 'extrasport' ); ?></p>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Почта для заявок', 'extrasport' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="email_timer"><?php esc_html_e( 'Получатели', 'extrasport' ); ?></label></th>
					<td>
						<input name="email_timer" type="text" id="email_timer" value="<?php echo esc_attr( extrasport_format_email_list( $emails['email_timer'] ?? '' ) ); ?>" class="large-text" placeholder="sales@club.ru, manager@club.ru">
						<p class="description"><?php esc_html_e( 'Можно указать несколько адресов через запятую.', 'extrasport' ); ?></p>
					</td>
				</tr>
			</table>

			<p class="description">
				<?php
				printf(
					/* translators: 1: brand primary color, 2: club slug */
					esc_html__( 'Тема popup: %1$s (%2$s). EXTRASPORT — оранжевая, De-vision — зелёная.', 'extrasport' ),
					esc_html( $brand['primary'] ),
					esc_html( $brand['slug'] )
				);
				?>
				<?php if ( $end_ms ) : ?>
					<br><?php esc_html_e( 'Обратный отсчёт завершится (МСК):', 'extrasport' ); ?>
					<strong><?php echo esc_html( extrasport_format_timer_datetime_admin( $club['timer_end'] ?? '' ) ); ?></strong>
				<?php endif; ?>
			</p>

			<?php submit_button( __( 'Сохранить настройки', 'extrasport' ) ); ?>
		</form>
	</div>
	<?php
}
