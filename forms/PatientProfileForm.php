<?php
// patient perfoms those func
namespace app\forms;

use app\models\User;
use app\models\HealthRecords;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Exception;
use Yii;

class PatientProfileForm extends Model
{
    public $password;
    public $password_confirm;
    public $password_current;

    public $email;
    public $phone_number;
    public $age;
    public $gender;
    
    public function rules()
    {
        return [
            [['password_current', 'password', 'password_confirm'], 'required'],
            [['password_current', 'password', 'password_confirm'], 'string'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match. Please type again."],

            [['email', 'phone_number', 'age', 'gender'], 'required'],
            ['email', 'email'],
            ['phone_number', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Phone number must be 10 or 11 digits.'],
            ['age', 'integer'],
        ];
    }

    public function updatePatientPassword() 
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

    public function updatePatientProfile() {
        $user = User::find()->where(['user_id' => Yii::$app->user->id])->one();

        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->age = $this->age;
        if ($this->gender) { // ensure that these fields do not bocome blank in the db when it is not modified  
            $user->gender = $this->gender;
        }

        $user->update(false);
        Yii::$app->session->setFlash('updatedSuccessfully', 'Your changes are saved successfully');
    }

}