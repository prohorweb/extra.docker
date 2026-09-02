<?php
/**
 * Primary navigation — Yii2 header layout port
 *
 * @package ExtraSport
 */

$club        = extrasport_get_club();
$is_home     = is_front_page();
$is_services = is_post_type_archive( 'service' );
$header_pos  = ( $is_home || $is_services ) ? 'site-header--overlay' : 'site-header--static';
$tel_clean   = preg_replace( '/\s+/', '', $club['tel'] );

$about_links     = extrasport_get_about_nav_links();
$about_external  = extrasport_get_about_external_links();

$nav_links = array(
	array( 'label' => 'Акции', 'url' => extrasport_get_shares_archive_url() ),
	array( 'label' => 'Услуги', 'url' => get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ),
	array( 'label' => 'Абонементы и цены', 'url' => extrasport_get_card_type_url() ),
	array( 'label' => 'Контакты', 'url' => home_url( '/#contacts' ) ),
);

$about_active = extrasport_is_about_nav_active( $about_links );
?>

<header id="mainNav" class="site-header <?php echo esc_attr( $header_pos ); ?>" aria-label="<?php esc_attr_e( 'Site header', 'extrasport' ); ?>">
	<div class="site-header__inner">
		<div class="site-header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo-link">
				<?php echo extrasport_render_brand_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>

		<div class="site-header__nav hidden xl:flex">
			<div class="site-header__club-info">
				<i class="fa-solid fa-location-dot text-brand-primary" aria-hidden="true"></i>
				<span class="site-header__club-label"><?php esc_html_e( 'Ваш клуб:', 'extrasport' ); ?></span>
				<button type="button" class="site-header__club-link" data-modal-open="clubModal">
					<?php echo esc_html( $club['address'] ); ?>
					<i class="fa-solid fa-chevron-down ms-1 text-xs" aria-hidden="true"></i>
				</button>
				<a href="tel:<?php echo esc_attr( $tel_clean ); ?>" class="site-header__club-phone">
					<?php echo esc_html( $club['tel'] ); ?>
				</a>
			</div>

			<nav class="site-header__nav-list" aria-label="<?php esc_attr_e( 'Main navigation', 'extrasport' ); ?>">
				<div class="site-header__nav-item group relative">
					<button type="button" class="site-header__nav-link flex items-center gap-1<?php echo $about_active ? ' is-active' : ''; ?>">
						<?php esc_html_e( 'О клубе', 'extrasport' ); ?>
						<i class="fa-solid fa-chevron-down text-[0.65rem]" aria-hidden="true"></i>
					</button>
					<ul class="site-header__dropdown absolute left-0 top-full z-50 hidden min-w-[220px] py-2 group-hover:block">
						<?php foreach ( $about_links as $link ) : ?>
							<li>
								<a href="<?php echo esc_url( $link['url'] ); ?>" class="<?php echo esc_attr( extrasport_nav_item_class( 'site-header__dropdown-link', $link['url'] ) ); ?>">
									<?php echo esc_html( $link['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
						<?php if ( ! empty( $about_external ) ) : ?>
							<li class="site-header__dropdown-divider" aria-hidden="true"></li>
							<?php foreach ( $about_external as $link ) : ?>
								<li>
									<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="site-header__dropdown-link">
										<?php echo esc_html( $link['label'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<?php foreach ( $nav_links as $link ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>" class="<?php echo esc_attr( extrasport_nav_item_class( 'site-header__nav-link', $link['url'] ) ); ?>">
						<?php echo esc_html( $link['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>

		<div class="site-header__bottom">
			<div class="site-header__actions">
				<button type="button" class="btn-outline-primary hidden md:inline-flex" data-modal-open="callModal">
					<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
					<?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?>
				</button>

				<button type="button" id="navToggle" class="site-header__burger flex items-center gap-2 xl:hidden" aria-expanded="false" aria-controls="mobileNav">
					<span class="font-oswald text-sm uppercase tracking-wider"><?php esc_html_e( 'Меню', 'extrasport' ); ?></span>
					<span class="flex flex-col gap-1" aria-hidden="true">
						<span class="block h-0.5 w-5 bg-white"></span>
						<span class="block h-0.5 w-5 bg-white"></span>
						<span class="block h-0.5 w-5 bg-white"></span>
					</span>
				</button>
			</div>
		</div>
	</div>
</header>

<!-- Mobile / offcanvas menu -->
<div id="mobileNav" class="mobile-nav hidden" aria-hidden="true">
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

		<div class="border-b border-white/10 px-4 py-3 text-sm xl:hidden">
			<button type="button" class="flex items-center gap-2 text-brand-primary hover:text-white" data-modal-open="clubModal">
				<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
				<?php echo esc_html( $club['address'] ); ?>
				<i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
			</button>
			<a href="tel:<?php echo esc_attr( $tel_clean ); ?>" class="mt-2 block text-brand-primary hover:text-white">
				<?php echo esc_html( $club['tel'] ); ?>
			</a>
		</div>

		<ul class="flex-1 overflow-y-auto px-4 py-6 font-oswald uppercase tracking-wide">
			<li class="mb-2">
				<button type="button" class="nav-dropdown-toggle flex w-full items-center justify-between py-3 text-white hover:text-brand-primary<?php echo $about_active ? ' is-active' : ''; ?>" aria-expanded="false" data-dropdown="aboutMenu">
					<?php esc_html_e( 'О клубе', 'extrasport' ); ?>
					<i class="fa-solid fa-chevron-down text-xs transition-transform" aria-hidden="true"></i>
				</button>
				<ul id="aboutMenu" class="nav-dropdown hidden pb-2 space-y-1 normal-case font-roboto text-sm">
					<?php foreach ( $about_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>" class="<?php echo esc_attr( extrasport_nav_item_class( 'block py-2 text-white/80 hover:text-brand-primary', $link['url'] ) ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
					<?php if ( ! empty( $about_external ) ) : ?>
						<?php foreach ( $about_external as $index => $link ) : ?>
							<li class="<?php echo 0 === $index ? 'border-t border-white/10 pt-2 mt-2' : ''; ?>">
								<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="block py-2 text-white/80 hover:text-brand-primary"><?php echo esc_html( $link['label'] ); ?></a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</li>
			<?php foreach ( $nav_links as $link ) : ?>
				<li>
					<a href="<?php echo esc_url( $link['url'] ); ?>" class="<?php echo esc_attr( extrasport_nav_item_class( 'block py-3 text-white hover:text-brand-primary', $link['url'] ) ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
				</li>
			<?php endforeach; ?>
			<li class="mt-4 md:hidden">
				<button type="button" class="btn-outline-primary w-full justify-center" data-modal-open="callModal">
					<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
					<?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?>
				</button>
			</li>
		</ul>
	</div>
</div>
