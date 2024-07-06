<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BarangStokopname */

$this->title = 'Update Barang Stokopname: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Stokopnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="barang-stokopname-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
