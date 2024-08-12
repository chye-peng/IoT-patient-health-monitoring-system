<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Medical Resources';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-medical-resources">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Information about their conditions, tips for health improvement, and user guides for the monitoring devices</p>

    <div>
        <div class="card card-lightblue" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
            <div class="card-header">
                <h3 class="card-title">Common questions of heart disease</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body" style="display: none;">
                <h5>Here are two crucial inquiries you should make to your cardiologist for a deeper comprehension of your condition. </h5>
                <p><b>Question 1: What are the origins and intensity of my heart condition? </b></p>
                <p>Gaining knowledge about the origins and intensity of your heart ailment can enhance your comprehension of your predicament and aid in planning your treatment strategy. Heart disease can be influenced by numerous factors, such as genetic predisposition, lifestyle habits, dietary choices, stress levels, and other health conditions.</p>
                <p>Your cardiologist can gauge the degree of harm to your heart and blood vessels by conducting a variety of examinations, such as an electrocardiogram (ECG), echocardiogram, angiogram, and stress test. These tests can reveal the efficiency of your heart’s function, the volume of blood flow reaching your heart, and the degree of narrowing or blockage in your arteries. Based on these test outcomes, your cardiologist can also ascertain the stage of your heart disease and the potential for complications.</p>
                
                <p><b>Question 2: What are the prescribed medications and their potential side effects?</b></p>
                <p>Should your cardiologist prescribe medications, it’s advantageous to delve into their specifics, such as their functions and potential side effects. Medications frequently address diverse aspects of heart disease, including reducing blood pressure, cholesterol, or blood sugar, averting blood clots, diminishing inflammation, or managing heart rhythm.</p>
            </div>
        </div>

        <div class="card card-teal" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
            <div class="card-header">
                <h3 class="card-title">Why health screenings are crucial</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body" style="display: none;">
                <p><b>What is health screening? </b></p>
                <p>A test conducted prior to the emergence of any disease symptoms is known as a screening test. The objective of this test is to identify potential diseases or assess your susceptibility to certain health conditions.</p>
                <br>
                <p><b>Why are health screenings important?</b></p>
                <p><b>The significance of health screening is reflected in multiple ways:</b></p>
                <p>Facilitates the early discovery of diseases</p>
                <p>Assists in pinpointing hidden health concerns</p>
                <p>Enables enhanced management of health</p>
            </div>
        </div>
    </div>
</div>
