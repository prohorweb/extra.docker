<?php
/**
 * Jobs helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_JOB_META_TITLE', '_job_meta_title' );
define( 'EXTRASPORT_JOB_META_KEYWORDS', '_job_meta_keywords' );
define( 'EXTRASPORT_JOB_META_DESCRIPTION', '_job_meta_description' );

/**
 * Jobs archive URL.
 *
 * @return string
 */
function extrasport_get_jobs_archive_url() {
	return get_post_type_archive_link( 'job' ) ?: home_url( '/jobs/' );
}

/**
 * Find job post by slug in any status.
 *
 * @param string $slug Job slug.
 * @return int Post ID or 0.
 */
function extrasport_find_job_post_id_by_slug( $slug ) {
	$slug = sanitize_title( (string) $slug );
	if ( ! $slug ) {
		return 0;
	}

	$posts = get_posts(
		array(
			'post_type'              => 'job',
			'name'                   => $slug,
			'post_status'            => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return ! empty( $posts[0] ) ? (int) $posts[0] : 0;
}

/**
 * Normalize job post into view data.
 *
 * @param WP_Post $job Job post.
 * @return array{id: int, title: string, content: string, modal_id: string}
 */
function extrasport_normalize_job_post( WP_Post $job ) {
	return array(
		'id'        => (int) $job->ID,
		'title'     => $job->post_title,
		'content'   => apply_filters( 'the_content', $job->post_content ),
		'modal_id'  => 'jobModal-' . $job->ID,
	);
}

/**
 * Archive SEO from seeded option.
 *
 * @return array{title: string, keywords: string, description: string, text: string}
 */
function extrasport_get_jobs_archive_seo() {
	$seo = get_option(
		'extrasport_jobs_archive_seo',
		array(
			'title'       => '',
			'keywords'    => '',
			'description' => '',
			'text'        => '',
		)
	);

	return is_array( $seo ) ? $seo : array();
}

/**
 * Jobs archive query.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_jobs_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'job' ) ) {
		return;
	}

	$query->set( 'posts_per_page', -1 );
	$query->set( 'orderby', 'menu_order' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'extrasport_jobs_archive_query' );

/**
 * Single job URLs redirect to archive (modal-based UX).
 *
 * @return void
 */
function extrasport_redirect_job_single_to_archive() {
	if ( ! is_singular( 'job' ) ) {
		return;
	}

	wp_safe_redirect( extrasport_get_jobs_archive_url(), 301 );
	exit;
}
add_action( 'template_redirect', 'extrasport_redirect_job_single_to_archive', 5 );

/**
 * Flush rewrite rules when job routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_jobs_rewrite() {
	if ( get_option( 'extrasport_jobs_rewrite_version' ) === '1' ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_jobs_rewrite_version', '1', false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_jobs_rewrite', 99 );

/**
 * Archive document title.
 *
 * @param string $title Document title.
 * @return string
 */
function extrasport_jobs_document_title( $title ) {
	if ( is_post_type_archive( 'job' ) ) {
		$seo = extrasport_get_jobs_archive_seo();
		if ( ! empty( $seo['title'] ) ) {
			return (string) $seo['title'];
		}
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'extrasport_jobs_document_title' );

/**
 * Jobs archive meta tags.
 *
 * @return void
 */
function extrasport_jobs_head_meta() {
	if ( ! is_post_type_archive( 'job' ) ) {
		return;
	}

	$seo = extrasport_get_jobs_archive_seo();
	if ( ! empty( $seo['keywords'] ) ) {
		echo '<meta name="keywords" content="' . esc_attr( (string) $seo['keywords'] ) . '">' . "\n";
	}
	if ( ! empty( $seo['description'] ) ) {
		echo '<meta name="description" content="' . esc_attr( (string) $seo['description'] ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'extrasport_jobs_head_meta', 1 );

/**
 * Allowed resume mime types.
 *
 * @return array<string, string>
 */
function extrasport_get_job_resume_mime_types() {
	return array(
		'pdf'  => 'application/pdf',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	);
}

/**
 * Maximum resume file size in bytes.
 *
 * @return int
 */
function extrasport_get_job_resume_max_bytes() {
	return 100 * 1024;
}
