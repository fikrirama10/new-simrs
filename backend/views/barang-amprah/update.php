<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */

$this->title = 'Update Barang Amprah: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Amprahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="barang-amprah-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
