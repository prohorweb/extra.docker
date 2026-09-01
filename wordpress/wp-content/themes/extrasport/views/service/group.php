<?php
/**
 * Group service view — list of directions.
 *
 * @package ExtraSport
 */

$uri = EXTRASPORT_URI;

while ( have_posts() ) {
	the_post();
	get_template_part(
		'sections/service-group-list',
		null,
		array(
			'uri'   => $uri,
			'cards' => extrasport_get_service_group_cards( get_the_ID() ),
		)
	);
}

extrasport_render_test_drive_section();
