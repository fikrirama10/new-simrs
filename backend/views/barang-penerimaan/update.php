<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BarangPenerimaan */

$this->title = 'Update Barang Penerimaan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Penerimaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="barang-penerimaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
