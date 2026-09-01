<?php
/**
 * Group programs archive — grouped service layout.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$uri  = EXTRASPORT_URI;
$cards = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$cards[] = extrasport_normalize_group_program_post( get_post() );
	}
	rewind_posts();
}

if ( empty( $cards ) ) {
	$cards = extrasport_get_group_program_placeholders( $uri );
}
?>

<?php
get_template_part(
	'sections/service-group-list',
	null,
	array(
		'title'       => sprintf(
			/* translators: %s: club title */
			__( 'Групповые программы клуба %s', 'extrasport' ),
			$club['title']
		),
		'uri'         => $uri,
		'cards'       => $cards,
		'breadcrumbs' => extrasport_get_group_programs_breadcrumbs(),
	)
);
?>

<?php extrasport_render_test_drive_section(); ?>
