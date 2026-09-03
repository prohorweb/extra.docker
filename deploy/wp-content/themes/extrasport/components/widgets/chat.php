<?php
/**
 * Floating chat widget
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$chat = array(
	array(
		'url'   => $club['whatsapp'],
		'title' => 'WhatsApp',
		'icon'  => EXTRASPORT_URI . '/assets/img/chat/wa.png',
	),
	array(
		'url'   => $club['vk'],
		'title' => 'VK',
		'icon'  => EXTRASPORT_URI . '/assets/img/chat/vk.png',
	),
	array(
		'url'   => $club['telegram'],
		'title' => 'Telegram',
		'icon'  => EXTRASPORT_URI . '/assets/img/chat/tg.png',
	),
);
?>

<div class="chat-24 fixed bottom-6 right-6 z-40">
	<button type="button" class="chat-24__button js-chat-show relative flex h-14 w-14 items-center justify-center rounded-full bg-brand-primary text-white shadow-lg hover:scale-105 transition" aria-label="<?php esc_attr_e( 'Open chat', 'extrasport' ); ?>">
		<i class="fa-solid fa-comments text-xl" aria-hidden="true"></i>
	</button>
	<div class="chat-24__content absolute bottom-16 right-0 hidden min-w-[200px] rounded-xl bg-brand-dark border border-white/10 p-3 shadow-2xl">
		<div class="chat-24__text mb-2 text-center text-xs text-white/60 min-h-[1rem]"></div>
		<div class="flex flex-col gap-2">
			<?php foreach ( $chat as $item ) : ?>
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>" class="chat-24__item flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-white/5" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $item['icon'] ); ?>" alt="" width="28" height="28" class="rounded-full">
						<span class="text-sm text-white/90"><?php echo esc_html( $item['title'] ); ?></span>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="chat-24__close js-chat-hide mt-2 w-full text-center text-xs text-white/50 hover:text-white">
			<?php esc_html_e( 'Закрыть', 'extrasport' ); ?>
		</button>
	</div>
</div>
