<?php
/**
 * Demo share posts seeded from theme templates.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_SHARES_SEED_VERSION', 2 );

/**
 * Share templates used for placeholders and DB seeding.
 *
 * @return array<int, array{slug: string, title: string, excerpt: string, date: string, image: string, content: string, price?: int}>
 */
function extrasport_get_share_seed_templates() {
	return array(
		array(
			'slug'    => 'leto',
			'title'   => 'Год ФИТНЕС+БАССЕЙН  ВСЕГО 5500!!!',
			'excerpt' => 'МАССАЖ в ПОДАРОК!!',
			'date'    => 'До 29 июня!',
			'image'   => 'assets/img/shares/leto.jpg',
			'price'   => 5500,
			'content' => '<p>Уникальное предложение: годовой абонемент «Фитнес + Бассейн» всего за <strong>5500 рублей</strong>!</p><p>При оформлении абонемента — <strong>массаж в подарок</strong>. Успейте воспользоваться акцией до 29 июня.</p><ul><li>Неограниченное посещение тренажёрного зала</li><li>Бассейн 25 м</li><li>Групповые программы</li><li>Зона отдыха и сауна</li></ul><p>Оставьте заявку на сайте или позвоните в отдел продаж — менеджер подберёт удобное время для оформления.</p>',
		),
		array(
			'slug'    => 'bassein',
			'title'   => 'Программы обучения плаванию',
			'excerpt' => 'Для детей разных возрастов и подготовки.',
			'date'    => 'Запись открыта!',
			'image'   => 'assets/img/shares/bassein.jpg',
			'content' => '<p>Набор в группы обучения плаванию для детей разного возраста и уровня подготовки.</p><p>Занятия проходят в бассейне под руководством опытных тренеров. Программа включает освоение техники, дыхания и безопасности на воде.</p><ul><li>Группы для начинающих и продолжающих</li><li>Малые группы до 8 человек</li><li>Удобное расписание в будни и выходные</li></ul><p>Запишитесь на пробное занятие — количество мест в группах ограничено.</p>',
		),
		array(
			'slug'    => 'fitnes',
			'title'   => 'Спортивные секции для детей!',
			'excerpt' => 'Детский абонемент от 4000р',
			'date'    => 'Запись открыта!',
			'image'   => 'assets/img/shares/fitnes.jpg',
			'price'   => 4000,
			'content' => '<p>Спортивные секции для детей: гимнастика, единоборства, ОФП и игровые тренировки.</p><p>Детский абонемент — <strong>от 4000 рублей</strong>. Регулярные занятия помогают развить координацию, выносливость и дисциплину.</p><ul><li>Тренеры с педагогическим и спортивным опытом</li><li>Безопасная среда и современный инвентарь</li><li>Гибкое расписание для школьников</li></ul><p>Приходите на пробную тренировку и выберите секцию вместе с ребёнком.</p>',
		),
		array(
			'slug'    => 'personal',
			'title'   => 'Персональные тренировки — скидка 20%',
			'excerpt' => 'Пакет из 10 занятий с тренером',
			'date'    => 'До конца месяца!',
			'image'   => 'assets/img/service/serv-1.jpg',
			'price'   => 12000,
			'content' => '<p>Специальное предложение на пакет персональных тренировок: <strong>10 занятий со скидкой 20%</strong>.</p><p>Тренер составит программу под ваши цели — похудение, набор мышечной массы, восстановление после перерыва или подготовка к соревнованиям.</p><ul><li>Индивидуальный план и контроль техники</li><li>Гибкий график в будни и выходные</li><li>Замеры и корректировка нагрузки</li></ul><p>Оставьте заявку — администратор подберёт тренера и удобное время для старта.</p>',
		),
		array(
			'slug'    => 'spa',
			'title'   => 'SPA-день в подарок',
			'excerpt' => 'При покупке годового абонемента',
			'date'    => 'Ограниченное предложение',
			'image'   => 'assets/img/service/serv-2.jpg',
			'content' => '<p>Оформите годовой абонемент и получите <strong>SPA-день в подарок</strong> для себя или близкого человека.</p><p>В программу входит доступ в зону отдыха, сауны и расслабляющие процедуры по клубному регламенту.</p><ul><li>Подарочный сертификат на SPA-день</li><li>Действует при оформлении до конца акции</li><li>Можно активировать в течение 30 дней</li></ul><p>Уточняйте детали у менеджера отдела продаж — количество подарков ограничено.</p>',
		),
		array(
			'slug'    => 'family',
			'title'   => 'Семейный абонемент',
			'excerpt' => 'Тренируйтесь вместе выгоднее',
			'date'    => 'Новинка клуба',
			'image'   => 'assets/img/service/serv-4.jpg',
			'price'   => 9900,
			'content' => '<p>Новый формат семейного абонемента: тренируйтесь вместе и экономьте на посещении клуба.</p><p>Абонемент рассчитан на двух взрослых или взрослого и ребёнка — удобный вариант для семейного спорта без лишних ограничений.</p><ul><li>Посещение тренажёрного зала и групповых программ</li><li>Единый счёт и прозрачные условия</li><li>Специальная цена для семьи</li></ul><p>Запишитесь на консультацию — подберём тариф под ваш состав семьи.</p>',
		),
	);
}

