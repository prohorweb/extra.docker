<?php
/**
 * Service card — same layout as share cards.
 *
 * @package ExtraSport
 *
 * @var array{title: string, excerpt: string, image: string, url: string}|null $service Service card data.
 * @var string                                                                 $class Extra CSS classes.
 */

$service = $args['service'] ?? null;
$class   = isset( $args['class'] ) ? (string) $args['class'] : 'share-card--service';
$class   = str_contains( $class, 'share-card--service' ) ? $class : trim( 'share-card--service ' . $class );

if ( ! $service && 'service' === get_post_type() ) {
	$service = extrasport_normalize_service_post( get_post() );
}

if ( empty( $service ) ) {
	return;
}

$is_static = str_contains( $class, 'share-card--static' );
$url       = ! empty( $service['url'] ) ? (string) $service['url'] : '';

if ( ! $is_static && ! $url ) {
	return;
}
?>

<div class="share-card <?php echo esc_attr( $class ); ?>">
	<?php if ( $url && ! $is_static ) : ?>
		<a href="<?php echo esc_url( $url ); ?>" class="share-card__link" aria-label="<?php echo esc_attr( $service['title'] ); ?>"></a>
	<?php endif; ?>
	<div class="share-card__media">
		<?php if ( ! empty( $service['image'] ) ) : ?>
			<img class="card-img-top" src="<?php echo esc_url( $service['image'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>">
		<?php else : ?>
			<div class="card-img-top bg-white/10"></div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="card-body__row">
			<div class="card-body_wrapper">
				<h5 class="card-title"><?php echo esc_html( $service['title'] ); ?></h5>
				<?php if ( ! empty( $service['excerpt'] ) ) : ?>
					<div class="card-text"><?php echo esc_html( $service['excerpt'] ); ?></div>
				<?php endif; ?>
			</div>
			<div class="btn-arrow" aria-hidden="true">
				<i class="fa-sharp fa-solid fa-arrow-right"></i>
			</div>
		</div>
	</div>
</div>
