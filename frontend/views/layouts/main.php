<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\assets\AppAsset;
use yii\widgets\MaskedInput;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">


<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head() ?>
    <?= $this->params['settings']->code_head ?>

    <?php if (!empty($this->params['club']->url_appstore) || !empty($this->params['club']->url_googleplay)) { ?>
    <link rel="stylesheet" href="/css/smartbanner.min.css">
    <script src="/js/smartbanner.min.js"></script>
    <meta name="smartbanner:title" content="Extra Sport">
    <meta name="smartbanner:author" content="ООО &#34;Extra Sport&#34;">
    <meta name="smartbanner:price" content="Доступно">
    <meta name="smartbanner:price-suffix-apple" content=" в App Store">
    <meta name="smartbanner:price-suffix-google" content=" в Google Play">
    <meta name="smartbanner:icon-apple" content="/images/350_350.png">
    <meta name="smartbanner:icon-google" content="/images/350_350.png">
    <meta name="smartbanner:button" content="Загрузить">
    <meta name="smartbanner:button-url-apple" content="<?= $this->params['club']->url_appstore ?>">
    <meta name="smartbanner:button-url-google" content="<?= $this->params['club']->url_googleplay ?>">
    <meta name="smartbanner:enabled-platforms" content="android,ios">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#000000ff" />

    <?php } ?>
    <script type="text/javascript">
    window.cookieconsent_options = {
        // message: 'Мы используем файлы cookie и сходные технологии (далее - файлы cookie) для предоставления вам персонализированной информации, подбора подходящей рекламы, в статических и исследовательских целях, а также для улучшения работы сайта! Подробнее с нашей политикой обработки данных из файлов cookie можно ознакомится <a href="/privacy/" target="_blank">здесь</a>. При использовании данного сайта, вы подтверждаете свое согласие на использование файлов cookie в соответствии с настоящим уведомлением в отношении данного типа файлов. Если вы не согласны с использованием файлов cookie, вы можете отключить их в настройках своего браузера или покинуть наш сайт.',
        message: 'Мы используем файлы cookie и сходные технологии для предоставления вам персонализированной информации, подбора подходящей рекламы, в статических и исследовательских целях, а также для улучшения работы сайта. <a href="/privacy/" target="_blank">Подробнее...</a> ',
        learnMore: '',
        dismiss: 'Принять',
        theme: '/css/light-bottom.css'
    };
    </script>
    <link rel="stylesheet" href="/css/sections.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>

<body id="page-top">
    <?= $this->params['settings']->code_body ?>
    <?php $this->beginBody() ?>
    <?= $this->render('header.php') ?>

    <?= $content ?>


    <?= $this->render('footer.php') ?>


    <?php $this->endBody() ?>

    <?= $this->params['settings']->yandex_metrica ?>

    <?= $this->params['settings']->google_analytics ?>

</body>

</html>
<?php $this->endPage() ?>