<?php
/**
 * Front Page Template
 *
 * @package ExtraSport
 */

get_header();
?>

<main id="main" class="site-main front-page-main">
    <div class="container">
        <div class="front-page-content">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    ?>
                    <section class="front-page-hero">
                        <div class="hero-content">
                            <h1><?php the_title(); ?></h1>
                            <?php the_content(); ?>
                        </div>
                    </section>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Services Section -->
        <section class="services-section">
            <h2><?php esc_html_e( 'Our Services', 'extrasport' ); ?></h2>
            <div class="services-grid">
                <?php
                $services = get_posts( array(
                    'post_type'      => 'service',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );

                if ( $services ) {
                    foreach ( $services as $service ) {
                        ?>
                        <div class="service-card">
                            <?php
                            if ( has_post_thumbnail( $service->ID ) ) {
                                echo get_the_post_thumbnail( $service->ID, 'medium' );
                            }
                            ?>
                            <h3><?php echo esc_html( $service->post_title ); ?></h3>
                            <div class="service-excerpt">
                                <?php echo wp_trim_words( $service->post_content, 20 ); ?>
                            </div>
                            <a href="<?php echo esc_url( get_permalink( $service->ID ) ); ?>" class="btn btn-primary">
                                <?php esc_html_e( 'Learn More', 'extrasport' ); ?>
                            </a>
                        </div>
                        <?php
                    }
                }
                wp_reset_postdata();
                ?>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="btn btn-secondary">
                <?php esc_html_e( 'View All Services', 'extrasport' ); ?>
            </a>
        </section>

        <!-- Programs Section -->
        <section class="programs-section">
            <h2><?php esc_html_e( 'Our Programs', 'extrasport' ); ?></h2>
            <div class="programs-grid">
                <?php
                $programs = get_posts( array(
                    'post_type'      => 'group_program',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );

                if ( $programs ) {
                    foreach ( $programs as $program ) {
                        ?>
                        <div class="program-card">
                            <?php
                            if ( has_post_thumbnail( $program->ID ) ) {
                                echo get_the_post_thumbnail( $program->ID, 'medium' );
                            }
                            ?>
                            <h3><?php echo esc_html( $program->post_title ); ?></h3>
                            <div class="program-excerpt">
                                <?php echo wp_trim_words( $program->post_content, 20 ); ?>
                            </div>
                            <a href="<?php echo esc_url( get_permalink( $program->ID ) ); ?>" class="btn btn-primary">
                                <?php esc_html_e( 'Learn More', 'extrasport' ); ?>
                            </a>
                        </div>
                        <?php
                    }
                }
                wp_reset_postdata();
                ?>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
