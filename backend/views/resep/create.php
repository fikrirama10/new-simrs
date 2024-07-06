<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */

$this->title = 'Create Obat Transaksi';
$this->params['breadcrumbs'][] = ['label' => 'Obat Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-transaksi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
