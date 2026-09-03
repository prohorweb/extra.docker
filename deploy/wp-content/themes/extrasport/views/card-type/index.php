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

		<?php
		$amenities_count = count( $amenities );
		$amenities_mod   = 'card-choice__services--default';

		if ( 6 === $amenities_count ) {
			$amenities_mod = 'card-choice__services--six';
		} elseif ( 4 === $amenities_count ) {
			$amenities_mod = 'card-choice__services--four';
		}
		?>
		<div class="card-choice__services <?php echo esc_attr( $amenities_mod ); ?>">
			<?php foreach ( $amenities as $amenity ) : ?>
				<div class="card-choice__service">
					<img
						class="card-choice__service-icon"
						src="<?php echo esc_url( $uri . '/assets/images/' . ltrim( (string) $amenity['icon'], '/' ) ); ?>"
						alt=""
						loading="lazy"
						decoding="async"
					>
					<p class="text-xs leading-snug text-white/90 sm:text-sm"><?php echo nl2br( esc_html( $amenity['label'] ) ); ?></p>
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

		<?php
		$plans_note = extrasport_get_membership_plans_footer_note();
		if ( $plans_note ) :
			?>
			<div class="card-choice__plans-note text-center text-sm leading-relaxed text-white/85 md:text-base">
				<p class="mb-2">
					<?php echo esc_html( $plans_note['lead'] ); ?>
					<span class="card-choice__plans-note-highlight font-oswald uppercase tracking-wide text-brand-primary">
						<?php echo esc_html( $plans_note['highlight'] ); ?>
					</span>
				</p>
				<p>
					<?php echo esc_html( $plans_note['promo'] ); ?>
					<a
						href="<?php echo esc_url( $plans_note['phone_href'] ); ?>"
						class="whitespace-nowrap text-brand-primary hover:text-white"
					><?php echo esc_html( $plans_note['phone'] ); ?></a>
				</p>
			</div>
		<?php endif; ?>
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
