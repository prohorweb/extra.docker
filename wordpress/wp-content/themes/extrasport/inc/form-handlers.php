<?php
/**
 * AJAX form handlers
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate name field (Cyrillic letters and spaces).
 *
 * @param string $name Name value.
 * @return bool
 */
function extrasport_validate_form_name( $name ) {
	$name = trim( (string) $name );
	return $name !== '' && ! preg_match( '/[^а-яё ]+/iu', $name );
}

/**
 * Validate phone field.
 *
 * @param string $tel Phone value.
 * @return bool
 */
function extrasport_validate_form_phone( $tel ) {
	$tel = trim( (string) $tel );
	return $tel !== '' && ! str_contains( $tel, '_' ) && ! preg_match( '/[^+\d ()-]/', $tel );
}

/**
 * Send form notification email.
 *
 * @param string $subject Email subject.
 * @param string $to_key  Settings key for recipient list.
 * @param array  $fields  Field label => value pairs.
 * @return bool
 */
function extrasport_send_form_email( $subject, $to_key, array $fields ) {
	$settings = extrasport_get_theme_settings();
	$from     = $settings['email_from'] ?: get_option( 'admin_email' );
	$to       = $settings[ $to_key ] ?? get_option( 'admin_email' );

	$lines = array();
	foreach ( $fields as $label => $value ) {
		$lines[] = $label . ': ' . $value;
	}

	$body = implode( "\n", $lines );

	return wp_mail( $to, $subject, $body, array( 'From: ' . $from ) );
}

/**
 * Verify AJAX nonce and respond on failure.
 *
 * @return void
 */
function extrasport_verify_form_nonce() {
	check_ajax_referer( 'extrasport_forms', 'nonce' );
}

/**
 * Handle callback form (subscribe3).
 *
 * @return void
 */
function extrasport_ajax_submit_callback() {
	extrasport_verify_form_nonce();

	$name   = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$tel    = sanitize_text_field( wp_unslash( $_POST['tel'] ?? '' ) );
	$accept = ! empty( $_POST['accept'] );

	if ( ! extrasport_validate_form_name( $name ) || ! extrasport_validate_form_phone( $tel ) || ! $accept ) {
		wp_send_json_error( array( 'message' => __( 'Проверьте правильность заполнения формы.', 'extrasport' ) ), 422 );
	}

	$club    = extrasport_get_club();
	$subject = sprintf( 'Заказ на обратный звонок %s', $club['title'] );
	$sent    = extrasport_send_form_email(
		$subject,
		'email_feedback',
		array(
			'Имя'    => $name,
			'Телефон'=> $tel,
			'Клуб'   => $club['title'],
		)
	);

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить заявку. Попробуйте позже.', 'extrasport' ) ), 500 );
	}

	wp_send_json_success( array( 'message' => __( 'Заявка отправлена.', 'extrasport' ) ) );
}
add_action( 'wp_ajax_extrasport_submit_callback', 'extrasport_ajax_submit_callback' );
add_action( 'wp_ajax_nopriv_extrasport_submit_callback', 'extrasport_ajax_submit_callback' );

/**
 * Handle subscribe / test-drive form (subscribe).
 *
 * @return void
 */
function extrasport_ajax_submit_subscribe() {
	extrasport_verify_form_nonce();

	$name   = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$tel    = sanitize_text_field( wp_unslash( $_POST['tel'] ?? '' ) );
	$accept = ! empty( $_POST['accept'] );
	$form   = sanitize_key( wp_unslash( $_POST['form_type'] ?? 'subscribe' ) );

	if ( ! extrasport_validate_form_name( $name ) || ! extrasport_validate_form_phone( $tel ) || ! $accept ) {
		wp_send_json_error( array( 'message' => __( 'Проверьте правильность заполнения формы.', 'extrasport' ) ), 422 );
	}

	$subject = 'gift' === $form
		? 'Заявка на подарочный сертификат Extra Sport'
		: 'Запись на пробную тренировку Extra Sport';

	$sent = extrasport_send_form_email(
		$subject,
		'email_subscribe',
		array(
			'Имя'    => $name,
			'Телефон'=> $tel,
			'Форма'  => $form,
		)
	);

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить заявку. Попробуйте позже.', 'extrasport' ) ), 500 );
	}

	wp_send_json_success( array( 'message' => __( 'Заявка отправлена.', 'extrasport' ) ) );
}
add_action( 'wp_ajax_extrasport_submit_subscribe', 'extrasport_ajax_submit_subscribe' );
add_action( 'wp_ajax_nopriv_extrasport_submit_subscribe', 'extrasport_ajax_submit_subscribe' );

/**
 * Handle timer popup form (subscribe4).
 *
 * @return void
 */
function extrasport_ajax_submit_timer() {
	extrasport_verify_form_nonce();

	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$tel  = sanitize_text_field( wp_unslash( $_POST['tel'] ?? '' ) );

	if ( ! extrasport_validate_form_name( $name ) || ! extrasport_validate_form_phone( $tel ) ) {
		wp_send_json_error( array( 'message' => __( 'Проверьте правильность заполнения формы.', 'extrasport' ) ), 422 );
	}

	$club    = extrasport_get_club();
	$subject = sprintf( 'Заявка с виджета таймер %s', $club['title'] );
	$sent    = extrasport_send_form_email(
		$subject,
		'email_timer',
		array(
			'Имя'    => $name,
			'Телефон'=> $tel,
			'Клуб'   => $club['title'],
		)
	);

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить заявку. Попробуйте позже.', 'extrasport' ) ), 500 );
	}

	wp_send_json_success( array( 'message' => __( 'Заявка отправлена.', 'extrasport' ) ) );
}
add_action( 'wp_ajax_extrasport_submit_timer', 'extrasport_ajax_submit_timer' );
add_action( 'wp_ajax_nopriv_extrasport_submit_timer', 'extrasport_ajax_submit_timer' );
