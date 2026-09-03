<?php
/**
 * Privacy and legal static pages (/privacy/, /legal/).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_LEGAL_PAGE_QUERY_VAR', 'extrasport_legal_page' );
define( 'EXTRASPORT_LEGAL_PAGES_REWRITE_VERSION', 1 );

/**
 * Registered legal page slugs.
 *
 * @return array<string, array{label: string, title: string}>
 */
function extrasport_get_legal_page_definitions() {
	return array(
		'privacy' => array(
			'label' => __( 'Политика конфиденциальности', 'extrasport' ),
			'title' => __( 'Политика конфиденциальности', 'extrasport' ),
		),
		'legal'   => array(
			'label' => __( 'Правовая информация', 'extrasport' ),
			'title' => __( 'Правовая информация', 'extrasport' ),
		),
	);
}

/**
 * Register pretty routes for legal pages.
 *
 * @return void
 */
function extrasport_register_legal_page_rewrites() {
	add_rewrite_tag( '%' . EXTRASPORT_LEGAL_PAGE_QUERY_VAR . '%', '([^&]+)' );

	foreach ( array_keys( extrasport_get_legal_page_definitions() ) as $slug ) {
		add_rewrite_rule(
			'^' . preg_quote( $slug, '/' ) . '/?$',
			'index.php?' . EXTRASPORT_LEGAL_PAGE_QUERY_VAR . '=' . $slug,
			'top'
		);
	}
}
add_action( 'init', 'extrasport_register_legal_page_rewrites', 20 );

/**
 * Flush rewrite rules when legal routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_legal_page_rewrites() {
	if ( get_option( 'extrasport_legal_pages_rewrite_version' ) === (string) EXTRASPORT_LEGAL_PAGES_REWRITE_VERSION ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_legal_pages_rewrite_version', (string) EXTRASPORT_LEGAL_PAGES_REWRITE_VERSION, false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_legal_page_rewrites', 98 );

/**
 * Document title for legal pages.
 *
 * @param string $title Document title.
 * @return string
 */
function extrasport_filter_legal_page_document_title( $title ) {
	if ( ! extrasport_is_legal_page() ) {
		return $title;
	}

	$page_title = extrasport_get_current_legal_page_title();
	if ( ! $page_title ) {
		return $title;
	}

	return $page_title . ' — ' . get_bloginfo( 'name' );
}
add_filter( 'pre_get_document_title', 'extrasport_filter_legal_page_document_title' );

/**
 * Custom theme routes that should not inherit the blog home/front-page shell.
 *
 * @return bool
 */
function extrasport_is_custom_shell_page() {
	return extrasport_is_legal_page() || extrasport_is_about_page();
}

/**
 * Current legal page slug or empty string.
 *
 * @return string
 */
function extrasport_get_current_legal_page_slug() {
	return sanitize_key( (string) get_query_var( EXTRASPORT_LEGAL_PAGE_QUERY_VAR ) );
}

/**
 * Whether the request matches a legal page.
 *
 * @param string $slug Optional slug to check.
 * @return bool
 */
function extrasport_is_legal_page( $slug = '' ) {
	$current = extrasport_get_current_legal_page_slug();

	if ( ! $current ) {
		return false;
	}

	if ( $slug ) {
		return $current === sanitize_key( $slug );
	}

	return true;
}

/**
 * Legal page URL.
 *
 * @param string $slug Page slug.
 * @return string
 */
