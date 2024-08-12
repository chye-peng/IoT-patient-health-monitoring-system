<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\forms\PatientProfileForm;
use app\models\User;
use app\forms\PatientListSearchForm;

class PatientController extends Controller
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
        return $this->render('patientDashboard');
    }

    public function actionChangePassword()
    {
        $model = new PatientProfileForm;

        if($model->load(Yii::$app->request->post()) ){
            $model->updatePatientPassword();
        }
        return $this->render('changePassword', ['model'=>$model]);
    }

    public function actionPatientProfile()
    {
        $edit_model = new PatientProfileForm();

        if ($edit_model->load(Yii::$app->request->post()) ) {
            $edit_model->updatePatientProfile();
        }

        return $this->render('patientProfile', ['edit_model'=>$edit_model]);
    }

    public function actionPatientHealthRecords()
    {
        $model = new PatientListSearchForm();
        $dataProvider = $model->searchSpecificPatient($this->request->queryParams); 

        return $this->render('patientHealthRecords', ['model' => $model, 'dataProvider' => $dataProvider]);
    }
}
