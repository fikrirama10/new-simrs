<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PenerimaanBarangSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penerimaan-barang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_penerimaan') ?>

    <?= $form->field($model, 'no_faktur') ?>

    <?= $form->field($model, 'no_up') ?>

    <?= $form->field($model, 'nilai_faktur') ?>

    <?php // echo $form->field($model, 'nilai_bayar') ?>

    <?php // echo $form->field($model, 'nilai_sisa') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'tgl_faktur') ?>

    <?php // echo $form->field($model, 'idsuplier') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
