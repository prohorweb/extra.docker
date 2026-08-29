<?php
/**
 * ExtraSport Theme Functions
 * 
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'EXTRASPORT_VERSION', '1.0.0' );
define( 'EXTRASPORT_DIR', get_template_directory() );
define( 'EXTRASPORT_URI', get_template_directory_uri() );

/**
 * Setup Theme Support
 */
function extrasport_setup() {
    // Enable theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'extrasport' ),
        'footer'  => esc_html__( 'Footer Menu', 'extrasport' ),
    ) );
}
add_action( 'after_setup_theme', 'extrasport_setup' );

/**
 * Enqueue Styles & Scripts
 */
function extrasport_enqueue_scripts() {
    // Theme stylesheet
    wp_enqueue_style(
        'extrasport-style',
        EXTRASPORT_URI . '/assets/css/style.css',
        array(),
        EXTRASPORT_VERSION
    );

    // Theme scripts
    wp_enqueue_script(
        'extrasport-main',
        EXTRASPORT_URI . '/assets/js/main.js',
        array(),
        EXTRASPORT_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'extrasport_enqueue_scripts' );

/**
 * Load Theme Includes
 */
require_once EXTRASPORT_DIR . '/inc/post-types.php';
require_once EXTRASPORT_DIR . '/inc/taxonomies.php';

/**
 * Custom logo support
 */
function extrasport_custom_logo_setup() {
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
add_action( 'after_setup_theme', 'extrasport_custom_logo_setup' );

/**
 * Set content width
 */
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

/**
 * Helper: Posted on date
 */
function extrasport_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    echo '<span class="posted-on">' . $time_string . '</span>';
}

/**
 * Sidebar Setup
 */
function extrasport_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Area 1', 'extrasport' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer widget area 1', 'extrasport' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Area 2', 'extrasport' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer widget area 2', 'extrasport' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Area 3', 'extrasport' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Footer widget area 3', 'extrasport' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'extrasport_widgets_init' );
