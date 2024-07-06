<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransaksiBayar */

$this->title = 'Create Transaksi Bayar';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-bayar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
