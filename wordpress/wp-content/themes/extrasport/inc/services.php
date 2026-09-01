<?php
/**
 * Service helpers and demo placeholders.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo top-level service cards for the archive.
 *
 * @return array<int, array{slug: string, title: string, excerpt: string, image: string, mode: string}>
 */
function extrasport_get_service_placeholder_templates() {
	return array(
		array(
			'slug'    => 'personal_training',
			'title'   => 'Персональный тренинг',
			'excerpt' => 'Индивидуальные занятия с инструктором.',
			'image'   => 'services/personal-1556301977.jpg',
			'mode'    => 'page',
		),
		array(
			'slug'    => 'bassejn',
			'title'   => 'Бассейн',
			'excerpt' => 'Плавание и aqua-программы.',
			'image'   => 'services/pool-1556302050.jpg',
			'mode'    => 'page',
		),
		array(
			'slug'    => 'detskij-klub',
			'title'   => 'Детский клуб',
			'excerpt' => 'Секции и программы для детей.',
			'image'   => 'services/kids-1556302084.jpg',
			'mode'    => 'page',
		),
		array(
			'slug'    => 'group-programs',
			'title'   => 'Групповые программы',
			'excerpt' => 'Направления групповых занятий.',
			'image'   => 'services/group_prog-1556302019.jpg',
			'mode'    => 'group',
		),
		array(
			'slug'    => 'boevye-iskusstva',
			'title'   => 'Боевые искусства',
			'excerpt' => 'Единоборства и силовые классы.',
			'image'   => 'services/fight-1556302125.jpg',
			'mode'    => 'group',
		),
	);
}

/**
 * Demo child services grouped under a parent slug.
 *
 * @return array<string, array<int, array{slug: string, title: string, excerpt: string, image: string, menu_order: int}>>
 */
function extrasport_get_service_child_seed_templates() {
	return array(
		'boevye-iskusstva' => array(
			array(
				'slug'        => 'boks',
				'title'       => 'Бокс',
				'excerpt'     => 'Групповые и персональные классы бокса.',
				'image'       => 'services/fight-1556302125.jpg',
				'menu_order'  => 10,
			),
			array(
				'slug'        => 'kikboksing',
				'title'       => 'Кикбоксинг',
				'excerpt'     => 'Удары руками и ногами, работа в парах.',
				'image'       => 'group_programs/TajBo-1706449795.jpg',
				'menu_order'  => 20,
			),
			array(
				'slug'        => 'muaj-taj',
				'title'       => 'Муай-тай',
				'excerpt'     => 'Тайский бокс и работа на лапах.',
				'image'       => 'group_programs/TajBo-1706449829.jpg',
				'menu_order'  => 30,
			),
			array(
				'slug'        => 'grappling',
				'title'       => 'Грэпpling',
				'excerpt'     => 'Борьба в партере и захваты.',
				'image'       => 'services/fight-1556301761.jpg',
				'menu_order'  => 40,
			),
		),
	);
}

/**
 * Card mode for a service: page|group.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_service_card_mode( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$mode    = (string) get_post_meta( $post_id, '_service_card_mode', true );

	if ( in_array( $mode, array( 'page', 'group' ), true ) ) {
		return $mode;
	}

	$post = get_post( $post_id );
	if ( $post instanceof WP_Post && (int) $post->post_parent > 0 ) {
		return 'page';
	}

	return 'page';
}

/**
 * Whether the service opens a grouped list instead of a content page.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return bool
 */
function extrasport_is_service_group( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post || 'service' !== $post->post_type ) {
		return false;
	}

	if ( (int) $post->post_parent > 0 ) {
		return false;
	}

	return 'group' === extrasport_get_service_card_mode( $post->ID );
}

/**
 * Public URL for a service card.
 *
 * @param int|WP_Post $post Post ID or object.
 * @return string
 */
function extrasport_get_service_card_url( $post ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	return get_permalink( $post );
}

/**
 * Resolve placeholder card URL.
 *
 * @param array{slug: string, mode: string} $template Placeholder template.
 * @return string
 */
function extrasport_get_service_placeholder_url( array $template ) {
	$archive_url = get_post_type_archive_link( 'service' ) ?: home_url( '/services/' );

	return trailingslashit( $archive_url ) . sanitize_title( $template['slug'] ) . '/';
}

/**
 * Demo image for a service post when no featured image is set.
 *
 * @param WP_Post $service Service post.
 * @return string
 */
function extrasport_get_service_demo_image( WP_Post $service ) {
	if ( (int) $service->post_parent > 0 ) {
		$parent = get_post( (int) $service->post_parent );
		if ( $parent instanceof WP_Post ) {
			$children = extrasport_get_service_child_seed_templates()[ $parent->post_name ] ?? array();
			foreach ( $children as $template ) {
				if ( $template['slug'] === $service->post_name ) {
					return extrasport_get_service_template_image_url( $template['image'] );
				}
			}
		}
	}

	foreach ( extrasport_get_service_placeholder_templates() as $template ) {
		if ( $template['slug'] === $service->post_name ) {
			return extrasport_get_service_template_image_url( $template['image'] );
		}
	}

	return extrasport_get_service_template_image_url_for_post( $service->ID );
}

