<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DokterSpesialis */

$this->title = 'Update Dokter Spesialis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dokter Spesialis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dokter-spesialis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
