<?php

use InstagramScraper\Instagram;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $model \common\models\Promo */

?>

    <header class="header header--minimal">
        <div class="container">
            <div class="header__row">
                <div class="header__logo"><img src="/images/logo-white.svg" alt=""></div>
                <div class="header__phone"><?= $model->phone ?></div>
            </div>
        </div>
    </header>

    <!-- section class="breadcramb-section breadcramb-section--pb0">
        <div class="container">
            <div class="breadcramb">
                <a href="/">Главная страница</a>
                <span>Промо</span>
            </div>
        </div>
    </section -->

    <main class="main-section main-section--lending-page lending-page">
        <div class="lending-page__banner"><img src="<?= $model->img ? '/uploads/image/promo/' . $model->img : '//placehold.it/1904x643' ?>" alt=""></div>
        <div class="container container--min">
            <div class="lending-page__well">
                <?= $model->text ?>
            </div>

            <table class="lending-page__table">
                <tr class="lending-page__head">
                    <th class="lending-page__head__item lending-page__head__item--black">Подарок</th>
                    <th class="lending-page__head__item">ТК «ПИТЕР»</th>
                    <th class="lending-page__head__item">ТК «родео <br>драйв»</th>
                    <th class="lending-page__head__item">ТК «июнь»</th>
                    <th class="lending-page__head__item">ТК «южный <br>полюс»</th>
                    <th class="lending-page__head__item">«Матроса <br>Железняка»</th>
                </tr>
                <tr class="lending-page__row lending-page__row--adres">
                    <td class="lending-page__cell"></td>
                    <td class="lending-page__cell">
                        <p>Фитнес клуб с бассейном</p>
                        <p>Санкт-Петербург, <br>ул. Типанова, 21</p>
                    </td>
                    <td class="lending-page__cell">
                        <p>Фитнес клуб с бассейном</p>
                        <p>Санкт-Петербург, <br> пр. Культуры, 1</p>
                    </td>
                    <td class="lending-page__cell">
                        <p>Санкт-Петербург, <br>Индустриальный <br> пр., 24</p>
                    </td>
                    <td class="lending-page__cell">
                        <p>Санкт-Петербург, <br>Пражская ул., <br> 48/50</p>
                    </td>
                    <td class="lending-page__cell">
                        <p>Санкт-Петербург,<br> ул. Матроса <br>Железняка, 57 А</p>
                    </td>
                </tr>

                <tr class="lending-page__row">
                    <td class="lending-page__cell"><img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt=""></td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="0"><?= $model->price1_piter ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="1"><?= $model->price1_rodeo ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="2"><?= $model->price1_june ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="1 месяц"  data-club="3"><?= $model->price1_polis ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="4"><?= $model->price1_matros ?> <span>₽</span></a>
                        </p>
                    </td>
                </tr>
                <tr class="lending-page__row lending-page__row--sep">
                    <td class="lending-page__desc" colspan="6">Чтобы оставить заявку, нажмите на цену</td>
                </tr>

                <tr class="lending-page__row">
                    <td class="lending-page__cell"><img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt=""></td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="0"><?= $model->price3_piter ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="1"><?= $model->price3_rodeo ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="2"><?= $model->price3_june ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="3"><?= $model->price3_polis ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="4"><?= $model->price3_matros ?> <span>₽</span></a>
                        </p>
                    </td>
                </tr>
                <tr class="lending-page__row lending-page__row--sep">
                    <td class="lending-page__desc" colspan="6">Чтобы оставить заявку, нажмите на цену</td>
                </tr>

                <tr class="lending-page__row">
                    <td class="lending-page__cell"><img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt=""></td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="0"><?= $model->price6_piter ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="1"><?= $model->price6_rodeo ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="2"><?= $model->price6_june ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="3"><?= $model->price6_polis ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="4"><?= $model->price6_matros ?> <span>₽</span></a>
                        </p>
                    </td>
                </tr>
                <tr class="lending-page__row lending-page__row--sep">
                    <td class="lending-page__desc" colspan="6">Чтобы оставить заявку, нажмите на цену</td>
                </tr>

                <tr class="lending-page__row">
                    <td class="lending-page__cell"><img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt=""></td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="0"><?= $model->price12_piter ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="1"><?= $model->price12_rodeo ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="2"><?= $model->price12_june ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="3"><?= $model->price12_polis ?> <span>₽</span></a>
                        </p>
                    </td>
                    <td class="lending-page__cell">
                        <p class="lending-price">
                            <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="4"><?= $model->price12_matros ?> <span>₽</span></a>
                        </p>
                    </td>
                </tr>
                <tr class="lending-page__row lending-page__row--sep">
                    <td class="lending-page__desc" colspan="6">Чтобы оставить заявку, нажмите на цену</td>
                </tr>
            </table>

            <div class="lending-page__list">
                <div class="lending-page__list__item">
                    <div class="lending-price-block">
                        <div class="lending-price-block__title">ТК «ПИТЕР»</div>
                        <div class="lending-price-block__desc">
                            <p>Фитнес клуб с бассейном</p>
                            <p>Санкт-Петербург, ул. Типанова, 21</p>
                        </div>
                        <div class="lending-price-block__row row">
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="0"><?= $model->price1_piter ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="0"><?= $model->price3_piter ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="0"><?= $model->price6_piter ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="0"><?= $model->price12_piter ?> <span>₽</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="lending-price-block__desc last">Чтобы оставить заявку, нажмите на цену</div>
                    </div>
                </div>
                <div class="lending-page__list__item">
                    <div class="lending-price-block">
                        <div class="lending-price-block__title">ТК «родео драйв»</div>
                        <div class="lending-price-block__desc">
                            <p>Фитнес клуб с бассейном</p>
                            <p>Санкт-Петербург, пр. Культуры, 1</p>
                        </div>
                        <div class="lending-price-block__row row">
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="1"><?= $model->price1_rodeo ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="1"><?= $model->price3_rodeo ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="1"><?= $model->price6_rodeo ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="1"><?= $model->price12_rodeo ?> <span>₽</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="lending-price-block__desc last">Чтобы оставить заявку, нажмите на цену</div>
                    </div>
                </div>
                <div class="lending-page__list__item">
                    <div class="lending-price-block">
                        <div class="lending-price-block__title">ТК «июнь»</div>
                        <div class="lending-price-block__desc">Санкт-Петербург, <br>Индустриальный пр., 24</div>
                        <div class="lending-price-block__row row">
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="2"><?= $model->price1_june ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="2"><?= $model->price3_june ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="2"><?= $model->price6_june ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="2"><?= $model->price12_june ?> <span>₽</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="lending-price-block__desc last">Чтобы оставить заявку, нажмите на цену</div>
                    </div>
                </div>
                <div class="lending-page__list__item">
                    <div class="lending-price-block">
                        <div class="lending-price-block__title">ТК «южный полюс»</div>
                        <div class="lending-price-block__desc">Санкт-Петербург, <br>Пражская ул., 48/50</div>
                        <div class="lending-price-block__row row">
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="3"><?= $model->price1_polis ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="3"><?= $model->price3_polis ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="3"><?= $model->price6_polis ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="3"><?= $model->price12_polis ?> <span>₽</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="lending-price-block__desc last">Чтобы оставить заявку, нажмите на цену</div>
                    </div>
                </div>
                <div class="lending-page__list__item">
                    <div class="lending-price-block">
                        <div class="lending-price-block__title">«Матроса Железняка»</div>
                        <div class="lending-price-block__desc">ул. Матроса Железняка, 57 А</div>
                        <div class="lending-price-block__row row">
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon1 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="1 месяц" data-club="4"><?= $model->price1_matros ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon3 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="3 месяца" data-club="4"><?= $model->price3_matros ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon6 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="6 месяцев" data-club="4"><?= $model->price6_matros ?> <span>₽</span></a>
                                </p>
                            </div>
                            <div class="lending-price-block__item col-3">
                                <img src="<?= $model->img ? '/uploads/image/promo/' . $model->icon12 : '//placehold.it/73x74' ?>" alt="">
                                <p class="lending-price">
                                    <a class="popup-to" href="#zvon-popup" data-month="12 месяцев" data-club="4"><?= $model->price12_matros ?> <span>₽</span></a>
                                </p>
                            </div>
                        </div>
                        <div class="lending-price-block__desc last">Чтобы оставить заявку, нажмите на цену</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lending-page__well mb0">
            <?= $model->text2 ?>
        </div>

    </main>

    <section class="instagram-section">
        <div class="container container--min">
            <div class="instagram-section__title"><a href="http://www.instagram.com/extra__sport/">@extra__sport</a></div>
            <div class="instagram-section__row row">

                <?php
                if ($this->beginCache('Instagram3', ['duration' => 3600])) {
                    $instagram = new Instagram();
                    Instagram::setProxy([
                        'address' => '217.29.53.109',
                        'port'    => '47332',
                        'tunnel'  => true,
                        'timeout' => 30,
                        'auth' => [
                            'user' => '8sZhCP',
                            'pass' => 'SDSvs4',
                            'method' => CURLAUTH_BASIC
                        ],
                    ]);
                    try {
                        $medias = $instagram->getMedias('extra__sport', 4);
                    } catch (Exception $e) {
                        $medias = [];
                        Yii::warning($e);
                    }
                    foreach ($medias as $key => $media) { ?>
                        <div class="instagram-section__item col-3 col-xs-6 <?= $key < 3 ? 'col-lg-4' : 'hidden-lg visible-xs' ?>">
                            <div class="inst-block">
                                <div class="inst-block__img"><a href="<?= $media->getLink() ?>" target="_blank"><img src="<?= $media->getImageThumbnailUrl() ?>" alt=""/></a></div>
                                <div class="inst-block__text"><?= StringHelper::truncate($media->getCaption(), 135) ?></div>
                                <div class="inst-block__date"><span><?= Yii::$app->formatter->asDate($media->getCreatedTime(), 'php:d') ?></span> <?= Yii::$app->formatter->asDate($media->getCreatedTime(), 'php:mm') ?></div>
                            </div>
                        </div>
                    <?php }
                    $this->endCache();
                } ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer__body">
            <div class="container">
                <div class="footer__row footer__row--adres">
                    <div class="footer__adres-col">
                        <div class="footer-adres">
                            <div class="footer-adres__title">ExtraSport Питер <br>С бассейном, </div>
                            <div class="footer-adres__text">ТК «Питер» <br>ул. Типанова 21 </div>
                            <div class="footer-adres__phone">Телефон: 335-69-69</div>
                        </div>
                    </div>
                    <div class="footer__adres-col">
                        <div class="footer-adres">
                            <div class="footer-adres__title">De-Vision С бассейном, </div>
                            <div class="footer-adres__text">ТК «Родео Драйв»<br> пр. Культуры 1</div>
                            <div class="footer-adres__phone">Телефон: 664-02-88</div>
                        </div>
                    </div>
                    <div class="footer__adres-col">
                        <div class="footer-adres">
                            <div class="footer-adres__title">ExtraSport Июнь</div>
                            <div class="footer-adres__text">ТРЦ «Июнь»<br> Индустриальный <br>пр., д. 24</div>
                            <div class="footer-adres__phone">Телефон: 677-14-33</div>
                        </div>
                    </div>
                    <div class="footer__adres-col">
                        <div class="footer-adres">
                            <div class="footer-adres__title">ExtraSport Южный </div>
                            <div class="footer-adres__text">ТРК «Южный Полюс»<br> ул. Пражская, 48/50</div>
                            <div class="footer-adres__phone">Телефон: 622-13-12</div>
                        </div>
                    </div>
                    <div class="footer__adres-col">
                        <div class="footer-adres">
                            <div class="footer-adres__title">ExtraSport «Матроса <br>Железняка» </div>
                            <div class="footer-adres__text">ЖК «Гранд Капитал»<br> ул. Матроса Железняка, 57А</div>
                            <div class="footer-adres__phone">Телефон: +7 911 092 55 55</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="container">
                <div class="footer__bottom__row">
                    <div class="footer__copy">© <?= (new DateTime)->format('Y') ?> ExtraSport, LLC</div>
                    <div class="footer__links">
                        <p><a href="/uploads/<?= $this->params['club']->pdf ?>" target="_blank">Правила поведения в клубе</a></p>
                        <p><a href="<?= Url::to(['/legal']) ?>">Правовая информация</a></p>
                    </div>
                    <div class="footer__design">
                        Разработка сайта: <a href="http://ra-vozduh.ru" target="_blank">РА VOZDUH</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <div class="popup-wrap" id="zvon-popup">
        <div class="popup-wrap__overflow"></div>

        <div class="popup popup--form">
            <a href="#" class="popup__close close"></a>
            <div class="popup__title">Заявка с ПРОМО страницы</div>

            <?php $form = ActiveForm::begin(['id' => 'callback', 'action' => Url::to(['/club/subscribe5/']), 'options' => ['class' => 'popup__form']]) ?>
            <div class="form-section__row row">
                <div class="form-section__item col-12 input-row">
                    <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваше имя *', 'required' => true]) ?>
                </div>
                <div class="form-section__item col-12 input-row">
                    <?= MaskedInput::widget([
                        'options' => ['class' => 'input-phone inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'],
                        'name' => 'tel',
                        'mask' => '+7 999 999 99 99',
                    ]) ?>
                </div>
            </div>
            <div class="form-section__soglas">
                <div class="checkbox">
                    <?= Html::checkbox('accept', false, ['id' => 'soglas-22']) ?>
                    <label for="soglas-22">Ознакомлен с <a href="<?= Url::to(['/privacy/']) ?>" target="_blank">политикой конфиденциальности</a></label>
                </div>
            </div>

            <div class="form-section__button"><button type="submit" class="btn btn--lg">Заказать звонок</button></div>

            <?= Html::hiddenInput('price', '', ['id' => 'price', 'required' => true]) ?>
            <?= Html::hiddenInput('club', '', ['id' => 'club', 'required' => true]) ?>
            <?= Html::hiddenInput('month', '', ['id' => 'month', 'required' => true]) ?>
            <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>

