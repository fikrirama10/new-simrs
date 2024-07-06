<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangatk */

$this->title = 'Update Amprah Gudangatk: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Amprah Gudangatks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="amprah-gudangatk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
