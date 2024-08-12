<?php

namespace app\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HealthRecords;
use app\models\RawHealthRecords;
use Yii;

class PatientListSearchForm extends HealthRecords
{
    public $username;
    public $email;
    public $phone_number;
    public $age;
    public $gender;
    public $is_active;
    public $created_at;
    public $username_sort;
    public $email_sort;
    public $is_active_sort;
    public $created_at_sort;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['health_record_id', 'user_id', 'is_delete'], 'integer'],
            [['heart_rate', 'spo2', 'ecg', 'remark', 'created_at', 'updated_at'], 'safe'],

            [['user_id', 'is_active', 'age'], 'integer'],
            [['username', 'email', 'phone_number', 'is_active', 'created_at', 'gender'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    // in MedstaffController
    // search all patient
    public function search($params)
    {
        $query = HealthRecords::find()
        ->joinWith('user')
        ->joinWith('authAssignment');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'username',
                    'age',
                    'gender',
                    'email',
                    'phone_number',
                    'is_active',
                    'created_at',
                    'heart_rate',
                    'spo2',
                    'ecg',
                    'remark',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'health_record_id' => $this->health_record_id,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'heart_rate', $this->heart_rate])
            ->andFilterWhere(['like', 'spo2', $this->spo2])
            ->andFilterWhere(['like', 'ecg', $this->ecg])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'health_records.created_at', $this->created_at])
            ->andFilterWhere(['users.is_delete' => '0'])
            ->andFilterWhere(['item_name' => 'patient']);

        return $dataProvider;
    }

    // in PatientController
    // search specific patient baseed on user_id (logged-in user)
    public function searchSpecificPatient($params)
    {
        $query = HealthRecords::find()
        ->joinWith('user')
        ->joinWith('authAssignment')
        ->where(['health_records.user_id' => Yii::$app->user->id]); // Filter by logged-in user

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'username',
                    'email',
                    'phone_number',
                    'is_active',
                    'created_at',
                    'heart_rate',
                    'spo2',
                    'ecg',
                    'remark',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'health_record_id' => $this->health_record_id,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'heart_rate', $this->heart_rate])
            ->andFilterWhere(['like', 'spo2', $this->spo2])
            ->andFilterWhere(['like', 'ecg', $this->ecg])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'health_records.created_at', $this->created_at])
            ->andFilterWhere(['item_name' => 'patient']);

        return $dataProvider;
    }

    // in MedstaffController
    // search raw health records 
    public function searchRawHealthRecords($params)
    {
        // Get the latest 7 records in descending order of created_at
        $query2 = RawHealthRecords::find()
        ->orderBy(['created_at' => SORT_DESC]) 
        ->limit(7) // get xx rows
        ; 

        // Use the query2 as the main query and order the results by created_at in ascending order
        $query = RawHealthRecords::find()
        ->from(['sub' => $query2])
        ->orderBy(['sub.created_at' => SORT_ASC])
        ; 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false, //disable pagination to ensure the limit() applied to the query
            'sort' => [
                'attributes' => [
                    'created_at',
                    'heart_rate',
                    'spo2',
                    'ecg',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'health_record_id' => $this->health_record_id,
        ]);

        $query->andFilterWhere(['like', 'heart_rate', $this->heart_rate])
            ->andFilterWhere(['like', 'spo2', $this->spo2])
            ->andFilterWhere(['like', 'ecg', $this->ecg])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }

}