<?php
/**
 * Sync published trainers with the production Piter roster.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_TRAINERS_ROSTER_VERSION', 7 );

/**
 * Canonical production roster for Piter (/es/command/).
 *
 * @return array<int, array<string, mixed>>
 */
function extrasport_get_production_trainers_roster() {
	return array(
		array(
			'slug'       => 'bedoidze-nikolaj',
			'title'      => 'Бедоидзе Николай',
			'position'   => 'Старший тренер тренажёрного зала',
			'image'      => '111-1558619997.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'pavel-urin',
			'title'      => 'Павел Юрин',
			'position'   => 'Персональный тренер',
			'image'      => 'Pavel-1558620440.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'artem-mahalov',
			'title'      => 'Артем Махалов',
			'position'   => 'Персональный тренер',
			'image'      => 'Artem-Mahalov-1-1558621177.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'lifar-denis',
			'title'      => 'Лифар Денис',
			'position'   => 'Персональный тренер',
			'image'      => 'Lifar-Denis-1-1558621707.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'pavel',
			'title'      => 'Шильцов Андрей',
			'position'   => 'Тренер тренажерного зала',
			'image'      => 'IMG_3102-1688586296.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'zalnina-ksenia',
			'title'      => 'Жалнина Ксения',
			'position'   => 'Персональный тренер',
			'image'      => 'htfdrftfytgj-1666787668.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'fominova-natalia',
			'title'      => 'Фоминова Наталия',
			'position'   => 'Тренер Групповых занятий',
			'image'      => 'Fominova-Natalia-1556294975.jpg',
			'directions' => array( 2 ),
		),
		array(
			'slug'       => 'fedotov-andrej',
			'title'      => 'Федотов Андрей',
			'position'   => 'Тренер тренажерного зала',
			'image'      => 'WhatsApp-Image-2022-08-29-at-17.16.34-1661858849.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'subin-artur',
			'title'      => 'Шубин Артур',
			'position'   => 'Тренер тренажерного зала',
			'image'      => 'WhatsApp-Image-2022-08-23-at-22.21.21-1661858686.jpg',
			'directions' => array(),
		),
		array(
			'slug'       => 'saksonova-natala',
			'title'      => 'Саксонова Надежда',
			'position'   => 'Тренер групповых программ',
			'image'      => '',
			'directions' => array(),
		),
		array(
			'slug'       => 'plotnikova-margarita',
			'title'      => 'Плотникова Маргарита',
			'position'   => 'Тренер групповых программм',
			'image'      => '',
			'directions' => array(),
		),
		array(
			'slug'       => 'petrov-denis',
			'title'      => 'Петров Денис',
			'position'   => 'Тренер по единоборствам',
			'image'      => '',
			'directions' => array(),
		),
		array(
			'slug'       => 'musaeva-ulia',
			'title'      => 'Мусаева Юлия',
			'position'   => 'Тренер групповых прграмм',
			'image'      => '',
			'directions' => array( 2 ),
		),
		array(
			'slug'       => 'strelcova-oksana',
			'title'      => 'Стрельцова Оксана',
			'position'   => 'Координатор.ТренерГрупповыхПрограмм',
			'image'      => 'f524cae3-9a91-4c16-9162-88486f20bc00-1787231350.jpg',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'timakov-evgenij',
			'title'      => 'Тимаков Евгений',
			'position'   => 'Тренер',
			'image'      => 'IMG_20250611_132733-1749637681.jpg',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'koskin-dmitrij',
			'title'      => 'Кошкин Дмитрий',
			'position'   => 'Тренер',
			'image'      => 'IMG-20251113-WA0001-1763112280.jpg',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'semeleva-veronika',
			'title'      => 'Щемелева Вероника',
			'position'   => 'Тренер',
			'image'      => 'IMG_0280-1749637324.jpg',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'holuasvili-giorgi',
			'title'      => 'Холуашвили Гиорги',
			'position'   => 'Тренер',
			'image'      => 'IMG-20251114-WA0000-1763344944.jpg',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'gvozdenko-ulia',
			'title'      => 'Гвозденко Юлия',
			'position'   => 'Тренер',
			'image'      => '',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'          => 'ludmila-cvetnikova',
			'title'         => 'Людмила Цветникова',
			'position'      => 'Тренер',
			'image'         => '',
			'directions'    => array( 1 ),
			'legacy_slugs'  => array( 'cvetkova-ludmila' ),
			'fetch'         => true,
		),
		array(
			'slug'       => 'bakreu-ulia',
			'title'      => 'Бакрэу Юлия',
			'position'   => 'Тренер',
			'image'      => '',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'encenko-irina',
			'title'      => 'Енченко Ирина',
			'position'   => 'тренер',
			'image'      => '',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'olga-degtareva',
			'title'      => 'Ольга Дегтярёва',
			'position'   => 'тренер детских направлений',
			'image'      => '',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'ulia-pavlova',
			'title'      => 'Юлия Павлова',
			'position'   => 'тренер',
			'image'      => '',
			'directions' => array( 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'redneva-anastasia',
			'title'      => 'Реднева Анастасия',
			'position'   => 'тренер',
			'image'      => '',
			'directions' => array( 1, 2 ),
			'fetch'      => true,
		),
		array(
			'slug'       => 'vasileva-aleksandra',
			'title'      => 'Васильева Александра',
			'position'   => 'Тренер',
			'image'      => 'IMG_9759-1787084645.jpg',
			'directions' => array( 1 ),
			'fetch'      => true,
		),
	);
}

/**
 * Permanently delete trainer posts outside the production roster.
 *
 * @param array<int, string> $published_slugs Canonical roster slugs.
 * @return void
 */
function extrasport_delete_orphan_trainer_posts( array $published_slugs ) {
	$published_slugs = array_values(
		array_unique(
			array_filter(
				array_map( 'sanitize_title', $published_slugs )
			)
		)
	);

	$posts = get_posts(
		array(
			'post_type'              => 'trainer',
			'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'trash', 'auto-draft' ),
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		$slug = preg_replace( '/__trashed$/', '', (string) $post->post_name );
		if ( in_array( $slug, $published_slugs, true ) ) {
			continue;
		}

		wp_delete_post( (int) $post->ID, true );
	}
}

/**
 *
 * @param string $filename Image filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_trainer_remote_photo( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( ! $filename ) {
		return 0;
	}

	$source = 'production/trainers/' . $filename;
	$remote = extrasport_get_legacy_club_public_base_url() . '/uploads/image/trainer/' . rawurlencode( $filename );

	return extrasport_import_remote_image( $remote, $source );
}

/**
 * Import trainer banner from production.
 *
 * @param string $filename Banner filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_trainer_remote_banner( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( ! $filename ) {
		return 0;
	}

	$source = 'production/trainer-banners/' . $filename;
	$remote = extrasport_get_legacy_club_public_base_url() . '/uploads/image/banners/1440/' . rawurlencode( $filename );

	return extrasport_import_remote_image( $remote, $source );
}

/**
 * Fetch trainer page data from production.
 *
 * @param string $slug Trainer slug.
 * @return array{content: string, image: string, banner: string, meta_title: string, meta_description: string, meta_keywords: string}
 */
function extrasport_fetch_production_trainer_page( $slug ) {
	$slug = sanitize_title( (string) $slug );
	$empty = array(
		'content'          => '',
		'image'            => '',
		'banner'           => '',
		'meta_title'       => '',
		'meta_description' => '',
		'meta_keywords'    => '',
	);

	if ( ! $slug ) {
		return $empty;
	}

	$url      = extrasport_get_legacy_club_public_base_url() . '/es/command/' . rawurlencode( $slug ) . '/';
	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 20,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $empty;
	}

	$html = (string) wp_remote_retrieve_body( $response );
	if ( ! $html ) {
		return $empty;
	}

	$data = $empty;

	if ( preg_match( '/<title>([^<]+)<\/title>/iu', $html, $match ) ) {
		$data['meta_title'] = trim( html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/iu', $html, $match ) ) {
		$data['meta_description'] = trim( html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '/<meta[^>]+name=["\']keywords["\'][^>]+content=["\']([^"\']+)["\']/iu', $html, $match ) ) {
		$data['meta_keywords'] = trim( html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '#<div class="item-page">(.*?)</div>\s*</div>\s*<div class="col-lg-6"#su', $html, $match ) ) {
		$data['content'] = extrasport_normalize_yii_html( $match[1] );
	}

	if ( preg_match( '#/uploads/image/trainer/([^"\']+)#i', $html, $match ) ) {
		$data['image'] = sanitize_file_name( $match[1] );
	}

	if ( preg_match( '#/uploads/image/banners/1440/([^"\']+)#i', $html, $match ) ) {
		$data['banner'] = sanitize_file_name( $match[1] );
	}

	return $data;
}

/**
 * Resolve trainer post ID by canonical or legacy slug.
 *
 * @param array<string, mixed> $entry Roster entry.
 * @return int
 */
function extrasport_resolve_roster_trainer_post_id( array $entry ) {
	$slug = sanitize_title( (string) ( $entry['slug'] ?? '' ) );
	if ( $slug ) {
		$post_id = extrasport_find_trainer_post_id_by_slug( $slug );
		if ( $post_id ) {
			return $post_id;
		}
	}

	$legacy_slugs = array_map( 'sanitize_title', (array) ( $entry['legacy_slugs'] ?? array() ) );
	foreach ( $legacy_slugs as $legacy_slug ) {
		if ( ! $legacy_slug ) {
			continue;
		}

		$post_id = extrasport_find_trainer_post_id_by_slug( $legacy_slug );
		if ( $post_id ) {
			return $post_id;
		}
	}

	return 0;
}

/**
 * Map Yii direction option IDs to WP term IDs.
 *
 * @param array<int> $yii_option_ids Yii option IDs.
 * @return array<int>
 */
function extrasport_map_trainer_direction_term_ids( array $yii_option_ids ) {
	$term_ids = array();

	foreach ( $yii_option_ids as $yii_option_id ) {
		$term = get_term_by( 'slug', (string) (int) $yii_option_id, 'trainer_direction' );
		if ( $term && ! is_wp_error( $term ) ) {
			$term_ids[] = (int) $term->term_id;
		}
	}

	return array_values( array_unique( $term_ids ) );
}

/**
 * Import roster trainer image from local Yii files or production.
 *
 * @param string $filename Image filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_roster_trainer_photo( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( ! $filename ) {
		return 0;
	}

	$attachment_id = extrasport_import_trainer_photo( $filename );
	if ( $attachment_id ) {
		return $attachment_id;
	}

	return extrasport_import_trainer_remote_photo( $filename );
}

/**
 * Sync published trainers with the production roster.
 *
 * @param bool $force Force re-sync.
 * @return void
 */
function extrasport_sync_trainers_roster( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_trainers_roster_version', 0 ) >= EXTRASPORT_TRAINERS_ROSTER_VERSION ) {
		return;
	}

	$roster          = extrasport_get_production_trainers_roster();
	$published_slugs = array();

	foreach ( $roster as $index => $entry ) {
		$slug = sanitize_title( (string) ( $entry['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$published_slugs[] = $slug;

		$post_id = extrasport_resolve_roster_trainer_post_id( $entry );
		$fetch   = ! empty( $entry['fetch'] );
		$remote  = $fetch ? extrasport_fetch_production_trainer_page( $slug ) : array();

		$data = array(
			'post_type'    => 'trainer',
			'post_title'   => (string) ( $entry['title'] ?? '' ),
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'menu_order'   => ( $index + 1 ) * 10,
			'post_content' => '',
		);

		if ( $fetch && ! empty( $remote['content'] ) ) {
			$data['post_content'] = (string) $remote['content'];
		}

		if ( $post_id ) {
			if ( ! $fetch || empty( $remote['content'] ) ) {
				unset( $data['post_content'] );
			}
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

		update_post_meta( $post_id, EXTRASPORT_TRAINER_POST_META, sanitize_text_field( (string) ( $entry['position'] ?? '' ) ) );

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
		$image_filename = sanitize_file_name( (string) ( $entry['image'] ?? '' ) );
		if ( $fetch && ! $image_filename && ! empty( $remote['image'] ) ) {
			$image_filename = sanitize_file_name( (string) $remote['image'] );
		}

		if ( $image_filename ) {
			$attachment_id = extrasport_import_roster_trainer_photo( $image_filename );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}

		$banner_filename = '';
		if ( $fetch && ! empty( $remote['banner'] ) ) {
			$banner_filename = sanitize_file_name( (string) $remote['banner'] );
		} elseif ( $image_filename ) {
			$banner_filename = $image_filename;
		}

		if ( $banner_filename ) {
			$banner_id = 0;
			if ( ! empty( $attachment_id ) && $banner_filename === $image_filename ) {
				$banner_id = (int) $attachment_id;
			} else {
				$banner_id = extrasport_import_trainer_banner_image( $banner_filename );
				if ( ! $banner_id ) {
					$banner_id = extrasport_import_trainer_remote_banner( $banner_filename );
				}
			}

			if ( $banner_id ) {
				update_post_meta( $post_id, EXTRASPORT_TRAINER_BANNER_META, $banner_id );
			}
		}

		$direction_ids = extrasport_map_trainer_direction_term_ids( (array) ( $entry['directions'] ?? array() ) );
		extrasport_set_trainer_directions( $post_id, $direction_ids );
	}

	foreach ( $roster as $entry ) {
		$canonical_slug = sanitize_title( (string) ( $entry['slug'] ?? '' ) );
		$canonical_id   = extrasport_find_trainer_post_id_by_slug( $canonical_slug );
		if ( ! $canonical_id ) {
			continue;
		}

		foreach ( (array) ( $entry['legacy_slugs'] ?? array() ) as $legacy_slug ) {
			$legacy_slug = sanitize_title( (string) $legacy_slug );
			if ( ! $legacy_slug ) {
				continue;
			}

			$legacy_id = extrasport_find_trainer_post_id_by_slug( $legacy_slug );
			if ( ! $legacy_id || (int) $legacy_id === (int) $canonical_id ) {
				continue;
			}

			$canonical_post = get_post( (int) $canonical_id );
			$legacy_post    = get_post( (int) $legacy_id );
			if ( $canonical_post instanceof WP_Post && $legacy_post instanceof WP_Post ) {
				if ( ! trim( wp_strip_all_tags( $canonical_post->post_content ) ) && trim( wp_strip_all_tags( $legacy_post->post_content ) ) ) {
					wp_update_post(
						array(
							'ID'           => (int) $canonical_id,
							'post_content' => $legacy_post->post_content,
						)
					);
				}

				if ( ! has_post_thumbnail( (int) $canonical_id ) && has_post_thumbnail( (int) $legacy_id ) ) {
					set_post_thumbnail( (int) $canonical_id, (int) get_post_thumbnail_id( (int) $legacy_id ) );
				}
			}

			wp_trash_post( (int) $legacy_id );
		}
	}

	extrasport_delete_orphan_trainer_posts( $published_slugs );

	if ( function_exists( 'extrasport_cleanup_duplicate_attachments_by_hash' ) ) {
		extrasport_cleanup_duplicate_attachments_by_hash();
	}

	if ( function_exists( 'extrasport_cleanup_orphan_trainer_attachments' ) ) {
		extrasport_cleanup_orphan_trainer_attachments();
	}

	update_option( 'extrasport_trainers_roster_version', EXTRASPORT_TRAINERS_ROSTER_VERSION, false );
}

/**
 * Sync roster after Yii import on bootstrap.
 *
 * @return void
 */
function extrasport_maybe_sync_trainers_roster() {
	if ( is_admin() ) {
		return;
	}

	extrasport_sync_trainers_roster( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_sync_trainers_roster', 39 );
