<?php

/** @var yii\web\View $this */

$this->title = 'Health Records';
$this->params['breadcrumbs'][] = $this->title;

?>

<!DOCTYPE html>
<html lang="en">
<body>
<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\User;
use app\models\HealthRecords;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Json;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$baseUrl = Yii::$app->homeUrl;
?>

<?php 
    $user = User::find()->where(['user_id' => Yii::$app->user->id])->one();
    // findAll is to return an array or collection of records rather than a sigle instance
    $health_records = HealthRecords::findAll(['user_id' => $user]); //find all health_records info where its user_id of health_records same as user_id of user table 

    // initialize variables as 0
    $totalHeartRate = 0;
    $totalSpo2 = 0;

    // set the no of health records retrieved
    $count = count($health_records);

    foreach ($health_records as $record) {
        $totalHeartRate += $record->heart_rate;
        $totalSpo2 += $record->spo2;
    }

    // $count ? ... : 0) checks if the count of health records is greater than 0. 
    // If it is, the total values are divided by the count to get the average. 
    // If the count is 0 (eg, no health records were found), the average values are set to 0 to avoid division by zero.
    $averageHeartRate = $count ? $totalHeartRate / $count : 0;
    $averageSpo2 = $count ? $totalSpo2 / $count : 0;

    $MaxHeartRate = 120;
    $MaxSpo2 = 100;

    $percentageHeartRate = ($averageHeartRate / $MaxHeartRate) * 100;
    if ($percentageHeartRate <= 49.17) {
        $progressBarClassHeartRate = 'bg-primary';
        $statusHeartRate = 'low';
    } elseif ($percentageHeartRate <= 83.34) {
        $progressBarClassHeartRate = 'bg-success';
        $statusHeartRate = 'normal';
    } else {
        $progressBarClassHeartRate = 'bg-danger';
        $statusHeartRate = 'high';
    }

    $percentageSpo2 = ($averageSpo2 / $MaxSpo2) * 100;
    if ($percentageSpo2 <= 90) {
        $progressBarClassSpo2 = 'bg-danger';
        $statusSpo2 = 'too low';
    } elseif ($percentageSpo2 <= 94) {
        $progressBarClassSpo2 = 'bg-warning';
        $statusSpo2 = 'low ';
    } else {
        $progressBarClassSpo2 = 'bg-primary';
        $statusSpo2 = 'normal';
    }
?>

<h2><?= $this->title ?></h2>

