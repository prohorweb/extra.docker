<?php
/**
 * Subscribe / test-drive section (reusable)
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$uri  = EXTRASPORT_URI;
?>

<section id="subscribe" class="relative overflow-hidden bg-brand-dark py-16">
	<video muted loop autoplay playsinline class="absolute inset-0 h-full w-full object-cover opacity-30 hidden md:block" aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/test-drive.mp4' ); ?>" type="video/mp4">
	</video>
	<video muted loop autoplay playsinline class="absolute inset-0 h-full w-full object-cover opacity-30 md:hidden" aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/test-drive_mobile.mp4' ); ?>" type="video/mp4">
	</video>
	<div class="relative z-10 mx-auto max-w-xl px-4">
		<h2 class="font-oswald mb-6 text-center text-3xl uppercase"><?php esc_html_e( 'Запишитесь на пробную тренировку', 'extrasport' ); ?></h2>
		<form id="subscribe" class="space-y-4 rounded-xl border border-white/10 bg-black/60 p-6 backdrop-blur" action="#" method="post" novalidate>
			<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
			<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
			<div class="flex items-start gap-2 text-sm">
				<input type="checkbox" name="accept" id="soglas-subscribe-inner" class="mt-1">
				<label for="soglas-subscribe-inner">
					<?php
					printf(
						wp_kses_post( __( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer" class="underline hover:text-brand-primary">политикой конфиденциальности</a>', 'extrasport' ) ),
						esc_url( $club['privacy_url'] )
					);
					?>
				</label>
			</div>
			<div class="form-error hidden text-sm text-red-400" role="alert"></div>
			<button type="submit" class="btn-primary btn-lg w-full justify-center uppercase"><?php esc_html_e( 'Записаться', 'extrasport' ); ?></button>
		</form>
	</div>
</section>
