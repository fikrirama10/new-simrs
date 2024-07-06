<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Rawat */

$this->title = 'Update Rawat: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rawats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rawat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
