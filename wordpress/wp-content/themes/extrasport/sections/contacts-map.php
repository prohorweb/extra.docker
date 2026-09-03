<?php
/**
 * Front page contacts + Yandex map section.
 *
 * @package ExtraSport
 */

$club      = $args['club'] ?? extrasport_get_club();
$brand     = $args['brand'] ?? extrasport_get_brand();
$uri       = $args['uri'] ?? EXTRASPORT_URI;
$tel_clean = $args['tel_clean'] ?? preg_replace( '/\s+/', '', $club['tel'] );
?>

<section id="contacts" class="page-section map-section relative bg-brand-dark bg-contain bg-no-repeat" style="background-image: url('<?php echo esc_url( $uri . '/assets/img/bg_contact.jpeg' ); ?>')">
	<div class="pointer-events-none absolute inset-0 bg-brand-dark/75" aria-hidden="true"></div>
	<div class="map-section__content relative z-10 mx-auto w-full max-w-7xl px-4 lg:px-6">
		<div class="max-w-md py-12 lg:py-0">
			<h2 class="font-oswald mb-8 text-3xl uppercase"><?php esc_html_e( 'Контакты', 'extrasport' ); ?></h2>
			<ul class="space-y-5 text-white/90">
				<li class="flex items-start gap-3">
					<i class="fa-solid fa-mobile-screen text-xl text-brand-primary mt-0.5" aria-hidden="true"></i>
					<a href="tel:<?php echo esc_attr( $tel_clean ); ?>" class="text-xl hover:text-brand-primary"><?php echo esc_html( $club['tel'] ); ?></a>
				</li>
				<li class="flex items-start gap-3">
					<i class="fa-regular fa-envelope text-brand-primary mt-0.5" aria-hidden="true"></i>
					<a href="mailto:<?php echo esc_attr( $club['email'] ); ?>" class="hover:text-brand-primary"><?php echo esc_html( $club['email'] ); ?></a>
				</li>
				<li class="flex items-start gap-3">
					<i class="fa-solid fa-location-dot text-brand-primary mt-0.5" aria-hidden="true"></i>
					<span><?php echo esc_html( $club['address'] ); ?></span>
				</li>
				<li class="flex items-start gap-3">
					<i class="fa-solid fa-train-subway text-brand-primary mt-0.5" aria-hidden="true"></i>
					<span><?php echo esc_html( $club['metro'] ); ?></span>
				</li>
				<li class="flex items-start gap-3">
					<i class="fa-regular fa-clock text-brand-primary mt-0.5" aria-hidden="true"></i>
					<div class="text-sm">
						<?php esc_html_e( 'Время работы', 'extrasport' ); ?><br>
						<?php esc_html_e( 'пн–пт:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work'] ); ?><br>
						<?php esc_html_e( 'сб–вс:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work_weekend'] ); ?>
					</div>
				</li>
				<li class="flex items-start gap-3">
					<i class="fa-solid fa-user-tie text-brand-primary mt-0.5" aria-hidden="true"></i>
					<div class="text-sm">
						<?php esc_html_e( 'Отдел продаж:', 'extrasport' ); ?><br>
						<?php esc_html_e( 'пн-вс:', 'extrasport' ); ?> <?php echo esc_html( $club['sales_work'] ); ?>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div
		id="map"
		class="map-section__map relative min-h-[75vh] w-full lg:absolute lg:inset-y-0 lg:right-0 lg:min-h-0 lg:h-full lg:w-[55%]"
		data-coords="<?php echo esc_attr( $club['coordinates'] ); ?>"
		data-marker="<?php echo esc_url( $brand['marker_url'] ); ?>"
		data-title="<?php echo esc_attr( $club['title'] ); ?>"
		data-hint="<?php echo esc_attr( $club['title'] ); ?>"
		data-balloon="<?php echo esc_attr( $club['address'] ); ?>"
	></div>
</section>
