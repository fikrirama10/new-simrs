<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */

$this->title = 'Create Barang Amprah';
$this->params['breadcrumbs'][] = ['label' => 'Barang Amprahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-amprah-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
