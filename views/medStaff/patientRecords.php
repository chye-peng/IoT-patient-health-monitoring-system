<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Json;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use miloschuman\highcharts\HighchartsAsset;
use yii\web\JqueryAsset;
HighchartsAsset::register($this);
JqueryAsset::register($this);

$this->title = 'Add Patient Record';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->homeUrl;

?>

<?php if (Yii::$app->session->hasFlash('successAddPatientRecord')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successAddPatientRecord') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('successSentEmailNewSignup')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('successSentEmailNewSignup') ?>
    </div>
<?php endif; ?>

<div class="row">
    <h4><a href="<?= $baseUrl ?>/medstaff/patient-list"><i class="nav-icon far fas fa-arrow-left"></i></a></h4>
</div>

<div class="row">
    <div class="site-patient-record col-6">
        <h1><?= $this->title ?></h1>
        <p>Please fill out the following fields to add patient record:</p>
        <p style="color:blue">** Please fill out all fields, if he/she is a new patient.</p>
        <p style="color:blue">** Please fill out all fields except email & phone number, if he/she is an existing patient.</p>

        <div class="row">
            <div class="col-lg-10">
                <?php $form = ActiveForm::begin(['id' => 'form-addpatientrecord']); ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'phone_number') ?>
                    <?= $form->field($model, 'heart_rate') ?>
                    <?= $form->field($model, 'spo2') ?>
                    <?= $form->field($model, 'ecg') ?>
                    <?= $form->field($model, 'remark')->label('Remark - <small>Optional</small>') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'addpatientrecord-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="card col-6">
        <h2>Current Measuring Health Records</h2>
        
        <div class="card-header">
            <!-- Graph -->
            <div id="graph-container">
                <?php
                    // Extracting data for the graph
                    $data = [];
                    foreach ($dataProvider->getModels() as $raw_model) {
                        $data[] = [
                            'measuringTime' => $raw_model->created_at,
                            'heartRate' => (float)$raw_model->heart_rate, // Convert to float
                            'spo2' => (float)$raw_model->spo2, // Convert to float
                            'ecg' => (float)$raw_model->ecg, // Convert to float
                        ];
                    }

                    // Converting data to JSON format
                    $data = Json::encode($data);

                    echo Highcharts::widget([ 
                        'id' => 'healthRecordsGraph',
                        'options' => [
                            'title' => ['text' => 'Graph'],
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
                <script>
                    var initialData = <?= $data ?>;
                    function initializeChart(data) {
                        Highcharts.chart('healthRecordsGraph', {
                            title: {
                                text: 'Graph'
                            },
                            xAxis: {
                                categories: data.map(item => item.measuringTime)
                            },
                            yAxis: {
                                title: {
                                    text: 'Values of physical signs'
                                }
                            },
                            series: [
                                {
                                    name: 'Heart Rate',
                                    data: data.map(item => item.heartRate)
                                },
                                {
                                    name: 'SpO2',
                                    data: data.map(item => item.spo2)
                                },
                                {
                                    name: 'ECG',
                                    data: data.map(item => item.ecg)
                                }
                            ]
                        });
                    }
                    $(document).ready(function() {
                        initializeChart(initialData);
                    });
                </script>

            </div> <br>

            <!-- data table -->
            <div id="table-container">
                <div class="row">
                    <table class="table table-bordered table-striped dataTable dtr-inline">
                    <?php 
                        $gridColumns = [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            [
                                'label' => 'Measuring Time',
                                'attribute' => 'created_at',
                                'value' => function ($raw_model) {
                                    return $raw_model->created_at;
                                },
                            ],
                            [
                                'attribute' => 'heart_rate',
                                'value' => function ($raw_model) {
                                    return $raw_model ->heart_rate;
                                },
                                
                            ],
                            [
                                'attribute' => 'spo2',
                                'value' => function ($raw_model) {
                                    return $raw_model ->spo2;
                                },
                            ],
                            [
                                'attribute' => 'ecg',
                                'value' => function ($raw_model) {
                                    return $raw_model ->ecg;
                                },
                            ],
                        ];
                        
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
    </div>
</div>

<!-- refresh the graph & table only -->
<?php
$this->registerJs("
    function refreshData() {
        $.ajax({
            url: window.location.href,
            type: 'GET',
            success: function(data) {
                $('#table-container').html($(data).find('#table-container').html());

                // Extract new data for the graph
                var newData = $(data).find('#graph-container').html().match(/var initialData = (\[.*?\]);/)[1];
                newData = JSON.parse(newData);

                // Update the graph with new data
                var chart = Highcharts.charts[0]; // Assumes there's only one chart instance
                chart.series[0].setData(newData.map(item => item.heartRate));
                chart.series[1].setData(newData.map(item => item.spo2));
                chart.series[2].setData(newData.map(item => item.ecg));
                chart.xAxis[0].setCategories(newData.map(item => item.measuringTime));
            }
        });
    }

    setInterval(refreshData, 2000); //refresh every x seconds
");
?>