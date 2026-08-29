<?php
/**
 * Template for displaying footer of the theme
 *
 * @package ExtraSport
 */

?>
        </div><!-- #content -->

        <footer id="site-footer" class="site-footer" role="contentinfo">
            <div class="site-footer-inner">
                <div class="footer-widgets">
                    <?php
                    if ( is_active_sidebar( 'footer-1' ) ) {
                        dynamic_sidebar( 'footer-1' );
                    }
                    if ( is_active_sidebar( 'footer-2' ) ) {
                        dynamic_sidebar( 'footer-2' );
                    }
                    if ( is_active_sidebar( 'footer-3' ) ) {
                        dynamic_sidebar( 'footer-3' );
                    }
                    ?>
                </div>

                <div class="site-footer-bottom">
                    <div class="site-info">
                        <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. 
                        <?php esc_html_e( 'All rights reserved.', 'extrasport' ); ?></p>
                    </div>

                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'container'      => false,
                    ) );
                    ?>
                </div>
            </div>
        </footer><!-- #site-footer -->
    </div><!-- #page -->

    <?php wp_footer(); ?>
</body>
</html>
