<?php
/**
 * Club overview gallery carousel (Yii about-page carousel).
 *
 * @package ExtraSport
 */

$slides = $args['slides'] ?? extrasport_get_club_gallery_slides();

if ( empty( $slides ) ) {
	return;
}
?>

<div class="about-page about-page--in-content">
	<div
		class="carousel carousel--about carousel--about-content"
		data-carousel
		data-carousel-interval="3000"
		id="carouselAboutFade"
	>
		<div class="carousel-track">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<div
					class="carousel-slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
					data-carousel-slide
				>
					<img
						class="about-page__gallery-img"
						src="<?php echo esc_url( $slide['url'] ); ?>"
						alt="<?php echo esc_attr( $slide['alt'] ); ?>"
						loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
					>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
