<?php
// admin performs those func
namespace app\forms;

use yii\base\Model;
use Yii;
use app\models\User;

class UserManagementForm extends Model
{
    public $user_id;
    public $email;
    public $phone_number;
    public $username;

    public function rules()
    {
        return [
            [['user_id', 'email', 'phone_number'], 'safe'], 
            ['email', 'email'],

            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'Username can only contain letters and spaces.'],    
            [['username', 'email', 'phone_number'], 'required'],
            ['phone_number', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Phone number must be 10 or 11 digits.'],
        ];
    }

    public function updateUser() {
        $userId = Yii::$app->getRequest()->getQueryParam('user_id');//use the user_id which is parsed from announcement settings view file 
        $user = User::findOne(['user_id'=> $userId]);

        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->update(false);
        Yii::$app->session->setFlash('updatedSuccessfully', 'Your changes are saved successfully');
    }

    public function addMedicalStaff()
    {
        //This checks whether the data in the model passes validation rules defined in the model.
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->setPassword($this->username); // Set the default password
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();

        $websiteLink = Yii::$app->urlManager->createAbsoluteUrl(['site/index']);
        Yii::$app
        ->mailer
        ->compose()
        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['appName'] . ' robot'])
        ->setTo($this->email)
        ->setSubject('New account has been created for CP Patient Health Monitoring System')
        ->setHtmlBody(
            'Hi, '.$user->username.'. <p><a href="' . $websiteLink . '">Welcome to CP Patient Health Monitoring System. </a>'. 
            'We are delighted to inform you that an account has been created for you. To log in to our website, please use the below credential.</p>'.
            '<p>Username: '.$user->username. '</p>'.
            '<p>Password: '.$user->username. '</p>'.
            '<br>'.
            '<p>We recommend that you change your password upon your first login to ensure the security of your account. You can do this by navigating to your change password setting to change your password.</p>'.
            '<p>If you have any questions or need assistance, feel free to reach out to our support team at '.Yii::$app->params['senderEmail'].'</p>'.
            '<br><br>'.
            '<p>Best Regards,</p>'.
            '<p><i>admin</i></p>'
            )
        ->send();
        Yii::$app->session->setFlash('successSentEmailNewSignup', 'Email sent regarding new medical staff account.');
        
        $authManager = Yii::$app->authManager;
        $authManager->assign($authManager->getRole('med_staff'), $user->id);

        // Indicate success in user feedback
        Yii::$app->session->setFlash('successAddMedStaff', 'New medical staff is added.');
        return true;
    }
}
