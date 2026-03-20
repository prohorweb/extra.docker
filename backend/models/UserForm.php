<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class UserForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 7],

            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' =>7],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email пользователя',
            'title' => 'Заголовок материала',
            'status' => 'Блокировка пользователя',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',

            'created_at' => 'Создано',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Save user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function saveUser()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();


        if ($user->save()) {
            // - - - - - Для контроля доступа к разделам сайта - - -
            $auth = Yii::$app->authManager;
            if (isset(Yii::$app->request->post()['access'])) {
                foreach (Yii::$app->request->post()['access'] as $access) {
                    $perm = $auth->getRole($access);
                    $auth->assign($perm, $user->id);
                }
            }
            // - - - - - - - - - - - - - - - - - - - -
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Update user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updateUser()
    {
        $user = User::findOne($this->id);
        $user->status = $this->status;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;

        // - - - - - - Для контроля доступа к разделам сайта - - -
        $auth = Yii::$app->authManager;
        if (isset(Yii::$app->request->post()['access'])) {
            $auth->revokeAll($user->id);
            foreach (Yii::$app->request->post()['access'] as $access) {
                $perm = $auth->getRole($access);
                $auth->assign($perm, $user->id);
            }
        }
        // - - - - - - - - - - - - - - - - - - - -

        return $user->save() ? $user : null;
    }


    public function getPermissions()
    {

        return [
            'rasp' => 'Расписание',
            'articles' => 'Советы тренеров',
            'club' => 'Информация о клубе',
            'services' => 'Услуги',
            'news' => 'Новости',
            'shares' => 'Акции',
            'club_cards' => 'Абонементы',
            'events' => 'Мероприятия',
            'trainers' => 'Команда',
            'user' => 'Пользователи',
            'main_banners' => 'Баннеры',
            'group_programs' => 'Групповые программы',
            'jobs' => 'Вакансии',
            'settings' => 'Настройки сайта',
            'push' => 'Push уведомления',
            'subscribe' => 'Подписки',
            'history' => 'Истории успеха'
        ];
    }

}
