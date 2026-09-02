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
	<?php wp_head(); ?>
</head>
<body id="page-top" <?php body_class( array_filter( array( is_front_page() ? 'is-front-page' : '', is_post_type_archive( 'service' ) ? 'is-services-archive' : '', extrasport_is_card_type_page() ? 'is-cards-page' : '' ) ) ); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'layouts/header' ); ?>

<main id="main" class="site-main">
