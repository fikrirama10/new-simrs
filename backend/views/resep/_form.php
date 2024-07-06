<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idtrx')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idjenis')->textInput() ?>

    <?= $form->field($model, 'tgl')->textInput() ?>

    <?= $form->field($model, 'idjenisrawat')->textInput() ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_harga')->textInput() ?>

    <?= $form->field($model, 'total_bayar')->textInput() ?>

    <?= $form->field($model, 'total_sisa')->textInput() ?>

    <?= $form->field($model, 'idrawat')->textInput() ?>

    <?= $form->field($model, 'jam')->textInput() ?>

    <?= $form->field($model, 'iduser')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
