<?php

namespace app\forms;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use Yii;
use app\models\User;

class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            Yii::$app->session->setFlash('wrongPasswordResetToken', 'Your password reset token is invalid. You cannot reset the password. 
            <br>Please reapply the <a href="request-password-reset">password reset link. </a>');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],*/
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match. Please type again."],

        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;

        if ($user === null) {
            Yii::$app->session->setFlash('resetTokenExpired', 'Password reset token has expired. 
            Please reapply the <a href="<?php ?>site/request-password-reset">password reset link. </a>');
            return false;
        }

        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        $user->save(false);
        Yii::$app->session->setFlash('successResetPassword', 'New password saved.');
        return true; 
    }
}
