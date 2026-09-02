<?php
/**
 * Front page helpers — carousel rendering and share placeholders.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			'type' => 'video',
			'mp4'  => $uri . '/assets/video/test-drive.mp4',
		),
	);
}

/**
 * Render default hero slide (video/image + heading).
 *
 * @param array<string, mixed>       $club      Club settings.
 * @param string                     $uri       Theme URI.
 * @param bool                       $is_active Whether slide is initially active.
 * @param array<string, string>|null $slide     Slide media config.
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
	return empty( $banner_posts ) ? 2 : 1 + count( $banner_posts );
}

/**
 * Render carousel slides markup.
 *
 * @param array<int, WP_Post>  $banner_posts Banner posts.
 * @param array<string, mixed> $club         Club settings.
 * @param string               $uri          Theme URI.
 * @return void
 */
function extrasport_render_carousel_slides( $banner_posts, $club, $uri ) {
	if ( empty( $banner_posts ) ) {
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

/**
 * Demo shares for the homepage actions block.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_share_placeholders( $uri ) {
	$archive_url = extrasport_get_shares_archive_url();
	$items       = array();

	foreach ( extrasport_get_share_seed_templates() as $template ) {
		$items[] = array(
			'title'   => $template['title'],
			'excerpt' => $template['excerpt'],
			'date'    => $template['date'],
			'image'   => $uri . '/' . ltrim( $template['image'], '/' ),
			'url'     => trailingslashit( $archive_url ) . $template['slug'] . '/',
		);
	}

	return array_slice( $items, 0, 3 );
}

/**
 * Normalize a share post into card data.
 *
 * @param WP_Post $share Share post.
 * @return array{title: string, excerpt: string, date: string, image: string, url: string}
 */
function extrasport_normalize_share_post( WP_Post $share ) {
	$share_excerpt = get_post_meta( $share->ID, '_share_excerpt', true );

	return array(
		'title'   => $share->post_title,
		'excerpt' => $share_excerpt ?: wp_trim_words( $share->post_content, 15 ),
		'date'    => (string) get_post_meta( $share->ID, '_share_date', true ),
		'image'   => get_the_post_thumbnail_url( $share->ID, 'large' ) ?: '',
		'url'     => get_permalink( $share->ID ),
	);
}

/**
 * Shares for the homepage — CPT first, demo fallback.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_front_page_shares( $uri ) {
	$share_posts = get_posts(
		array(
			'post_type'      => 'share',
			'posts_per_page' => 3,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( empty( $share_posts ) ) {
		return extrasport_get_share_placeholders( $uri );
	}

	return array_map( 'extrasport_normalize_share_post', $share_posts );
}
