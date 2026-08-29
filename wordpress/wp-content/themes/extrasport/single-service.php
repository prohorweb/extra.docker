<?php
/**
 * Single Service Template
 *
 * @package ExtraSport
 */

get_header();
?>

<main id="main" class="site-main single-service-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'service-content' ); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header><!-- .entry-header -->

            <?php
            if ( has_post_thumbnail() ) {
                ?>
                <figure class="featured-image service-image">
                    <?php the_post_thumbnail( 'large' ); ?>
                </figure>
                <?php
            }
            ?>

            <div class="entry-content service-description">
                <?php the_content(); ?>
            </div><!-- .entry-content -->

            <div class="service-meta">
                <?php
                // Display service meta information if available
                $price = get_post_meta( get_the_ID(), '_service_price', true );
                $duration = get_post_meta( get_the_ID(), '_service_duration', true );
                
                if ( $price ) {
                    echo '<div class="service-price"><strong>' . esc_html__( 'Price:', 'extrasport' ) . '</strong> ' . esc_html( $price ) . '</div>';
                }
                if ( $duration ) {
                    echo '<div class="service-duration"><strong>' . esc_html__( 'Duration:', 'extrasport' ) . '</strong> ' . esc_html( $duration ) . '</div>';
                }
                ?>
            </div>

            <div class="service-cta">
                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-large">
                    <?php esc_html_e( 'Book Now', 'extrasport' ); ?>
                </a>
            </div>

            <?php
            // Related services
            $related = get_posts( array(
                'post_type'      => 'service',
                'posts_per_page' => 3,
                'exclude'        => get_the_ID(),
                'orderby'        => 'rand',
            ) );

            if ( $related ) {
                ?>
                <section class="related-services">
                    <h2><?php esc_html_e( 'Related Services', 'extrasport' ); ?></h2>
                    <div class="related-grid">
                        <?php
                        foreach ( $related as $post_item ) {
                            setup_postdata( $post_item );
                            get_template_part( 'template-parts/content', 'service' );
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
