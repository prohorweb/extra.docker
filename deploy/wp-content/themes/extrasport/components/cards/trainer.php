<?php
/**
 * Trainer card.
 *
 * @package ExtraSport
 *
 * @var array{title: string, excerpt: string, date: string, image: string, url: string}|null $trainer Trainer card data.
 */

$trainer = $args['trainer'] ?? null;

if ( ! $trainer && 'trainer' === get_post_type() ) {
	$trainer = extrasport_normalize_trainer_post( get_post() );
}

if ( empty( $trainer ) || empty( $trainer['url'] ) ) {
	return;
}

get_template_part(
	'components/cards/share',
	null,
	array(
		'share' => $trainer,
		'class' => 'trainer-card',
	)
);
