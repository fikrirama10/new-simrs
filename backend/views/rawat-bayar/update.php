<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RawatBayar */

$this->title = 'Update Rawat Bayar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rawat Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rawat-bayar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
