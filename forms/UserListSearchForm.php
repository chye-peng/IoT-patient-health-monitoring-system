<?php
// admin performs those func 
namespace app\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class UserListSearchForm extends User
{
    public $item_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'is_active'], 'integer'],
            [['username', 'email', 'phone_number', 'is_active', 'created_at', 'item_name'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function search($params)
    {
        $query = User::find()
        ->joinWith('authAssignment');

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
                    'item_name',
                    'created_at',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'users.created_at', $this->created_at])
            ->andFilterWhere(['like', 'auth_assignment.item_name', $this->item_name])
            ->andFilterWhere(['in', 'item_name', ['patient', 'med_staff']])
            ->andFilterWhere(['is_delete' => '0']);

        return $dataProvider;
    }

}