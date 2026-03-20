<?php

namespace api\controllers;

use common\models\User;
use serhatozles\simplehtmldom\SimpleHTMLDom;
use Yii;
use common\models\Events;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Events Controller API
 */
class EventsController extends ActiveController
{
    public $modelClass = 'common\models\Events';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['index'], $actions['view']);
        return $actions;
    }

    /**
     * Rest Description: Список мероприятий.
     */
    public function actionIndex()
    {
        $user = User::findIdentityByAccessToken(explode(" ", Yii::$app->request->headers['authorization'])[1]);

        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->get('db' . ($user->id != 1 ? $user->id : '')),
            'sql' => 'SELECT *
                    FROM (SELECT * FROM ' . Events::tableName() .' WHERE status = 10 AND `date` >= DATE(NOW())
                          ORDER BY `date` ASC LIMIT 1) AS a
                    UNION SELECT *
                          FROM (SELECT * FROM ' . Events::tableName() .' WHERE status = 10 AND `date` >= DATE(NOW())
                                ORDER BY `date` ASC) AS b
                    UNION SELECT *
                          FROM (SELECT * FROM ' . Events::tableName() .' WHERE status = 10 AND `date` < DATE(NOW())
                                ORDER BY `date` DESC) AS c',
            'key' => 'id',
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        $models = $dataProvider->getModels();
        foreach($models as $i => $model){
            $paragraphsArray = [];
            $imagesArray = [];
            $dom = SimpleHTMLDom::str_get_html(html_entity_decode($model['content']));
            foreach ($dom->find('p') as $key => $element) {
                $paragraphsArray[$element->tag_start] = ['paragraphId' => $key + 1, 'paragraphText' => $element->outertext];
                foreach ($element->find('img') as $key2 => $img) {
                    $imagesArray[$element->tag_start] = ['paragraphId' => $key + 1, 'imageLink' => $img->src];
                }
            }
            foreach ($dom->find('ul') as $key => $element) {
                $paragraphsArray[$element->tag_start] = ['paragraphId' => $key + 1, 'paragraphText' => $element->outertext];
                foreach ($element->find('img') as $key2 => $img) {
                    $imagesArray[$element->tag_start] = ['paragraphId' => $key + 1, 'imageLink' => $img->src];
                }
            }
            ksort($paragraphsArray);
            ksort($imagesArray);
            $models[$i]['content'] = [];
            $models[$i]['content']['paragraphsArray'] = array_values($paragraphsArray);
            $models[$i]['content']['imagesArray']  = array_values($imagesArray);

            $text = $model['title'] . '\r\n\r\n' . str_replace("\r\n", '\'+\'\r\n', $model['intro']  . '\n\n');
            $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model['alias'] . '/');
            $models[$i]['url_twitter'] = 'http://twitter.com/share?text=' . str_replace(" ", "+", $text) . '&url=' . $url . '&counturl=' . $url;

            $text = str_replace("\r\n", '\'+\'\r\n', $model['intro']);
            $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model['alias'] . '/');
            $models[$i]['url_facebook'] = 'http://www.facebook.com/sharer.php?title=' . str_replace(" ", "+", $model['title']) . '&description=' . str_replace(" ", "+", $text) . '&u=' . $url;

            $text = str_replace("\r\n", '\'+\'\r\n', $model['intro']);
            $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model['alias'] . '/');
            $models[$i]['url_vk'] = 'http://vkontakte.ru/share.php?url=' . $url . '&title=' . str_replace(" ", "+", $model['title']) . '&description=' . str_replace(" ", "+", $text) . '&u=' . $url . '&noparse=true';
        }
        $dataProvider->setModels($models);

        return $dataProvider;
    }

    /**
     * Rest Description: Мероприятие по идентификатору.
     */
    public function actionView($id)
    {
        $model = Events::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}