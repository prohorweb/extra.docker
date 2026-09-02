<?php
/**
 * Sync De-vision trainers with the production roster (de-vision.ru/dv/command/).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_TRAINERS_ROSTER_VERSION', 5 );

/**
 * Ensure trainer direction terms exist for the current site.
 *
 * @return array<int, int> Yii option ID => term ID.
 */
function extrasport_ensure_trainer_direction_terms() {
	$defaults = array(
		1 => __( 'Персональные тренировки', 'extrasport' ),
		2 => __( 'Групповые занятия', 'extrasport' ),
	);

	$map = array();

	foreach ( $defaults as $yii_id => $title ) {
		$slug = (string) $yii_id;
		$term = get_term_by( 'slug', $slug, 'trainer_direction' );

		if ( ! $term ) {
			$created = wp_insert_term(
				$title,
				'trainer_direction',
				array(
					'slug' => $slug,
				)
			);

			if ( is_wp_error( $created ) ) {
				continue;
			}

			$term_id = (int) $created['term_id'];
		} else {
			$term_id = (int) $term->term_id;
			if ( $term->name !== $title ) {
				wp_update_term(
					$term_id,
					'trainer_direction',
					array(
						'name' => $title,
					)
				);
			}
		}

		update_term_meta( $term_id, EXTRASPORT_TRAINER_DIRECTION_YII_META, (int) $yii_id );
		$map[ (int) $yii_id ] = $term_id;
	}

	return $map;
}

/**
 * Canonical De-vision trainers roster with production direction marks.
 *
 * directions: 1 = personal, 2 = group. Empty = only in «Все направления».
 *
 * @return array<int, array<string, mixed>>
 */