<div class="card ">
    <div class="card-body">
        <!-- Graph -->
        <div class="row">
            <div class="col-7">
                <?php
                    // Extracting data for the graph
                    $data = [];
                    foreach ($dataProvider->getModels() as $model) {
                        $data[] = [
                            'measuringTime' => $model->created_at,
                            'heartRate' => (float)$model->heart_rate, // Convert to float
                            'spo2' => (float)$model->spo2, // Convert to float
                            'ecg' => (float)$model->ecg, // Convert to float
                        ];
                    }

                    // Converting data to JSON format
                    $data = Json::encode($data);

                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'Health Records Graph'],
                            'xAxis' => [
                                'categories' => array_map(function($item) {
                                    return $item['measuringTime'];
                                }, json_decode($data, true))
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'Values of vital health parameters']
                            ],
                            'series' => [
                                ['name' => 'Heart Rate', 'data' => array_map(function($item) {
                                    return $item['heartRate'];
                                }, json_decode($data, true))],
                                ['name' => 'SpO2', 'data' => array_map(function($item) {
                                    return $item['spo2'];
                                }, json_decode($data, true))],
                                ['name' => 'ECG', 'data' => array_map(function($item) {
                                    return $item['ecg'];
                                }, json_decode($data, true))]
                            ], 
                        ]
                    ]);
                ?>
            </div>

            <div class="col-5">
                <h4>Average</h4> 

                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-card-widget="collapse" href="">
                                <div class="progress-group" style="color: black;">
                                    <i class="nav-icon far fas fa-heart"></i> Heart Rate (bpm): <b><?= round($averageHeartRate) ?></b> 
                                    <span class="float-right"><i> <?= $statusHeartRate ?> </i> </span> 
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div class="card-body" style="display: none;">
                        <div class="progress progress-sm">
                            <div class="progress-bar <?= $progressBarClassHeartRate ?> progress-bar-striped" style="width: <?= min($percentageHeartRate, 100) ?>%"></div>
                        </div>
                        <b>Low:</b> &lt;60 bpm (A heart rate below 60 beats per minute (bpm) is considered low. However, exceptions apply for athletes. If you’re not an athlete and your heart rate falls below 60 bpm, it’s essential to consult a medical staff.) <br>
                        <b>Normal:</b> 60-100 bpm (Most adults have a resting heart rate within this range. Regular exercise, stress management and overall well-being contribute to maintaining a normal heart rate.)<br>
                        <b>High:</b> &gt;100 bpm (A resting heart rate exceeding 100 bpm is considered high. Factors like stress, anxiety or medical conditions can elevate the heart rate. If you consistently experience a high resting heart rate, seek medical advice to identify any underlying issues.)
                    </div>
                </div>

                <div class="card card-outline card-gray">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-card-widget="collapse" href="">
                                <div class="progress-group" style="color: black;">
                                    <i class="nav-icon far fas fa-water"></i> Oxygen saturation [SpO2] (%): <b><?= round($averageSpo2) ?></b> 
                                    <span class="float-right"><i> <?= $statusSpo2 ?> </i> </span> 
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div class="card-body" style="display: none;">
                        <div class="progress progress-sm">
                            <div class="progress-bar <?= $progressBarClassSpo2 ?> progress-bar-striped" style="width: <?= min($percentageSpo2, 100) ?>%"></div>
                        </div>
                        <b>Too Low:</b> &lt;90%  (This is an emergency situation. Immediate medical attention is required.)<br>
                        <b>Low:</b> &gt;90 - 94% (This is still concerning. It is important to contact medical staff promptly for evaluation and further guidance.)<br>
                        <b>Normal:</b> 95 - 100% (This is considered healthy. No immediate action is needed, but it’s always good to monitor and maintain these levels.)
                    </div>
                </div>

                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-card-widget="collapse" href="">
                                <div class="progress-group" style="color: black;">
                                    <i class="nav-icon far fas fa-stethoscope"></i> Electrocardiogram [ECG]
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div class="card-body" style="display: none;">
                        <b>Functions:</b> <br>
                        Irregularity in the rhythm of the heartbeat results in arrhythmia.
                        Analysis of PQRST wave helps identifying the type of arrhythmia. <br><br>

                        <b>Steps to check for a normal or abnormal heartbeat:</b> <br>
                        1. <u>Calculate heart rates of Atrial and Ventricle.</u> A rate lesser than 60 bpm indicates slow heartbeat rate, 
                        a value between 60 bpm and 100 bpm is considered normal and a rate higher than 100 bpm suggests fast heart rate.<br>
                        2. <u>Check if the rhythm is regular or irregular.</u> This can be determined by analysing if RR intervals and PP intervals are regularly spaced. 
                        Once the irregularity is identified, the next step is to determine if 
                        the irregularity is occasional, regularly irregular or irregularly irregular.<br>
                        <img class="attachment-img" src="/fyp/images/ecg2.png" alt="ecg 2 image" style="height:70%; width:90%;"> <br>
                    </div>
                </div>

            </div>
        </div> 

        <!-- data table -->
        <div>
            <table class="table table-bordered table-striped dataTable dtr-inline">
            <?php 
                $gridColumns = [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'label' => 'Measuring Time',
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return $model->created_at;
                        },
                    ],
                    [
                        'attribute' => 'heart_rate',
                        'value' => function ($model) {
                            return $model ->heart_rate;
                        },
                        
                    ],
                    [
                        'attribute' => 'spo2',
                        'value' => function ($model) {
                            return $model ->spo2;
                        },
                    ],
                    [
                        'attribute' => 'ecg',
                        'value' => function ($model) {
                            return $model ->ecg;
                        },
                    ],
                    [
                        'attribute' => 'remark',
                        'value' => function ($model) {
                            return $model ->remark;
                        },
                    ],
                ];
            
                // Renders a export dropdown menu
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'clearBuffers' => true, //optional //ensure that only export content is included in exported file 
                    'dropdownOptions' => [
                        'label' => 'Export', 
                        'class' => 'btn btn-outline-secondary btn-default',
                    ],
                    'filename' => 'health record of '.$user->username.date(" d-m-Y"),
                    'columnSelectorOptions' => [
                        'label' => 'Select Columns to Export',
                        'class' => 'btn btn-outline-secondary',
                    ],
                    'showConfirmAlert' => false, 
                    'exportConfig' => [ // export into csv & pdf, disable the other formats
                        ExportMenu::FORMAT_HTML => false, // Disable HTML export
                        ExportMenu::FORMAT_TEXT => false, // Disable Text export
                        ExportMenu::FORMAT_EXCEL_X => false, // Disable Excel_X export
                        ExportMenu::FORMAT_EXCEL => false, // Disable Excel export
                    ],
                ]);
                
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'pager' => ['options' => ['class' => 'pagination float-right']], //align the pagination button to the right 
                    'layout' => "{items}\n{summary}\n{pager}",
                ]);
            ?>
                
            </table>
        </div>
    </div>
</div>

