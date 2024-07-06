<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DataSatuan */

$this->title = 'Create Data Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Data Satuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-satuan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
