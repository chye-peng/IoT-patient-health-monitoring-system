<?php

/** @var yii\web\View $this */

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;

?>

<!DOCTYPE html>
<html lang="en">
<body>
    
<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap5\Html;
?>


<?php if (Yii::$app->session->hasFlash('updatedSuccessfully')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('updatedSuccessfully') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('CurrentPasswordNotExist')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('CurrentPasswordNotExist') ?>
    </div>
<?php endif; ?>

<h2><?= $this->title ?></h2>

<div class="card ">
    <div class="card-body">
        <?php
        $form = ActiveForm::begin([
            'id' => 'update-form',
            'method' => 'post',
        ]);
        ?>
        <?= $form->field($model, 'password_current', [
                    'template' => '{label}
                        <div class="input-group">
                            {input}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-eye-slash" id="toggleCurrentPassword"></i>
                                </span>
                            </div>
                            {error}
                        </div>'
                ])->passwordInput()->label('Current Password') ?> 
        

        <?= $form->field($model, 'password', [
                    'template' => '{label}
                        <div class="input-group">
                            {input}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-eye-slash" id="togglePassword"></i>
                                </span>
                            </div>
                            {error}
                        </div>'
                ])->passwordInput()->label('New Password') ?> 

        <?= $form->field($model, 'password_confirm', [
                    'template' => '{label}
                        <div class="input-group">
                            {input}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-eye-slash" id="toggleConfirmPassword"></i>
                                </span>
                            </div>
                            {error}
                        </div>'
                ])->passwordInput()->label('Confirm New Password') ?>

        <?php 
            $this->registerJs("
                // toggle password visibility
                document.getElementById('toggleCurrentPassword').addEventListener('click', function () {
                    var passwordInput = document.getElementById('patientprofileform-password_current'); // patientprofileform-password is the combination of small case of model name and form field name
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });

                document.getElementById('togglePassword').addEventListener('click', function () {
                    var passwordInput = document.getElementById('patientprofileform-password'); 
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });

                document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
                    var passwordInput = document.getElementById('patientprofileform-password_confirm'); 
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            ");
        ?>
        
        <div>
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'update-button']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>

    </div>
</div>
</body>
</html>




