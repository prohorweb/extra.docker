<?php
/**
 * Theme header — document shell + navigation
 *
 * @package ExtraSport
 */

$club  = extrasport_get_club();
$brand = extrasport_get_brand();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> style="--color-brand-primary: <?php echo esc_attr( $brand['primary'] ); ?>; --color-brand-accent: <?php echo esc_attr( $brand['accent'] ); ?>;">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#141416">
	<?php wp_head(); ?>
</head>
<body id="page-top" <?php body_class( is_front_page() ? 'is-front-page' : '' ); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'layouts/header' ); ?>

<main id="main" class="site-main">
