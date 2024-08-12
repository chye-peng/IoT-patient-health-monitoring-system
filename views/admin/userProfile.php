<?php

/** @var yii\web\View $this */

$this->title = 'Edit user profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html lang="en">
<body>
<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\User;

$baseUrl = Yii::$app->homeUrl;
?>

<?php if (Yii::$app->session->hasFlash('updatedSuccessfully')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('updatedSuccessfully') ?>
    </div>
<?php endif; ?>

<?php 
    $userId = Yii::$app->getRequest()->getQueryParam('user_id');//use the user_id which is parsed from announcement settings view file 
    $user = User::findOne(['user_id'=> $userId]);
?>

<div class="row">
    <h4><a href="<?= $baseUrl ?>/admin/user-list"><i class="nav-icon far fas fa-arrow-left"></i></a></h4>
    <h2><?= $this->title ?></h2>
</div>

<div class="card ">
    <div class="card-body">
        <?php
            $form = ActiveForm::begin([
                'id' => 'update-form',
                'method' => 'post',
            ]);
            ?>

            <?= $form->field($model, 'email')->textInput(['value' => $user -> email]) ?> 
            <?= $form->field($model, 'phone_number')->textInput(['value' => $user -> phone_number]) ?> 
            <div>
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'update-button']) ?>
            </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
</body>
</html>
