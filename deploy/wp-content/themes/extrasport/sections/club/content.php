<?php
/**
 * Club overview content and technical stats.
 *
 * @package ExtraSport
 */

$club    = $args['club'] ?? extrasport_get_club();
$uri     = $args['uri'] ?? EXTRASPORT_URI;
$stats   = $args['stats'] ?? extrasport_get_club_stats();
$content = $args['content'] ?? extrasport_get_club_page_content();
$slides  = $args['slides'] ?? extrasport_get_club_gallery_slides();
?>

<section class="page-section page-section--actions page-section--actions-list page-section--club-content" id="actions">
	<div
		class="page-section__parallax-bg"
		style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
		aria-hidden="true"
	></div>

	<div class="page-section__inner relative z-10 mx-auto w-full max-w-7xl px-4 py-10 md:px-6 md:py-14">
		<h1 class="section-heading mb-4 md:mb-5">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Обзор клуба %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h1>

		<div class="club-content-panel">
			<?php
			get_template_part(
				'sections/club/gallery',
				null,
				array(
					'slides' => $slides,
				)
			);
			?>

			<?php if ( $content ) : ?>
				<div class="about-page__text break-words text-white/90">
					<?php echo wp_kses_post( $content ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $stats ) : ?>
				<div class="about-page__params mt-10 md:mt-14">
					<h2 class="about-page__params-title section-heading mb-8 md:mb-10">
						<?php esc_html_e( 'Технические характеристики клуба', 'extrasport' ); ?>
					</h2>

					<div class="about-page__params-grid">
						<?php foreach ( $stats as $stat ) : ?>
							<div class="about-page__params-item">
								<div class="param-block">
									<div class="param-block__num"><?php echo esc_html( $stat['num'] ); ?></div>
									<div class="param-block__text"><?php echo wp_kses_post( $stat['text'] ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
