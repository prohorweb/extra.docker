<?php
/**
 * Template part for displaying service card
 *
 * @package ExtraSport
 */

?>
<div class="service-card">
    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <figure class="service-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium' ); ?>
            </a>
        </figure>
        <?php
    }
    ?>

    <div class="service-content">
        <h3 class="service-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="service-excerpt">
            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
        </div>

        <div class="service-footer">
            <a href="<?php the_permalink(); ?>" class="btn btn-outline">
                <?php esc_html_e( 'View Details', 'extrasport' ); ?>
            </a>
        </div>
    </div>
</div>
