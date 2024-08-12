<?php
// medical staff perfoms those func
namespace app\forms;

use app\models\User;
use app\models\HealthRecords;
use yii\base\Model;
use Exception;
use Yii;

class MedStaffProfileForm extends Model
{
    public $password;
    public $password_confirm;
    public $password_current;

    public $username;
    public $email;
    public $phone_number;
    public $user_id;
    public $heart_rate;
    public $spo2;
    public $ecg;
    public $remark;
    
    public function rules()
    {
        return [
            [['password_current', 'password', 'password_confirm'], 'required'],
            [['password_current', 'password', 'password_confirm'], 'string'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match. Please type again."],

            [['username', 'email', 'phone_number'], 'trim'],
            [['email', 'phone_number'], 'safe'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'Username can only contain letters and spaces.'],    
            ['email', 'email'],
            ['phone_number', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Phone number must be 10 or 11 digits.'],

            [['user_id', 'heart_rate', 'spo2', 'ecg'], 'required'],
            [['heart_rate', 'spo2', 'ecg'], 'number'],
            ['remark', 'string', 'max' => 255],
        ];
    }

    public function updateMedStaffPassword() 
    {
        $user_model = User::find()->where(['user_id' => Yii::$app->user->id])->one();

        //compare the password_current with the password_hash in the user table
        if (!Yii::$app->security->validatePassword($this->password_current, $user_model->password_hash)) {
            Yii::$app->session->setFlash('CurrentPasswordNotExist', 'The current password is incorrect.');
            return false;
        }

        if (!empty($this->password)){
            $user_model->setPassword($this->password);
        }

        $user_model->update(false, ['password_hash']);
        Yii::$app->session->setFlash('updatedSuccessfully', 'Your new password is saved successfully');
    }

    public function addPatientRecords()
    {
        // Check if the user already exists
        $user = User::findByUsername($this->username);
        
        if (!$user) {
            // User does not exist, create new user
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->phone_number = $this->phone_number;
            $user->setPassword($this->username); // Set the default password
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            
            if (!$user->save()) {
                throw new Exception('Failed to save user.');
            }

            $websiteLink = Yii::$app->urlManager->createAbsoluteUrl(['site/index']);
            Yii::$app
            ->mailer
            ->compose()
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['appName'] . ' robot'])
            ->setTo($this->email)
            ->setSubject('New account has been created for CP Patient Health Monitoring System')
            ->setHtmlBody(
                'Hi, '.$user->username.'. <p><a href="' . $websiteLink . '">Welcome to CP Patient Health Monitoring System. </a>'. 
                'We are delighted to inform you that an account has been created for you. 
                To log in to our website, please use the below credential.</p>'.
                '<p>Username: '.$user->username. '</p>'.
                '<p>Password: '.$user->username. '</p>'.
                '<p>We recommend that you change your password upon your first login to ensure the security of your account. 
                You can do this by navigating to your change password setting to change your password.</p>'.
                '<p>If you have any questions or need assistance, feel free to reach out to our support team at '.
                Yii::$app->params['senderEmail'].'</p>'.
                '<br><br>'.
                '<p>Best Regards,</p>'.
                '<p><i>admin</i></p>'
                )
            ->send();
            Yii::$app->session->setFlash('successSentEmailNewSignup', 'Email sent regarding new patient account.');
            
            $authManager = Yii::$app->authManager;
            $authManager->assign($authManager->getRole('patient'), $user->id);
        }
        
        // Whether the user was just created or already existed, add health records
        $health_records = new HealthRecords(); 
        $health_records->user_id = $user->id; // Use the ID of the user
        $health_records->heart_rate = $this->heart_rate;
        $health_records->spo2 = $this->spo2;
        $health_records->ecg = $this->ecg;
        $health_records->remark = $this->remark;

        if (!$health_records->save(false)) {
            throw new Exception('Failed to save health records.');
        }

        // Indicate success in user feedback
        Yii::$app->session->setFlash('successAddPatientRecord', 'Patient record is added.');
        return true;
    }
}
