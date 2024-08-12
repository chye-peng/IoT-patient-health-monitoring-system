<?php 
/** @var yii\web\View $this */

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap5\Html;
use app\models\User;
use yii\bootstrap5\Modal;

$this->title = 'User List';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-list">
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
                            <?= $form->field($model, 'item_name', ['options' => ['class' => 'col-6']])->label('Role')->dropDownList([
                                '' => 'All',
                                'med_staff' => 'Medical Staff',
                                'patient' => 'Patient', 
                                
                            ]) ?>
                            <?= $form->field($model, 'created_at', ['options' => ['class' => 'col-6']])
                            ->widget(DatePicker::class,['dateFormat' => 'yyyy-MM-dd', 'options' => ['class' => 'form-control', 'readonly' => true],]) ?>
                        </div>

                        <br>
                        <?= Html::submitButton('<i class="nav-icon far fas fa-search"></i> Find', ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'filter-button']) ?> 
                        <br><br><hr>

                    </div>
                <?php
                    ActiveForm::end();
                ?>

                <?= Html::a('<i class="nav-icon far fas fa-plus"></i> Add Medical Staff', ['med-staff-signup'], ['class' => 'btn btn-primary mt-2 float-right', 'name' => 'add-button']) ?>
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
                                        return $model->username;
                                    },
                                    
                                ],
                                [
                                    'attribute' => 'email',
                                    'value' => function ($model) {
                                        return $model->email;
                                    },
                                    
                                ],
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return $model->phone_number;
                                    },
                                    
                                ],
                                [
                                    'label' => 'Status',
                                    'attribute' => 'is_active',
                                    'filter' => [
                                        '0' => 'Deleted',
                                        '10' => 'Active', 
                                    ],
                                    'value' => function ($model) {
                                        if ($model->is_active == 0) {
                                            return '<span class="badge badge-danger">Deleted</span>';
                                        } 
                                        elseif ($model->is_active == 10) {
                                            return '<span class="badge badge-success">Active</span>';
                                        }
                                    },
                                    'format' => 'raw', 
                                ],
                                [
                                    'label' => 'Role',
                                    'attribute' => 'item_name',
                                    'filter' => [
                                        'med_staff' => 'Medical Staff',
                                        'patient' => 'Patient', 
                                    ],
                                    'value' => function ($model) {
                                        if ($model-> authAssignment ->item_name == 'med_staff') {
                                            return '<span class="badge badge-danger">Medical Staff</span>';
                                        } 
                                        elseif ($model-> authAssignment ->item_name == 'patient') {
                                            return '<span class="badge badge-warning">Patient</span>';
                                        }
                                    },
                                    'format' => 'raw', 
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'value' => function ($model) {
                                        return $model->created_at;
                                    },
                                    
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{edit} {delete} {testing}',
                                    'buttons' => [
                                        'edit' => function ($action, User $model, $key) {
                                            return Html::a('<i class="nav-icon far fas fa-pen-fancy"></i> Update', ['user-profile?user_id='.$model->user_id], ['class' => 'btn btn-primary btn-sm']); 
                                            
                                        },
                                        'delete'=> function ($action, User $model, $key) {
                                            return Html::a('<i class="nav-icon far fas fa-trash"></i> Delete', ['soft-delete-user','user_id' => $model->user_id], [  //soft-delete-user is a action of controller
                                                'class' => 'btn btn-danger btn-sm',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this data?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],                                                
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
                                'filename' => 'user_list_'.date("d-m-Y"),
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



