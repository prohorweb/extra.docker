<?php
/**
 * News list card.
 *
 * @package ExtraSport
 *
 * @var array{title: string, excerpt: string, date: string, day: string, month: string, year: string, url: string}|null $news News card data.
 */

$news = $args['news'] ?? null;

if ( ! $news && 'news' === get_post_type() ) {
	$news = extrasport_normalize_news_post( get_post() );
}

if ( empty( $news ) || empty( $news['url'] ) ) {
	return;
}
?>

<article class="news-blog__item">
	<div class="news-block">
		<?php if ( ! empty( $news['day'] ) ) : ?>
			<div class="news-block__date">
				<h3 class="m-0"><?php echo esc_html( $news['day'] ); ?></h3>
				<span class="ms-2"><?php echo esc_html( $news['month'] ); ?></span>
				<span class="ms-2"><?php echo esc_html( $news['year'] ); ?> <?php esc_html_e( 'года', 'extrasport' ); ?></span>
			</div>
		<?php endif; ?>
		<div class="news-block__wrap">
			<h3 class="news-block__title">
				<a href="<?php echo esc_url( $news['url'] ); ?>"><?php echo esc_html( $news['title'] ); ?></a>
			</h3>
			<?php if ( ! empty( $news['excerpt'] ) ) : ?>
				<div class="news-block__text"><?php echo esc_html( $news['excerpt'] ); ?></div>
			<?php endif; ?>
			<div class="news-block__button">
				<a href="<?php echo esc_url( $news['url'] ); ?>" class="btn-primary btn-lg">
					<?php esc_html_e( 'Подробнее', 'extrasport' ); ?>
					<i class="fa-sharp fa-solid fa-angles-right ms-2" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</div>
</article>
