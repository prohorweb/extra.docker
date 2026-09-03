<?php
/**
 * Theme footer — layout partials + document close
 *
 * @package ExtraSport
 */

?>
</main><!-- #main -->

<?php
get_template_part( 'layouts/footer' );
get_template_part( 'components/modals/club' );
get_template_part( 'components/modals/callback' );
get_template_part( 'components/modals/rules' );
get_template_part( 'components/modals/finish' );
get_template_part( 'components/modals/timer' );
get_template_part( 'components/widgets/chat' );
get_template_part( 'components/widgets/present-video' );
?>

<?php wp_footer(); ?>
</body>
</html>