/**
 * Unique images for legacy duplicate slugs from seed v1.
 *
 * @return array<string, string>
 */
function extrasport_get_share_legacy_image_map() {
	return array(
		'leto-2'    => 'assets/img/service/serv-3.jpg',
		'bassein-2' => 'assets/img/service/serv-5.jpg',
		'fitnes-2'  => 'assets/img/clubs/welcom-block-img-1.jpg',
	);
}

/**
 * Build seed items — one post per template.
 *
 * @return array<int, array{slug: string, title: string, excerpt: string, date: string, image: string, content: string, menu_order: int, price?: int}>
 */
function extrasport_get_share_seed_items() {
	$items = array();

	foreach ( extrasport_get_share_seed_templates() as $index => $template ) {
		$items[] = array_merge(
			$template,
			array(
				'menu_order' => $index,
			)
		);
	}

	return $items;
}

/**
 * Assign featured image from a theme asset path.
 *
 * @param int                  $post_id Post ID.
 * @param string               $image   Theme-relative image path.
 * @param array<string, int>   $cache   Attachment cache keyed by image path.
 * @return void
 */
function extrasport_set_share_featured_image( $post_id, $image, array &$cache ) {
	if ( ! $post_id || ! $image ) {
		return;
	}

	if ( ! isset( $cache[ $image ] ) ) {
		$cache[ $image ] = extrasport_import_theme_image( $image );
	}

	if ( $cache[ $image ] ) {
		set_post_thumbnail( $post_id, $cache[ $image ] );
	}
}

/**
 * Sync featured images for all seeded and legacy share slugs.
 *
 * @return void
 */
function extrasport_sync_share_seed_images() {
	$cache = array();

	foreach ( extrasport_get_share_seed_templates() as $template ) {
		$post_id = extrasport_find_share_by_slug( $template['slug'] );
		if ( $post_id ) {
			extrasport_set_share_featured_image( $post_id, $template['image'], $cache );
		}
	}

	foreach ( extrasport_get_share_legacy_image_map() as $slug => $image ) {
		$post_id = extrasport_find_share_by_slug( $slug );
		if ( $post_id ) {
			extrasport_set_share_featured_image( $post_id, $image, $cache );
		}
	}
}

/**
 * Create demo share posts for the current site.
 *
 * @param bool $force Ignore existing posts when true.
 * @return int Number of created posts.
 */
function extrasport_seed_shares( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_shares_seed_version', 0 ) >= EXTRASPORT_SHARES_SEED_VERSION ) {
		return 0;
	}

	$created     = 0;
	$image_cache = array();
	$seeded_slug = array();

	foreach ( extrasport_get_share_seed_items() as $item ) {
		if ( isset( $seeded_slug[ $item['slug'] ] ) ) {
			continue;
		}

		$existing_id = extrasport_find_share_by_slug( $item['slug'] );
		if ( $existing_id && ! $force ) {
			$seeded_slug[ $item['slug'] ] = $existing_id;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'share',
				'post_status'  => 'publish',
				'post_title'   => wp_strip_all_tags( $item['title'] ),
				'post_name'    => sanitize_title( $item['slug'] ),
				'post_content' => $item['content'],
				'menu_order'   => (int) $item['menu_order'],
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_share_date', $item['date'] );
		update_post_meta( $post_id, '_share_excerpt', $item['excerpt'] );

		if ( ! empty( $item['price'] ) ) {
			update_post_meta( $post_id, '_share_price', (int) $item['price'] );
		}

		extrasport_set_share_featured_image( $post_id, $item['image'], $image_cache );

		$seeded_slug[ $item['slug'] ] = $post_id;
		++$created;
	}

	extrasport_sync_share_seed_images();

	update_option( 'extrasport_shares_seed_version', EXTRASPORT_SHARES_SEED_VERSION, false );

	return $created;
}

/**
 * Find a share post by slug.
 *
 * @param string $slug Post slug.
 * @return int Post ID or 0.
 */
function extrasport_find_share_by_slug( $slug ) {
	$posts = get_posts(
		array(
			'post_type'      => 'share',
			'name'           => sanitize_title( $slug ),
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	return ! empty( $posts ) ? (int) $posts[0] : 0;
}

/**
 * Seed shares once per site (demo content on first install).
 *
 * @return void
 */
function extrasport_maybe_seed_shares() {
	if ( wp_installing() ) {
		return;
	}

	if ( is_admin() && (int) get_option( 'extrasport_shares_seed_version', 0 ) >= EXTRASPORT_SHARES_SEED_VERSION ) {
		return;
	}

	extrasport_seed_shares( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_shares', 30 );

/**
 * Seed shares when a multisite blog is created.
 *
 * @param WP_Site|object $new_site New site object.
 * @param array          $args     Site arguments.
 * @return void
 */
function extrasport_seed_shares_on_site_created( $new_site, $args = array() ) {
	unset( $args );

	switch_to_blog( (int) $new_site->blog_id );
	extrasport_seed_shares( false );
	restore_current_blog();
}
add_action( 'wp_initialize_site', 'extrasport_seed_shares_on_site_created', 20, 2 );
