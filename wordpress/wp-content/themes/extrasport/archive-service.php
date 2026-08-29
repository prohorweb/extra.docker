<?php
/**
 * Archive Service Template
 *
 * @package ExtraSport
 */

get_header();
?>

<main id="main" class="site-main archive-service-main">
    <div class="container">
        <header class="archive-header services-header">
            <h1 class="archive-title"><?php esc_html_e( 'Our Services', 'extrasport' ); ?></h1>
            <p class="archive-description"><?php esc_html_e( 'Discover our wide range of professional services.', 'extrasport' ); ?></p>
        </header><!-- .archive-header -->

        <div class="services-filter">
            <?php
            $terms = get_terms( array(
                'taxonomy'   => 'service_category',
                'hide_empty' => false,
            ) );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                echo '<div class="filter-buttons">';
                echo '<a href="' . esc_url( get_post_type_archive_link( 'service' ) ) . '" class="filter-btn active">' . esc_html__( 'All Services', 'extrasport' ) . '</a>';
                foreach ( $terms as $term ) {
                    echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="filter-btn">' . esc_html( $term->name ) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="services-grid">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'template-parts/content', 'service' );
                }
                the_posts_pagination( array(
                    'mid_size' => 2,
                ) );
            } else {
                get_template_part( 'template-parts/content', 'none' );
            }
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
