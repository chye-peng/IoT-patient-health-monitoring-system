<?php

/** @var yii\web\View $this */

$this->title = 'Medical Staff dashboard';
$this->params['breadcrumbs'][] = $this->title;

?>

<h2><?= $this->title ?></h2>

<div class="row">
    <div class="small-box bg-info mr-3 my-3 col-3">
        <div class="inner">
            <h3><?= Yii::$app->user->identity->username ?></h3>
            <p>My account</p>
        </div>
        <div class="icon">
            <i class="fas fa-house-user"></i>
        </div>
    </div>

    <div class="small-box bg-info mr-3 my-3 col-3">
        <div class="inner">
            <h3><?= date("d-m-Y") ?></h3>
            <p>Last login</p>
        </div>
        <div class="icon">
            <i class="fas fa-key"></i>
        </div>
    </div>
</div>
