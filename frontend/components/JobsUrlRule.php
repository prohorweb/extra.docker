<?php
namespace frontend\components;

use common\models\History;
use common\models\News2;
use common\models\Partners;
use Yii;
use common\models\GroupPrograms;
use common\models\Services;
use common\models\Shares;
use common\models\Articles;
use common\models\Events;
use common\models\Jobs;
use common\models\News;
use common\models\Trainers;
use yii\web\UrlRuleInterface;

class JobsUrlRule implements UrlRuleInterface
{
    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param \yii\web\Request $request the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        //This rule only applies to paths that start
        if (strpos($pathInfo, 'es/news') !== 0 && strpos($pathInfo, 'services') !== 0 && strpos($pathInfo, 'contacts') !== 0
            && strpos($pathInfo, 'schedule') !== 0 && strpos($pathInfo, 'es/history') !== 0
            && strpos($pathInfo, 'es/club') !== 0 && strpos($pathInfo, 'es/command') !== 0
            && strpos($pathInfo, 'es/events') !== 0 && strpos($pathInfo, 'es/job') !== 0
            && strpos($pathInfo, 'card/shares') !== 0 && strpos($pathInfo, 'card/type') !== 0
            && strpos($pathInfo, 'card/freezing') !== 0 && strpos($pathInfo, 'card/gift') !== 0
            && strpos($pathInfo, 'events') !== 0 && strpos($pathInfo, 'es/article') !== 0
            && strpos($pathInfo, 'tv') !== 0 && strpos($pathInfo, 'news/') !== 0) {
            return false;
        }

        $params = [];
        $parameters = explode('/', $pathInfo);

        if (count($parameters) > 1 && $parameters[0] == 'events' && $parameters[1] == 'subscribe') { // для работы отправки формы на мероприятия
            return false; // для работы отправки формы на мероприятия
        }

        /*if(count($parameters) == 1 && $parameters[0] == 'schedule'){
            return ['/es/schedule/', $params];
        }*/

        if (count($parameters) == 2) {
            if ($parameters[0] == 'events' && empty($parameters[1])) {
                return ['/es/events/', $params];
            } elseif($parameters[0] == 'tv' && empty($parameters[1])){
                return ['/schedule/tv/', $params];
            } elseif($parameters[0] == 'news' && empty($parameters[1])){
                return ['/news2/', $params];
            }
            return [$parameters[0], $params];
        }

        if (count($parameters) == 3 && empty($parameters[2])) {
            if ($parameters[0] == 'es' || $parameters[0] == 'card' || $parameters[0] == 'schedule'
                || ($parameters[0] == 'services' && $parameters[1] == 'programs') || ($parameters[0] == 'services' && $parameters[1] == 'subscribe')) {
                $parameters[1] = $parameters[1] == 'freezing' ? 'type/freezing' : $parameters[1];
                $parameters[1] = $parameters[1] == 'gift' ? 'type/gift' : $parameters[1];
                $parameters[1] = $parameters[1] == 'list' ? 'schedule/list' : $parameters[1];
                $parameters[1] = $parameters[1] == 'subcat' ? 'schedule/subcat' : $parameters[1];
                $parameters[1] = $parameters[0] == 'services' && $parameters[1] == 'subscribe' ? 'services/subscribe' : $parameters[1];
                return [$parameters[1], $params];
            } else {
                switch ($parameters[0]) {
                    case 'services':
                        $model = Services::findOne(['status' => 10, 'alias' => $parameters[1]]);
                        if ($model) {
                            $params['id'] = $model->id;
                        }
                        break;
                    case 'tv':
                        return ['/schedule/day/', $params];
                    case 'news':
                        $model = News2::findOne(['status' => 10, 'alias' => $parameters[1]]);
                        if ($model) {
                            $params['id'] = $model->id;
                        }
                        return ['/news2/view/', $params];
                }
            }
        }

        if (count($parameters) > 3) {
            $model = '';
            switch ($parameters[1]) {
                case 'history':
                    $model = History::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'article':
                    $model = Articles::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'command':
                    $model = Trainers::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'events':
                    $model = Events::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'job':
                    $model = Jobs::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'programs':
                    $model = GroupPrograms::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'shares':
                    $model = Shares::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'news':
                    $model = News::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
                case 'partners':
                    $model = Partners::findOne(['status' => 10, 'alias' => $parameters[2]]);
                    break;
            }

            if ($model) {
                $params['id'] = $model->id;
                return [$parameters[1].'/view/', $params];
            }
        }

        //controller/action that will handle the request
        $route = $parameters[0] . (count($parameters) > 1 ? '/view/' : '');

        //Yii::trace("Request parsed with URL rule: site/jobs", __METHOD__);
        return [$route, $params];
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route !== 'site/jobs') {
            return false;
        }
        //If a parameter is defined and not empty - add it to the URL
        $url = 'jobs/';
        if (array_key_exists('category', $params) && !empty($params['category'])) {
            $url .= $params['category'];
        }
        if (array_key_exists('subcategory', $params) && !empty($params['subcategory'])) {
            $url .= ',' . $params['subcategory'];
        }
        if (array_key_exists('state', $params) && !empty($params['state'])) {
            $url .= '/' . $params['state'];
        }
        if (array_key_exists('city', $params) && !empty($params['city'])) {
            $url .= ',' . $params['city'];
        }
        if (array_key_exists('page', $params) && !empty($params['page'])) {
            $url .= '/' . $params['page'];
        }
        return $url;
    }
}