<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarang */

$this->title = 'Create Data Barang';
$this->params['breadcrumbs'][] = ['label' => 'Data Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-barang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
