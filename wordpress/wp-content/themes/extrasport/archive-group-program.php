<?php
/**
 * Archive Group Program Template
 *
 * @package ExtraSport
 */

get_header();
?>

<div class="page-content archive-program-main">
    <div class="container">
        <header class="archive-header programs-header">
            <h1 class="archive-title"><?php esc_html_e( 'Our Programs', 'extrasport' ); ?></h1>
            <p class="archive-description"><?php esc_html_e( 'Choose from our diverse range of fitness and training programs.', 'extrasport' ); ?></p>
        </header><!-- .archive-header -->

        <div class="programs-filter">
            <?php
            $terms = get_terms( array(
                'taxonomy'   => 'program_type',
                'hide_empty' => false,
            ) );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                echo '<div class="filter-buttons">';
                echo '<a href="' . esc_url( get_post_type_archive_link( 'group_program' ) ) . '" class="filter-btn active">' . esc_html__( 'All Programs', 'extrasport' ) . '</a>';
                foreach ( $terms as $term ) {
                    echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="filter-btn">' . esc_html( $term->name ) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="programs-grid">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'template-parts/content', 'group_program' );
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
</div>

<?php
get_footer();
