<?php
/**
 * Membership plans page — /cards/
 *
 * @package ExtraSport
 */

$club       = extrasport_get_club();
$uri        = EXTRASPORT_URI;
$amenities  = extrasport_filter_membership_amenities( extrasport_get_membership_amenities() );
$plans      = extrasport_get_membership_plans();
$type_url   = extrasport_get_card_type_url();
?>

<section
	id="actions"
	class="page-section page-section--actions page-section--actions-list page-section--card-type"
	style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
>
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">

		<h1 class="section-heading mb-4 md:mb-5">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Выбор абонемента %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h1>

		<h2 class="card-choice__subtitle text-center font-oswald text-xl uppercase md:text-2xl">
			<?php esc_html_e( 'В каждый абонемент входит', 'extrasport' ); ?>
		</h2>

		<div class="card-choice__services grid grid-cols-2 gap-x-6 gap-y-5 md:grid-cols-4 md:gap-x-8 md:gap-y-6">
			<?php foreach ( $amenities as $amenity ) : ?>
				<div class="card-choice__service text-center">
					<img
						class="card-choice__service-icon"
						src="<?php echo esc_url( $uri . '/assets/images/' . $amenity['icon'] ); ?>"
						alt=""
						loading="lazy"
						decoding="async"
					>
					<p class="text-sm leading-snug text-white/90"><?php echo nl2br( esc_html( $amenity['label'] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="card-choice__note text-center text-sm text-white/70">
			<span><?php esc_html_e( '* наличие и период варьируются в зависимости от абонемента', 'extrasport' ); ?></span>
		</p>

		<div class="card-choice__plans grid gap-8 md:grid-cols-2">
			<?php foreach ( $plans as $plan ) : ?>
				<?php get_template_part( 'components/cards/membership-plan', null, array( 'plan' => $plan, 'uri' => $uri ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
foreach ( $plans as $plan ) {
	get_template_part( 'components/modals/membership-order', null, array( 'plan' => $plan ) );
}

extrasport_render_test_drive_section(
	array(
		'form_type'   => 'membership_cards',
		'source_url'  => $type_url,
	)
);
