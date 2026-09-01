<?php
/**
 * Group service archive — child services or group programs.
 *
 * @package ExtraSport
 *
 * @var string               $title   Section title.
 * @var string               $uri     Theme URI.
 * @var array<int, array>    $cards   Card data.
 */

$title = $args['title'] ?? get_the_title();
$uri   = $args['uri'] ?? EXTRASPORT_URI;
$cards = $args['cards'] ?? extrasport_get_service_group_cards();
?>

<section
	id="actions"
	class="page-section page-section--actions page-section--actions-list"
	style="background-image: url('<?php echo esc_url( extrasport_get_default_actions_bg_url() ); ?>');"
>
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
		<h1 class="section-heading mb-4 md:mb-5"><?php echo esc_html( $title ); ?></h1>

		<?php if ( ! empty( $cards ) ) : ?>
			<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $cards as $service ) : ?>
					<?php get_template_part( 'components/cards/service', null, array( 'service' => $service ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'components/content-none' ); ?>
		<?php endif; ?>
	</div>
</section>
