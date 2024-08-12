<?php

namespace app\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $title;
    public $message;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'title', 'message'], 'required'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'Name can only contain letters and spaces.'],
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                // ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->title)
                ->setHtmlBody(
                    '<p>'.$this->message. '</p>'.
                    '<br><br>'.
                    '<p>Best Regards,</p>'.
                    '<p><i>'.$this->name.'</i></p>'.
                    '<p>'.$this->email.'</p>'
                )
                ->send();

            return true;
        }
        return false;
    }
}
