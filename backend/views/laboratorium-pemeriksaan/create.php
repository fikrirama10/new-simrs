<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumPemeriksaan */

$this->title = 'Create Laboratorium Pemeriksaan';
$this->params['breadcrumbs'][] = ['label' => 'Laboratorium Pemeriksaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorium-pemeriksaan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
