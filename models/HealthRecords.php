<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "health_records".
 *
 * @property int $health_record_id
 * @property int $user_id
 * @property string $heart_rate
 * @property string $spo2
 * @property string $ecg
 * @property string|null $remark
 * @property string $created_at
 * @property string|null $updated_at
 * @property int|null $is_delete
 *
 * @property Users $user
 */
class HealthRecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'health_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'heart_rate', 'spo2', 'ecg'], 'required'],
            [['user_id', 'is_delete'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['heart_rate', 'spo2', 'ecg', 'remark'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'health_record_id' => 'Health Record ID',
            'user_id' => 'User ID',
            'heart_rate' => 'Heart Rate',
            'spo2' => 'SpO2',
            'ecg' => 'ECG',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    //make a reletion to define the link between users and auth_assignment
    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'user_id']);
    }
}
