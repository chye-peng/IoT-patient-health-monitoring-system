<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\forms\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('successSentEmailResetPassword')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successSentEmailResetPassword') ?>
    </div>
<?php endif; ?>

<body class="request-password-reset-page" style="min-height: 332.135px;">
<div class="site-request-password-reset">
    <div>
        <div class="request-password-reset-box">
            <div class="request-password-reset-logo">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>

            <p class="request-password-reset-box-msg">Please fill out your email. A link to reset password will be sent there.</p>

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="col-12">
                    <?= Html::submitButton('Request', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>

            <p class="mt-3 mb-1 text-right">
            <?= Html::a('<u>Login</u>', ['site/login']) ?>
            </p>
        </div>
    </div>
</div>
</body>
