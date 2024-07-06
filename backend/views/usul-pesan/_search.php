<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UsulPesanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usul-pesan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_up') ?>

    <?= $form->field($model, 'tgl_up') ?>

    <?= $form->field($model, 'tgl_setuju') ?>

    <?= $form->field($model, 'total_harga') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'jenis_up') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
