<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumPemeriksaan */

$this->title = 'Update Laboratorium Pemeriksaan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Laboratorium Pemeriksaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="laboratorium-pemeriksaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
