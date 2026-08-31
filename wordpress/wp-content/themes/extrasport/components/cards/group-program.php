<article <?php post_class( 'card-grid overflow-hidden rounded-xl border border-white/10 bg-white/5 text-center transition hover:border-brand-primary/50' ); ?>>
	<a href="<?php the_permalink(); ?>" class="block p-4">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="mb-4 aspect-square overflow-hidden rounded-lg">
				<?php the_post_thumbnail( 'medium', array( 'class' => 'h-full w-full object-cover' ) ); ?>
			</div>
		<?php endif; ?>
		<h3 class="font-oswald text-base uppercase"><?php the_title(); ?></h3>
		<?php
		$level = get_post_meta( get_the_ID(), '_program_level', true );
		if ( $level ) :
			?>
			<p class="mt-2 text-xs uppercase tracking-wide text-brand-primary"><?php echo esc_html( $level ); ?></p>
		<?php endif; ?>
	</a>
</article>
