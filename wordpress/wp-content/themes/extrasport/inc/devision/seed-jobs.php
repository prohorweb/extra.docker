<?php
/**
 * Seed De-vision job vacancies from production content.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_JOBS_SEED_VERSION', 1 );

/**
 * Canonical De-vision job vacancies.
 *
 * @return array<int, array{slug: string, title: string, content: string, menu_order: int, meta_title?: string, meta_description?: string}>
 */
function extrasport_get_devision_jobs_roster() {
	return array(
		array(
			'slug'             => 'administrator',
			'title'            => 'Администратор',
			'menu_order'       => 1,
			'meta_title'       => 'Администратор — вакансия клуба De-vision',
			'meta_description' => 'Вакансия администратора в фитнес-клубе De-vision в ТРЦ «Родео Драйв».',
			'content'          => <<<'HTML'
<p>По всем вопросам звоните: <a href="tel:+78126440288">8(812)644-02-88</a></p>
<p>Почта: <a href="mailto:rodeo_manager@de-vision.ru">rodeo_manager@de-vision.ru</a></p>
<p><u><b>Требуемый опыт работы: не требуется</b></u></p>
<p><em>Полная занятость, сменный график</em></p>
<p>Перед тем как откликнуться на вакансию, просим обратить Ваше внимание, что утренние смены начинаются с 7:00.</p>
<p><b>Обязанности:</b></p>
<p>Встреча клиентов клуба</p>
<p>Распределение входящих звонков</p>
<p>Слежение за выходом персонала согласно графику</p>
<p><b>Требования:</b></p>
<p>ответственность, внимательность, коммуникабельность,</p>
<p>умение работать в команде,</p>
<p>владение ПК на уровне уверенного пользователя,</p>
<p>желательно знание кассовой дисциплины,</p>
<p>к рассмотрению принимаются резюме с фото.</p>
<p><b>Условия:</b></p>
<p>работа в спортивном клубе по адресу: пр-т. Культуры д.1 ТРК «Родео Драйв»</p>
<p>ставка 120р/час</p>
<p>график 2/2, полный рабочий день,</p>
<p>работа в большой, сильной компании,</p>
<p>оформление по ТК РФ,</p>
<p>возможность бесплатных занятий в спортивном клубе.</p>
HTML,
		),
	);
}

/**
 * Seed De-vision jobs.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_devision_jobs( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_jobs_seed_version', 0 ) >= EXTRASPORT_DEVISION_JOBS_SEED_VERSION ) {
		return;
	}

	$items           = extrasport_get_devision_jobs_roster();
	$published_slugs = array();

	foreach ( $items as $item ) {
		$slug = sanitize_title( (string) ( $item['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$published_slugs[] = $slug;
		$post_id           = extrasport_find_job_post_id_by_slug( $slug );
		$post_data         = array(
			'post_type'    => 'job',
			'post_name'    => $slug,
			'post_title'   => sanitize_text_field( (string) ( $item['title'] ?? '' ) ),
			'post_content' => wp_kses_post( (string) ( $item['content'] ?? '' ) ),
			'post_status'  => 'publish',
			'menu_order'   => (int) ( $item['menu_order'] ?? 0 ),
		);

		if ( $post_id ) {
			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );
		} else {
			$inserted = wp_insert_post( $post_data, true );
			if ( is_wp_error( $inserted ) || ! $inserted ) {
				continue;
			}
			$post_id = (int) $inserted;
		}

		update_post_meta( $post_id, EXTRASPORT_JOB_META_TITLE, sanitize_text_field( (string) ( $item['meta_title'] ?? $item['title'] ?? '' ) ) );
		update_post_meta( $post_id, EXTRASPORT_JOB_META_KEYWORDS, '' );
		update_post_meta( $post_id, EXTRASPORT_JOB_META_DESCRIPTION, sanitize_textarea_field( (string) ( $item['meta_description'] ?? '' ) ) );
	}

	$existing = get_posts(
		array(
			'post_type'              => 'job',
			'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $existing as $existing_id ) {
		$post = get_post( (int) $existing_id );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		if ( in_array( $post->post_name, $published_slugs, true ) ) {
			continue;
		}
		wp_delete_post( (int) $existing_id, true );
	}

	update_option(
		'extrasport_jobs_archive_seo',
		array(
			'title'       => __( 'Вакансии клуба De-vision', 'extrasport' ),
			'keywords'    => '',
			'description' => __( 'Открытые вакансии фитнес-клуба De-vision в ТРЦ «Родео Драйв».', 'extrasport' ),
			'text'        => '',
		),
		false
	);

	update_option( 'extrasport_devision_jobs_seed_version', EXTRASPORT_DEVISION_JOBS_SEED_VERSION, false );
}
