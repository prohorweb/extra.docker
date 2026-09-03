<?php
/**
 * Promo timer popup helpers
 *
 * All timer datetimes are stored and interpreted in Europe/Moscow (UTC+3).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_TIMER_TIMEZONE', 'Europe/Moscow' );

/**
 * Timezone used for promo timer scheduling.
 *
 * @return DateTimeZone
 */
function extrasport_get_timer_timezone() {
	static $timezone = null;

	if ( ! $timezone instanceof DateTimeZone ) {
		$timezone = new DateTimeZone( EXTRASPORT_TIMER_TIMEZONE );
	}

	return $timezone;
}

/**
 * Build DateTimeImmutable from stored or submitted timer value.
 *
 * @param string $value Datetime string.
 * @return DateTimeImmutable|null
 */
function extrasport_create_timer_datetime( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return null;
	}

	$timezone   = extrasport_get_timer_timezone();
	$normalized = str_replace( 'T', ' ', $value );

	foreach ( array( 'Y-m-d H:i:s', 'Y-m-d H:i' ) as $format ) {
		$datetime = DateTimeImmutable::createFromFormat( $format, $normalized, $timezone );

		if ( $datetime instanceof DateTimeImmutable ) {
			return $datetime;
		}
	}

	return null;
}

/**
 * Parse stored timer datetime to Unix timestamp.
 *
 * @param string $value Datetime string.
 * @return int
 */
function extrasport_parse_timer_datetime( $value ) {
	$datetime = extrasport_create_timer_datetime( $value );

	return $datetime ? $datetime->getTimestamp() : 0;
}

/**
 * Format datetime for HTML datetime-local input.
 *
 * @param string $value Stored datetime.
 * @return string
 */
function extrasport_format_timer_datetime_local( $value ) {
	$datetime = extrasport_create_timer_datetime( $value );

	return $datetime ? $datetime->format( 'Y-m-d\TH:i' ) : '';
}

/**
 * Format datetime for admin display.
 *
 * @param string $value Stored datetime.
 * @return string
 */
function extrasport_format_timer_datetime_admin( $value ) {
	$datetime = extrasport_create_timer_datetime( $value );

	return $datetime ? $datetime->format( 'd.m.Y H:i' ) : '';
}

/**
 * Sanitize datetime-local value from admin form.
 *
 * @param string $value Raw input.
 * @return string MySQL datetime or empty string.
 */
function extrasport_sanitize_timer_datetime( $value ) {
	$datetime = extrasport_create_timer_datetime( $value );

	return $datetime ? $datetime->format( 'Y-m-d H:i:s' ) : '';
}

/**
 * Whether promo timer is within its configured date window.
 *
 * @return bool
 */
function extrasport_is_timer_in_window() {
	$club = extrasport_get_club();

	if ( empty( $club['timer_enabled'] ) ) {
		return false;
	}

	$start = extrasport_parse_timer_datetime( $club['timer_start'] ?? '' );
	$end   = extrasport_parse_timer_datetime( $club['timer_end'] ?? '' );

	if ( ! $start || ! $end || $end <= $start ) {
		return false;
	}

	$now = time();

	return $now >= $start && $now < $end;
}

/**
 * Whether promo timer popup should render on the current request.
 *
 * @return bool
 */
function extrasport_is_timer_active() {
	if ( isset( $_COOKIE['popup-timer'] ) ) {
		return false;
	}

	return extrasport_is_timer_in_window();
}

/**
 * Promo end timestamp in milliseconds for JS countdown.
 *
 * @return int
 */
function extrasport_get_timer_end_ms() {
	if ( ! extrasport_is_timer_in_window() ) {
		return 0;
	}

	$club = extrasport_get_club();
	$end  = extrasport_parse_timer_datetime( $club['timer_end'] ?? '' );

	return $end ? $end * 1000 : 0;
}
