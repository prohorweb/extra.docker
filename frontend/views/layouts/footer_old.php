<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Url;

$club_url = explode('.', $_SERVER['HTTP_HOST'])[0];
$sub_url = explode('.', $_SERVER['HTTP_HOST'])[1];

$tg = '';
switch ($club_url) {
    case 'piter':
        $tg = '79669223172';
        break;
    case 'matros':
        $tg = '79817750373';
        break;
    case 'piter':
        $tg = '79500064785';
        break;
    case 'matros':
        $tg = '79312089566';
        break;

    default:
        $tg = '79669223172';
}

if ($_SERVER['HTTP_HOST'] == 'extrasport.local') {
    $url = 'extrasport.local';
} else {
    $reg_url = explode('.', $_SERVER['HTTP_HOST'])[2];
    $url = $sub_url . '.' . $reg_url;
}

?>
<!-- Footer-->
<footer>
    <div class="footer-top py-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-4">
                    <div class="row">
                        <div class="col-xl-7">
                            <ul class="map-section__list p-0">
                                <li class="map-section__phone"><i class="fa-regular fa-mobile fs-4"></i><a class="fs-4"
                                        href="tel:<?= $this->params['club']->tel ?>"><?= $this->params['club']->tel ?></a>
                                </li>
                                <li class="map-section__mail"><i class="fa-regular fa-envelope"></i><a
                                        href="mailto:<?= $this->params['club']->email ?>"><?= $this->params['club']->email ?></a>
                                </li>
                                <li class="map-section__adres"> <i class="fa-regular fa-location-dot"></i>
                                    <?= $this->params['club']->address ?>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-start mt-3">Мы в :
                                <a class="fa-brands fa-vk fs-2 ps-3" href="http://vk.com/extrasport_ru"
                                    target="_blunk"></a>
                                <a class="fa-brands fa-youtube fs-2 ps-3"
                                    href="http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                                    target="_blunk"></a>
                            </div>
                        </div>
                        <div class="col-xl-5 d-none d-xl-block">
                            <ul class="map-section__list p-0">
                                <li class="map-section__time"><i class="fa-regular fa-timer"></i>Время работы<br> пн–пт:
                                    <?= $this->params['club']->start_work ?><br> сб–вс:
                                    <?= $this->params['club']->start_work_weekend ?>
                                </li>
                                <li class="map-section__prodag"><i class="fa-regular fa-user-tie-hair"></i>Отдел
                                    продаж:<br> пн-вс: 10:00 до 22:00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-8">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="call d-flex justify-content-evenly py-5"><a href="#callModal"
                                    data-bs-toggle="modal">
                                    <div class="btn btn-primary btn-lg fs-6"><i
                                            class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Заказать звонок
                                    </div>
                                </a></div>
                        </div>
                        <div class="col-lg-5">
                            <div class="footer__api d-flex flex-lg-column justify-content-center">
                                <?php if ($this->params['club']->url_appstore) { ?>
                                <a class="d-flex align-items-center justify-content-end my-3 ps-3"
                                    href="<?= $this->params['club']->url_appstore ?>" target="_blunk"
                                    onclick="ym(21525628, 'reachGoal', 'download_app');">
                                    <p class="m-0 text-end">Загрузите в <br><b>APP STORE</b></p><i
                                        class="fa-brands fa-app-store-ios fs-1 ps-3"> </i>                                   
                                </a>
                                <?php }
                                if ($this->params['club']->url_googleplay) { ?>
                                <a class="d-flex align-items-center justify-content-end my-3 ps-3"
                                    href="<?= $this->params['club']->url_googleplay ?>" target="_blunk"
                                    onclick="ym(21525628, 'reachGoal', 'download_app');">
                                    <p class="m-0 text-end">Доступно в <br><b>GOOGLE PLAY</b></p><i
                                        class="fa-brands fa-google-play fs-1 ps-3"></i>
                                  
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom col-lg-12">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-md-start">©
                    <?= (new DateTime)->format('Y') ?> ExtraSport, LLC
                </div>
                <div class="col-md-8 text-md-end"><a class="text-decoration-none me-2"
                        href="#rules"  data-bs-toggle="modal">Правила поведения в клубе
                        &nbsp; |</a><a class="text-decoration-none" href="http://<?= $club_url?>.<?= $url ?>/legal/"
                        target="_blunk">Правовая информация</a></div>
            </div>
        </div>
    </div>
</footer>

<!-- Club modal popup-->
<div class="modal fade" id="clubModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button"
                    aria-label="Close"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="modal-body p-4">
                        <a class="card" href="http://piter.<?= $url ?>">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="card-body_wrapper text-start">
                                        <h5 class="card-title">EXTRASPORT ТК «ПИТЕР»</h5>
                                        <div class="card-text">Санкт-Петербург, ул. Типанова, 21</div>
                                    </div>
                                    <div class="btn-arrow d-flex align-items-center"><i
                                            class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                                </div>
                            </div>
                        </a>
                        <a class="card" href="http://piter.<?= $url ?>"
                            <?= isset($_SESSION["_language"]) && $_SESSION["_language"] == 'db' ? 'class="close"' : '' ?>>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="card-body_wrapper text-start">
                                        <h5 class="card-title">EXTRASPORT ТРЦ «ИЮНЬ»</h5>
                                        <div class="card-text">Санкт-Петербург, Индустриальный пр., 24</div>
                                    </div>
                                    <div class="btn-arrow d-flex align-items-center"><i
                                            class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                                </div>
                            </div>
                        </a>
                        <a class="card" href="http://matros.<?= $url ?>"
                            <?= isset($_SESSION["_language"]) && $_SESSION["_language"] == 'db4' ? 'class="close"' : '' ?>>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="card-body_wrapper text-start">
                                        <h5 class="card-title">EXTRASPORT ТРК «ЮЖНЫЙ ПОЛЮС»</h5>
                                        <div class="card-text">Санкт-Петербург, ул. Пражская, 48/50</div>
                                    </div>
                                    <div class="btn-arrow d-flex align-items-center"><i
                                            class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                                </div>
                            </div>
                        </a>
                        <a class="card" href="http://matros.<?= $url ?>"
                            <?= isset($_SESSION["_language"]) && $_SESSION["_language"] == 'db4' ? 'class="close"' : '' ?>>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="card-body_wrapper text-start">
                                        <h5 class="card-title">EXTRASPORT «МАТРОСА ЖЕЛЕЗНЯКА»</h5>
                                        <div class="card-text">Санкт-Петербург, ул. Матроса Железняка, 57А</div>
                                    </div>
                                    <div class="btn-arrow d-flex align-items-center"><i
                                            class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                                </div>
                            </div>
                        </a>
                        <a class="card" href="http://de-vision.ru">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="card-body_wrapper text-start">
                                        <h5 class="card-title">De-vision ТРК "РОДЕО ДРАЙВ"</h5>
                                        <div class="card-text">Санкт-Петербург, пр. Культуры, 1</div>
                                    </div>
                                    <div class="btn-arrow d-flex align-items-center"><i
                                            class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('/site/_callback') ?>

<?php if(Yii::$app->session->hasFlash('mailerFormSubmitted2') || Yii::$app->session->hasFlash('mailerFormSubmitted')) { ?>
<div class="popup-wrap active" id="finish-popup">
    <div class="popup-wrap__overflow"></div>
    <div class="popup-wrap__block d-flex  align-items-center">
        <div class="popup popup--finish">
            <div class="fs-4 mb-3">Спасибо, ваша заявка отправлена!</div>
            <p>В ближайшее время мы вам перезвоним.</p>
            <p>Данное окно закроется автоматически через 5 секунд</p>
        </div>
    </div>
</div>
<?php
        $this->registerJs(
            <<<JS
	window.setTimeout(function() { jQuery(".popup-wrap").fadeOut(500); jQuery(".popup-overflow").fadeOut(500); }, 5000);
JS
        );
    } else { ?>
<div class="popup-overflow"></div>
<?php } ?>


<?php if ($this->params['settings']->wa || $this->params['settings']->vk || $this->params['settings']->tg) { ?>
<div class="chat-24">
    <a href="#" class="chat-24__button js-chat-show">
        <div class="circlephone" style="transform-origin: center;"></div>
        <div class="circle-fill" style="transform-origin: center;"></div>
        <div class="img-circle" style="transform-origin: center;">
            <div class="img-circleblock" style="transform-origin: center;"></div>
        </div>
    </a>
    <div class="chat-24__content">
        <?php if ($this->params['settings']->wa) { ?>
        <a href="<?= $this->params['settings']->wa ?>" data-title="WhatsApp" class="chat-24__item" target="_blank"><img
                src="/images/chat/wa.png" alt=""></a>
        <?php }
            if ($this->params['settings']->vk) { ?>
        <a href="<?= $this->params['settings']->vk ?>" data-title="VK" class="chat-24__item" target="_blank"><img
                src="/images/chat/vk.png" alt=""></a>
        <?php }
            if ($this->params['settings']->tg) { ?>
        <a href="<?= $this->params['settings']->tg ?>" data-title="Telegram" class="chat-24__item" target="_blank"><img
                src="/images/chat/tg.png" alt=""></a>
        <?php } ?>
        <a href="#" class="chat-24__close js-chat-hide"></a>
        <div class="chat-24__text"></div>
    </div>
</div>
<?php } ?>


<?php if (!isset($_COOKIE['popup-timer']) && !Yii::$app->session->hasFlash('mailerFormSubmitted') && $this->params['settings']->timer && strtotime($this->params['settings']->date_timer . ' ' . $this->params['settings']->timer_start) < strtotime('now') && strtotime($this->params['settings']->date_timer . ' ' . $this->params['settings']->timer_end) > strtotime('now')) { ?>

<div class="popup-wrap active" id="popup-timer"
    data-time="<?= (strtotime($this->params['settings']->date_timer . ' ' . $this->params['settings']->timer_end) - strtotime('now')) * 1000 ?>">

    <div class="popup-wrap__overflow "></div>
    <div class="popup-wrap__align d-flex align-items-center">
        <div class="popup popup--timer">
            <a href="#" class="popup__close btn-close btn-close-white close"></a>
            <div class="popup__title">
                <?= $this->params['settings']->timer_title ?>
            </div>
            <div class="popup__desc">
                <?= $this->params['settings']->timer_intro ?>
            </div>
            <div class="popup__timer">
                <div id="timer"></div>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'popup-timer', 'action' => Url::to(['/club/subscribe4/']), 'options' => ['onsubmit' => (explode('.', $_SERVER['HTTP_HOST'])[0] == 'piter' ? "gtag('event', 'send_form', {'event_category': 'form'}); ym(21525628, 'reachGoal', 'akciya');" : "") . "dataLayer.push({'event': 'timer'});return true;", 'class' => 'popup__form']]) ?>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <?= Html::textInput('name', null, ['class' => 'input-timer inputbox inputbox--border form-control', 'placeholder' => 'Имя *', 'required' => true]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= MaskedInput::widget([
                        'options' => ['class' => 'input-phone input-timer inputbox inputbox--border form-control', 'placeholder' => 'Телефон *', 'required' => true],
                        'name' => 'tel',
                        'mask' => '+7 999 999 99 99',
                        ]) ?>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-timer btn-primary btn-lg w-100">Заказать звонок</button>
                    </div>
                </div>
            </div>

            <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
            <?= \himiklab\yii2\recaptcha\ReCaptcha3::widget([
                'name' => 'reCaptcha',
                'action' => 'subscribe4',
            ]) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>

</div>
<?php } ?>


