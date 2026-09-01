<?php
/**
 * Breadcrumb navigation
 *
 * @package ExtraSport
 */

if ( empty( $args['items'] ) || ! is_array( $args['items'] ) ) {
	return;
}

$class      = isset( $args['class'] ) ? (string) $args['class'] : '';
$list_class = isset( $args['list_class'] ) ? (string) $args['list_class'] : '';
?>

<nav class="mb-8 <?php echo esc_attr( $class ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumb', 'extrasport' ); ?>">
	<ol class="flex flex-wrap items-center gap-2 text-sm text-white/60 <?php echo esc_attr( $list_class ); ?>">
		<?php foreach ( $args['items'] as $index => $item ) : ?>
			<?php if ( $index > 0 ) : ?>
				<li aria-hidden="true" class="text-white/30">/</li>
			<?php endif; ?>
			<li>
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-brand-primary"><?php echo esc_html( $item['label'] ); ?></a>
				<?php else : ?>
					<span class="text-white" aria-current="page"><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
