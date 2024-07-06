<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RawatResep */

$this->title = 'Create Rawat Resep';
$this->params['breadcrumbs'][] = ['label' => 'Rawat Reseps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-resep-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
