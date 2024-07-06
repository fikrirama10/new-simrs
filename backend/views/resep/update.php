<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */

$this->title = 'Update Obat Transaksi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obat-transaksi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
