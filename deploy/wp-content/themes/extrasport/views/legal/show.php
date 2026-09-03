<?php
/**
 * Privacy / legal static page view.
 *
 * @package ExtraSport
 */

$slug    = extrasport_get_current_legal_page_slug();
$content = extrasport_get_legal_page_content( $slug );
$title   = extrasport_get_current_legal_page_title();
?>

<section class="page-section page-section--actions-list single-legal bg-brand-dark">
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
		<h1 class="section-heading legal-page__title mb-8 py-4 text-2xl font-semibold md:mb-10 md:text-3xl lg:text-4xl"><?php echo esc_html( $title ); ?></h1>

		<div class="legal-page__wrap mx-auto max-w-4xl">
			<div class="legal-page__content prose prose-invert max-w-none entry-content text-white">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered in extrasport_get_legal_page_content(). ?>
			</div>
		</div>
	</div>
</section>
