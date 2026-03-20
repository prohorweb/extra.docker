<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\assets\TinyMceAsset;
use yii\helpers\Html;

AppAsset::register($this);
TinyMceAsset::register($this);

$this->registerJs(<<<JS
if (typeof tinymce !== 'undefined') {
    tinymce.remove('textarea.js-rich-editor');
    tinymce.init({
        selector: 'textarea.js-rich-editor',
        license_key: 'gpl',
        height: 420,
        menubar: false,
        plugins: 'lists link image code table autoresize',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image table | code',
        branding: false,
        convert_urls: false,
        relative_urls: false,
        remove_script_host: false,
        setup: function (editor) {
            editor.on('change keyup', function () {
                editor.save();
            });
        }
    });
}
JS
);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<section class="main-section">

    <?= $this->render('left') ?>

    <section class="center-section">
        <?= $this->render('header') ?>
        <?= $content ?>
    </section>

</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
