<?php
/**
 * Trainer helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_TRAINER_POST_META', '_trainer_post' );
define( 'EXTRASPORT_TRAINER_BANNER_META', '_trainer_banner_id' );
define( 'EXTRASPORT_TRAINER_META_TITLE', '_trainer_meta_title' );
define( 'EXTRASPORT_TRAINER_META_KEYWORDS', '_trainer_meta_keywords' );
define( 'EXTRASPORT_TRAINER_META_DESCRIPTION', '_trainer_meta_description' );
define( 'EXTRASPORT_TRAINER_DIRECTION_YII_META', '_yii_option_id' );

/**
 * Trainers archive URL.
 *
 * @return string
 */
function extrasport_get_trainers_archive_url() {
	return get_post_type_archive_link( 'trainer' ) ?: home_url( '/trainers/' );
}

/**
 * Whether current service is personal training page.
 *
 * @param int|null $post_id Service post ID.
 * @return bool
 */
function extrasport_is_personal_training_service( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );

	return $post instanceof WP_Post && 'service' === $post->post_type && 'personal_training' === $post->post_name;
}

/**
 * Trainer direction terms for filters (Yii2: trainer_options_type).
 *
 * @return array<int, WP_Term>
 */
function extrasport_get_trainer_directions() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'trainer_direction',
			'hide_empty' => false,
			'orderby'    => 'meta_value_num',
			'meta_key'   => EXTRASPORT_TRAINER_DIRECTION_YII_META,
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
		return array();
	}

	if ( $terms ) {
		return $terms;
	}

	// Fallback when terms exist without Yii meta (legacy import).
	$terms = get_terms(
		array(
			'taxonomy'   => 'trainer_direction',
			'hide_empty' => false,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);

	return is_array( $terms ) ? $terms : array();
}

/**
 * Selected trainer direction ID from request (Yii2 POST/GET param "filter").
 *
 * @return string
 */
