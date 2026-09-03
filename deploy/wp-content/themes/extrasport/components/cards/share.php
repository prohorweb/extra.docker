<?php
/**
 * Share / promotion card — homepage and archive.
 *
 * @package ExtraSport
 *
 * @var array{title: string, excerpt: string, date: string, image: string, url: string}|null $share Share card data.
 * @var string                                                                             $class Extra CSS classes.
 */

$share = $args['share'] ?? null;
$class = isset( $args['class'] ) ? (string) $args['class'] : '';

if ( ! $share && 'share' === get_post_type() ) {
	$share = extrasport_normalize_share_post( get_post() );
}

if ( empty( $share ) ) {
	return;
}

$is_static = str_contains( $class, 'share-card--static' );
$url       = ! empty( $share['url'] ) ? (string) $share['url'] : '';

if ( ! $is_static && ! $url ) {
	return;
}
?>

<div class="share-card <?php echo esc_attr( $class ); ?>">
	<?php if ( $url && ! $is_static ) : ?>
		<a href="<?php echo esc_url( $url ); ?>" class="share-card__link" aria-label="<?php echo esc_attr( $share['title'] ); ?>"></a>
	<?php endif; ?>
	<?php if ( ! empty( $share['date'] ) ) : ?>
		<div class="date-action"><?php echo esc_html( $share['date'] ); ?></div>
	<?php endif; ?>
	<div class="share-card__media">
		<?php if ( ! empty( $share['image'] ) ) : ?>
			<img class="card-img-top" src="<?php echo esc_url( $share['image'] ); ?>" alt="<?php echo esc_attr( $share['title'] ); ?>">
		<?php elseif ( str_contains( $class, 'trainer-card' ) ) : ?>
			<div class="card-img-top share-card__placeholder">
				<img class="membership-card__logo" src="<?php echo esc_url( extrasport_get_trainer_placeholder_logo_url() ); ?>" alt="">
			</div>
		<?php else : ?>
			<div class="card-img-top bg-white/10"></div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="card-body__row">
			<div class="card-body_wrapper">
				<h5 class="card-title"><?php echo esc_html( $share['title'] ); ?></h5>
				<?php if ( ! empty( $share['excerpt'] ) ) : ?>
					<div class="card-text"><?php echo esc_html( $share['excerpt'] ); ?></div>
				<?php endif; ?>
			</div>
			<div class="btn-arrow" aria-hidden="true">
				<i class="fa-sharp fa-solid fa-arrow-right"></i>
			</div>
		</div>
	</div>
</div>
