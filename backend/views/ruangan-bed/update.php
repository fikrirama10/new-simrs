<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganBed */

$this->title = 'Update Ruangan Bed: ' . $model->ruangan->nama_ruangan;
$this->params['breadcrumbs'][] = ['label' => 'Ruangan Beds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ruangan-bed-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
