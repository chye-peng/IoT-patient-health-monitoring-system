<?php

namespace app\models;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;
use yii\db\Expression;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property string $phone_number
 * @property string|null $verification_token
 * @property int|null $age
 * @property string|null $gender
 * @property int $is_active
 * @property string $created_at
 * @property string|null $updated_at
 * @property int|null $is_delete
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const IS_DELETE_YES = 1;
    const IS_DELETE_NO = 0;
    public $role;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'phone_number'], 'required'],
            [['age', 'is_active', 'is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone_number', 'verification_token', 'gender'], 'string', 'max' => 255],

            ['is_active', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'verification_token' => 'Verification Token',
            'age' => 'Age',
            'gender' => 'Gender',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($user_id)
    {
        $user = static::findOne(['user_id' => $user_id]); // get a user model from the database by searching for a row where the id column matches the provided $id

        if ($user !== null) { //if user model is found
            $user->role = $user->getRole(); //call getRole() method on the user model to retrieve the user's role and assigns it to the role property of the user model.
        }

        // If no user model is found, the returned value will be null.
        // If a user model is found, it may have its role property populated based on the user's role.
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    // use in LoginForn, MedStaffProfileForm
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::STATUS_ACTIVE, 'is_delete' => self::IS_DELETE_NO]);
    }

    // use in ResetPasswordForm
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'is_active' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    // use in MedStaffProfileForm
    public function setPassword($password)
    {
        //When a user provides a password for the first time (e.g., upon registration), the password needs to be hashed
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    // use in ResetPasswordForm
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    // use in PasswordResetRequestForm
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    // use in PasswordResetRequestForm
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    // use in ResetPasswordForm
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // use in SignupForm
    public function getRole()
    {
        // simply returns the value of the role property.
        return $this->role; 
    }

    //make a reletion to define the link between users and health_records table
    // use in UserListSearchForm
    public function getHealthRecords()
    {
        return $this->hasOne(HealthRecords::class, ['user_id' => 'user_id']);
    }

    //make a reletion to define the link between users and auth_assignment table
    // use in UserListSearchForm & PatientListSearchForm
    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'user_id']);
    }

    //count the total number of registered user(include admin, staff, patient), then show the number at the admin dashboard
    public static function getTotalUserCount()
    {
        return self::find()->count();
    }

    //count the today number of registered user, then show the number at the admin dashboard
    public static function getTodayUserCount()
    {
        return self::find()
        ->where(['>=', 'created_at', new Expression('CURDATE()')])
        ->andWhere(['<', 'created_at', new Expression('CURDATE() + INTERVAL 1 DAY')])
        ->count();
    }

    public static function getAdminCount()
    {
        return self::find()
        ->joinWith('authAssignment')
        ->where(['item_name' => 'admin'])
        ->count();
    }

    public static function getMedStaffCount()
    {
        return self::find()
        ->joinWith('authAssignment')
        ->where(['item_name' => 'med_staff'])
        ->count();
    }

    public static function getPatientCount()
    {
        return self::find()
        ->joinWith('authAssignment')
        ->where(['item_name' => 'patient'])
        ->count();
    }
}
