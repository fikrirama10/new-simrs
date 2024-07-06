<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganKelas */

$this->title = 'Update Ruangan Kelas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ruangan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ruangan-kelas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
