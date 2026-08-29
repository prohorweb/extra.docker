<article <?php post_class( 'card-grid overflow-hidden rounded-xl border border-white/10 bg-white/5 transition hover:border-brand-primary/50' ); ?>>
	<a href="<?php the_permalink(); ?>" class="block">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="aspect-[16/9] overflow-hidden">
				<?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover transition duration-300 hover:scale-105' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="p-5">
			<h3 class="font-oswald mb-2 text-xl uppercase"><?php the_title(); ?></h3>
			<?php if ( has_excerpt() ) : ?>
				<p class="text-sm text-white/70"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
			<?php endif; ?>
		</div>
	</a>
</article>
