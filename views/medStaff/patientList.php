<?php 
/** @var yii\web\View $this */

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap5\Html;
use app\models\HealthRecords;

$this->title = 'Patient List';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="patient-list">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $this->title ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card-body">
                <!-- Filter area -->
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'filter-form',
                        'method' => 'get',
                    ]);
                ?>
                    <div class="container">
                        <div class="row">
                            <?= $form->field($model, 'username', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'email', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'phone_number', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'created_at', ['options' => ['class' => 'col-6']])->label('Measuring Time')
                            ->widget(DatePicker::class,['dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control', 'readonly' => true],]) ?>
                        </div>

                        <div class="row">
                            <?= $form->field($model, 'heart_rate', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'spo2', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'ecg', ['options' => ['class' => 'col-6']]) ?>
                            <?= $form->field($model, 'remark', ['options' => ['class' => 'col-6']]) ?>
                        </div>

                        <br>
                        <?= Html::submitButton('<i class="nav-icon far fas fa-search"></i> Find', ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'filter-button']) ?> 
                        <br><br><hr>

                    </div>
                <?php
                    ActiveForm::end();
                ?>

                <?= Html::a('<i class="nav-icon far fas fa-plus"></i> Add', ['patient-records'], ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'add-button']) ?>
                <!-- data table -->
                <div>
                    <div class="row">
                        <table class="table table-bordered table-striped dataTable dtr-inline">
                        <?php 
                            $gridColumns = [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'username',
                                    'value' => function ($model) {
                                        return $model->user->username;
                                    },
                                ],
                                [
                                    'attribute' => 'age',
                                    'value' => function ($model) {
                                        return $model->user->age;
                                    },
                                ],
                                [
                                    'attribute' => 'gender',
                                    'value' => function ($model) {
                                        return $model->user->gender;
                                    },
                                ],
                                [
                                    'attribute' => 'email',
                                    'value' => function ($model) {
                                        return $model->user->email;
                                    },
                                ],
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return $model->user->phone_number;
                                    },
                                ],
                                [
                                    'label' => 'Account Status',
                                    'attribute' => 'is_active',
                                    'filter' => [
                                        '0' => 'Deleted',
                                        '10' => 'Active', 
                                    ],
                                    'value' => function ($model) {
                                        if ($model->user->is_active == 0) {
                                            return '<span class="badge badge-danger">Deleted</span>';
                                        } 
                                        elseif ($model->user->is_active == 10) {
                                            return '<span class="badge badge-success">Active</span>';
                                        }
                                    },
                                    'format' => 'raw', 
                                ],
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
                                'filename' => 'patient_list_'.date("d-m-Y"),
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
        </div>
    </section>
</div>




