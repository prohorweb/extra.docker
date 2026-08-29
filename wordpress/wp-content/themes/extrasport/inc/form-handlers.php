<?php
/**
 * Form validation, lead storage, and email notifications
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Minimum seconds between form render (token issue) and submission.
 */
define( 'EXTRASPORT_FORM_MIN_AGE', 2 );

/**
 * Maximum form token age — must exceed full-page cache TTL.
 */
define( 'EXTRASPORT_FORM_TOKEN_MAX_AGE', DAY_IN_SECONDS );

/**
 * Create signed form render token (server-issued, cache-safe).
 *
 * @return string
 */
function extrasport_create_form_token() {
	$issued_at = time();
	$signature = hash_hmac( 'sha256', (string) $issued_at, wp_salt( 'extrasport_form' ) );

	return $issued_at . '.' . $signature;
}

/**
 * Verify signed form token and submission timing.
 *
 * @param string $token Token from hidden form field.
 * @return true|WP_Error
 */
function extrasport_verify_form_token( $token ) {
	$token = trim( (string) $token );

	if ( '' === $token || ! str_contains( $token, '.' ) ) {
		return new WP_Error( 'extrasport_form_token_invalid', 'invalid' );
	}

	list( $issued_at, $signature ) = array_pad( explode( '.', $token, 2 ), 2, '' );
	$issued_at = (int) $issued_at;

	if ( $issued_at <= 0 || '' === $signature ) {
		return new WP_Error( 'extrasport_form_token_invalid', 'invalid' );
	}

	$expected = hash_hmac( 'sha256', (string) $issued_at, wp_salt( 'extrasport_form' ) );
	if ( ! hash_equals( $expected, $signature ) ) {
		return new WP_Error( 'extrasport_form_token_invalid', 'invalid' );
	}

	$age = time() - $issued_at;

	if ( $age < EXTRASPORT_FORM_MIN_AGE ) {
		return new WP_Error(
			'extrasport_form_too_fast',
			__( 'Проверьте правильность заполнения формы.', 'extrasport' )
		);
	}

	if ( $age > EXTRASPORT_FORM_TOKEN_MAX_AGE ) {
		return new WP_Error(
			'extrasport_form_token_expired',
			__( 'Страница устарела. Обновите её и попробуйте снова.', 'extrasport' )
		);
	}

	return true;
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
 * Persist lead before email/CRM dispatch.
 *
 * @param string $type   Lead type slug.
 * @param string $name   Submitter name.
 * @param string $tel    Submitter phone.
 * @param array  $extra  Additional meta fields.
 * @return int|false Post ID on success.
 */
function extrasport_store_lead( $type, $name, $tel, array $extra = array() ) {
	$club = extrasport_get_club();

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'lead',
			'post_status' => 'private',
			'post_title'  => sprintf( '%s — %s', sanitize_text_field( $type ), sanitize_text_field( $name ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, 'lead_type', sanitize_key( $type ) );
	update_post_meta( $post_id, 'name', sanitize_text_field( $name ) );
	update_post_meta( $post_id, 'tel', sanitize_text_field( $tel ) );
	update_post_meta( $post_id, 'club', sanitize_text_field( $club['title'] ?? '' ) );
	update_post_meta( $post_id, 'blog_id', get_current_blog_id() );

	foreach ( $extra as $key => $value ) {
		update_post_meta( $post_id, sanitize_key( $key ), sanitize_text_field( (string) $value ) );
	}

	return (int) $post_id;
}

/**
 * Process validated lead: store first, then notify by email.
 *
 * @param string $type      callback|subscribe|timer.
 * @param string $name      Submitter name.
 * @param string $tel       Submitter phone.
 * @param bool   $accept    Privacy checkbox state.
 * @param string $form_type Subscribe form variant.
 * @return int|WP_Error Lead post ID.
 */
function extrasport_process_lead_submission( $type, $name, $tel, $accept, $form_type = 'subscribe' ) {
	$club  = extrasport_get_club();
	$extra = array(
		'accept'    => $accept ? '1' : '0',
		'form_type' => $form_type,
	);

	$lead_id = extrasport_store_lead( $type, $name, $tel, $extra );
	if ( ! $lead_id ) {
		return new WP_Error(
			'extrasport_lead_store_failed',
			__( 'Не удалось сохранить заявку. Попробуйте позже.', 'extrasport' ),
			array( 'status' => 500 )
		);
	}

	switch ( $type ) {
		case 'callback':
			$subject = sprintf( 'Заказ на обратный звонок %s', $club['title'] );
			$to_key  = 'email_feedback';
			$fields  = array(
				'Имя'     => $name,
				'Телефон' => $tel,
				'Клуб'    => $club['title'],
			);
			break;

		case 'subscribe':
			$subject = 'gift' === $form_type
				? 'Заявка на подарочный сертификат Extra Sport'
				: 'Запись на пробную тренировку Extra Sport';
			$to_key  = 'email_subscribe';
			$fields  = array(
				'Имя'     => $name,
				'Телефон' => $tel,
				'Форма'   => $form_type,
			);
			break;

		case 'timer':
		default:
			$subject = sprintf( 'Заявка с виджета таймер %s', $club['title'] );
			$to_key  = 'email_timer';
			$fields  = array(
				'Имя'     => $name,
				'Телефон' => $tel,
				'Клуб'    => $club['title'],
			);
			break;
	}

	$sent = extrasport_send_form_email( $subject, $to_key, $fields );
	update_post_meta( $lead_id, 'email_sent', $sent ? '1' : '0' );

	return $lead_id;
}
