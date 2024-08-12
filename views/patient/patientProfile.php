<?php

/** @var yii\web\View $this */

$this->title = 'Personal profile';
$this->params['breadcrumbs'][] = $this->title;

?>

<!DOCTYPE html>
<html lang="en">
<body>
<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\User;
use app\models\HealthRecords;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Json;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$baseUrl = Yii::$app->homeUrl;
?>

<?php if (Yii::$app->session->hasFlash('updatedSuccessfully')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('updatedSuccessfully') ?>
    </div>
<?php endif; ?>

<?php 
    $user = User::find()->where(['user_id' => Yii::$app->user->id])->one();
?>

<h2><?= $this->title ?></h2>

<div class="card">
    <div class="card-body">
        <?php
            $form = ActiveForm::begin([
                'id' => 'update-form',
                'method' => 'post',
            ]);
            ?>

            <?= $form->field($edit_model, 'email')->textInput(['value' => $user -> email]) ?> 
            <?= $form->field($edit_model, 'phone_number')->textInput(['value' => $user -> phone_number]) ?> 
            <?= $form->field($edit_model, 'age')->textInput(['value' => $user -> age]) ?> 

            <b>Gender </b>
            <?= $user->gender == 'Male' ? '<span class="badge badge-info">Male</span>' : 
            ($user->gender == 'Female' ? '<span class="badge" style="background-color: fuchsia;">Female</span>' : 
            '<span class="badge badge-danger">--</span>') ?>

            <?= $form->field($edit_model, 'gender', ['options' => ['class'=>'col-6']])->label('')
                    ->dropdownList(
                        [
                            'Male' => 'Male', 
                            'Female' => 'Female',
                        ],
                        [
                            'prompt' => 'Please select',
                        ]
                    ) ?>            

            <div>
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'update-button']) ?>
            </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div> 
<br>