<?php if(Yii::$app->session->hasFlash('mailerFormSubmitted')) { ?>
    <div class="popup-wrap active" id="finish-popup">
        <div class="popup-wrap__overflow"></div>
        <div class="popup popup--finish">
            <a href="#" class="popup__close close"></a>

            <div class="popup__title title-h1">Спасибо, <br>ваша заявка отправлена!</div>
            <p>В ближайшее время мы вам перезвоним.</p>
            <br>
            <p>Данное окно закроется автоматически<br> через 5 секунд</p>
        </div>
    </div>
    <?php
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".popup-wrap").fadeOut(500); jQuery(".popup-overflow").fadeOut(500); }, 5000);
JS
    );
}


JSRegister::begin(); ?>
    <script>
        $(document).ready(function() {
            $(".popup-to").click(function(){
                $('#price').val($(this).text());
                $('#month').val($(this).data('month'));
                $('#club').val($(this).data('club'));

                return false;
            });

            $('#callback').on('beforeValidate', function (e) {
                $('.help-block').remove();
                var ret = true;

                if($(this)[0][1].value.length == 0){
                    $(this).find('div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя не может быть пустым</div>');
                    ret = false;
                } else if($(this)[0][1].value.match(/[^а-яё ]+/gi)){
                    $(this).find('div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя должно содержать только буквы кириллицы</div>');
                    ret = false;
                }
                if($(this)[0][2].value.length == 0){
                    $(this).find('div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон не может быть пустым</div>');
                    ret = false;
                } else if($(this)[0][2].value.match(/[_]+/ig)) {
                    $(this).find('div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон заполнено неправильно</div>');
                    ret = false;
                }
                if(!$(this)[0][3].checked){
                    $('#callback div.checkbox').append('<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, установите флажок "Ознакомлен"</div>');
                    ret = false;
                }

                return ret;
            });
        });
    </script>
<?php JSRegister::end();