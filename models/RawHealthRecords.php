<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "raw_health_records".
 *
 * @property int $health_record_id
 * @property string $heart_rate
 * @property string $spo2
 * @property string $ecg
 * @property string $created_at
 * @property string|null $updated_at
 * @property int|null $is_delete
 */
class RawHealthRecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'raw_health_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['heart_rate', 'spo2', 'ecg'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_delete'], 'integer'],
            [['heart_rate', 'spo2', 'ecg'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'health_record_id' => 'Health Record ID',
            'heart_rate' => 'Heart Rate',
            'spo2' => 'SpO2',
            'ecg' => 'ECG',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
        ];
    }
}
