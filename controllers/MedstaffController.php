<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\forms\MedStaffProfileForm;
use app\forms\PatientListSearchForm;

class MedstaffController extends Controller
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

    public function actionIndex()
    {
        return $this->render('medStaffDashboard');
    }

    public function actionChangePassword()
    {
        $model = new MedStaffProfileForm;

        if($model->load(Yii::$app->request->post()) ){
            $model->updateMedStaffPassword();
        }
        return $this->render('changePassword', ['model'=>$model]);
    }

    public function actionPatientList()
    {
        $model = new PatientListSearchForm();
        
        $dataProvider = $model->search($this->request->queryParams); 

        return $this->render('patientList', [
            'model' => $model,
            'dataProvider' => $dataProvider, 
        ]);
    }

    public function actionPatientRecords()
    {
        $model = new MedStaffProfileForm();

        $raw_model = new PatientListSearchForm();
        $dataProvider = $raw_model->searchRawHealthRecords($this->request->queryParams); 

        if ($model->load(Yii::$app->request->post()) ) {
            $model->addPatientRecords();
        }

        return $this->render('patientRecords', [
            'model' => $model,
            'raw_model' => $raw_model,
            'dataProvider' => $dataProvider, 
        ]);
    }
}
