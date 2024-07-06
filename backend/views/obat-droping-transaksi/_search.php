<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatDropingTransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-droping-transaksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idtrx') ?>

    <?= $form->field($model, 'idjenis') ?>

    <?= $form->field($model, 'ketrangan') ?>

    <?= $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
