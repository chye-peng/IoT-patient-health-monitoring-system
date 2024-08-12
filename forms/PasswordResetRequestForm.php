<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['is_active' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'is_active' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
        Yii::$app
            ->mailer
            ->compose()
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['appName'] . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for CP Patient Health Monitoring System')
            ->setHtmlBody(
                'Hi, '.$user->username.'. <p>Click the link below to reset your password. Please reset it within an hour.'. '. </p>'.
                '<p><a href="' . $resetLink . '">Set your password here before login </a></p>'.
                '<br><br>'.
                '<p>Best Regards,</p>'.
                '<p><i>admin</i></p>'
                )
            ->send();
        Yii::$app->session->setFlash('successSentEmailResetPassword', 'Check your email for further instructions.');
    }
}
