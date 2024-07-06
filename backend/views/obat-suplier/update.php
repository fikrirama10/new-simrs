<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatSuplier */

$this->title = 'Update Obat Suplier: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Supliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="obat-suplier-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