<div class="modal fade popup-wrap" id="rules" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button"
                    aria-label="Close"></div>
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">ПРАВИЛА СПОРТИВНОГО КЛУБА «ЭКСТРА СПОРТ»</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>1. Часы работы Клуба устанавливаются с 8.00 до 22.00 (вход в Клуб до 21.30). В праздничные и выходные
                    дни с 09.00 до 22.00 (Вход в клуб до 21.30). Клуб имеет право изменять часы работы. Информация об
                    изменениях часов работы распространяется на информационных стендах.</p>

                <p>2. Пропуском в Клуб является клубная карта, которая оформляется только по предъявлению паспорта и
                    после подписания договора с Клубом. Клубная карта является собственностью клуба &laquo;Экстра
                    Спорт&raquo;. Член Клуба обязан предъявлять клубную карту при входе в помещение Клуба. В случае
                    разового временного отсутствия клубной карты у Члена Клуба, доступ в Клуб осуществляется по
                    временному пропуску, который выписывается по предъявлению документа, удостоверяющего личность Члена
                    Клуба. При утере карты необходимо уведомить клуб в обязательном порядке и заплатить штраф, согласно
                    прейскуранту Клуба, во избежание недоразумений.</p>

                <p>3. Членство в Клубе является персональным и не может быть передано или использовано другими лицами
                    без переоформления клубной карты. Член Клуба обязан предъявить клубную карту или ключ от шкафа
                    работникам Клуба по их требованию. Если Клубное Членство было передано другому лицу без
                    переоформления, то абонемент закрывается без возврата денег.</p>

                <p>4. Член Клуба имеет право переоформить клубную карту на другое лицо в случае невозможности
                    самостоятельного посещения Клуба. Для переоформления клубной карты Члену Клуба необходимо письменно
                    изъявить свое желание, предъявить оригинал договора оказания физкультурно-оздоровительных и
                    спортивных услуг и документ, удостоверяющий личность. С момента переоформления прежняя клубная карта
                    прекращает свое действие. Оформление новой клубной карты оплачивается отдельно согласно прейскуранту
                    на рецепции клуба.</p>

                <p>5. Член Клуба имеет право получить ключ от шкафа в обмен на клубную карту. В случае утери (или порчи)
                    клубной карты, ключа от шкафа или любого другого инвентаря, выдаваемого Клубом на время занятий,
                    Член Клуба обязан заплатить штраф, размер которого устанавливается Клубом. При выходе из клуба после
                    тренировки Член Клуба обязан вернуть ключ с замком на рецепцию и забрать свои карточку или договор.
                    Нельзя покидать территорию Клуба, не вернув замок с ключом.</p>

                <p>6. В период нахождения в Клубе личные вещи Члена Клуба должны храниться в шкафах раздевалки. Клуб не
                    несет ответственности за личные вещи, оставленные в раздевалках и в помещениях для тренировок. После
                    занятия Член Клуба обязан освободить шкаф от личных вещей и сдать ключ на рецепцию. Ценные вещи
                    рекомендуется сдавать на хранение в сейф на рецепции клуба. Нельзя оставлять свои личные вещи в
                    шкафчиках в раздевалках не на время тренировок.</p>

                <p>7. При первом посещении Клуба, Члену Клуба необходимо сфотографироваться в отделе продаж, в противном
                    случае Клуб имеет право не допустить клиента до занятий.</p>

                <p>8. При первом посещении Клуба Члену Клуба настоятельно рекомендуется пройти инструктаж и консультацию
                    спортивного врача и в дальнейшем строго придерживаться рекомендаций врача, инструкторов и персонала
                    Клуба.</p>

                <p>9. Член Клуба обязан осуществлять тренировки в Клубе в чистой спортивной одежде (верхняя часть тела
                    должна быть закрыта) и закрытой чистой спортивной обуви, соблюдать правила общей гигиены, перед
                    посещением сауны принять душ. Запрещается тренироваться босиком, в пляжных или домашних тапочках и
                    т.п. Исключения составляют спец. классы (например: йога,Pilates). Обувь должна быть сменной.</p>

                <p>10. Клуб имеет право не допускать Члена Клуба на тренировку в одежде и обуви, не предназначенной для
                    конкретного типа занятий.</p>

                <p>11. Член Клуба обязан соблюдать чистоту во всех помещениях Клуба, которые используются им до, во
                    время и после тренировок. 12. Запрещено лить воду на камни в сауне. Сауна финская, сухая, с
                    постоянной температурой не более 98 С.</p>

                <p>13. Член Клуба помимо настоящих Правил обязан соблюдать правила посещения отдельных зон Клуба (салон
                    красоты, банный комплекс, сауну и т.д.). Запрещается пользоваться в душевых и в сауне скрабом для
                    тела.</p>

                <p>14. Член Клуба в период нахождения в Клубе обязуется соблюдать правила общественного порядка (вести
                    себя культурно, не использовать в своей речи ненормативную лексику, не доставлять неудобства
                    посетителям Клуба и т.д.). В случае несоблюдения данной нормы, Клуб имеет право расторгнуть договор
                    в одностороннем порядке без возврата денежных средств. При наличии договоренности с администрацией
                    Клуба расторжение договора может быть заменено на сокращение срока его действия.</p>

                <p>15. Член Клуба обязан покидать территорию Клуба не позднее установленного времени его закрытия. При
                    задержке выхода из клуба более, чем на 5 минут, Члену Клуба делается предупреждение в письменном
                    виде, и после 3-х предупреждений администрация Клуба в праве закрыть абонемент за нарушение Правил
                    Клуба.</p>

                <p>16. Для обеспечения безопасности тренировочного процесса в тренажерном зале, Член Клуба обязан
                    выполнять упражнения с весами, определенными инструктором Клуба. Определение весов для конкретного
                    Клиента производится тренером во время персональной тренировки, которая оплачивается отдельно в
                    кассу или на расчетный счет Клуба.</p>

                <p>17. После окончания тренировок Член Клуба обязан вернуть спортивный инвентарь (грифы, блины, гантели
                    и т.д.) в специально отведенные места в надлежащем состоянии. За утерю и порчу оборудования,
                    инвентаря Член Клуба несет материальную ответственность.</p>

                <p>18. Групповые занятия в Клубе проводятся по расписаниям, установленным Клубом. Клуб оставляет за
                    собой право вносить изменения и дополнения в расписание и осуществлять замену заявленного в
                    расписании инструктора. Член Клуба обязан приходить на групповые занятия без опозданий.</p>

                <p>19. Родители несут персональную ответственность за детей на территории Клуба. Дети до 14 лет обязаны
                    посещать Клуб только с персональным тренером Клуба (либо с тренером групповых специализированных для
                    детей тренировок в назначенное расписанием время только в указанном в расписании помещении). Время
                    персонального тренера Клуба либо коммерческие групповые занятия оплачиваются отдельно.</p>

                <p>20. Запрещено проведение персональных тренировок лицами, не являющимися тренерами-инструкторами
                    Клуба.</p>

                <p>21. Во время проведения клубных мероприятий Клуб имеет право ограничить зону, предназначенную для
                    тренировок. Клуб имеет право закрывать помещения на время проведения специальных мероприятий и/или
                    ремонтных работ, о чем Члены Клуба информируются заранее путем размещения в Клубе объявлений не
                    менее чем за 24 часа до проведения указанных мероприятий.</p>

                <p>22. Член Клуба не возражает против осуществления в здании Клуба и на прилегающей к зданию территории
                    видеосъемки и видеонаблюдения.</p>

                <p>23. Члену Клуба запрещено осуществлять кино- и фотосъемку в Клубе без письменного разрешения Клуба.
                </p>

                <p>24. Члену Клуба запрещено самостоятельно пользоваться музыкальной и другой технической аппаратурой
                    Клуба.</p>

                <p>25. Все помещения Клуба являются зонами, свободными от курения. Члену Клуба запрещено приносить в
                    Клуб напитки и продукты питания, а также употреблять алкогольные и слабоалкогольные напитки, включая
                    пиво в помещениях Клуба. Члену Клуба запрещено находиться в Клубе в нетрезвом виде, принимать пищу в
                    местах, предназначенных для тренировок, в зонах отдыха и раздевалках.</p>

                <p>26. Члену Клуба запрещено входить на территорию, предназначенную для служебного пользования, за
                    исключением случаев, когда имеется специальное приглашение.</p>

                <p>27. Члену Клуба запрещено размещать объявления, рекламные материалы, проводить опросы и
                    распространять товары на территории Клуба без письменного разрешения Клуба.</p>

                <p>28. В Клубе действует закрытая клубная система. Клуб вправе отказать Члену Клуба и/или гостю Члена
                    Клуба в заключение договора и/или в пропуске в здание Клуба. Клуб вправе в одностороннем порядке
                    расторгнуть договор без объяснения причин.</p>

                <p>29. Члену Клуба запрещено входить на территорию Клуба с домашними животными.</p>

                <p>30. Члену Клуба, а также приглашенным им (и/или сопровождающим его лицам) запрещено проносить на
                    территорию Клуба любое холодное и/или огнестрельное оружие.</p>

                <p>31. Клуб не несет ответственности за технические неудобства, вызванные проведением муниципальными
                    властями и арендодателями профилактических, ремонтно-строительных и иных работ.</p>

                <p>32. Гости клуба, не являющиеся его членами и никогда ранее не посещавшими клуб, имеют право на один
                    гостевой визит, который может состояться только во время, установленное администрацией клуба (в
                    будние дни с 10:00 до 18:00, в выходные и праздники с 10:00 до 22:00) и в помещениях, установленных
                    администрацией клуба в специальных объявлениях/приказах. Вход осуществляется ТОЛЬКО при наличии
                    документа удостоверяющего личность. Гостевой визит оформляется в отделе продаж.</p>

                <p>33. Абонементы с пометкой &laquo;VIP&raquo; переоформлению и возврату не подлежат.</p>

                <p>34. Все дополнительные услуги Клуба, не включенные в договор на клубное членство, являются платными и
                    оплачиваются перед оказанием данной услуги.</p>

                <p>35. Клуб имеет право в одностороннем порядке дополнять и изменять настоящие Правила. Новые правила и
                    / или их дополнения, изменения вступают в силу для Члена Клуба с момента размещения Правил или
                    соответствующих приказов/нормативных актов для всеобщего ознакомления на рецепции или в других
                    доступных для Клиентов помещениях Клуба.</p>

            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-lg" href="/uploads/rules.pdf" target="_blank">Cкачать PDF</a>
            </div>
        </div>
    </div>
</div>

<?php if(!isset($_COOKIE['popup-video']) && $this->params['club']->url_windowsmarket) { ?>
<!-- Present Video -->
<div class="present-video js-pv is-minimize is-show">
    <div class="present-video__close js-pv-close">
        <svg>
            <use href="/images/pv-sprite.svg#close"></use>
        </svg>
    </div>
    <div class="present-video__minimize js-pv-minimize">
        <svg>
            <use href="/images/pv-sprite.svg#minimize"></use>
        </svg>
    </div>

    <?= $this->params['club']->url_windowsmarket ?>

    <div class="present-video__mute js-pv-mute">
        <svg>
            <use href="/images/pv-sprite.svg#mute"></use>
        </svg>
    </div>
    <div class="present-video__volume js-pv-volume">
        <svg>
            <use href="/images/pv-sprite.svg#volume"></use>
        </svg>
    </div>
    <!--<div class="present-video__zoom js-pv-zoom"></div>-->
    <div class="present-video__play js-pv-play">
        <div class="present-video__play-btn">
            <svg>
                <use href="/images/pv-sprite.svg#play"></use>
            </svg>
        </div>
    </div>
    <div class="present-video__pause js-pv-pause"></div>
</div>
<!-- Present Video end -->
<?php } ?>