/**
 * Theme service image file names (Yii2 frontend).
 *
 * @return array<int, string>
 */
function extrasport_get_service_template_image_paths() {
	return array(
		'serv-1.jpg',
		'serv-2.jpg',
		'serv-3.jpg',
		'serv-4.jpg',
		'serv-5.jpg',
	);
}

/**
 * Resolve a Yii service image URL.
 *
 * @param int|string $source 1-5 index or file name.
 * @return string
 */
function extrasport_get_service_template_image_url( $source ) {
	if ( is_string( $source ) && str_contains( $source, '/' ) ) {
		return extrasport_get_yii_service_image_url( $source );
	}

	if ( is_string( $source ) && preg_match( '/^serv-\d+\.jpg$/', $source ) ) {
		return extrasport_get_yii_service_image_url( $source );
	}

	if ( is_string( $source ) && str_contains( $source, 'serv-' ) ) {
		return extrasport_get_yii_service_image_url( basename( $source ) );
	}

	$paths = extrasport_get_service_template_image_paths();
	$index = max( 1, min( count( $paths ), (int) $source ) ) - 1;

	return extrasport_get_yii_service_image_url( $paths[ $index ] );
}

/**
 * Pick a stable template image for any post ID.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_service_template_image_url_for_post( $post_id ) {
	return extrasport_get_service_template_image_url( ( (int) $post_id % 5 ) + 1 );
}

/**
 * Preview image URL for admin lists and cards.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_post_preview_image_url( $post_id ) {
	$post_id = (int) $post_id;

	if ( has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
		if ( $url ) {
			return $url;
		}
	}

	return '';
}

/**
 * Import Yii service media when the services list is opened in admin.
 *
 * @param WP_Screen|null $screen Current admin screen.
 * @return void
 */
function extrasport_seed_service_media_on_screen( $screen ) {
	if ( ! $screen instanceof WP_Screen || 'edit' !== $screen->base || 'service' !== $screen->post_type ) {
		return;
	}

	extrasport_seed_service_media( false );
}
add_action( 'current_screen', 'extrasport_seed_service_media_on_screen' );

/**
 * Demo services for the archive when CPT is empty.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{title: string, excerpt: string, image: string, url: string}>
 */
function extrasport_get_service_placeholders( $uri ) {
	$items = array();

	foreach ( extrasport_get_service_placeholder_templates() as $template ) {
		$items[] = array(
			'title'   => $template['title'],
			'excerpt' => $template['excerpt'],
			'image'   => extrasport_get_yii_service_image_url( $template['image'] ),
			'url'     => extrasport_get_service_placeholder_url( $template ),
		);
	}

	return $items;
}

/**
 * Top-level services for the archive.
 *
 * @return array<int, WP_Post>
 */
