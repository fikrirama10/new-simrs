<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatDropingTransaksi */

$this->title = 'Update Obat Droping Transaksi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Droping Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obat-droping-transaksi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
