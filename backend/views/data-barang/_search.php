<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarangSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-barang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_barang') ?>

    <?= $form->field($model, 'kode_barang') ?>

    <?= $form->field($model, 'idkategori') ?>

    <?= $form->field($model, 'idsatuan') ?>

    <?php // echo $form->field($model, 'harga') ?>

    <?php // echo $form->field($model, 'stok') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
