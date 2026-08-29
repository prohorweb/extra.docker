<?php
/**
 * Club rules modal
 *
 * @package ExtraSport
 */
?>

<div id="rules" class="modal" aria-hidden="true" role="dialog" aria-labelledby="rulesModalTitle">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--lg">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 id="rulesModalTitle" class="font-oswald text-xl uppercase mb-4 pr-8">
			<?php esc_html_e( 'Правила спортивного клуба «Экстра Спорт»', 'extrasport' ); ?>
		</h2>
		<div class="modal__scroll prose prose-invert max-w-none text-sm text-white/80 space-y-3 max-h-[60vh] overflow-y-auto pe-2">
			<p><?php esc_html_e( 'Настоящие Правила и техника безопасности фитнес-клуба «Экстра спорт» являются обязательными для выполнения всеми членами клуба.', 'extrasport' ); ?></p>
			<p><?php esc_html_e( 'Часы работы Клуба устанавливаются с 7.00 до 23.00 (вход в Клуб до 22.00). В праздничные и выходные дни с 09.00 до 22.00.', 'extrasport' ); ?></p>
			<p><?php esc_html_e( 'Пропуском в Клуб является клубная карта, которая оформляется только по предъявлению паспорта и после подписания договора с Клубом.', 'extrasport' ); ?></p>
			<p><?php esc_html_e( 'Членство в Клубе является персональным и не может быть передано или использовано другими лицами без переоформления клубной карты.', 'extrasport' ); ?></p>
			<p><?php esc_html_e( 'При первом посещении Клуба, Члену Клуба настоятельно рекомендуется пройти фитнес-тестирование и следовать рекомендациям инструкторов.', 'extrasport' ); ?></p>
			<p class="text-white/50 italic"><?php esc_html_e( 'Полный текст правил будет перенесён из legacy-системы на следующем этапе.', 'extrasport' ); ?></p>
		</div>
	</div>
</div>