function extrasport_get_selected_trainer_direction() {
	if ( isset( $_POST['reset'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}

	if ( isset( $_POST['filter'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return sanitize_text_field( wp_unslash( (string) $_POST['filter'] ) );
	}

	return sanitize_text_field( wp_unslash( (string) ( $_GET['filter'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Resolve active trainer direction filter to a taxonomy term.
 *
 * Empty string means "Все направления" (no filter).
 *
 * @param string $direction Raw direction slug from request or args.
 * @return WP_Term|null
 */
function extrasport_resolve_trainer_direction_filter_term( $direction = '' ) {
	$direction = sanitize_text_field( (string) $direction );
	if ( '' === $direction ) {
		$direction = extrasport_get_selected_trainer_direction();
	}

	if ( '' === $direction ) {
		return null;
	}

	$term = get_term_by( 'slug', $direction, 'trainer_direction' );
	if ( $term instanceof WP_Term && ! is_wp_error( $term ) ) {
		return $term;
	}

	return null;
}

/**
 * Apply trainer direction filter args to a WP_Query query.
 *
 * Trainers without direction marks are excluded when a filter is active.
 *
 * @param array<string, mixed> $query_args Query args.
 * @param string               $direction  Optional raw direction slug.
 * @return array<string, mixed>
 */
function extrasport_apply_trainer_direction_filter( array $query_args, $direction = '' ) {
	$term = extrasport_resolve_trainer_direction_filter_term( $direction );
	if ( ! $term ) {
		return $query_args;
	}

	$query_args['tax_query'] = array(
		array(
			'taxonomy'         => 'trainer_direction',
			'field'            => 'term_id',
			'terms'            => array( (int) $term->term_id ),
			'operator'         => 'IN',
			'include_children' => false,
		),
	);

	return $query_args;
}

/**
 * Assign trainer direction terms.
 *
 * Empty array means the trainer is shown only in "Все направления".
 *
 * @param int        $post_id  Trainer post ID.
 * @param array<int> $term_ids Direction term IDs.
 * @return void
 */
function extrasport_set_trainer_directions( $post_id, array $term_ids ) {
	$post_id  = (int) $post_id;
	$term_ids = array_values( array_unique( array_map( 'intval', $term_ids ) ) );

	if ( ! $post_id || 'trainer' !== get_post_type( $post_id ) ) {
		return;
	}

	wp_set_object_terms( $post_id, $term_ids, 'trainer_direction', false );
}

/**
 * Get assigned trainer direction term IDs.
 *
 * @param int $post_id Trainer post ID.
 * @return array<int>
 */
function extrasport_get_trainer_direction_term_ids( $post_id ) {
	$terms = wp_get_object_terms(
		(int) $post_id,
		'trainer_direction',
		array(
			'fields' => 'ids',
		)
	);

	if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
		return array();
	}

	return array_map( 'intval', $terms );
}

/**
 * Breadcrumbs for a single trainer page (О клубе → Тренеры → name).
 *
 * @param int|null $post_id Trainer post ID.
 * @return array<int, array{label: string, url: string}>
 */
function extrasport_get_trainer_breadcrumbs( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );

	if ( ! $post instanceof WP_Post || 'trainer' !== $post->post_type ) {
		return array();
	}

	return array(
		array(
			'label' => __( 'О клубе', 'extrasport' ),
			'url'   => extrasport_get_about_page_url( 'club' ),
		),
		array(
			'label' => __( 'Тренеры', 'extrasport' ),
			'url'   => extrasport_get_trainers_archive_url(),
		),
		array(
			'label' => $post->post_title,
			'url'   => '',
		),
	);
}

/**
 * Sort trainer queries: posts with a featured image first, then menu_order.
 *
 * @param array<string, mixed> $query_args WP_Query args.
 * @return array<string, mixed>
 */
function extrasport_apply_trainer_thumbnail_priority_sort( array $query_args ) {
	$query_args['extrasport_thumbnail_priority'] = true;

	return $query_args;
}

/**
 * Apply thumbnail-first ordering in SQL.
 *
 * @param array<string, string> $clauses Query clauses.
 * @param WP_Query              $query   Query instance.
 * @return array<string, string>
 */
function extrasport_trainer_thumbnail_priority_clauses( $clauses, $query ) {
	if ( ! $query->get( 'extrasport_thumbnail_priority' ) ) {
		return $clauses;
	}

	global $wpdb;

	$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS extrasport_trainer_thumb
		ON ({$wpdb->posts}.ID = extrasport_trainer_thumb.post_id AND extrasport_trainer_thumb.meta_key = '_thumbnail_id')";

	$clauses['orderby'] = "CASE
		WHEN extrasport_trainer_thumb.meta_value IS NOT NULL
			AND extrasport_trainer_thumb.meta_value <> ''
			AND extrasport_trainer_thumb.meta_value <> '0'
		THEN 1
		ELSE 0
	END DESC, {$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC";

	return $clauses;
}
add_filter( 'posts_clauses', 'extrasport_trainer_thumbnail_priority_clauses', 10, 2 );

/**
 * Query trainers.
 *
 * @param array<string, mixed> $args Query args.
 * @return array<int, WP_Post>
 */
function extrasport_get_trainers( array $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'direction' => '',
			'limit'     => -1,
			'exclude'   => array(),
		)
	);

	$query_args = array(
		'post_type'              => 'trainer',
		'post_status'            => 'publish',
		'posts_per_page'         => (int) $args['limit'],
		'orderby'                => 'menu_order',
		'order'                  => 'ASC',
		'post__not_in'           => array_map( 'intval', (array) $args['exclude'] ),
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	);

	$direction = sanitize_text_field( (string) $args['direction'] );

	$query = new WP_Query(
		extrasport_apply_trainer_thumbnail_priority_sort(
			extrasport_apply_trainer_direction_filter( $query_args, $direction )
		)
	);

	return is_array( $query->posts ) ? $query->posts : array();
}

/**
 * Get random trainers for the single trainer page.
 *
 * @param int $exclude_id Current trainer ID.
 * @param int $limit      Max items.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_other_trainers( $exclude_id = 0, $limit = 3 ) {
	$posts = get_posts(
		array(
			'post_type'              => 'trainer',
			'post_status'            => 'publish',
			'posts_per_page'         => (int) $limit,
			'orderby'                => 'rand',
			'post__not_in'           => $exclude_id ? array( (int) $exclude_id ) : array(),
			'meta_query'             => array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => '_thumbnail_id',
					'value'   => '0',
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			),
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
		)
	);

	if ( empty( $posts ) ) {
		return array();
	}

	return array_map( 'extrasport_normalize_trainer_post', $posts );
}

/**
 * Club logo URL for trainer image placeholders.
 *
 * @return string
 */
function extrasport_get_trainer_placeholder_logo_url() {
	if ( 'extrasport' === extrasport_get_current_club_slug() ) {
		return EXTRASPORT_URI . '/assets/images/logo-short.svg';
	}

	return (string) ( extrasport_get_brand()['logo_url'] ?? EXTRASPORT_URI . '/assets/images/logo-short.svg' );
}

/**
 * Normalize trainer post into card data.
 *
 * @param WP_Post $trainer Trainer post.
 * @return array{title: string, excerpt: string, date: string, image: string, url: string}
 */
function extrasport_normalize_trainer_post( WP_Post $trainer ) {
	$position = (string) get_post_meta( $trainer->ID, EXTRASPORT_TRAINER_POST_META, true );

	return array(
		'title'   => $trainer->post_title,
		'excerpt' => $position ?: (string) get_the_excerpt( $trainer ),
		'date'    => '',
		'image'   => get_the_post_thumbnail_url( $trainer->ID, 'extrasport-trainer-card' )
			?: get_the_post_thumbnail_url( $trainer->ID, 'large' )
			?: '',
		'url'     => get_permalink( $trainer->ID ),
	);
}

/**
 * Hero/banner image URL for trainer single page.
 *
 * @param int|null $post_id Trainer post ID.
 * @return string
 */
function extrasport_get_trainer_banner_url( $post_id = null ) {
	$post_id       = $post_id ?: get_the_ID();
	$attachment_id = (int) get_post_meta( $post_id, EXTRASPORT_TRAINER_BANNER_META, true );

	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}

	return get_the_post_thumbnail_url( $post_id, 'full' ) ?: '';
}

/**
 * Archive SEO from seeded option.
 *
 * @return array{title: string, keywords: string, description: string}
 */
function extrasport_get_trainers_archive_seo() {
	$seo = get_option(
		'extrasport_trainers_archive_seo',
		array(
			'title'       => '',
			'keywords'    => '',
			'description' => '',
		)
	);

	return is_array( $seo ) ? $seo : array();
}

/**
 * Render reusable trainers block.
 *
 * @param array<string, mixed> $args Block options.
 * @return void
 */
function extrasport_render_trainers_section( array $args = array() ) {
	get_template_part(
		'sections/trainers/block',
		null,
		wp_parse_args(
			$args,
			array(
				'heading'       => true,
				'show_filter'   => false,
				'show_all_link' => false,
				'filter_action' => '',
				'direction'     => '',
				'limit'         => -1,
				'exclude'       => array(),
				'section_class' => 'other-teams',
			)
		)
	);
}

/**
 * Trainers archive query.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_trainer_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'trainer' ) ) {
		return;
	}

	$query->set( 'posts_per_page', -1 );
	$query->set( 'orderby', 'menu_order' );
	$query->set( 'order', 'ASC' );
	$query->set( 'extrasport_thumbnail_priority', true );

	$direction = extrasport_get_selected_trainer_direction();
	$term        = extrasport_resolve_trainer_direction_filter_term( $direction );
	if ( ! $term ) {
		return;
	}

	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy'         => 'trainer_direction',
				'field'            => 'term_id',
				'terms'            => array( (int) $term->term_id ),
				'operator'         => 'IN',
				'include_children' => false,
			),
		)
	);
}
add_action( 'pre_get_posts', 'extrasport_trainer_archive_query' );

/**
 * Flush rewrite rules when trainer routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_trainer_rewrite() {
	if ( get_option( 'extrasport_trainer_rewrite_version' ) === '1' ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_trainer_rewrite_version', '1', false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_trainer_rewrite', 99 );

/**
 * Apply trainer SEO title on the front end.
 *
 * @param string $title Document title.
 * @return string
 */
function extrasport_trainer_document_title( $title ) {
	if ( is_singular( 'trainer' ) ) {
		$custom = (string) get_post_meta( get_queried_object_id(), EXTRASPORT_TRAINER_META_TITLE, true );
		return $custom ?: $title;
	}

	if ( is_post_type_archive( 'trainer' ) ) {
		$seo = extrasport_get_trainers_archive_seo();
		if ( ! empty( $seo['title'] ) ) {
			return (string) $seo['title'];
		}
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'extrasport_trainer_document_title' );

/**
 * Output trainer meta tags on the front end.
 *
 * @return void
 */
function extrasport_trainer_head_meta() {
	$keywords = '';
	$desc     = '';

	if ( is_singular( 'trainer' ) ) {
		$post_id  = get_queried_object_id();
		$keywords = (string) get_post_meta( $post_id, EXTRASPORT_TRAINER_META_KEYWORDS, true );
		$desc     = (string) get_post_meta( $post_id, EXTRASPORT_TRAINER_META_DESCRIPTION, true );
	} elseif ( is_post_type_archive( 'trainer' ) ) {
		$seo      = extrasport_get_trainers_archive_seo();
		$keywords = (string) ( $seo['keywords'] ?? '' );
		$desc     = (string) ( $seo['description'] ?? '' );
	} else {
		return;
	}

	if ( $keywords ) {
		printf( '<meta name="keywords" content="%s">' . "\n", esc_attr( $keywords ) );
	}

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'extrasport_trainer_head_meta', 1 );