function extrasport_get_top_level_services() {
	$posts = get_posts(
		array(
			'post_type'      => 'service',
			'post_parent'    => 0,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	return is_array( $posts ) ? $posts : array();
}

/**
 * Child services inside a group section.
 *
 * @param int $parent_id Parent service ID.
 * @return array<int, WP_Post>
 */
function extrasport_get_service_children( $parent_id ) {
	$posts = get_posts(
		array(
			'post_type'      => 'service',
			'post_parent'    => (int) $parent_id,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	return is_array( $posts ) ? $posts : array();
}

/**
 * Normalize a service post into card data.
 *
 * @param WP_Post $service Service post.
 * @return array{title: string, excerpt: string, image: string, url: string}
 */
function extrasport_normalize_service_post( WP_Post $service ) {
	$intro = extrasport_get_service_intro( $service->ID );
	$image = extrasport_get_service_card_image_url( $service->ID );

	return array(
		'title'   => $service->post_title,
		'excerpt' => $intro ?: ( $service->post_excerpt ?: wp_trim_words( $service->post_content, 15 ) ),
		'image'   => $image,
		'url'     => extrasport_get_service_card_url( $service ),
	);
}

/**
 * Card image URL from the featured image attachment.
 *
 * @param int $post_id Service post ID.
 * @return string
 */
function extrasport_get_service_card_image_url( $post_id ) {
	$post_id = (int) $post_id;

	if ( has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'extrasport-service-card' );
		if ( $url ) {
			return $url;
		}

		$url = get_the_post_thumbnail_url( $post_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}

	$post = get_post( $post_id );
	if ( $post instanceof WP_Post && 'service' === $post->post_type ) {
		return extrasport_get_service_demo_image( $post );
	}

	return '';
}

/**
 * Default background for actions sections without a post thumbnail.
 *
 * @return string
 */
function extrasport_get_default_actions_bg_url() {
	return EXTRASPORT_URI . '/assets/img/actions-bg.jpg';
}

/**
 * Parallax section background from the post featured image.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_parallax_bg_url( $post_id = 0 ) {
	$post_id = (int) ( $post_id ?: get_the_ID() );

	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'large' ) ?: get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}

	return extrasport_get_default_actions_bg_url();
}

/**
 * Inline background-image style for parallax actions sections.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function extrasport_get_parallax_bg_style( $post_id = 0 ) {
	return sprintf(
		'background-image: url(%s);',
		esc_url( extrasport_get_parallax_bg_url( $post_id ) )
	);
}

/**
 * Demo child service cards for a grouped parent.
 *
 * @param string $parent_slug Parent service slug.
 * @param string $uri         Theme URI.
 * @return array<int, array{title: string, excerpt: string, image: string, url: string}>
 */
function extrasport_get_service_child_placeholders( $parent_slug, $uri ) {
	$templates  = extrasport_get_service_child_seed_templates()[ $parent_slug ] ?? array();
	$parent_url = extrasport_get_service_placeholder_url( array( 'slug' => $parent_slug, 'mode' => 'group' ) );
	$cards      = array();

	foreach ( $templates as $template ) {
		$cards[] = array(
			'title'   => $template['title'],
			'excerpt' => $template['excerpt'],
			'image'   => extrasport_get_yii_service_image_url( $template['image'] ),
			'url'     => trailingslashit( $parent_url ) . sanitize_title( $template['slug'] ) . '/',
		);
	}

	return $cards;
}

/**
 * Cards for a grouped service section.
 *
 * @param int|null $parent_id Group service ID. Null uses current post in loop.
 * @return array<int, array{title: string, excerpt: string, image: string, url: string}>
 */
function extrasport_get_service_group_cards( $parent_id = null ) {
	$parent_id = $parent_id ?: get_the_ID();
	$parent    = get_post( $parent_id );
	$children  = extrasport_get_service_children( $parent_id );

	if ( ! empty( $children ) ) {
		return array_map( 'extrasport_normalize_service_post', $children );
	}

	if ( $parent instanceof WP_Post ) {
		return extrasport_get_service_child_placeholders( $parent->post_name, EXTRASPORT_URI );
	}

	return array();
}

/**
 * Intro text for a service single page.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function extrasport_get_service_intro( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$intro   = (string) get_post_meta( $post_id, '_service_intro', true );

	if ( $intro ) {
		return $intro;
	}

	$post = get_post( $post_id );

	return $post instanceof WP_Post && $post->post_excerpt ? $post->post_excerpt : '';
}

/**
 * Other services for the single page grid.
 *
 * Child pages: siblings under the same parent (up to 3).
 * Top-level pages: other top-level services (up to 3).
 *
 * @param int $exclude_id Current service ID.
 * @param int $limit      Max items.
 * @return array<int, array{title: string, excerpt: string, image: string, url: string}>
 */
function extrasport_get_other_services( $exclude_id = 0, $limit = 3 ) {
	$exclude_id = (int) $exclude_id;
	$post       = get_post( $exclude_id );

	if ( ! $post instanceof WP_Post || 'service' !== $post->post_type ) {
		return array();
	}

	$parent_id = (int) $post->post_parent;

	$posts = get_posts(
		array(
			'post_type'      => 'service',
			'post_parent'    => $parent_id > 0 ? $parent_id : 0,
			'post__not_in'   => array( $exclude_id ),
			'posts_per_page' => $limit,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	$cards = array();

	foreach ( $posts as $service_post ) {
		$cards[] = extrasport_normalize_service_post( $service_post );
	}

	if ( ! empty( $cards ) ) {
		return $cards;
	}

	return array_slice( extrasport_get_service_placeholders( EXTRASPORT_URI ), 0, $limit );
}

/**
 * Find top-level service by slug.
 *
 * @param string $slug Post slug.
 * @return int
 */
function extrasport_find_top_level_service_by_slug( $slug ) {
	$post = get_posts(
		array(
			'post_type'      => 'service',
			'name'           => sanitize_title( $slug ),
			'post_parent'    => 0,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	return ! empty( $post ) ? (int) $post[0] : 0;
}

/**
 * Parent group service URL for legacy /services/programs/ redirects.
 *
 * @param string $parent_slug Parent service slug.
 * @return string
 */
function extrasport_get_group_service_url( $parent_slug = 'group-programs' ) {
	$parent_id = extrasport_find_top_level_service_by_slug( $parent_slug );

	if ( $parent_id ) {
		return get_permalink( $parent_id ) ?: home_url( '/services/' . $parent_slug . '/' );
	}

	return home_url( '/services/' . $parent_slug . '/' );
}
