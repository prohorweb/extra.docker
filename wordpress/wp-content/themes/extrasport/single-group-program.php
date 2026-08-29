<?php
/**
 * Single Group Program Template
 *
 * @package ExtraSport
 */

get_header();
?>

<main id="main" class="site-main single-program-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'program-content' ); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header><!-- .entry-header -->

            <?php
            if ( has_post_thumbnail() ) {
                ?>
                <figure class="featured-image program-image">
                    <?php the_post_thumbnail( 'large' ); ?>
                </figure>
                <?php
            }
            ?>

            <div class="entry-content program-description">
                <?php the_content(); ?>
            </div><!-- .entry-content -->

            <div class="program-meta">
                <?php
                // Display program meta information
                $level = get_post_meta( get_the_ID(), '_program_level', true );
                $schedule = get_post_meta( get_the_ID(), '_program_schedule', true );
                $price = get_post_meta( get_the_ID(), '_program_price', true );
                
                if ( $level ) {
                    echo '<div class="program-level"><strong>' . esc_html__( 'Level:', 'extrasport' ) . '</strong> ' . esc_html( $level ) . '</div>';
                }
                if ( $schedule ) {
                    echo '<div class="program-schedule"><strong>' . esc_html__( 'Schedule:', 'extrasport' ) . '</strong> ' . esc_html( $schedule ) . '</div>';
                }
                if ( $price ) {
                    echo '<div class="program-price"><strong>' . esc_html__( 'Price:', 'extrasport' ) . '</strong> ' . esc_html( $price ) . '</div>';
                }
                ?>
            </div>

            <div class="program-cta">
                <a href="<?php echo esc_url( home_url( '/signup' ) ); ?>" class="btn btn-primary btn-large">
                    <?php esc_html_e( 'Enroll Now', 'extrasport' ); ?>
                </a>
            </div>

            <?php
            // Related programs
            $related = get_posts( array(
                'post_type'      => 'group_program',
                'posts_per_page' => 3,
                'exclude'        => get_the_ID(),
                'orderby'        => 'rand',
            ) );

            if ( $related ) {
                ?>
                <section class="related-programs">
                    <h2><?php esc_html_e( 'Related Programs', 'extrasport' ); ?></h2>
                    <div class="related-grid">
                        <?php
                        foreach ( $related as $post_item ) {
                            setup_postdata( $post_item );
                            get_template_part( 'template-parts/content', 'group_program' );
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
                <?php
            }

            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
            ?>
        </article><!-- #post-<?php the_ID(); ?> -->
    </div>
</main>

<?php
get_footer();
