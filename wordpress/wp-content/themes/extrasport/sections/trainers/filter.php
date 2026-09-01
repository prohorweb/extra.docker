<?php
/**
 * Trainer direction filter (Yii2: trainer_options_type + POST filter).
 *
 * @package ExtraSport
 */

$directions = extrasport_get_trainer_directions();
$selected   = extrasport_get_selected_trainer_direction();
$selected   = extrasport_resolve_trainer_direction_filter_term( $selected ) ? $selected : '';
$action     = ! empty( $args['action'] ) ? (string) $args['action'] : extrasport_get_trainers_archive_url();

if ( empty( $directions ) ) {
	return;
}
?>

<nav class="trainers-filter mb-3" aria-label="<?php esc_attr_e( 'Фильтр тренеров', 'extrasport' ); ?>">
	<div class="trainers-filter__bar">
		<span class="trainers-filter__label"><?php esc_html_e( 'Выберите направление', 'extrasport' ); ?></span>

		<button
			type="button"
			class="trainers-filter__toggle lg:hidden"
			aria-controls="trainersFilterCollapse"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Показать фильтр', 'extrasport' ); ?>"
			data-trainers-filter-toggle
		>
			<span class="trainers-filter__toggle-icon" aria-hidden="true"></span>
		</button>

		<div class="trainers-filter__controls hidden lg:flex" id="trainersFilterCollapse">
			<form class="trainers-filter__form" method="get" action="<?php echo esc_url( $action ); ?>">
				<label class="sr-only" for="trainer-direction"><?php esc_html_e( 'Направление', 'extrasport' ); ?></label>
				<select id="trainer-direction" name="filter" class="form-select trainers-filter__select">
					<option value=""><?php esc_html_e( 'Все направления', 'extrasport' ); ?></option>
					<?php foreach ( $directions as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<button type="submit" class="btn-primary btn-lg trainers-filter__button">
					<?php esc_html_e( 'Показать', 'extrasport' ); ?>
				</button>

				<a href="<?php echo esc_url( $action ); ?>" class="btn-primary btn-lg trainers-filter__button trainers-filter__button--reset">
					<?php esc_html_e( 'Сбросить', 'extrasport' ); ?>
				</a>
			</form>
		</div>
	</div>
</nav>