function extrasport_get_legal_page_url( $slug ) {
	$slug = sanitize_key( $slug );

	if ( ! isset( extrasport_get_legal_page_definitions()[ $slug ] ) ) {
		return home_url( '/' );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Default privacy policy HTML (matches production extrasport.ru).
 *
 * @return string
 */
function extrasport_get_default_privacy_content() {
	$club = extrasport_get_club();

	ob_start();
	?>
	<p><?php esc_html_e( 'При заполнении форм обратной связи на нашем сайте вы предоставляете свою персональную информацию такую как: имя, номер телефона и адрес электронной почты. Наша политика конфиденциальности объясняет, для чего мы используем данную информацию и какие меры безопасности принимаются для защиты ваших личных данных.', 'extrasport' ); ?></p>

	<p><?php esc_html_e( 'Мы получаем персональную информацию:', 'extrasport' ); ?></p>
	<ul>
		<li><?php esc_html_e( 'когда вы заполняете форму обратной связи;', 'extrasport' ); ?></li>
		<li><?php esc_html_e( 'когда сохраняются файлы «cookie». Файл «cookie» сохраняется на вашем компьютере в момент посещения какой-либо страницы сайта. С его помощью определяется ваш браузер, также «cookie» помогает запоминать ваши настройки.', 'extrasport' ); ?></li>
	</ul>

	<p><?php esc_html_e( 'Мы используем персональную информацию:', 'extrasport' ); ?></p>
	<ul>
		<li><?php esc_html_e( 'чтобы повысить качество предоставляемых услуг;', 'extrasport' ); ?></li>
		<li><?php esc_html_e( 'чтобы установить с вами обратную связь, обработать вашу заявку;', 'extrasport' ); ?></li>
		<li><?php esc_html_e( 'чтобы улучшить функционал нашего сайта;', 'extrasport' ); ?></li>
		<li><?php esc_html_e( 'чтобы сообщить вам самые последние новости и сведения о новых акциях, посредством телекомутационных каналов связи, в т. ч. посредством СМС.', 'extrasport' ); ?></li>
	</ul>

	<p><?php esc_html_e( 'Мы защищаем персональную информацию:', 'extrasport' ); ?></p>
	<p><?php esc_html_e( 'Мы не раскрываем вашу персональную информацию третьим лицам. Персональные данные могут быть переданы лицам, не связанным с нашим сайтом, в том случае, если этого требует действующее законодательство Российской Федерации.', 'extrasport' ); ?></p>
	<p><?php esc_html_e( 'Для защиты ваших данных мы стараемся принять все необходимые меры: используем безопасный сервер, доступ ко всей информации имеет ограниченное число лиц.', 'extrasport' ); ?></p>

	<h2><?php esc_html_e( 'Сторонние организации', 'extrasport' ); ?></h2>
	<p><?php esc_html_e( 'Наш сайт может содержать ссылки на сайты сторонних организаций, неподконтрольных нам и имеющих собственную политику конфиденциальности. В связи с этим, мы не несем ответственности за действия данных ресурсов.', 'extrasport' ); ?></p>

	<h2><?php esc_html_e( 'Согласие пользователя', 'extrasport' ); ?></h2>
	<p><?php esc_html_e( 'Используя наш сайт, вы выражаете согласие с действующей политикой конфиденциальности и разрешаете обработку ваших персональных данных, согласно закону «О персональных данных» № 152 ФЗ РФ.', 'extrasport' ); ?></p>

	<?php if ( ! empty( $club['company_name'] ) ) : ?>
		<p><strong><?php echo esc_html( $club['company_name'] ); ?></strong></p>
	<?php endif; ?>
	<?php
	return (string) ob_get_clean();
}

/**
 * Load legal page HTML from the legacy Yii database when available.
 *
 * @param string $slug privacy|legal.
 * @return string
 */
function extrasport_get_yii_legal_page_content( $slug ) {
	if ( ! extrasport_is_yii_db_enabled() ) {
		return '';
	}

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return '';
	}

	$row = $yii->get_row( 'SELECT privacy, legal FROM club LIMIT 1' );
	if ( ! $row ) {
		return '';
	}

	$privacy = trim( (string) ( $row->privacy ?? '' ) );
	$legal   = trim( (string) ( $row->legal ?? '' ) );

	if ( 'privacy' === $slug ) {
		return $privacy ?: $legal;
	}

	if ( 'legal' === $slug ) {
		return $legal ?: $privacy;
	}

	return '';
}

/**
 * Resolved HTML content for a legal page.
 *
 * @param string $slug privacy|legal.
 * @return string
 */
function extrasport_get_legal_page_content( $slug ) {
	$slug = sanitize_key( $slug );

	$yii_content = extrasport_get_yii_legal_page_content( $slug );
	if ( $yii_content ) {
		return wp_kses_post( $yii_content );
	}

	if ( 'privacy' === $slug ) {
		return extrasport_get_default_privacy_content();
	}

	if ( 'legal' === $slug ) {
		return extrasport_get_default_legal_content();
	}

	return '';
}

/**
 * Default legal information HTML (matches production extrasport.ru /legal/).
 *
 * @return string
 */
function extrasport_get_default_legal_content() {
	return extrasport_get_default_privacy_content();
}

/**
 * Current legal page title.
 *
 * @return string
 */
function extrasport_get_current_legal_page_title() {
	$slug = extrasport_get_current_legal_page_slug();
	$defs = extrasport_get_legal_page_definitions();

	return $defs[ $slug ]['title'] ?? '';
}

/**
 * Allowed HTML for consent labels (privacy links in forms and cookie banner).
 *
 * @return array<string, array<string, bool>>
 */
function extrasport_get_form_consent_allowed_html() {
	return array(
		'a' => array(
			'href'   => array(),
			'target' => array(),
			'rel'    => array(),
			'class'  => array(),
		),
	);
}

/**
 * Privacy + marketing consent label for forms.
 *
 * @return string
 */
function extrasport_get_form_consent_label_html() {
	$url = esc_url( extrasport_get_club()['privacy_url'] );

	return wp_kses(
		sprintf(
			/* translators: %s: privacy policy URL */
			__( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer" class="form-consent__link">политикой конфиденциальности</a> и даю согласие на получение рекламной информации', 'extrasport' ),
			$url
		),
		extrasport_get_form_consent_allowed_html()
	);
}
