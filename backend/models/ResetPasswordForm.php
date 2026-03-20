<?php
namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $new_password;
    public $password_repeat;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 7],

            ['new_password', 'required'],
            ['new_password', 'string', 'min' => 7],

            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' =>7],
            ['password_repeat', 'compare', 'compareAttribute'=>'new_password', 'message'=>"Пароли не совпадают" ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'password_repeat' => 'Повтор пароля',
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = Yii::$app->user->identity;
        /** @var \common\models\User $user */
        if($user->validatePassword($this->password)) {
            $user->setPassword($this->new_password);
            $user->removePasswordResetToken();
            return $user->save(false);
        } else {
            $this->addError('password', 'Wrong password');
            return false;
        }
    }

    /**
     * Change password.
     *
     * @param $id
     * @return bool if password was changed.
     */
    public function changePassword($id)
    {
        $user = User::findOne($id);
        $user->setPassword($this->new_password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
