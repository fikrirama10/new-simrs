<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarang */

$this->title = 'Update Data Barang: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Data Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-barang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
