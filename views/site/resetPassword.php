<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\form\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('wrongPasswordResetToken')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('wrongPasswordResetToken') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('resetTokenExpired')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('resetTokenExpired') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('successResetPassword')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successResetPassword') ?>
    </div>
<?php endif; ?>

<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please input your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

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
                ])->passwordInput() ?>

                <?= $form->field($model, 'password_confirm', [
                    'template' => '{label}
                        <div class="input-group">
                            {input}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-eye-slash" id="togglePasswordConfirm"></i>
                                </span>
                            </div>
                            {error}
                        </div>'
                ])->passwordInput() 
                ->label('Confirm New Password')?>

                <?php 
                    $this->registerJs("
                        // toggle password visibility
                        document.getElementById('togglePassword').addEventListener('click', function () {
                            var passwordInput = document.getElementById('resetpasswordform-password'); // resetpasswordform-password is the combination of small case of model name and form field name
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

                <?php 
                    $this->registerJs("
                        // toggle password visibility
                        document.getElementById('togglePasswordConfirm').addEventListener('click', function () {
                            var passwordInput = document.getElementById('resetpasswordform-password_confirm'); // resetpasswordform-password is the combination of small case of model name and form field name
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

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
