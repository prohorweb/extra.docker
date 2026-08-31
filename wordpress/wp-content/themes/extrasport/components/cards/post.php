<?php
/**
 * Template part for displaying default post content
 *
 * @package ExtraSport
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>
    </header>

    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <figure class="entry-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium' ); ?>
            </a>
        </figure>
        <?php
    }
    ?>

    <div class="entry-summary">
        <?php
        the_excerpt();
        ?>
    </div>

    <footer class="entry-footer">
        <a href="<?php the_permalink(); ?>" class="read-more-link">
            <?php esc_html_e( 'Read More', 'extrasport' ); ?>
        </a>
    </footer>
</article>
<?php
/**
 * Template part for displaying posts
 *
 * @package ExtraSport
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>
        <div class="entry-meta">
            <?php extrasport_posted_on(); ?>
        </div>
    </header>

    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <figure class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium' ); ?>
            </a>
        </figure>
        <?php
    }
    ?>

    <div class="entry-content">
        <?php
        the_excerpt();
        ?>
        <a href="<?php the_permalink(); ?>" class="read-more">
            <?php esc_html_e( 'Read More', 'extrasport' ); ?>
        </a>
    </div>
</article>
