<?php
/**
 * Front Page Template
 *
 * Adapted from frontend/views/site/index.php + club/_subscribe.php
 *
 * @package ExtraSport
 */

get_header();

$club      = extrasport_get_club();
$site_name = get_bloginfo( 'name' );
$uri       = EXTRASPORT_URI;
$tel_clean = preg_replace( '/\s+/', '', $club['tel'] );

$banners = get_posts(
	array(
		'post_type'      => 'banner',
		'posts_per_page' => 10,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

$shares = get_posts(
	array(
		'post_type'      => 'share',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

/**
 * Render carousel slides markup.
 *
 * @param array<int, WP_Post> $banner_posts Banner posts.
 */
function extrasport_render_carousel_slides( $banner_posts, $site_name, $uri ) {
	?>
	<div data-carousel-slide class="carousel-slide is-active relative h-full w-full" aria-hidden="false">
		<div class="absolute inset-0 z-10 flex flex-col items-center justify-center px-4 text-center">
			<h1 class="font-oswald text-3xl uppercase leading-tight md:text-5xl lg:text-6xl">
				<?php esc_html_e( 'Сеть фитнес клубов на результат!', 'extrasport' ); ?>
			</h1>
			<p class="mt-3 text-lg text-white/90 md:text-xl">
				<?php printf( esc_html__( 'Ваш клуб — %s', 'extrasport' ), esc_html( $site_name ) ); ?>
			</p>
		</div>
		<video class="h-full w-full object-cover" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/bg_moution.mp4' ); ?>" type="video/mp4">
			<source src="<?php echo esc_url( $uri . '/assets/video/bg_moution.webm' ); ?>" type="video/webm">
		</video>
	</div>
	<?php
	foreach ( $banner_posts as $banner ) {
		$title    = get_post_meta( $banner->ID, '_banner_title', true ) ?: $banner->post_title;
		$subtitle = get_post_meta( $banner->ID, '_banner_subtitle', true );
		$link     = get_post_meta( $banner->ID, '_banner_link', true );
		$image    = get_post_meta( $banner->ID, '_banner_image', true );
		?>
		<div data-carousel-slide class="carousel-slide relative h-full w-full" aria-hidden="true">
			<div class="absolute inset-0 z-10 flex flex-col items-center justify-end p-6 md:items-end md:p-10">
				<div class="max-w-lg text-center md:text-right">
					<h2 class="font-oswald text-2xl uppercase md:text-4xl"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $subtitle ) : ?>
						<p class="mt-2 text-white/80"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<?php if ( $link ) : ?>
						<a href="<?php echo esc_url( $link ); ?>" class="btn-primary btn-lg mt-4 inline-flex bg-black hover:bg-black/80">
							<?php esc_html_e( 'Узнать больше »', 'extrasport' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( has_post_thumbnail( $banner->ID ) ) : ?>
				<?php echo get_the_post_thumbnail( $banner->ID, 'full', array( 'class' => 'h-full w-full object-cover' ) ); ?>
			<?php elseif ( $image ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="h-full w-full object-cover">
			<?php endif; ?>
		</div>
		<?php
	}
}
?>

<div class="page-content front-page-main">

	<!-- Hero / Carousel -->
	<header class="masthead relative -mt-[1px]">
		<!-- Desktop carousel -->
		<div class="carousel hidden md:block relative h-[70vh] min-h-[480px] max-h-[820px] overflow-hidden" data-carousel data-carousel-interval="8000" id="carouselDesktop">
			<div class="carousel-track relative h-full">
				<?php extrasport_render_carousel_slides( $banners, $site_name, $uri ); ?>
			</div>
			<?php if ( count( $banners ) > 0 ) : ?>
				<div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
					<button type="button" class="carousel-dot is-active h-2 w-2 rounded-full bg-white" data-carousel-dot aria-label="<?php esc_attr_e( 'Slide 1', 'extrasport' ); ?>"></button>
					<?php foreach ( $banners as $i => $_b ) : ?>
						<button type="button" class="carousel-dot h-2 w-2 rounded-full bg-white/40" data-carousel-dot aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'extrasport' ), $i + 2 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<button type="button" class="carousel-arrow carousel-arrow--prev absolute left-4 top-1/2 z-20 -translate-y-1/2" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous slide', 'extrasport' ); ?>">
				<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
			</button>
			<button type="button" class="carousel-arrow carousel-arrow--next absolute right-4 top-1/2 z-20 -translate-y-1/2" data-carousel-next aria-label="<?php esc_attr_e( 'Next slide', 'extrasport' ); ?>">
				<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
			</button>
		</div>

		<!-- Mobile carousel -->
		<div class="carousel md:hidden relative h-[60vh] min-h-[360px] overflow-hidden" data-carousel data-carousel-interval="8000" id="carouselMobile">
			<div class="carousel-track relative h-full">
				<?php extrasport_render_carousel_slides( $banners, $site_name, $uri ); ?>
			</div>
			<?php if ( count( $banners ) > 0 ) : ?>
				<div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-2">
					<button type="button" class="carousel-dot is-active h-2 w-2 rounded-full bg-white" data-carousel-dot></button>
					<?php foreach ( $banners as $_b ) : ?>
						<button type="button" class="carousel-dot h-2 w-2 rounded-full bg-white/40" data-carousel-dot></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<button type="button" class="carousel-arrow carousel-arrow--prev absolute left-2 top-1/2 z-20 -translate-y-1/2" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous', 'extrasport' ); ?>">
				<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
			</button>
			<button type="button" class="carousel-arrow carousel-arrow--next absolute right-2 top-1/2 z-20 -translate-y-1/2" data-carousel-next aria-label="<?php esc_attr_e( 'Next', 'extrasport' ); ?>">
				<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
			</button>
		</div>
	</header>

	<!-- About / Service video -->
	<section id="about" class="relative overflow-hidden bg-black">
		<video class="hidden w-full md:block" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.mp4' ); ?>" type="video/mp4">
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.webm' ); ?>" type="video/webm">
		</video>
		<video class="block w-full md:hidden" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.mp4' ); ?>" type="video/mp4">
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.webm' ); ?>" type="video/webm">
		</video>
	</section>

	<!-- Shares / Actions -->
	<section id="actions" class="bg-brand-dark py-16 md:py-24">
		<div class="mx-auto max-w-7xl px-4 lg:px-6">
			<h2 class="font-oswald mb-12 text-center text-3xl uppercase md:text-4xl">
				<?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $site_name ) ); ?>
			</h2>

			<?php if ( $shares ) : ?>
				<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
					<?php foreach ( $shares as $index => $share ) : ?>
						<?php
						$share_date    = get_post_meta( $share->ID, '_share_date', true );
						$share_excerpt = get_post_meta( $share->ID, '_share_excerpt', true );
						$hidden_class  = ( 2 === $index ) ? 'hidden lg:block' : '';
						?>
						<a href="<?php echo esc_url( get_permalink( $share->ID ) ); ?>" class="share-card group overflow-hidden rounded-lg bg-white/5 border border-white/10 transition hover:border-brand-primary <?php echo esc_attr( $hidden_class ); ?>">
							<div class="relative">
								<?php if ( $share_date ) : ?>
									<span class="absolute left-3 top-3 z-10 rounded bg-brand-primary px-3 py-1 text-xs font-semibold uppercase"><?php echo esc_html( $share_date ); ?></span>
								<?php endif; ?>
								<?php if ( has_post_thumbnail( $share->ID ) ) : ?>
									<?php echo get_the_post_thumbnail( $share->ID, 'large', array( 'class' => 'aspect-[4/3] w-full object-cover transition group-hover:scale-105' ) ); ?>
								<?php else : ?>
									<div class="aspect-[4/3] bg-white/10"></div>
								<?php endif; ?>
							</div>
							<div class="flex items-center justify-between gap-4 p-4">
								<div>
									<h3 class="font-oswald text-lg uppercase text-white group-hover:text-brand-primary"><?php echo esc_html( $share->post_title ); ?></h3>
									<p class="mt-1 text-sm text-white/70 line-clamp-2">
										<?php echo esc_html( $share_excerpt ?: wp_trim_words( $share->post_content, 15 ) ); ?>
									</p>
								</div>
								<i class="fa-solid fa-arrow-right shrink-0 text-brand-primary opacity-0 transition group-hover:opacity-100" aria-hidden="true"></i>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="text-center text-white/60"><?php esc_html_e( 'Акции скоро появятся.', 'extrasport' ); ?></p>
			<?php endif; ?>

			<div class="mt-10 text-center">
				<a href="<?php echo esc_url( home_url( '/shares/' ) ); ?>" class="btn-primary btn-lg">
					<?php esc_html_e( 'Все акции', 'extrasport' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Test-drive / Subscribe -->
	<section id="contact" class="test-drive relative flex min-h-[520px] items-center overflow-hidden py-16">
		<video class="absolute inset-0 hidden h-full w-full object-cover md:block" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/test-drive.mp4' ); ?>" type="video/mp4">
		</video>
		<video class="absolute inset-0 block h-full w-full object-cover md:hidden" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/test-drive_mobile.mp4' ); ?>" type="video/mp4">
		</video>
		<div class="absolute inset-0 bg-black/60"></div>

		<div class="relative z-10 mx-auto max-w-7xl px-4 lg:px-6">
			<h2 class="font-oswald mb-10 text-center text-3xl uppercase md:text-4xl">
				<?php esc_html_e( 'Фитнес тест-драйв', 'extrasport' ); ?>
			</h2>
			<div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
				<div class="space-y-4 text-lg text-white/90">
					<p><?php esc_html_e( 'Хотите больше узнать о нашем клубе? Оставьте заявку, и наши менеджеры проведут для вас подробную экскурсию.', 'extrasport' ); ?></p>
					<p><?php esc_html_e( 'Для тех, кому экскурсии мало, мы предлагаем услугу «фитнес тест-драйв» — безлимитную неделю фитнеса!', 'extrasport' ); ?></p>
				</div>
				<form id="subscribe" class="mx-auto w-full max-w-md space-y-4" action="#" method="post" novalidate>
					<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
					<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
					<div class="flex items-start gap-2 text-sm">
						<input type="checkbox" name="accept" id="soglas-subscribe" class="mt-1">
						<label for="soglas-subscribe">
							<?php
							printf(
								wp_kses_post( __( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer" class="underline hover:text-brand-primary">политикой конфиденциальности</a>', 'extrasport' ) ),
								esc_url( $club['privacy_url'] )
							);
							?>
						</label>
					</div>
					<div class="form-error hidden text-sm text-red-400" role="alert"></div>
					<button type="submit" class="btn-primary btn-lg w-full justify-center uppercase">
						<?php esc_html_e( 'Записаться', 'extrasport' ); ?>
					</button>
				</form>
			</div>
		</div>
	</section>

	<!-- Contacts + Map -->
	<section id="contacts" class="map-section relative bg-brand-dark bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url( $uri . '/assets/img/bg_contact.jpeg' ); ?>')">
		<div class="pointer-events-none absolute inset-0 bg-brand-dark/75" aria-hidden="true"></div>
		<div class="relative z-10 mx-auto max-w-7xl px-4 py-12 lg:px-6 lg:py-16">
			<div class="max-w-md">
				<h2 class="font-oswald mb-8 text-3xl uppercase"><?php esc_html_e( 'Контакты', 'extrasport' ); ?></h2>
				<ul class="space-y-5 text-white/90">
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-mobile-screen text-xl text-brand-primary mt-0.5" aria-hidden="true"></i>
						<a href="tel:<?php echo esc_attr( $tel_clean ); ?>" class="text-xl hover:text-brand-primary"><?php echo esc_html( $club['tel'] ); ?></a>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-regular fa-envelope text-brand-primary mt-0.5" aria-hidden="true"></i>
						<a href="mailto:<?php echo esc_attr( $club['email'] ); ?>" class="hover:text-brand-primary"><?php echo esc_html( $club['email'] ); ?></a>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-location-dot text-brand-primary mt-0.5" aria-hidden="true"></i>
						<span><?php echo esc_html( $club['address'] ); ?></span>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-train-subway text-brand-primary mt-0.5" aria-hidden="true"></i>
						<span><?php echo esc_html( $club['metro'] ); ?></span>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-regular fa-clock text-brand-primary mt-0.5" aria-hidden="true"></i>
						<div class="text-sm">
							<?php esc_html_e( 'Время работы', 'extrasport' ); ?><br>
							<?php esc_html_e( 'пн–пт:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work'] ); ?><br>
							<?php esc_html_e( 'сб–вс:', 'extrasport' ); ?> <?php echo esc_html( $club['start_work_weekend'] ); ?>
						</div>
					</li>
					<li class="flex items-start gap-3">
						<i class="fa-solid fa-user-tie text-brand-primary mt-0.5" aria-hidden="true"></i>
						<div class="text-sm">
							<?php esc_html_e( 'Отдел продаж:', 'extrasport' ); ?><br>
							<?php esc_html_e( 'пн-вс: 10:00 до 22:00', 'extrasport' ); ?>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div
			id="map"
			class="map-section__map relative h-[400px] w-full lg:absolute lg:inset-y-0 lg:right-0 lg:h-auto lg:w-[55%]"
			data-coords="<?php echo esc_attr( $club['coordinates'] ); ?>"
			data-marker="<?php echo esc_url( $uri . '/assets/img/marker.png' ); ?>"
		></div>
	</section>

</div>

<?php
wp_reset_postdata();
get_footer();
