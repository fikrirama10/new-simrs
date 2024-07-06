<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RadiologiTindakan */

$this->title = 'Buat Radiologi Tindakan';
$this->params['breadcrumbs'][] = ['label' => 'Radiologi Tindakans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="radiologi-tindakan-create col-md-6">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
