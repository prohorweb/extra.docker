<?php
/**
 * Site footer — visible block
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$tel  = preg_replace( '/\s+/', '', $club['tel'] );
?>

<footer class="site-footer bg-black border-t border-white/10">
	<div class="mx-auto max-w-7xl px-4 py-12 lg:px-6">
		<div class="grid gap-10 lg:grid-cols-2">

			<!-- Contacts -->
			<div class="grid gap-8 sm:grid-cols-2">
				<ul class="space-y-4 text-white/90">
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-mobile-screen text-xl text-brand-primary mt-1" aria-hidden="true"></i>
						<a href="tel:<?php echo esc_attr( $tel ); ?>" class="text-xl hover:text-brand-primary"><?php echo esc_html( $club['tel'] ); ?></a>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-regular fa-envelope text-brand-primary mt-1" aria-hidden="true"></i>
						<a href="mailto:<?php echo esc_attr( $club['email'] ); ?>" class="hover:text-brand-primary"><?php echo esc_html( $club['email'] ); ?></a>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-location-dot text-brand-primary mt-1" aria-hidden="true"></i>
						<span><?php echo esc_html( $club['address'] ); ?></span>
					</li>
				</ul>

				<ul class="hidden xl:block space-y-4 text-white/90 text-sm">
					<li class="flex items-start gap-3">
						<i class="fa-regular fa-clock text-brand-primary mt-1" aria-hidden="true"></i>
						<div>
							<?php esc_html_e( 'Время работы', 'extrasport' ); ?><br>
							<?php esc_html_e( 'пн–пт:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work'] ); ?><br>
							<?php esc_html_e( 'сб–вс:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work_weekend'] ); ?>
						</div>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-user-tie text-brand-primary mt-1" aria-hidden="true"></i>
						<div>
							<?php esc_html_e( 'Отдел продаж:', 'extrasport' ); ?><br>
							<?php esc_html_e( 'пн-вс: 10:00 до 22:00', 'extrasport' ); ?>
						</div>
					</li>
				</ul>

				<div class="sm:col-span-2 flex items-center gap-4 text-sm">
					<span><?php esc_html_e( 'Мы в:', 'extrasport' ); ?></span>
					<a href="<?php echo esc_url( $club['vk'] ); ?>" target="_blank" rel="noopener noreferrer" class="text-2xl text-white/80 hover:text-brand-primary" aria-label="VK">
						<i class="fa-brands fa-vk" aria-hidden="true"></i>
					</a>
					<a href="<?php echo esc_url( $club['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="text-2xl text-white/80 hover:text-brand-primary" aria-label="YouTube">
						<i class="fa-brands fa-youtube" aria-hidden="true"></i>
					</a>
				</div>
			</div>

			<!-- CTA + App stores -->
			<div class="flex flex-col items-center justify-center gap-8 lg:flex-row lg:justify-end">
				<button type="button" class="btn-primary btn-lg" data-modal-open="callModal">
					<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
					<?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?>
				</button>

				<div class="flex flex-col gap-4">
					<?php if ( ! empty( $club['url_appstore'] ) ) : ?>
						<a href="<?php echo esc_url( $club['url_appstore'] ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-end gap-3 text-white/90 hover:text-white">
							<p class="text-end text-sm m-0"><?php esc_html_e( 'Загрузите в', 'extrasport' ); ?><br><strong>APP STORE</strong></p>
							<i class="fa-brands fa-app-store-ios text-3xl" aria-hidden="true"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $club['url_googleplay'] ) ) : ?>
						<a href="<?php echo esc_url( $club['url_googleplay'] ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-end gap-3 text-white/90 hover:text-white">
							<p class="text-end text-sm m-0"><?php esc_html_e( 'Доступно в', 'extrasport' ); ?><br><strong>GOOGLE PLAY</strong></p>
							<i class="fa-brands fa-google-play text-3xl" aria-hidden="true"></i>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="border-t border-white/10 py-4">
		<div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 text-sm text-white/60 md:flex-row md:justify-between lg:px-6">
			<p class="m-0">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> ExtraSport, LLC</p>
			<div class="flex flex-wrap gap-4">
				<button type="button" class="hover:text-white" data-modal-open="rules">
					<?php esc_html_e( 'Правила поведения в клубе', 'extrasport' ); ?>
				</button>
				<span aria-hidden="true">|</span>
				<a href="<?php echo esc_url( $club['legal_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-white">
					<?php esc_html_e( 'Правовая информация', 'extrasport' ); ?>
				</a>
			</div>
		</div>
	</div>
</footer>