function extrasport_get_devision_trainers_roster() {
	return array(
		array(
			'slug'       => 'denis-cernoguzov',
			'title'      => 'Денис Черногузов',
			'position'   => 'Старший тренер тренажерного зала',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'aleksandra-zueva',
			'title'      => 'Александра Зуева',
			'position'   => 'Старший тренер аэробного зала',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'byrina-anna',
			'title'      => 'Бырина Анна',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'evgenia-lejn',
			'title'      => 'Евгения Лейн',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'anton-cajkovskij',
			'title'      => 'Антон Чайковский',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'tatana-mikulinskaa',
			'title'      => 'Татьяна Микулинская',
			'position'   => 'Иструктор групповых программ',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'olga-sinica',
			'title'      => 'Ольга Синица',
			'position'   => 'инструктор групповых программ',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'tatana-palaria',
			'title'      => 'Татьяна Палария',
			'position'   => 'Инструктор по йоге',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'samil',
			'title'      => 'Шамиль Мамиев',
			'position'   => 'Тренер тренажёрного зала',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'babosko',
			'title'      => 'Бабошко Александр',
			'position'   => 'Тренер тренажёрного зала',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'nadezda-pavlova',
			'title'      => 'Надежда Павлова',
			'position'   => 'тренер групповых программ',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'osenko-ekaterina',
			'title'      => 'Осенко Екатерина',
			'position'   => 'Старший тренер АЭ зала',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'ekaterina-zajceva',
			'title'      => 'Екатерина Зайцева',
			'position'   => 'тренер групповых программ',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'zamena',
			'title'      => 'Замена',
			'position'   => 'Тренер',
			'directions' => array( 1, 2 ),
			'fetch'      => false,
		),
		array(
			'slug'       => 'dina-bazenova',
			'title'      => 'Дина Баженова',
			'position'   => 'Старший тренер аэробного зала',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'abdul-magomedov',
			'title'      => 'АБДУЛ МАГОМЕДОВ',
			'position'   => 'Тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'oleg-nesterov',
			'title'      => 'Олег Нестеров',
			'position'   => 'Тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'aroslav-lusin',
			'title'      => 'Ярослав Лушин',
			'position'   => 'Тренер по карате',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'bairma-sadapova',
			'title'      => 'Баирма Шадапова',
			'position'   => 'Инструктор по йоге',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'litvinenko-ludmila',
			'title'      => 'Литвиненко Людмила',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'marina-kuznecova',
			'title'      => 'Марина Кузнецова',
			'position'   => 'Тренер ГП',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'viktoria-kuznecova',
			'title'      => 'Виктория Кузнецова',
			'position'   => 'Тренер ГП',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'tatana-antonova',
			'title'      => 'Татьяна Антонова',
			'position'   => 'Тренер ГП',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'zajceva-ulia',
			'title'      => 'Зайцева Юлия',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'oleg-romanenko',
			'title'      => 'Олег Романенко',
			'position'   => 'Персональный тренер',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'murasev-evgenij',
			'title'      => 'Мурашев Евгений',
			'position'   => 'Персональный тренер',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
	);
}

/**
 * Fetch a De-vision trainer page from production.
 *
 * @param string $slug Trainer slug.
 * @return array{content: string, image: string, banner: string, meta_title: string, meta_description: string, meta_keywords: string, position: string}
 */
function extrasport_fetch_devision_production_trainer_page( $slug ) {
	$slug  = sanitize_title( (string) $slug );
	$empty = array(
		'content'          => '',
		'image'            => '',
		'banner'           => '',
		'meta_title'       => '',
		'meta_description' => '',
		'meta_keywords'    => '',
		'position'         => '',
	);

	if ( ! $slug ) {
		return $empty;
	}

	$html = extrasport_fetch_devision_production_html( 'dv/command/' . $slug . '/' );
	if ( ! $html ) {
		return $empty;
	}

	if ( preg_match( '/<h2[^>]*class="section-heading"[^>]*>(.*?)<\/h2>/s', $html, $title_match ) ) {
		$empty['meta_title'] = wp_strip_all_tags( $title_match[1] );
	}

	if ( preg_match( '/<div class="item-page">(.*?)<\/div>\s*<\/div>\s*<div class="col-lg-6">/s', $html, $content_match ) ) {
		$empty['content'] = extrasport_normalize_yii_html( $content_match[1] );
	}

	if ( preg_match( '/uploads\/image\/trainer\/([^"\'\?]+)/', $html, $image_match ) ) {
		$empty['image'] = sanitize_file_name( $image_match[1] );
	}

	if ( preg_match( '/uploads\/image\/banners\/1440\/([^"\'\?]+)/', $html, $banner_match ) ) {
		$empty['banner'] = sanitize_file_name( $banner_match[1] );
	} elseif ( $empty['image'] ) {
		$empty['banner'] = $empty['image'];
	}

	if ( preg_match( '/<meta name="description" content="([^"]*)"/', $html, $desc_match ) ) {
		$empty['meta_description'] = sanitize_textarea_field( html_entity_decode( $desc_match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '/<meta name="keywords" content="([^"]*)"/', $html, $keywords_match ) ) {
		$empty['meta_keywords'] = sanitize_text_field( html_entity_decode( $keywords_match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	return $empty;
}

/**
 * Sync De-vision trainers with production roster and direction marks.
 *
 * @param bool $force Force re-sync.
 * @return void
 */
function extrasport_sync_devision_trainers_roster( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_trainers_roster_version', 0 ) >= EXTRASPORT_DEVISION_TRAINERS_ROSTER_VERSION ) {
		return;
	}

	$direction_map = extrasport_ensure_trainer_direction_terms();
	$roster        = extrasport_get_devision_trainers_roster();

	if ( ! $roster ) {
		return;
	}

	$list_html   = extrasport_fetch_devision_production_html( 'command/' );
	$list_images = array();

	if ( $list_html && preg_match_all( '/<a class="card" href="\/dv\/command\/([^"\/]+)\/">([\s\S]*?)<\/a>/', $list_html, $cards, PREG_SET_ORDER ) ) {
		foreach ( $cards as $card ) {
			$slug = sanitize_title( (string) $card[1] );
			$body = (string) $card[2];

			if ( preg_match( '/uploads\/image\/trainer\/([^"\'\?]+)/', $body, $image_match ) ) {
				$list_images[ $slug ] = sanitize_file_name( wp_basename( html_entity_decode( $image_match[1], ENT_QUOTES, 'UTF-8' ) ) );
			} else {
				$list_images[ $slug ] = '';
			}
		}
	}

	$published_slugs = array();

	foreach ( $roster as $index => $entry ) {
		$slug = sanitize_title( (string) ( $entry['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$published_slugs[] = $slug;
		$fetch             = ! empty( $entry['fetch'] );
		$remote            = $fetch ? extrasport_fetch_devision_production_trainer_page( $slug ) : array();
		$post_id           = extrasport_find_trainer_post_id_by_slug( $slug );

		$data = array(
			'post_type'   => 'trainer',
			'post_title'  => (string) ( $entry['title'] ?? '' ),
			'post_name'   => $slug,
			'post_status' => 'publish',
			'menu_order'  => ( $index + 1 ) * 10,
		);

		if ( $fetch && ! empty( $remote['content'] ) ) {
			$data['post_content'] = (string) $remote['content'];
		}

		if ( $post_id ) {
			$data['ID'] = $post_id;
			wp_update_post( $data );
		} else {
			$inserted = wp_insert_post( $data, true );
			if ( is_wp_error( $inserted ) ) {
				continue;
			}
			$post_id = (int) $inserted;
		}

		if ( ! $post_id ) {
			continue;
		}

		$position = (string) ( $entry['position'] ?? '' );
		if ( $position ) {
			update_post_meta( $post_id, EXTRASPORT_TRAINER_POST_META, sanitize_text_field( $position ) );
		}

		if ( $fetch && ! empty( $remote['meta_title'] ) ) {
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_TITLE, sanitize_text_field( (string) $remote['meta_title'] ) );
		}
		if ( $fetch && ! empty( $remote['meta_description'] ) ) {
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_DESCRIPTION, sanitize_textarea_field( (string) $remote['meta_description'] ) );
		}
		if ( $fetch && ! empty( $remote['meta_keywords'] ) ) {
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_KEYWORDS, sanitize_text_field( (string) $remote['meta_keywords'] ) );
		}

		$attachment_id = 0;
		if ( array_key_exists( $slug, $list_images ) ) {
			$image_filename = sanitize_file_name( (string) $list_images[ $slug ] );
		} else {
			$image_filename = sanitize_file_name( (string) ( $remote['image'] ?? '' ) );
		}

		if ( $image_filename ) {
			$attachment_id = extrasport_import_trainer_remote_photo( $image_filename );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			} else {
				delete_post_thumbnail( $post_id );
			}
		} else {
			delete_post_thumbnail( $post_id );
			delete_post_meta( $post_id, EXTRASPORT_TRAINER_BANNER_META );
		}

		$banner_filename = '';
		if ( $image_filename ) {
			$banner_filename = sanitize_file_name( (string) ( $remote['banner'] ?? $image_filename ) );
		}

		if ( $banner_filename ) {
			$banner_id = 0;
			if ( $attachment_id && $banner_filename === $image_filename ) {
				$banner_id = (int) $attachment_id;
			} else {
				$banner_id = extrasport_import_trainer_remote_banner( $banner_filename );
			}

			if ( $banner_id ) {
				update_post_meta( $post_id, EXTRASPORT_TRAINER_BANNER_META, $banner_id );
			}
		}

		$direction_ids = array();
		foreach ( (array) ( $entry['directions'] ?? array() ) as $yii_id ) {
			$yii_id = (int) $yii_id;
			if ( isset( $direction_map[ $yii_id ] ) ) {
				$direction_ids[] = (int) $direction_map[ $yii_id ];
			}
		}

		if ( ! $direction_ids ) {
			$direction_ids = array_values( $direction_map );
		}

		extrasport_set_trainer_directions( $post_id, array_values( array_unique( $direction_ids ) ) );
	}

	extrasport_delete_orphan_trainer_posts( $published_slugs );

	update_option( 'extrasport_devision_trainers_roster_version', EXTRASPORT_DEVISION_TRAINERS_ROSTER_VERSION, false );
}
