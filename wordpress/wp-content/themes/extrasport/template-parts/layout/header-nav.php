<?php
/**
 * Primary navigation — Tailwind port of layouts/header.php
 *
 * @package ExtraSport
 */

$club     = extrasport_get_club();
$nav_pos  = is_front_page() ? 'absolute top-0 left-0' : 'relative';
$logo_uri = EXTRASPORT_URI . '/assets/img/logo.svg';

$about_links = array(
	array( 'label' => 'Обзор клуба', 'url' => home_url( '/club/' ) ),
	array( 'label' => 'Тренеры', 'url' => home_url( '/trainers/' ) ),
	array( 'label' => 'Новости', 'url' => home_url( '/news/' ) ),
	array( 'label' => 'Мероприятия', 'url' => home_url( '/events/' ) ),
	array( 'label' => 'Вакансии', 'url' => home_url( '/jobs/' ) ),
);

$nav_links = array(
	array( 'label' => 'Акции', 'url' => get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' ) ),
	array( 'label' => 'Услуги', 'url' => get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ),
	array( 'label' => 'Абонементы и цены', 'url' => get_post_type_archive_link( 'group_program' ) ?: home_url( '/services/programs/' ) ),
	array( 'label' => 'Контакты', 'url' => home_url( '/#contacts' ) ),
);
?>

<nav id="mainNav" class="main-nav <?php echo esc_attr( $nav_pos ); ?> z-40 w-full bg-black/80 backdrop-blur-sm" aria-label="<?php esc_attr_e( 'Main navigation', 'extrasport' ); ?>">
	<div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 lg:px-6">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="shrink-0">
			<img src="<?php echo esc_url( $logo_uri ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-10 w-auto lg:h-12">
		</a>

		<!-- Desktop: club info bar -->
		<div class="hidden lg:flex flex-1 items-center justify-center gap-6 text-sm text-white/90">
			<div class="flex items-center gap-1">
				<i class="fa-solid fa-location-dot text-brand-primary" aria-hidden="true"></i>
				<span class="hidden xl:inline"><?php esc_html_e( 'Ваш клуб:', 'extrasport' ); ?></span>
				<button type="button" class="hover:text-brand-primary underline-offset-2 hover:underline" data-modal-open="clubModal">
					<?php echo esc_html( $club['address'] ); ?>
					<i class="fa-solid fa-chevron-down ms-1 text-xs" aria-hidden="true"></i>
				</button>
			</div>
			<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $club['tel'] ) ); ?>" class="hover:text-brand-primary">
				<?php echo esc_html( $club['tel'] ); ?>
			</a>
		</div>

		<!-- Tablet call button -->
		<button type="button" class="btn-primary hidden md:inline-flex lg:hidden" data-modal-open="callModal">
			<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
			<?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?>
		</button>

		<!-- Mobile: call + burger -->
		<div class="flex items-center gap-2 md:hidden">
			<button type="button" class="btn-primary btn-sm" data-modal-open="callModal">
				<i class="fa-solid fa-phone-volume me-1" aria-hidden="true"></i>
				<span class="sr-only"><?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?></span>
				<span aria-hidden="true"><?php esc_html_e( 'Звонок', 'extrasport' ); ?></span>
			</button>
			<button type="button" id="navToggle" class="flex items-center gap-2 uppercase text-sm font-oswald tracking-wider text-white" aria-expanded="false" aria-controls="mobileNav">
				<?php esc_html_e( 'Меню', 'extrasport' ); ?>
				<span class="flex flex-col gap-1" aria-hidden="true">
					<span class="block h-0.5 w-5 bg-white"></span>
					<span class="block h-0.5 w-5 bg-white"></span>
					<span class="block h-0.5 w-5 bg-white"></span>
				</span>
			</button>
		</div>

		<!-- Desktop call button -->
		<button type="button" class="btn-primary hidden lg:inline-flex" data-modal-open="callModal">
			<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
			<?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?>
		</button>
	</div>
</nav>

