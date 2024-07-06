<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatFarmasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-farmasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_resep') ?>

    <?= $form->field($model, 'tgl') ?>

    <?= $form->field($model, 'idjenis') ?>

    <?= $form->field($model, 'total_harga') ?>

    <?php // echo $form->field($model, 'tuslah') ?>

    <?php // echo $form->field($model, 'obat_racik') ?>

    <?php // echo $form->field($model, 'jasa_racik') ?>

    <?php // echo $form->field($model, 'keuntungan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
