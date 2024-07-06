<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Personel */

$this->title = 'Update Personel: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Personels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="personel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