<!-- Mobile / offcanvas menu -->
<div id="mobileNav" class="mobile-nav fixed inset-0 z-50 hidden" aria-hidden="true">
	<div class="mobile-nav__backdrop absolute inset-0 bg-black/60" data-nav-close></div>
	<div class="mobile-nav__panel absolute inset-y-0 right-0 flex w-full max-w-sm flex-col bg-brand-dark shadow-2xl">

		<div class="flex items-center justify-between border-b border-white/10 px-4 py-4">
			<button type="button" class="font-oswald text-lg uppercase text-white hover:text-brand-primary" data-modal-open="clubModal">
				<?php echo esc_html( $club['title'] ); ?>
			</button>
			<button type="button" class="text-white/70 hover:text-white p-2" data-nav-close aria-label="<?php esc_attr_e( 'Close menu', 'extrasport' ); ?>">
				<i class="fa-solid fa-xmark text-xl" aria-hidden="true"></i>
			</button>
		</div>

		<div class="border-b border-white/10 px-4 py-3 text-sm lg:hidden">
			<button type="button" class="flex items-center gap-2 text-white/90 hover:text-brand-primary" data-modal-open="clubModal">
				<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
				<?php echo esc_html( $club['address'] ); ?>
				<i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
			</button>
			<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $club['tel'] ) ); ?>" class="mt-2 block text-white/90 hover:text-brand-primary">
				<?php echo esc_html( $club['tel'] ); ?>
			</a>
		</div>

		<ul class="flex-1 overflow-y-auto px-4 py-6 font-oswald uppercase tracking-wide">
			<li class="mb-2">
				<button type="button" class="nav-dropdown-toggle flex w-full items-center justify-between py-3 text-white hover:text-brand-primary" aria-expanded="false" data-dropdown="aboutMenu">
					<?php esc_html_e( 'О клубе', 'extrasport' ); ?>
					<i class="fa-solid fa-chevron-down text-xs transition-transform" aria-hidden="true"></i>
				</button>
				<ul id="aboutMenu" class="nav-dropdown hidden ps-4 pb-2 space-y-1 normal-case font-roboto text-sm">
					<?php foreach ( $about_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>" class="block py-2 text-white/80 hover:text-brand-primary"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
					<li class="border-t border-white/10 pt-2 mt-2">
						<a href="<?php echo esc_url( $club['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="block py-2 text-white/80 hover:text-brand-primary"><?php esc_html_e( 'Истории успеха', 'extrasport' ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( $club['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="block py-2 text-white/80 hover:text-brand-primary"><?php esc_html_e( 'Советы тренеров', 'extrasport' ); ?></a>
					</li>
				</ul>
			</li>
			<?php foreach ( $nav_links as $link ) : ?>
				<li>
					<a href="<?php echo esc_url( $link['url'] ); ?>" class="block py-3 text-white hover:text-brand-primary"><?php echo esc_html( $link['label'] ); ?></a>
				</li>
			<?php endforeach; ?>
			<li class="mt-4 md:hidden">
				<button type="button" class="btn-primary w-full justify-center" data-modal-open="callModal">
					<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
					<?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?>
				</button>
			</li>
		</ul>
	</div>
</div>

<!-- Desktop horizontal menu (large screens) -->
<div class="hidden lg:block border-b border-white/10 bg-black/90">
	<div class="mx-auto flex max-w-7xl justify-center gap-8 px-6 py-2 font-oswald text-sm uppercase tracking-wide">
		<div class="relative group">
			<button type="button" class="py-2 text-white hover:text-brand-primary flex items-center gap-1">
				<?php esc_html_e( 'О клубе', 'extrasport' ); ?>
				<i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
			</button>
			<ul class="absolute left-0 top-full z-50 hidden min-w-[220px] rounded-md bg-brand-dark py-2 shadow-xl group-hover:block border border-white/10">
				<?php foreach ( $about_links as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>" class="block px-4 py-2 text-white/90 hover:bg-white/5 hover:text-brand-primary normal-case font-roboto"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php foreach ( $nav_links as $link ) : ?>
			<a href="<?php echo esc_url( $link['url'] ); ?>" class="py-2 text-white hover:text-brand-primary"><?php echo esc_html( $link['label'] ); ?></a>
		<?php endforeach; ?>
	</div>
</div>
