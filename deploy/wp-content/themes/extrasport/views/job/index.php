<?php
/**
 * Jobs archive view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$jobs = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$jobs[] = extrasport_normalize_job_post( get_post() );
	}
}
?>

<section class="page-section page-section--actions-list page-section--jobs bg-brand-dark">
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
		<h1 class="section-heading mb-4 py-4 text-2xl md:mb-5 md:text-3xl lg:text-4xl">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Вакансии клуба %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h1>

		<?php if ( ! empty( $jobs ) ) : ?>
			<h2 class="careers-page__subtitle mb-6 text-xl font-semibold md:mb-8 md:text-2xl">
				<?php esc_html_e( 'Открытые вакансии:', 'extrasport' ); ?>
			</h2>

			<div class="careers-page__grid">
				<?php foreach ( $jobs as $job ) : ?>
					<div class="careers-page__item">
						<div class="career-item">
							<h3 class="career-item__title"><?php echo esc_html( $job['title'] ); ?></h3>
							<button
								type="button"
								class="btn-primary btn-lg career-item__button"
								data-modal-open="<?php echo esc_attr( $job['modal_id'] ); ?>"
							>
								<?php esc_html_e( 'Информация о вакансии', 'extrasport' ); ?>
								<i class="fa-sharp fa-solid fa-angles-right ms-2" aria-hidden="true"></i>
							</button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<?php
			$seo = extrasport_get_jobs_archive_seo();
			$text = trim( (string) ( $seo['text'] ?? '' ) );
			?>
			<h2 class="careers-page__subtitle mb-6 text-xl font-semibold md:mb-8 md:text-2xl">
				<?php esc_html_e( 'Вакансий нет', 'extrasport' ); ?>
			</h2>
			<?php if ( $text ) : ?>
				<div class="careers-page__empty prose prose-invert max-w-none text-white">
					<?php echo wp_kses_post( $text ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>

<?php foreach ( $jobs as $job ) : ?>
	<div
		id="<?php echo esc_attr( $job['modal_id'] ); ?>"
		class="modal"
		aria-hidden="true"
		role="dialog"
		aria-labelledby="<?php echo esc_attr( $job['modal_id'] ); ?>Title"
	>
		<div class="modal__backdrop" data-modal-close></div>
		<div class="modal__panel modal__panel--lg modal__scroll max-h-[90vh] overflow-y-auto">
			<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
				<i class="fa-solid fa-xmark" aria-hidden="true"></i>
			</button>
			<h2 id="<?php echo esc_attr( $job['modal_id'] ); ?>Title" class="job-modal__title mb-4 text-xl font-semibold uppercase md:text-2xl">
				<?php echo esc_html( $job['title'] ); ?>
				<span class="job-modal__club"><?php echo esc_html( $club['title'] ); ?></span>
			</h2>
			<div class="job-modal__content prose prose-invert max-w-none mb-8 text-white">
				<?php echo $job['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered via the_content. ?>
			</div>
			<button
				type="button"
				class="btn-primary btn-lg job-modal__apply"
				data-job-apply
				data-job-title="<?php echo esc_attr( $job['title'] ); ?>"
			>
				<?php esc_html_e( 'Откликнуться', 'extrasport' ); ?>
				<i class="fa-sharp fa-solid fa-angles-right ms-2" aria-hidden="true"></i>
			</button>
		</div>
	</div>
<?php endforeach; ?>

<?php
if ( ! empty( $jobs ) ) {
	get_template_part( 'components/modals/job-apply' );
}
