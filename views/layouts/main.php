<?php

use app\assets\AdminLteAsset;
use yii\helpers\Html;
/** @var yii\web\View $this */

AdminLteAsset::register($this); // it registers assets related to the sidebars

$baseUrl = Yii::$app->homeUrl;
?>

<style>
    /* let the the validation error message be red colour */
    .help-block {
        color: red;
    }
</style>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" ng-app="app">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Yii::t('app', 'FYP') ?></title>
        <?php $this->registerCsrfMetaTags() ?>
        <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-gray navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= $baseUrl ?>" class="brand-link">
            <span class="brand-text font-weight-bold"><?= Yii::t('app', 'FYP') ?></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                </div>
                <div class="info">
                    <?php if(!Yii::$app->user->isGuest) :?>
                        <a href="#" class="d-block"><i class="nav-icon far fas fa-user text-light"></i> <?= Yii::$app->user->identity->username ?></a>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->isGuest) :?>
                        <a href="#" class="d-block"><i class="nav-icon far fas fa-user text-light"></i> Visitor</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="sidebar-title" style="color: #c2c7d0"><i class="nav-icon far fas fa-compass text-light"></i> Navigation</li>
                <?php if(Yii::$app->user->isGuest) :?>
                    <li class="nav-item">
                        <a href= <?= $baseUrl ?>site/index class="nav-link">
                            <i class="nav-icon far fas fa-home text-light"></i>
                            <p><?= Yii::t('app', 'Home') ?></p> 
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href= <?= $baseUrl ?>site/contact class="nav-link">
                            <i class="nav-icon far fas fa-phone text-light"></i>
                            <p><?= Yii::t('app', 'Contact') ?></p> 
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href= <?= $baseUrl ?>site/medical-resources class="nav-link">
                            <i class="nav-icon far fas fa-info text-light"></i>
                            <p><?= Yii::t('app', 'Medical Resources') ?></p> 
                        </a>
                    </li>

                    <div class="user-panel pb-3 mb-3 d-flex"></div>
                    
                    <li class="nav-item">
                        <a href= <?= $baseUrl ?>site/login class="nav-link">
                            <i class="nav-icon far fa fa-sign-in-alt text-light"></i>
                            <p><?= Yii::t('app', 'Login') ?></p> <!-- translates the text "Login" using the translation defined in the Yii application. -->
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href= <?= $baseUrl ?>site/signup class="nav-link">
                            <i class="nav-icon far fas fa-user-plus text-light"></i>
                            <p><?= Yii::t('app', 'Sign Up') ?></p> <!-- translates the text "Login" using the translation defined in the Yii application. -->
                        </a>
                    </li>
                    
                    
                <?php endif; ?>

                <?php if(!Yii::$app->user->isGuest) :?>
                    <?php    
                        if (Yii::$app->user->can('admin')) { 
                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-chalkboard text-light"></i> Dashboard', ['/admin/'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-list text-light"></i> User List', ['/admin/user-list'], ['class' => 'nav-link']);
                            echo '</li>';
                        }
                    ?>

                    <?php    
                        if (Yii::$app->user->can('med_staff')) { 
                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-chalkboard text-light"></i> Dashboard', ['/medstaff/'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-list text-light"></i> Patient List', ['/medstaff/patient-list'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-user-lock text-light"></i> Change Password', ['/medstaff/change-password'], ['class' => 'nav-link']);
                            echo '</li>';
                        }
                    ?>

                    <?php    
                        if (Yii::$app->user->can('patient')) { 
                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-chalkboard text-light"></i> Dashboard', ['/patient/'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-child text-light"></i> Patient Profile', ['/patient/patient-profile'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-book-medical text-light"></i> Health Records', ['/patient/patient-health-records'], ['class' => 'nav-link']);
                            echo '</li>';

                            echo '<li class="nav-item">';
                            echo Html::a('<i class="nav-icon far fas fa-user-lock text-light"></i> Change Password', ['/patient/change-password'], ['class' => 'nav-link']);
                            echo '</li>';
                        }
                    ?>

                    <li class="nav-item">
                        <?= Html::a('<i class="nav-icon far fas fa-sign-out-alt text-light"></i> Logout', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
                    </li>
                <?php endif; ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="card">
            <div class="card-body">
                <section class="content">
                    <?=$content?>
                </section>
            </div>
        </div>
        <!-- /.content -->
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>