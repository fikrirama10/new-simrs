<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatDropingTransaksi */

$this->title = 'Obat Droping Transaksi';
$this->params['breadcrumbs'][] = ['label' => 'Obat Droping Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-droping-transaksi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
