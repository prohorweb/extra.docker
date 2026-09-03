<?php
/**
 * 404 not found view.
 *
 * @package ExtraSport
 */
?>

<div class="page-content bg-brand-dark py-16 md:py-24">
	<div class="mx-auto max-w-2xl px-4 text-center lg:px-6">
		<h1 class="font-oswald mb-4 text-5xl uppercase text-brand-primary">404</h1>
		<p class="mb-8 text-lg text-white/80"><?php esc_html_e( 'Запрашиваемая страница не найдена.', 'extrasport' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary btn-lg inline-flex">
			<?php esc_html_e( 'На главную', 'extrasport' ); ?>
		</a>
	</div>
</div>
