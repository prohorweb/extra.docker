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
$brand     = extrasport_get_brand();
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

$shares           = extrasport_get_front_page_shares( $uri );
$hero_slide_count = extrasport_get_hero_slide_count( $banners );

/**
 * Placeholder hero slides until banner CPT is populated.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{type: string, mp4?: string, webm?: string, src?: string}>
 */
function extrasport_get_hero_placeholder_videos( $uri ) {
	return array(
		array(
			'type' => 'video',
			'mp4'  => $uri . '/assets/video/bg_moution.mp4',
			'webm' => $uri . '/assets/video/bg_moution.webm',
		),
		array(
			'type' => 'image',
			'src'  => $uri . '/assets/img/actions-bg.jpg',
		),
		array(
			'type' => 'video',
			'mp4'  => $uri . '/assets/video/test-drive.mp4',
		),
	);
}

/**
 * Render default hero slide (video/image + heading).
 *
 * @param array<string, mixed> $club      Club settings.
 * @param string               $uri       Theme URI.
 * @param bool                 $is_active Whether slide is initially active.
 * @param array<string, string>|null $slide Slide media config.
 * @return void
 */
function extrasport_render_hero_carousel_slide( $club, $uri, $is_active = false, $slide = null ) {
	$slide        = $slide ?? extrasport_get_hero_placeholder_videos( $uri )[0];
	$active_class = $is_active ? ' is-active' : '';
	$aria_hidden  = $is_active ? 'false' : 'true';
	?>
	<div data-carousel-slide class="carousel-slide<?php echo esc_attr( $active_class ); ?>" aria-hidden="<?php echo esc_attr( $aria_hidden ); ?>">
		<div class="masthead__content">
			<h1 class="masthead-heading">
				<?php esc_html_e( 'Сеть фитнес клубов на результат!', 'extrasport' ); ?>
				<span class="masthead-subheading block"><?php printf( esc_html__( 'Ваш клуб — %s', 'extrasport' ), esc_html( $club['title'] ) ); ?></span>
			</h1>
		</div>
		<?php if ( 'image' === ( $slide['type'] ?? 'video' ) ) : ?>
			<img
				class="carousel-slide__media"
				src="<?php echo esc_url( $slide['src'] ); ?>"
				alt=""
			>
		<?php else : ?>
			<video class="carousel-slide__media" muted autoplay loop playsinline>
				<source src="<?php echo esc_url( $slide['mp4'] ); ?>" type="video/mp4">
				<?php if ( ! empty( $slide['webm'] ) ) : ?>
					<source src="<?php echo esc_url( $slide['webm'] ); ?>" type="video/webm">
				<?php endif; ?>
			</video>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render hero carousel dot navigation.
 *
 * @param int $slide_count Total slides.
 * @return void
 */
function extrasport_render_hero_carousel_dots( $slide_count ) {
	if ( $slide_count <= 1 ) {
		return;
	}
	?>
	<div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-2 md:bottom-6">
		<?php for ( $i = 0; $i < $slide_count; $i++ ) : ?>
			<button
				type="button"
				class="carousel-dot h-2 w-2 rounded-full bg-white/40<?php echo 0 === $i ? ' is-active' : ''; ?>"
				data-carousel-dot
				aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'extrasport' ), $i + 1 ) ); ?>"
			></button>
		<?php endfor; ?>
	</div>
	<?php
}

/**
 * Total hero carousel slides (placeholder duplicates when no banners).
 *
 * @param array<int, WP_Post> $banner_posts Banner posts.
 * @return int
 */
function extrasport_get_hero_slide_count( $banner_posts ) {
	return empty( $banner_posts ) ? 3 : 1 + count( $banner_posts );
}

/**
 * Render carousel slides markup.
 *
 * @param array<int, WP_Post> $banner_posts Banner posts.
 * @param array<string, mixed> $club        Club settings.
 * @param string               $uri         Theme URI.
 * @return void
 */
