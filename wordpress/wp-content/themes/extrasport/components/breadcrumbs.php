<?php
/**
 * Breadcrumb navigation
 *
 * @package ExtraSport
 */

if ( empty( $args['items'] ) || ! is_array( $args['items'] ) ) {
	return;
}
?>

<nav class="mb-8 hidden md:block" aria-label="<?php esc_attr_e( 'Breadcrumb', 'extrasport' ); ?>">
	<ol class="flex flex-wrap items-center gap-2 text-sm text-white/60">
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
