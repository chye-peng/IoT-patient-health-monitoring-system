<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\forms\UserListSearchForm3;
use app\forms\UserListSearchForm2;
use app\forms\UserListSearchForm;
use app\models\User;
use app\forms\UserManagementForm;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('adminDashboard');
    }

    protected function findUserModel($user_id)
    {
        if (($model = User::findOne(['user_id' => $user_id])) !== null) { 
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSoftDeleteUser($user_id)
    {
        $announcement = $this->findUserModel($user_id); 
        $announcement->is_delete = User::IS_DELETE_YES;
        $announcement->save();

        return $this->redirect(['user-list']);
    }

    public function actionUserList()
    {
        $model = new UserListSearchForm();
        $dataProvider = $model->search($this->request->queryParams); 

        return $this->render('userList', [
            'model' => $model,
            'dataProvider' => $dataProvider, 
        ]);
    }

    public function actionUserProfile()
    {
        $model = new UserManagementForm();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->updateUser();
        }

        return $this->render('userProfile', ['model'=>$model]);
    }

    public function actionMedStaffSignup()
    {
        $model = new UserManagementForm();

        if ($model->load(Yii::$app->request->post()) &&  $model->addMedicalStaff()) {
            return $this->render('medStaffSignup', [
                'model' => $model,
            ]);
        }

        return $this->render('medStaffSignup', [
            'model' => $model,
        ]);
    }
}
