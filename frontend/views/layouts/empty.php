<?php

/* @var $this \yii\web\View */
/* @var $content string */

use lajax\translatemanager\widgets\ToggleTranslate;
use lajax\translatemanager\helpers\Language;
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
    <!-- Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5"> 

    <link href="css/fonts/font-awesome_all.css" rel="stylesheet">
    <link href="css/fonts/font-awesome_sharp-solid.css" rel="stylesheet">
    
   
    <?= $this->params['settings']->code_head ?>
    <?php $this->head() ?>
    <script type="text/javascript">
        window.cookieconsent_options = {
            message: 'Мы используем файлы cookie и сходные технологии (далее - файлы cookie) для предоставления вам персонализированной информации, подбора подходящей рекламы, в статических и исследовательских целях, а также для улучшения работы сайта Подробнее с нашей политикой обработки данных из файлов cookie можно ознакомится <a href="http://piter.extrasport.ru/privacy/" target="_blank">здесь</a>. При использовании данного сайта, вы подтверждаете свое согласие на использование файлов cookie в соответствии с настоящим уведомлением в отношении данного типа файлов. Если вы не согласны с использованием файлов cookie, вы можете отключить их в настройках своего браузера или покинуть наш сайт.',
            learnMore: '',
            dismiss: 'Закрыть',
            theme: '/css/light-bottom.css'
        };
    </script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>

<body>
    <?= $this->params['settings']->code_body ?>
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>