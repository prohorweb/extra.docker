<?php
/**
 * Page Template
 *
 * @package ExtraSport
 */

get_header();
?>

<div class="page-content page-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header><!-- .entry-header -->

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
</div>

<?php
get_footer();