function extrasport_render_carousel_slides( $banner_posts, $club, $uri ) {
	if ( empty( $banner_posts ) ) {
		// Temporary placeholders until banner CPT content is imported.
		foreach ( extrasport_get_hero_placeholder_videos( $uri ) as $index => $slide ) {
			extrasport_render_hero_carousel_slide( $club, $uri, 0 === $index, $slide );
		}
		return;
	}

	extrasport_render_hero_carousel_slide( $club, $uri, true );

	foreach ( $banner_posts as $banner ) {
		$title    = get_post_meta( $banner->ID, '_banner_title', true ) ?: $banner->post_title;
		$subtitle = get_post_meta( $banner->ID, '_banner_subtitle', true );
		$link     = get_post_meta( $banner->ID, '_banner_link', true );
		$image    = get_post_meta( $banner->ID, '_banner_image', true );
		?>
		<div data-carousel-slide class="carousel-slide" aria-hidden="true">
			<div class="masthead__content items-end justify-end p-6 md:p-10">
				<div class="max-w-lg text-center md:text-right">
					<h2 class="masthead-heading text-2xl md:text-4xl"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $subtitle ) : ?>
						<p class="masthead-subheading mt-2 text-xl md:text-2xl"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<?php if ( $link ) : ?>
						<a href="<?php echo esc_url( $link ); ?>" class="btn-primary btn-lg mt-4 inline-flex bg-black hover:bg-black/80">
							<?php esc_html_e( 'Узнать больше »', 'extrasport' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( has_post_thumbnail( $banner->ID ) ) : ?>
				<?php echo get_the_post_thumbnail( $banner->ID, 'full', array( 'class' => 'carousel-slide__media' ) ); ?>
			<?php elseif ( $image ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="carousel-slide__media">
			<?php endif; ?>
		</div>
		<?php
	}
}
?>

<div class="page-content front-page-main">

	<!-- Hero / Carousel -->
	<header class="masthead">
		<!-- Desktop carousel: wheel navigation, no auto-advance -->
		<div
			class="carousel carousel--hero hidden md:block"
			data-carousel
			data-carousel-wheel
			data-carousel-interval="false"
			id="carouselDesktop"
		>
			<div class="carousel-track">
				<?php extrasport_render_carousel_slides( $banners, $club, $uri ); ?>
			</div>
			<?php extrasport_render_hero_carousel_dots( $hero_slide_count ); ?>
		</div>

		<!-- Mobile carousel: auto-advance every 8s -->
		<div
			class="carousel carousel--hero md:hidden"
			data-carousel
			data-carousel-interval="8000"
			id="carouselMobile"
		>
			<div class="carousel-track">
				<?php extrasport_render_carousel_slides( $banners, $club, $uri ); ?>
			</div>
			<?php extrasport_render_hero_carousel_dots( $hero_slide_count ); ?>
		</div>
	</header>

	<!-- About / Service video -->
	<section id="about" class="page-section page-section--media bg-black">
		<video class="hidden md:block" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.mp4' ); ?>" type="video/mp4">
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.webm' ); ?>" type="video/webm">
		</video>
		<video class="block md:hidden" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.mp4' ); ?>" type="video/mp4">
			<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.webm' ); ?>" type="video/webm">
		</video>
	</section>

	<!-- Shares / Actions -->
	<section
		id="actions"
		class="page-section page-section--centered page-section--actions"
		style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
	>
		<div class="page-section__inner">
			<div class="text-center">
				<h2 class="section-heading">
					<?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $club['title'] ) ); ?>
				</h2>
			</div>

			<div class="grid gap-6 text-center md:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $shares as $index => $share ) : ?>
					<?php $hidden_class = ( 2 === $index ) ? 'md:hidden lg:block' : ''; ?>
					<a href="<?php echo esc_url( $share['url'] ); ?>" class="share-card <?php echo esc_attr( $hidden_class ); ?>">
						<?php if ( ! empty( $share['date'] ) ) : ?>
							<div class="date-action"><?php echo esc_html( $share['date'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $share['image'] ) ) : ?>
							<img class="card-img-top" src="<?php echo esc_url( $share['image'] ); ?>" alt="<?php echo esc_attr( $share['title'] ); ?>">
						<?php else : ?>
							<div class="card-img-top bg-white/10"></div>
						<?php endif; ?>
						<div class="card-body">
							<div class="card-body__row">
								<div class="card-body_wrapper">
									<h5 class="card-title"><?php echo esc_html( $share['title'] ); ?></h5>
									<?php if ( ! empty( $share['excerpt'] ) ) : ?>
										<div class="card-text"><?php echo esc_html( $share['excerpt'] ); ?></div>
									<?php endif; ?>
								</div>
								<div class="btn-arrow" aria-hidden="true">
									<i class="fa-sharp fa-solid fa-arrow-right"></i>
								</div>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>

			<div class="flex justify-center pb-8 pt-4">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' ) ); ?>" class="btn-xl">
					<?php esc_html_e( 'Все акции', 'extrasport' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Test-drive / Subscribe -->
	<section id="contact" class="page-section page-section--centered page-section--test-drive test-drive relative">
		<video class="absolute inset-0 hidden h-full w-full object-cover md:block" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/test-drive.mp4' ); ?>" type="video/mp4">
		</video>
		<video class="absolute inset-0 block h-full w-full object-cover md:hidden" muted autoplay loop playsinline>
			<source src="<?php echo esc_url( $uri . '/assets/video/test-drive_mobile.mp4' ); ?>" type="video/mp4">
		</video>
		<div class="absolute inset-0 bg-black/60"></div>

		<div class="page-section__inner">
			<h2 class="font-oswald mb-10 text-center text-3xl uppercase md:text-4xl">
				<?php esc_html_e( 'Фитнес тест-драйв', 'extrasport' ); ?>
			</h2>
			<div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
				<div class="space-y-4 text-lg text-white/90">
					<p><?php esc_html_e( 'Хотите больше узнать о нашем клубе? Оставьте заявку, и наши менеджеры проведут для вас подробную экскурсию.', 'extrasport' ); ?></p>
					<p><?php esc_html_e( 'Для тех, кому экскурсии мало, мы предлагаем услугу «фитнес тест-драйв» — безлимитную неделю фитнеса!', 'extrasport' ); ?></p>
				</div>
				<form id="subscribe" class="mx-auto w-full max-w-md space-y-4" action="#" method="post" novalidate data-form-type="subscribe">
					<?php get_template_part( 'template-parts/layout/form', 'honeypot', array( 'form_id' => 'subscribe-front' ) ); ?>
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
	<section id="contacts" class="page-section map-section relative bg-brand-dark bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url( $uri . '/assets/img/bg_contact.jpeg' ); ?>')">
		<div class="pointer-events-none absolute inset-0 bg-brand-dark/75" aria-hidden="true"></div>
		<div class="map-section__content relative z-10 mx-auto w-full max-w-7xl px-4 lg:px-6">
			<div class="max-w-md py-12 lg:py-0">
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
							<?php esc_html_e( 'пн-вс:', 'extrasport' ); ?> <?php echo esc_html( $club['sales_work'] ); ?>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div
			id="map"
			class="map-section__map relative min-h-[50vh] w-full lg:absolute lg:inset-y-0 lg:right-0 lg:min-h-0 lg:h-full lg:w-[55%]"
			data-coords="<?php echo esc_attr( $club['coordinates'] ); ?>"
			data-marker="<?php echo esc_url( $brand['marker_url'] ); ?>"
			data-title="<?php echo esc_attr( $club['title'] ); ?>"
			data-hint="<?php echo esc_attr( $club['title'] ); ?>"
			data-balloon="<?php echo esc_attr( $club['address'] ); ?>"
		></div>
	</section>

</div>

<?php
wp_reset_postdata();
get_footer();
