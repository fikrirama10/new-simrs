<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */

$this->title = 'Update Amprah Gudangobat: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Amprah Gudangobats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="amprah-gudangobat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
