<?php
/**
 * Test-drive section — same layout on all pages, form context varies by route.
 *
 * @package ExtraSport
 *
 * @var array<string, mixed> $club    Club settings.
 * @var string               $uri     Theme URI.
 * @var array<string, string> $context form_id, form_type, source_url.
 */

$club    = $args['club'] ?? extrasport_get_club();
$uri     = $args['uri'] ?? EXTRASPORT_URI;
$context = $args['context'] ?? extrasport_get_test_drive_form_context();

$form_id     = sanitize_key( $context['form_id'] ?? 'test-drive' );
$form_type   = sanitize_key( $context['form_type'] ?? 'test_drive' );
$source_url  = esc_url( $context['source_url'] ?? '' );
$accept_id = 'soglas-' . $form_id;
?>

<section id="contact" class="page-section page-section--centered page-section--test-drive page-section--h-75 test-drive relative">
	<video class="absolute inset-0 hidden h-full w-full object-cover md:block" muted autoplay loop playsinline aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/test-drive.mp4' ); ?>" type="video/mp4">
	</video>
	<video class="absolute inset-0 block h-full w-full object-cover md:hidden" muted autoplay loop playsinline aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/test-drive_mobile.mp4' ); ?>" type="video/mp4">
	</video>
	<div class="absolute inset-0 bg-black/60" aria-hidden="true"></div>

	<div class="page-section__inner relative z-10">
		<h2 class="font-oswald mb-10 text-center text-3xl uppercase md:text-4xl">
			<?php esc_html_e( 'Фитнес тест-драйв', 'extrasport' ); ?>
		</h2>
		<div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
			<div class="space-y-4 text-lg text-white/90">
				<p><?php esc_html_e( 'Хотите больше узнать о нашем клубе? Оставьте заявку, и наши менеджеры проведут для вас подробную экскурсию.', 'extrasport' ); ?></p>
				<p><?php esc_html_e( 'Для тех, кому экскурсии мало, мы предлагаем услугу «фитнес тест-драйв» — безлимитную неделю фитнеса!', 'extrasport' ); ?></p>
			</div>
			<form
				id="<?php echo esc_attr( $form_id ); ?>"
				class="mx-auto w-full max-w-md space-y-4"
				action="#"
				method="post"
				novalidate
				data-form-type="subscribe"
				data-form-variant="<?php echo esc_attr( $form_type ); ?>"
			>
				<?php get_template_part( 'components/form', 'honeypot', array( 'form_id' => $form_id ) ); ?>
				<input type="hidden" name="source_url" value="<?php echo esc_attr( $source_url ); ?>">
				<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
				<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
				<div class="flex items-start gap-2 text-sm">
					<input type="checkbox" name="accept" id="<?php echo esc_attr( $accept_id ); ?>" class="mt-1">
					<label for="<?php echo esc_attr( $accept_id ); ?>">
						<?php
						printf(
							wp_kses_post( __( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer" class="underline hover:text-brand-primary">политикой конфиденциальности</a>', 'extrasport' ) ),
							esc_url( $club['privacy_url'] )
						);
						?>
					</label>
				</div>
				<div class="form-error hidden text-sm text-red-400" role="alert"></div>
				<button type="submit" class="btn-primary btn-lg w-full justify-center uppercase">
					<?php esc_html_e( 'Записаться', 'extrasport' ); ?>
				</button>
			</form>
		</div>
	</div>
</section>
