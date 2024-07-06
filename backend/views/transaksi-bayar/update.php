<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransaksiBayar */

$this->title = 'Update Transaksi Bayar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaksi-bayar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
