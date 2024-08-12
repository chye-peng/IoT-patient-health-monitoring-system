<?php
/** @var yii\web\View $this */

use app\models\User;
use yii\helpers\Json;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$this->title = 'Admin dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<h2>Admin Dashboard</h2>

<div class="row">
    <div class="small-box bg-info mr-2 my-2 col-3">
        <div class="inner">
            <h3><?= date("d-m-Y") ?></h3>
            <p>Last login</p>
        </div>
        <div class="icon">
            <i class="fas fa-key"></i>
        </div>
    </div>

    <div class="small-box bg-info m-2 col-2">
        <div class="inner">
            <h3><?= User::getTotalUserCount() ?></h3>
            <p>Total registered user</p>
        </div>
        <div class="icon">
        <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="small-box bg-info m-2 col-2">
        <div class="inner">
            <h3><?= User::getTodayUserCount() ?></h3>
            <p>Today registered user</p>
        </div>
        <div class="icon">
        <i class="fas fa-user"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="small-box bg-info m-2 col-4">
        <div class="inner" >
            <br><br><br><br>
            <p><i>Total no. of admin:</i> <b><?= User::getAdminCount() ?></b></p>
            <p><i>Total no. of medical staff:</i> <b><?= User::getMedStaffCount() ?></b></p>
            <p><i>Total no. of patient:</i> <b><?= User::getPatientCount() ?></b></p>
            <h5>Number registered user based on roles</h5>
        </div>
        <div class="icon">
        <i class="fas fa-user-tie"></i>
        </div>
    </div>

    <div class="small-box m-2 col-4">
        <?php
            // define the total user count
            $totalUsers = User::getTotalUserCount();

            // Calculate percentages //use round() to calculate % properly
            $adminPercentage = ($totalUsers > 0) ? round((User::getAdminCount() / $totalUsers) * 100, 2) : 0;
            $medStaffPercentage = ($totalUsers > 0) ? round((User::getMedStaffCount() / $totalUsers) * 100, 2) : 0;
            $patientPercentage = ($totalUsers > 0) ? round((User::getPatientCount() / $totalUsers) * 100, 2) : 0;    
        
            echo Highcharts::widget([
                'options' => [
                    'chart' => [
                        'type' => 'pie'
                    ],
                'title' => ['text' => 'Total Registered User Percentage (%)'],
                'series' => [
                    [
                        'name' => 'User Distribution',
                        'data' => [
                            ['Admin', $adminPercentage],
                            ['Medical Staff', $medStaffPercentage],
                            ['Patient', $patientPercentage]
                        ]
                    ],
                ]
                ]
            ]);
        ?>
        </div>
</div>

