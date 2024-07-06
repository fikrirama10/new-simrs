<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatSatuan */

$this->title = 'Create Obat Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Obat Satuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-satuan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
