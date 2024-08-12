<?php

/** @var yii\web\View $this */

$this->title = 'Homepage';
$baseUrl = Yii::$app->homeUrl;
?>

<div class="site-index">
    <div class="jumbotron text-center bg-transparent mb-5">
        <img class="attachment-img" src="/fyp/images/logo.jpeg" alt="website Logo" class="rounded-circle" style="height:200px; width:300px;">

        <h1 class="display-4">CP Patient Health Monitoring System</h1>
        <p class="lead">This virtual hospital is an online health information resource for medical staff and patients.</p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="card card-olive shadow-none col-6">
                <div class="card-header">
                    <h3 class="card-title">Care powered by innovation</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
            </div>

                <div class="card-body" style="display: block;">
                To offer you with more excellent service, we are constantly evolving to enhance your well-being in remarkable ways. 
                We spare no effort in ensuring our virtual hospital remains at the cutting edge of medical technology, 
                embracing new techniques, state-of-the-art equipment, and the latest healthcare innovations.
                </div>
            </div>

            <div class="card card-success shadow-none col-6">
                <div class="card-header">
                    <h3 class="card-title">Premium healthcare with a human connection</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body" style="display: block;">
                In our virtual hospital, every action we take is rooted in our commitment to providing you 
                with the finest healthcare experience, delivered with a touch of personal care.
                </div>
            </div>
        </div>
    </div>
</div>
