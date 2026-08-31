<?php
/**
 * Membership plan card.
 *
 * @package ExtraSport
 *
 * @var array{title: string, price: string, video: int, modal_id: string} $plan Plan data.
 * @var string                                                          $uri  Theme URI.
 */

$plan = $args['plan'] ?? null;
$uri  = $args['uri'] ?? EXTRASPORT_URI;

if ( empty( $plan ) ) {
	return;
}

$video_src = $uri . '/assets/video/card-bg-' . (int) $plan['video'] . '.mp4';
$card_mod  = 'cardModal3' === ( $plan['modal_id'] ?? '' ) ? ' membership-card--12-months' : '';
?>

<div class="membership-card<?php echo esc_attr( $card_mod ); ?>">
	<div class="membership-card__box">
		<div class="membership-card__media">
			<div class="membership-card__header">
				<div class="membership-card__spacer" aria-hidden="true"></div>
				<div class="membership-card__row">
					<div class="membership-card__month"><?php echo esc_html( $plan['title'] ); ?></div>
					<img class="membership-card__logo" src="<?php echo esc_url( $uri . '/assets/images/logo-short.svg' ); ?>" alt="">
					<div class="membership-card__price"><?php echo esc_html( $plan['price'] ); ?></div>
				</div>
				<div class="membership-card__cta-area">
					<button type="button" class="membership-card__cta btn-primary btn-lg justify-center" data-modal-open="<?php echo esc_attr( $plan['modal_id'] ); ?>">
						<i class="fa-sharp fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
						<?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?>
					</button>
				</div>
			</div>
			<video class="membership-card__video" muted loop autoplay playsinline aria-hidden="true">
				<source src="<?php echo esc_url( $video_src ); ?>" type="video/mp4">
			</video>
		</div>
	</div>
</div>
