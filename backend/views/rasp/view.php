<?php

/* @var $this yii\web\View */
/* @var $typeRasp \common\models\TypeRasp */
/* @var $week integer */
/* @var $year integer */
/* @var $weeks \common\models\Weeks */
/* @var $rasp array */
/* @var $arr_time array */
/* @var $model \common\models\Rasp */
/* @var $rooms \common\models\Rooms */
/* @var $groupPrograms \common\models\GroupPrograms */
/* @var $trainers \common\models\Trainers */
/* @var $dataProvider yii\data\ActiveDataProvider */


use kartik\depdrop\DepDrop;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\ActiveForm;

$this->title = $typeRasp->title . ' ' . (new DateTime())->setISODate($year, $week)->format('d.m') . ' - ' . (new DateTime())->setISODate($year, $week, 7)->format('d.m');

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
);
}
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/rasp?yearMonth=' . $_SESSION['yearMonth']]) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">
        <div class="row mb25">
            <div class="col span6">
                <?php $form = ActiveForm::begin(['action' => "make-empty?id=$typeRasp->id&week=$week&year=$year"]) ?>
                    <?= $form->field($weeks, 'is_empty', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="weeks-is_empty">Отметить расписание как не заполненное</label>', 'onChange' => 'this.form.submit()'], false) ?>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col span6">
                <?= Html::button('Очистить', ['class' => 'btn fright ml10', 'data-toggle' => 'modal', 'data-target' => '#clearModal']) ?>
                <?= Html::button('Автозаполнение', ['class' => 'btn fright', 'data-toggle' => 'modal', 'data-target' => '#autoCompleteModal']) ?>
            </div>
        </div>


        <div class="raspisan-list clearfix">
            <?php
            $firstDayWeek = (new DateTime())->setISODate($year, $week);
            $lastDayWeek = (new DateTime())->setISODate($year, $week, 7);
            while ($firstDayWeek <= $lastDayWeek) { ?>

            <div class="raspisan-list__col">
                <div class="raspisan-list__head">
                    <p><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:l') ?><br><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:j F') ?></p>
                    <p><a href="#"  class="add_rasp" data-toggle="modal" data-target="#myModal" data-day="<?= Yii::$app->formatter->asDate($firstDayWeek, 'php:Y-m-d') ?>" data-title="<?= Yii::$app->formatter->asDate($firstDayWeek, 'php:l, j F') ?>">Добавить</a></p>
                </div>

                <?php
                $arr = ArrayHelper::map($rasp[$firstDayWeek->format('N')-1], 'id', 'time', 'time');

                $break = true;
                foreach ($arr_time as $time) {
                    if (in_array($time, array_keys($arr)) && $break) {
                        foreach ($arr[$time] as $key => $mod) {
                        //$lesson = ArrayHelper::index($rasp[$firstDayWeek->format('N')-1], null, 'time')[$time][0];
                        $lesson = ArrayHelper::index($rasp[$firstDayWeek->format('N')-1], null, 'id')[$key][0]; ?>
                        <div class="time-box" <?= $lesson->programClasses->color ? 'style="background-color: ' . $lesson->programClasses->color . '"' : '' ?>>
                            <div class="clearfix time-box__head">
                                <div class="time-box__time"><?= substr($lesson->time, 0, 5) ?></div>
                                <ul class="time-box__buttons">
                                    <?php if ($lesson->is_pay) { ?>
                                        <li><a href="#"><img src="<?= Url::to('../images/icon-rub.png') ?>" alt=""></a>
                                        </li>
                                    <?php }
                                    if ($lesson->is_preliminary) { ?>
                                        <li><a href="#"><img src="<?= Url::to('../images/icon-edit.png') ?>" alt=""></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="time-box__zal"><?= $lesson->rooms->title ?></div>
                            <div class="time-box__name"><?= $lesson->programClasses->title ?></div>
                            <div class="time-box__traner"><?= $lesson->trainer ? $lesson->trainer->title : '' ?></div>

                            <?php if($lesson->status == 0) { ?>
                                <div class="time-box__traner" style="color: red; font-weight: bold; margin-top: 10px;">Занятие отменено</div>
                            <?php } elseif($lesson->status == 20) { ?>
                                <div class="time-box__traner" style="color: red; font-weight: bold; margin-top: 10px;">Замена тренера на <?= $lesson->lastTrainer->title ?></div>
                            <?php } ?>

                            <div class="time-box__abs">
                                <a href="#" class="btn btn--red modal-edit" data-toggle="modal" data-target="#myModal" data-id="<?= $lesson->id ?>"><i class="flaticon-pancil"></i></a>
                                <a href="<?= Url::to('delete?id=' . $lesson->id . '&week=' . $week . '&year=' . $year) ?>" data-method="post" class="btn btn--red"><i class="flaticon-delete"></i></a>
                            </div>
                        </div>
                    <?php }} else { ?>
                        <div class="time-box time-box--none"></div>
                    <?php }
                    $break = $break ? (isset($arr[$time]) && count($arr[$time]) < array_count_values($arr_time)[$time] ? false : true) : true;
                } ?>

            </div>
                <?php $firstDayWeek->modify('+1 day') ?>
            <?php } ?>
        </div>


        <?php Modal::begin([
            'id' => 'myModal',
            'header' => '<h2>Title</h2>',
        ]) ?>
        <?php $form = ActiveForm::begin(['action' => "create-rasp?week=$week&year=$year"]) ?>
        <div class="input-row row clearfix">
            <div class="col span6">
                <?= $form->field($model, 'time')->textInput()->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '99:99', 'options' => ['class' => 'inputbox']]) ?>
                <?php /*= $form->field($model, 'time')->dropDownList(['9:30' => '9:30', '10:00' => '10:00']) */?>
            </div>
            <div class="col span6">
                <?= $form->field($model, 'rooms_id')->dropDownList(ArrayHelper::map($rooms,'id','title'), ['prompt'=>'Выберите...']) ?>
            </div>
        </div>
        <div class="input-row row clearfix">
            <div class="col span6">
                <?= $form->field($model, 'group_programs_id')->dropDownList(ArrayHelper::map($groupPrograms,'id','title'), ['id'=>'group-id', 'prompt' => 'Выберите...']) ?>
            </div>
            <div class="col span6">
                <?= $form->field($model, 'program_classes_id')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'program-id'],
                    'pluginOptions' => [
                        'depends' => ['group-id'],
                        'placeholder' => 'Выберите...',
                        'url' => Url::to(['subcat'])
                    ]
                ]) ?>
            </div>
        </div>
        <div id="status" class="input-row none">
            <?= Html::label('Статус', null, ['class' => 'input-label']) ?>
            <?= Html::dropDownList('Rasp[status]', null, [10 => 'По плану', 0 => 'Занятие отменено', 20 => 'Замена тренера']) ?>
        </div>
        <div class="input-row">
            <?= $form->field($model, 'trainer_id')->dropDownList(ArrayHelper::map($trainers,'id','title'), ['prompt' => 'Выберите...']) ?>
        </div>
        <div class="input-row">
            <?= $form->field($model, 'comment', ['labelOptions' => ['class' => 'input-label']])->textarea()->hint('осталось символов: <span id="charsLeftIntro"></span>') ?>
        </div>
        <div class="input-row">
            <?= $form->field($model, 'is_pay', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="rasp-is_pay">Платное</label>'], false) ?>
            <?= $form->field($model, 'is_preliminary', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="rasp-is_preliminary">Предварительная запись</label>'], false) ?>
            <?= $form->field($model, 'is_new', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="rasp-is_new">Новое</label>'], false) ?>
        </div>
        <?= $form->field($model, 'date')->hiddenInput(['value' => ''])->label(false) ?>
        <?= $form->field($model, 'type_rasp_id')->hiddenInput(['value' => $typeRasp->id])->label(false) ?>
        <div class="input-row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
        </div>
        <?php ActiveForm::end() ?>

        <?php Modal::end() ?>

        <div id="clearModal" class="popup_wrap">
            <div class="popup">
                <a href="#" class="close" data-dismiss="modal" aria-hidden="true"></a>
                <div class="popup__title tcenter">Вы действительно хотите <br>очистить расписание?</div>
                <form>
                    <div class="tcenter buttons-inline">
                        <a href="<?= Url::to('clear?id=' . $typeRasp->id . '&week=' . $week . '&year=' . $year) ?>" class="btn btn--grey">Да</a>
                        <a href="#" class="btn" data-dismiss="modal">Нет</a>
                    </div>
                </form>
            </div>
        </div>

        <div id="autoCompleteModal" class="popup_wrap">
            <div class="popup">
                <a href="#" class="close" data-dismiss="modal" aria-hidden="true"></a>
                <div class="popup__title tcenter">Заполнить расписание</div>
                <?php $form = ActiveForm::begin(['action' => "auto-complete?id=$typeRasp->id&week=$week&year=$year"]) ?>
                    <div class="mb15">
                        <div class="input-label">Выберите распиcание</div>
                        <?php
                        $val = [];
                        foreach ($weeksComplete as $model) {
                            $val[$model->id] = Yii::$app->formatter->asDate((new DateTime())->setISODate($model->year, $model->week), "'c' d MMMM y") . ' до ' . Yii::$app->formatter->asDate((new DateTime())->setISODate($model->year, $model->week, 7), "d MMMM y");
                        } ?>
                        <?= $form->field(new \common\models\Weeks(), 'id')->dropDownList($val, ['prompt' => 'Выберите...'])->label(false) ?>
                    </div>
                    <button class="btn">Применить</button>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
<?php
$lang = Yii::$app->language == 'en' ? 'en' : '';
$this->registerJs(<<<JS
    $('.add_rasp').click(function (e) {
        $("#myModal form")[0].reset();
        $('#status').hide();
        $("select#rasp-trainer_id").prop('disabled', false);
        $('.modal-header h2').html($(this).data('title'));
        $('#rasp-date').attr('value', $(this).data('day'));
        $('#myModal form').attr('action', 'create-rasp?week=${week}&year=${year}');
    });

    $('.modal-edit').click(function (e) {
        $.ajax({
            url: 'view',
            type: "GET",
            datatype: "json",
            data: {'id':$(this).data('id'), 'week': ${week}, 'year': ${year}},
            success: function (data) {
                $('#rasp-time').val(data.time.substr(0, 5));
                $("select#rasp-rooms_id option[value=" + data.rooms_id + "]").prop('selected', true);
                $("select#group-id").val(data.group_programs_id).change();
                setTimeout(function(){
                    $("select#program-id").val(data.program_classes_id).change().delay(3000);
                }, 2000);
                $("select#rasp-trainer_id option[value=" + data.trainer_id + "]").prop('selected', true);
                //$("select#rasp-trainer_id").prop('disabled', 'disabled');
                $("#rasp-comment").val(data.comment);
                $("#rasp-is_pay").prop('checked', data.is_pay == 1);
                $("#rasp-is_preliminary").prop('checked', data.is_preliminary == 1);
                $("#rasp-is_new").prop('checked', data.is_new == 1);
                $('#rasp-date').val(data.date);
                $('#rasp-type_rasp_id').val(data.type_rasp_id);
                $('#status').show();
                $("#status select option[value=" + data.status + "]").prop('selected', true);
                }
        });
        
        $('#myModal form').attr('action', 'update?id=' + $(this).data('id') + '&week=${week}&year=${year}');
    });
    
    $('#program-id').change(function (e) {
        if($(this).val() != "0"){
            $.ajax({
                url: '/admin/$lang/program-classes/view',
                type: "GET",
                data: {'id':$(this).val()},
                success: function (data) {
                    //console.log(data);
                    $("#rasp-comment").val(data.intro);
                }
            });
        }
    });
    
    $('#status').on('change', function(e) {
        if($(this).find(":selected").val()  == "20"){
            $("select#rasp-trainer_id").prop('disabled', false);
        } else {
            $("select#rasp-trainer_id").prop('disabled', 'disabled');
        }
    });
JS
);