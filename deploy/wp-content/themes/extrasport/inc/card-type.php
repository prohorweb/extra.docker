<?php
/**
 * /cards/ — membership plans page (Yii2 TypeController port).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CARD_TYPE_QUERY_VAR', 'extrasport_card_type' );

/**
 * Register /cards/ rewrite rule.
 *
 * @return void
 */
function extrasport_register_card_type_rewrite() {
	add_rewrite_tag( '%' . EXTRASPORT_CARD_TYPE_QUERY_VAR . '%', '1' );
	add_rewrite_rule( '^cards/?$', 'index.php?' . EXTRASPORT_CARD_TYPE_QUERY_VAR . '=1', 'top' );
}
add_action( 'init', 'extrasport_register_card_type_rewrite' );

/**
 * Flush rewrite rules after cards route changes.
 *
 * @return void
 */
function extrasport_maybe_flush_card_type_rewrite() {
	if ( get_option( 'extrasport_card_type_rewrite_version' ) === '2' ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_card_type_rewrite_version', '2', false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_card_type_rewrite', 99 );

/**
 * Whether the current request is the membership plans page.
 *
 * @return bool
 */
function extrasport_is_card_type_page() {
	return (bool) get_query_var( EXTRASPORT_CARD_TYPE_QUERY_VAR );
}

/**
 * Membership plans page URL.
 *
 * @return string
 */
function extrasport_get_card_type_url() {
	return home_url( '/cards/' );
}

/**
 * Icon directory for membership amenity SVGs (relative to assets/images/).
 *
 * @return string
 */
function extrasport_get_membership_amenity_icon_dir() {
	return extrasport_is_devision_site() ? 'devision/' : '';
}

/**
 * Included amenities for the card type page.
 *
 * @return array<int, array{icon: string, label: string}>
 */
function extrasport_get_membership_amenities() {
	$icon_dir = extrasport_get_membership_amenity_icon_dir();

	if ( extrasport_is_devision_site() ) {
		return array(
			array(
				'icon'  => $icon_dir . 'card-choice-services-1.svg',
				'label' => "Безлимитный\nфитнес *",
			),
			array(
				'icon'  => $icon_dir . 'card-choice-services-2.svg',
				'label' => "Неограниченное\nколичество визитов *",
			),
			array(
				'icon'  => $icon_dir . 'card-choice-services-3.svg',
				'label' => "Полный доступ\nво все зоны клуба *",
			),
			array(
				'icon'  => $icon_dir . 'card-choice-services-4.svg',
				'label' => "Ринг. Тренировки\nпо расписанию *",
			),
			array(
				'icon'  => $icon_dir . 'card-choice-services-5.svg',
				'label' => "Турецкая парна,\nнеограничено",
			),
			array(
				'icon'  => $icon_dir . 'card-choice-services-6.svg',
				'label' => "Плавательный\nбассейн *",
			),
		);
	}

	return array(
		array(
			'icon'  => $icon_dir . 'card-choice-services-1.svg',
			'label' => "Безлимитный\nфитнес *",
		),
		array(
			'icon'  => $icon_dir . 'card-choice-services-3.svg',
			'label' => "Полный доступ\nво все зоны клуба *",
		),
		array(
			'icon'  => $icon_dir . 'card-choice-services-5.svg',
			'label' => "Финская сухая сауна,\nнеограничено",
		),
		array(
			'icon'  => $icon_dir . 'card-choice-services-6.svg',
			'label' => "Плавательный\nбассейн *",
		),
	);
}

/**
 * Filter amenities by current club.
 *
 * @param array<int, array{icon: string, label: string, clubs?: array<string>}> $amenities Amenities list.
 * @return array<int, array{icon: string, label: string, clubs?: array<string>}>
 */
function extrasport_filter_membership_amenities( array $amenities ) {
	$slug = extrasport_get_current_club_slug();

	return array_values(
		array_filter(
			$amenities,
			static function ( $item ) use ( $slug ) {
				if ( empty( $item['clubs'] ) ) {
					return true;
				}

				return in_array( $slug, $item['clubs'], true );
			}
		)
	);
}

/**
 * Footer note under membership plan cards.
 *
 * @return array{lead: string, highlight: string, promo: string, phone: string, phone_href: string}|null
 */
function extrasport_get_membership_plans_footer_note() {
	if ( extrasport_is_devision_site() ) {
		$phone = '+7 (812) 565-49-95';

		return array(
			'lead'       => __( 'Во все абонементы входит', 'extrasport' ),
			'highlight'  => __( 'БЕЗЛИМИТНЫЙ ФИТНЕС С БАССЕЙНОМ', 'extrasport' ),
			'promo'      => __( 'Уточняйте текущие акции и спецпредложения у менеджеров клуба по телефону', 'extrasport' ),
			'phone'      => $phone,
			'phone_href' => 'tel:' . preg_replace( '/[^\d+]/', '', $phone ),
		);
	}

	return null;
}
