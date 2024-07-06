<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarangKategori */

$this->title = 'Update Data Barang Kategori: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Data Barang Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-barang-kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
