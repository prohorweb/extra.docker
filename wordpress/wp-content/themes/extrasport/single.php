<?php
/**
 * Single Post Template
 *
 * @package ExtraSport
 */

get_header();
?>

<main id="main" class="site-main single-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-content' ); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <?php extrasport_posted_on(); ?>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <?php
            if ( has_post_thumbnail() ) {
                ?>
                <figure class="featured-image">
                    <?php the_post_thumbnail( 'large' ); ?>
                </figure>
                <?php
            }
            ?>

            <div class="entry-content">
                <?php
                the_content();
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extrasport' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div><!-- .entry-content -->

            <?php
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
            ?>
        </article><!-- #post-<?php the_ID(); ?> -->
    </div>
</main>

<?php
get_footer();
