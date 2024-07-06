<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RawatJenis */

$this->title = 'Create Rawat Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Rawat Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-jenis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
