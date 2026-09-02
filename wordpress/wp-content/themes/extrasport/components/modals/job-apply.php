<?php
/**
 * Job application modal.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<div id="jobApplyModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="jobApplyModalTitle">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--sm modal__scroll max-h-[90vh] overflow-y-auto">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>

		<h2 id="jobApplyModalTitle" class="font-oswald mb-2 text-center text-xl uppercase md:text-2xl">
			<?php esc_html_e( 'Откликнуться на вакансию', 'extrasport' ); ?>
		</h2>
		<p class="job-apply__desc mb-6 text-center text-sm text-white/80">
			<?php esc_html_e( 'Пожалуйста, заполните форму, наш менеджер свяжется с вами.', 'extrasport' ); ?>
		</p>

		<form id="jobApplyForm" class="space-y-4" action="#" method="post" enctype="multipart/form-data" novalidate data-form-type="job_apply">
			<?php get_template_part( 'components/form', 'honeypot', array( 'form_id' => 'jobApplyForm' ) ); ?>
			<input type="hidden" name="form_token" value="<?php echo esc_attr( extrasport_create_form_token() ); ?>">
			<input type="hidden" name="title" value="">

			<div class="form-group">
				<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
			</div>
			<div class="form-group">
				<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
			</div>
			<div class="form-group">
				<input type="file" name="rezume" id="jobApplyResume" class="sr-only" accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
				<label for="jobApplyResume" class="btn-secondary job-apply__upload-label mt-1 inline-flex cursor-pointer">
					<?php esc_html_e( 'Прикрепить резюме *', 'extrasport' ); ?>
				</label>
				<div class="job-apply__file-name mt-2 text-sm text-white/70"></div>
				<p class="mt-2 text-xs text-white/60">
					<?php esc_html_e( 'Формат *.pdf или *.docx. Вес файла не более 100 Кб', 'extrasport' ); ?>
				</p>
			</div>
			<div class="form-group flex items-start gap-2 text-sm">
				<input type="checkbox" name="accept" id="soglas-job-apply" class="mt-1">
				<label for="soglas-job-apply">
					<?php
					printf(
						/* translators: %s: privacy policy URL */
						wp_kses_post( __( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer">политикой конфиденциальности</a>', 'extrasport' ) ),
						esc_url( $club['privacy_url'] )
					);
					?>
				</label>
			</div>
			<div class="form-error hidden text-sm text-red-400" role="alert"></div>
			<button type="submit" class="btn-primary btn-lg w-full justify-center">
				<?php esc_html_e( 'Отправить', 'extrasport' ); ?>
				<i class="fa-sharp fa-solid fa-angles-right ms-2" aria-hidden="true"></i>
			</button>
		</form>
	</div>
</div>
