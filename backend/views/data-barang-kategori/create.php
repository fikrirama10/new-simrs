<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarangKategori */

$this->title = 'Create Data Barang Kategori';
$this->params['breadcrumbs'][] = ['label' => 'Data Barang Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-barang-kategori-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
