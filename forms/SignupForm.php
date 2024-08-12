<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirm;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'phone_number', 'password_confirm'], 'trim'],
            [['username', 'password', 'email', 'phone_number', 'password_confirm'], 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'Username can only contain letters and spaces.'],            
            ['email', 'email'],
            [['password', 'password_confirm'], 'string'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match. Please type again."],
            ['phone_number', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Phone number must be 10 or 11 digits.'],
        ];
    }

    public function signup()
    {
        //This checks whether the data in the model passes validation rules defined in the model.
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();

        // authManager must be put after $user->save(), otherwise user_id is null when save into auth_assignment table
        $authManager = Yii::$app->authManager;
        $authManager->assign($authManager->getRole('patient'), $user->id);
        Yii::$app->session->setFlash('successSignUp', 'New account is registered.');

        return true;
    }
    
}
