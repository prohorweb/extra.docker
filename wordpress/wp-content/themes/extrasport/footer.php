<?php
/**
 * Theme footer — layout partials + document close
 *
 * Adapted from frontend/views/layouts/footer.php
 *
 * @package ExtraSport
 */

?>
</main><!-- #main -->

<?php
get_template_part( 'template-parts/layout/footer', 'main' );
get_template_part( 'template-parts/layout/modal', 'club' );
get_template_part( 'template-parts/layout/modal', 'callback' );
get_template_part( 'template-parts/layout/modal', 'rules' );
get_template_part( 'template-parts/layout/modal', 'finish' );
get_template_part( 'template-parts/layout/modal', 'timer' );
get_template_part( 'template-parts/layout/widget', 'chat' );
get_template_part( 'template-parts/layout/widget', 'present-video' );
?>

<?php wp_footer(); ?>
</body>
</html>
