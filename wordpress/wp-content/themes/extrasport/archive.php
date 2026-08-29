<?php
/**
 * Archive Template (Default)
 *
 * @package ExtraSport
 */

get_header();
?>

<div class="page-content archive-main">
    <div class="container">
        <header class="archive-header">
            <h1 class="archive-title"><?php the_archive_title(); ?></h1>
            <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
        </header><!-- .archive-header -->

        <div class="archive-grid">
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
</div>

<?php
get_footer();
