<?php
namespace backend\controllers;

use Yii;
use PharException;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\ResetPasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $auth = Yii::$app->authManager;
        Yii::$app->view->params['roles'] = array_keys($auth->getRolesByUser(Yii::$app->user->id));
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'reset-password', 'change-password', 'change-club'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['backup'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->id == 1) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = '//main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Resets password.
     * @return mixed
     * @throws BadRequestHttpException
     * @internal param string $token
     */
    public function actionResetPassword()
    {
        try {
            $model = new ResetPasswordForm();
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Change password.
     * @param $id
     * @return mixed
     * @throws BadRequestHttpException
     * @internal param string $token
     */
    public function actionChangePassword($id)
    {
        try {
            $model = new ResetPasswordForm();
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword($id)) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionChangeClub()
    {
        $allowedBd = ['', '4'];
        $bd = isset($_POST['bd']) ? (string)$_POST['bd'] : '';
        if (!in_array($bd, $allowedBd, true)) {
            $bd = '';
        }

        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'bd',
            'value' => $bd,
        ]));

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBackup()
    {
        FileHelper::createDirectory(dirname(dirname(__DIR__)) . '/backups');

        /** @var \demi\backup\Component $backup */
        $backup = Yii::$app->backup;

        try {
            var_dump($backup->create());
        } catch (PharException $e) {
            var_dump($e->getMessage());
        }
    }
}
