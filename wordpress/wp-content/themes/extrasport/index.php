<?php
/**
 * Main Template File
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package ExtraSport
 */

get_header();
?>

<div class="page-content">
    <div class="container">
        <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                get_template_part( 'template-parts/content', get_post_type() );
            }
            the_posts_pagination();
        } else {
            get_template_part( 'template-parts/content', 'none' );
        }
        ?>
    </div>
</div>

<?php
get_footer();
