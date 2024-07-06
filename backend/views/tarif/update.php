<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tarif */

$this->title = 'Update Tarif: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tarifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_fornEdit', [
        'model' => $model,
    ]) ?>

</div>
