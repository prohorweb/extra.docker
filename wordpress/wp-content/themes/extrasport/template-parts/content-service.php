<article <?php post_class( 'card-grid group overflow-hidden rounded-xl border border-white/10 bg-white/5 transition hover:border-brand-primary/50' ); ?>>
	<a href="<?php the_permalink(); ?>" class="block">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="aspect-[16/10] overflow-hidden">
				<?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover transition duration-300 group-hover:scale-105' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="flex items-center justify-between gap-4 p-5">
			<h3 class="font-oswald text-lg uppercase leading-tight"><?php the_title(); ?></h3>
			<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-white/20 text-brand-primary transition group-hover:bg-brand-primary group-hover:text-white">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</span>
		</div>
	</a>
</article>
