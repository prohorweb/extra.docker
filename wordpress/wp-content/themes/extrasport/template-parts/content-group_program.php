<?php
/**
 * Template part for displaying program card
 *
 * @package ExtraSport
 */

?>
<div class="program-card">
    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <figure class="program-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium' ); ?>
            </a>
        </figure>
        <?php
    }
    ?>

    <div class="program-content">
        <h3 class="program-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="program-excerpt">
            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
        </div>

        <div class="program-meta-mini">
            <?php
            $level = get_post_meta( get_the_ID(), '_program_level', true );
            if ( $level ) {
                echo '<span class="program-level-badge">' . esc_html( $level ) . '</span>';
            }
            ?>
        </div>

        <div class="program-footer">
            <a href="<?php the_permalink(); ?>" class="btn btn-outline">
                <?php esc_html_e( 'View Details', 'extrasport' ); ?>
            </a>
        </div>
    </div>
</div>
