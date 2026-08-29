<?php
/**
 * Theme header — document shell + navigation
 *
 * Adapted from frontend/views/layouts/main.php + header.php
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#141416">
	<?php wp_head(); ?>
</head>
<body id="page-top" <?php body_class( is_front_page() ? 'is-front-page' : '' ); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/layout/header', 'nav' ); ?>

<main id="main" class="site-main">
