<?php
/**
 * Reusable trainers list block.
 *
 * @package ExtraSport
 */

$club          = extrasport_get_club();
$heading       = ! empty( $args['heading'] );
$show_filter   = ! empty( $args['show_filter'] );
$show_all_link = ! empty( $args['show_all_link'] );
$direction     = sanitize_text_field( (string) ( $args['direction'] ?? extrasport_get_selected_trainer_direction() ) );
$filter_term   = extrasport_resolve_trainer_direction_filter_term( $direction );
$direction     = $filter_term ? (string) $filter_term->slug : '';
$limit         = isset( $args['limit'] ) ? (int) $args['limit'] : -1;
$exclude       = array_map( 'intval', (array) ( $args['exclude'] ?? array() ) );
$section_class = sanitize_html_class( (string) ( $args['section_class'] ?? 'other-teams' ) );

$trainers = extrasport_get_trainers(
	array(
		'direction' => $direction,
		'limit'     => $limit,
		'exclude'   => $exclude,
	)
);
?>

<section class="<?php echo esc_attr( $section_class ); ?>">
	<?php if ( $heading ) : ?>
		<h2 class="section-heading mb-4 py-4 text-2xl uppercase md:mb-5 md:text-3xl lg:text-4xl">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Тренеры клуба %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h2>
	<?php endif; ?>

	<?php if ( $show_filter ) : ?>
		<?php
		get_template_part(
			'sections/trainers/filter',
			null,
			array(
				'action' => (string) ( $args['filter_action'] ?? '' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( $trainers ) : ?>
		<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $trainers as $trainer ) : ?>
				<?php get_template_part( 'components/cards/trainer', null, array( 'trainer' => extrasport_normalize_trainer_post( $trainer ) ) ); ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="text-center text-white/70"><?php esc_html_e( 'Записей не найдено', 'extrasport' ); ?></p>
	<?php endif; ?>

	<?php if ( $show_all_link ) : ?>
		<div class="mt-10 flex justify-center">
			<a href="<?php echo esc_url( extrasport_get_trainers_archive_url() ); ?>" class="btn-xl">
				<?php esc_html_e( 'Все тренеры', 'extrasport' ); ?>
			</a>
		</div>
	<?php endif; ?>
</section>
