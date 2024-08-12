<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Add Medical Staff';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->homeUrl;
?>

<?php if (Yii::$app->session->hasFlash('successAddMedStaff')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successAddMedStaff') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('successSentEmailNewSignup')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successSentEmailNewSignup') ?>
    </div>
<?php endif; ?>

<div class="row">
    <h4><a href="<?= $baseUrl ?>/admin/user-list"><i class="nav-icon far fas fa-arrow-left"></i></a></h4>
    <h1><?= $this->title ?></h1>
</div>

<div class="site-patient-record">
    <div class="row">
    <p>Please fill out the following fields to add medical staff:</p>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'form-add-med-staff']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'phone_number') ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>

