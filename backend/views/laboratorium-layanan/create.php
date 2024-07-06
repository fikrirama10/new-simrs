<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumLayanan */

$this->title = 'Create Laboratorium Layanan';
$this->params['breadcrumbs'][] = ['label' => 'Laboratorium Layanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorium-layanan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
