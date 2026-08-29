<?php
/**
 * Custom REST API routes
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme REST routes.
 *
 * @return void
 */
function extrasport_register_rest_routes() {
	register_rest_route(
		'extrasport/v1',
		'/lead',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'extrasport_rest_submit_lead',
			'permission_callback' => '__return_true',
			'args'                => array(
				'type'       => array(
					'required'          => true,
					'type'              => 'string',
					'enum'              => array( 'callback', 'subscribe', 'timer' ),
					'sanitize_callback' => 'sanitize_key',
				),
				'name'       => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'tel'        => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'accept'     => array(
					'type' => 'boolean',
				),
				'form_type'  => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_key',
				),
				'website'    => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'form_token' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);

	register_rest_route(
		'extrasport/v1',
		'/rules',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'extrasport_rest_get_rules',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'extrasport_register_rest_routes' );

/**
 * Verify REST nonce for logged-in users only (public forms skip nonce for cache compatibility).
 *
 * @param WP_REST_Request $request Request object.
 * @return true|WP_Error
 */
function extrasport_verify_rest_nonce( WP_REST_Request $request ) {
	if ( ! is_user_logged_in() ) {
		return true;
	}

	$nonce = $request->get_header( 'X-WP-Nonce' );

	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return new WP_Error(
			'extrasport_invalid_nonce',
			__( 'Недействительный запрос. Обновите страницу и попробуйте снова.', 'extrasport' ),
			array( 'status' => 403 )
		);
	}

	return true;
}

/**
 * Fake success response for bots (honeypot filled or invalid token).
 *
 * @return WP_REST_Response
 */
function extrasport_rest_fake_success() {
	return new WP_REST_Response(
		array(
			'message' => __( 'Заявка отправлена.', 'extrasport' ),
		),
		200
	);
}

/**
 * POST /extrasport/v1/lead — form submissions.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response|WP_Error
 */
function extrasport_rest_submit_lead( WP_REST_Request $request ) {
	if ( ! empty( $request['website'] ) ) {
		return extrasport_rest_fake_success();
	}

	$nonce_check = extrasport_verify_rest_nonce( $request );
	if ( is_wp_error( $nonce_check ) ) {
		return $nonce_check;
	}

	$token_check = extrasport_verify_form_token( (string) $request['form_token'] );
	if ( is_wp_error( $token_check ) ) {
		if ( 'extrasport_form_token_invalid' === $token_check->get_error_code() ) {
			return extrasport_rest_fake_success();
		}

		return new WP_Error(
			$token_check->get_error_code(),
			$token_check->get_error_message(),
			array( 'status' => 422 )
		);
	}

	$type   = $request['type'];
	$name   = trim( (string) $request['name'] );
	$tel    = trim( (string) $request['tel'] );
	$accept = (bool) $request['accept'];

	if ( ! extrasport_validate_form_name( $name ) || ! extrasport_validate_form_phone( $tel ) ) {
		return new WP_Error(
			'extrasport_invalid_fields',
			__( 'Проверьте правильность заполнения формы.', 'extrasport' ),
			array( 'status' => 422 )
		);
	}

	if ( in_array( $type, array( 'callback', 'subscribe' ), true ) && ! $accept ) {
		return new WP_Error(
			'extrasport_accept_required',
			__( 'Для продолжения установите флажок «Ознакомлен».', 'extrasport' ),
			array( 'status' => 422 )
		);
	}

	$result = extrasport_process_lead_submission( $type, $name, $tel, $accept, (string) $request['form_type'] );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return new WP_REST_Response(
		array(
			'message' => __( 'Заявка отправлена.', 'extrasport' ),
			'id'      => $result,
		),
		200
	);
}

/**
 * GET /extrasport/v1/rules — club rules HTML for modal lazy-load.
 *
 * @return WP_REST_Response
 */
function extrasport_rest_get_rules() {
	return new WP_REST_Response(
		array(
			'html' => extrasport_get_rules_html(),
		),
		200
	);
}
