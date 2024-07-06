<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-transaksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idtrx') ?>

    <?= $form->field($model, 'idjenis') ?>

    <?= $form->field($model, 'tgl') ?>

    <?= $form->field($model, 'idjenisrawat') ?>

    <?php // echo $form->field($model, 'no_rm') ?>

    <?php // echo $form->field($model, 'total_harga') ?>

    <?php // echo $form->field($model, 'total_bayar') ?>

    <?php // echo $form->field($model, 'total_sisa') ?>

    <?php // echo $form->field($model, 'idrawat') ?>

    <?php // echo $form->field($model, 'jam') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
