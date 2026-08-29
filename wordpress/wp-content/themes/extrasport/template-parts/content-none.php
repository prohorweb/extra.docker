<?php
/**
 * Template part for displaying no content
 *
 * @package ExtraSport
 */

?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing here', 'extrasport' ); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'extrasport' ); ?></p>
        <?php get_search_form(); ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->
