<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelJenis */

$this->title = 'Update Personel Jenis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Personel Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="personel-jenis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
