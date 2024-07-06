<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatStokOpname */

$this->title = 'Update Obat Stok Opname: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Stok Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obat-stok-opname-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
