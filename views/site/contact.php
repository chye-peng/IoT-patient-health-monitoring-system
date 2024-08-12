<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3>Get in touch with us today. We are always here to assist you.</h3>
    <p>The CP Patient Health Monitoring System is dedicated to providing you with the necessary support and information to enhance your health management. 
    If you have any questions and/or concerns, please contact us using the contact information below.</p>

    <div class="card-header">
        <div class="row g-0 contact-page-map-section">
            <div class="col-md-6 pe-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.14662745759!2d101.69798647485987!3d3.055405696920368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4abb795025d9%3A0x1c37182a714ba968!2sAsia%20Pacific%20University%20of%20Technology%20%26%20Innovation%20(APU)!5e0!3m2!1sen!2smy!4v1713511900975!5m2!1sen!2smy" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6 px-4 px-lg-7 py-6 bg-primary text-white">
                <br>
                <div>
                    <div>
                        <i class="nav-icon far fas fa-phone-volume text-light"></i>
                        General Line:<br>
                        <a href="tel:+(6011) 7323 4372" class="nav-link">+(6011) 7323 4372</a>
                    </div>
                </div>
                <hr style="height:2px;border-width:0;background-color:white">
                <div>
                    <div>
                    <i class="nav-icon far fas fa-briefcase-medical text-light"></i>
                        Appointment Line:<br>
                        <a href="tel:+(603) 9876 5432" class="nav-link">+(603) 9876 5432</a>
                    </div>
                </div>
                <hr style="height:2px;border-width:0;background-color:white">
                <div>
                    <div>
                        <i class="nav-icon far fas fa-envelope text-light"></i>
                        Email:<br>
                        <a href="mailto:careline@cp.com.my" class="nav-link">careline@cp.com.my</a>
                    </div>
                </div> 
                <br>
            </div>
        </div>
    </div>
    <br><br>

    <div class="row mb-7 mb-md-9">
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <div class="contact-page-info-row mb-5 mb-md-6">
                        <i class="nav-icon far fas fa-hospital text-blue"></i>
                        <div>
                            <b>APU</b><br>
                            Jalan Teknologi 5, Taman Teknologi Malaysia, <br>
                            57000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur.                     
                        </div>
                    </div>

                    <div class="contact-page-info-row mb-5 mb-md-6">
                        <i class="nav-icon far fas fa-clock text-blue"></i>
                        <div>                            
                            Specialists Clinic hours:<br>
                            Monday to Friday - 9.00 am to 5.00 pm<br>
                            Saturday - 9.00 am to 1.00 pm<br>
                            Sunday / Public Holidays - Closed                        
                        </div>
                    </div>
                    
                    <div class="contact-page-info-row mb-5 mb-md-6">
                        <i class="nav-icon far fas fa-stethoscope text-blue"></i>
                        <div>
                            Accident &amp; Emergency:<br>
                            It is open 24 hours.                        
                        </div>
                    </div>
                </div>
            </div>

            <hr class="d-md-none mt-5 mb-7 px-0" style="margin-left:12px; width:calc(100% - 24px);">
            <div class="col-md-6">
                <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                <div class="alert alert-success">
                    Thank you for contacting us. We will respond to you as soon as possible.
                </div>

                <?php else: ?>

                <p>
                    If you have any inquiries or other feedback, please fill out the following form to get in touch.
                    Thank you.
                </p>

                <div class="row">
                    <div class="col-lg-10">

                        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($model, 'email') ?>
                            <?= $form->field($model, 'title') ?>
                            <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
                            <div class="form-group">
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

                <?php endif; ?>
            </div>
    </div>
</div>
