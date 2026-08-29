<?php
/**
 * Front Page Template (Home)
 * 
 * Adapted from Yii2 site/index.php
 * Shows: Banners, About video, Shares (Actions), Subscribe, Map & Contacts
 *
 * @package ExtraSport
 */

get_header();

// Get blog name dynamically (for multisite)
$site_name = get_bloginfo( 'name' );
?>

<main id="main" class="site-main front-page-main">

    <!-- ============================================================
         CAROUSEL / BANNERS SECTION
         ============================================================ -->
    <section id="banners" class="banners-carousel-section">
        <div class="carousel carousel-fade d-none d-md-block" id="carouselMain" data-bs-ride="carousel" data-bs-interval="5000">
            
            <!-- Default First Slide -->
            <div class="carousel-item active" data-bs-interval="5000">
                <div class="carousel-caption-custom">
                    <h1 class="carousel-title">Сеть фитнес клубов на результат!</h1>
                    <p class="carousel-subtitle">Ваш клуб — <?php echo esc_html( $site_name ); ?></p>
                </div>
                <video class="carousel-video w-100" muted autoplay loop>
                    <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/bg_motion.mp4' ); ?>" type="video/mp4">
                </video>
            </div>

            <!-- Banners from Posts -->
            <?php
            $banners = get_posts( array(
                'post_type'      => 'banner',
                'posts_per_page' => 10,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ) );

            if ( $banners ) {
                foreach ( $banners as $banner ) {
                    $banner_title = get_post_meta( $banner->ID, '_banner_title', true );
                    $banner_subtitle = get_post_meta( $banner->ID, '_banner_subtitle', true );
                    $banner_link = get_post_meta( $banner->ID, '_banner_link', true );
                    $banner_image = get_post_meta( $banner->ID, '_banner_image', true );
                    ?>
                    <div class="carousel-item" data-bs-interval="5000">
                        <div class="carousel-caption-custom">
                            <h2 class="carousel-title"><?php echo esc_html( $banner_title ?: $banner->post_title ); ?></h2>
                            <p class="carousel-subtitle"><?php echo esc_html( $banner_subtitle ); ?></p>
                            <?php if ( $banner_link ) { ?>
                                <a href="<?php echo esc_url( $banner_link ); ?>" class="btn btn-primary btn-lg">Узнать больше »</a>
                            <?php } ?>
                        </div>
                        <?php if ( has_post_thumbnail( $banner->ID ) ) { ?>
                            <figure class="carousel-image">
                                <?php echo get_the_post_thumbnail( $banner->ID, 'full' ); ?>
                            </figure>
                        <?php } elseif ( $banner_image ) { ?>
                            <img src="<?php echo esc_url( $banner_image ); ?>" alt="<?php echo esc_attr( $banner->post_title ); ?>" class="w-100">
                        <?php } ?>
                    </div>
                    <?php
                }
            }
            wp_reset_postdata();
            ?>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMain" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e( 'Previous', 'extrasport' ); ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMain" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e( 'Next', 'extrasport' ); ?></span>
            </button>
        </div>

        <!-- Mobile Carousel -->
        <div class="carousel carousel-fade d-block d-md-none" id="carouselMobile" data-bs-ride="carousel" data-bs-interval="5000">
            
            <div class="carousel-item active" data-bs-interval="5000">
                <div class="carousel-caption-custom">
                    <h1 class="carousel-title">Сеть фитнес клубов на результат!</h1>
                    <p class="carousel-subtitle">Ваш клуб — <?php echo esc_html( $site_name ); ?></p>
                </div>
                <video class="carousel-video w-100" muted autoplay loop>
                    <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/bg_motion_mobile.mp4' ); ?>" type="video/mp4">
                </video>
            </div>

            <?php
            if ( $banners ) {
                foreach ( $banners as $banner ) {
                    $banner_title = get_post_meta( $banner->ID, '_banner_title', true );
                    $banner_subtitle = get_post_meta( $banner->ID, '_banner_subtitle', true );
                    $banner_link = get_post_meta( $banner->ID, '_banner_link', true );
                    ?>
                    <div class="carousel-item" data-bs-interval="5000">
                        <div class="carousel-caption-custom">
                            <h2 class="carousel-title"><?php echo esc_html( $banner_title ?: $banner->post_title ); ?></h2>
                            <p class="carousel-subtitle"><?php echo esc_html( $banner_subtitle ); ?></p>
                            <?php if ( $banner_link ) { ?>
                                <a href="<?php echo esc_url( $banner_link ); ?>" class="btn btn-primary btn-lg">Узнать больше »</a>
                            <?php } ?>
                        </div>
                        <?php if ( has_post_thumbnail( $banner->ID ) ) { ?>
                            <figure class="carousel-image">
                                <?php echo get_the_post_thumbnail( $banner->ID, 'full' ); ?>
                            </figure>
                        <?php } ?>
                    </div>
                    <?php
                }
            }
            wp_reset_postdata();
            ?>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMobile" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMobile" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>

    <!-- ============================================================
         ABOUT / VIDEO SECTION
         ============================================================ -->
    <section id="about" class="about-section position-relative">
        <video class="about-video w-100 d-none d-md-block" muted autoplay loop>
            <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/service.mp4' ); ?>" type="video/mp4">
        </video>
        <video class="about-video w-100 d-block d-md-none" muted autoplay loop>
            <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/service_mobile.mp4' ); ?>" type="video/mp4">
        </video>
    </section>

    <!-- ============================================================
         SHARES / ACTIONS SECTION
         ============================================================ -->
    <section id="actions" class="actions-section page-section">
        <div class="container">
            <div class="text-center section-header">
                <h2 class="section-title"><?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $site_name ) ); ?></h2>
            </div>

            <div class="shares-grid">
                <?php
                $shares = get_posts( array(
                    'post_type'      => 'share',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );

                if ( $shares ) {
                    foreach ( $shares as $index => $share ) {
                        $share_date = get_post_meta( $share->ID, '_share_date', true );
                        $share_excerpt = get_post_meta( $share->ID, '_share_excerpt', true );
                        ?>
                        <div class="share-card <?php if ( $index === 2 ) { echo 'd-lg-block d-md-none'; } ?>">
                            <a href="<?php echo esc_url( get_permalink( $share->ID ) ); ?>" class="share-link">
                                <?php if ( $share_date ) { ?>
                                    <div class="share-date-badge"><?php echo esc_html( $share_date ); ?></div>
                                <?php } ?>

                                <?php if ( has_post_thumbnail( $share->ID ) ) { ?>
                                    <figure class="share-image">
                                        <?php echo get_the_post_thumbnail( $share->ID, 'large' ); ?>
                                    </figure>
                                <?php } ?>

                                <div class="share-content">
                                    <h3 class="share-title"><?php echo esc_html( $share->post_title ); ?></h3>
                                    <p class="share-excerpt"><?php echo esc_html( wp_trim_words( $share->post_content, 20 ) ); ?></p>
                                    <div class="share-arrow">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="text-center">' . esc_html__( 'No shares available.', 'extrasport' ) . '</p>';
                }
                wp_reset_postdata();
                ?>
            </div>

            <div class="text-center section-footer">
                <a href="<?php echo esc_url( home_url( '/shares/' ) ); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e( 'Все акции', 'extrasport' ); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SUBSCRIBE SECTION
         ============================================================ -->
    <section id="subscribe" class="subscribe-section">
        <?php
        // Get subscribe form or fallback to simple form
        $subscribe_form = get_posts( array(
            'post_type'      => 'page',
            'meta_key'       => '_is_subscribe_form',
            'meta_value'     => 1,
            'posts_per_page' => 1,
        ) );

        if ( $subscribe_form && isset( $subscribe_form[0] ) ) {
            echo apply_filters( 'the_content', $subscribe_form[0]->post_content );
        } else {
            ?>
            <div class="container">
                <div class="subscribe-form-wrapper text-center">
                    <h3><?php esc_html_e( 'Subscribe to our updates', 'extrasport' ); ?></h3>
                    <form class="subscribe-form" method="post">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e( 'Your email', 'extrasport' ); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'extrasport' ); ?></button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </section>

    <!-- ============================================================
         CONTACTS SECTION WITH MAP
         ============================================================ -->
    <section id="contacts" class="contacts-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="contacts-info">
                        <h2 class="section-title"><?php esc_html_e( 'Контакты', 'extrasport' ); ?></h2>
                        
                        <ul class="contacts-list">
                            <!-- Phone -->
                            <li class="contact-item contact-phone">
                                <i class="fa-solid fa-mobile"></i>
                                <a href="tel:+79031234567" class="contact-link">+7 903 123-45-67</a>
                            </li>

                            <!-- Email -->
                            <li class="contact-item contact-email">
                                <i class="fa-solid fa-envelope"></i>
                                <a href="mailto:info@extrasport.local" class="contact-link">info@extrasport.local</a>
                            </li>

                            <!-- Address -->
                            <li class="contact-item contact-address">
                                <i class="fa-solid fa-location-dot"></i>
                                <span class="contact-text">Москва, ул. Примера, 1</span>
                            </li>

                            <!-- Metro -->
                            <li class="contact-item contact-metro">
                                <i class="fa-solid fa-train-subway"></i>
                                <span class="contact-text">м. Красные ворота, м. Комсомольская</span>
                            </li>

                            <!-- Hours -->
                            <li class="contact-item contact-hours">
                                <i class="fa-solid fa-clock"></i>
                                <div class="contact-hours-text">
                                    <strong><?php esc_html_e( 'Время работы:', 'extrasport' ); ?></strong>
                                    <div><?php esc_html_e( 'Пн-Пт: 06:00 - 23:00', 'extrasport' ); ?></div>
                                    <div><?php esc_html_e( 'Сб-Вс: 08:00 - 22:00', 'extrasport' ); ?></div>
                                </div>
                            </li>

                            <!-- Sales -->
                            <li class="contact-item contact-sales">
                                <i class="fa-solid fa-user-tie"></i>
                                <div class="contact-sales-text">
                                    <strong><?php esc_html_e( 'Отдел продаж:', 'extrasport' ); ?></strong>
                                    <div><?php esc_html_e( 'Пн-Вс: 10:00 - 22:00', 'extrasport' ); ?></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div id="map" class="map-container"></div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
?>
