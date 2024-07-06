<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OperasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_ok') ?>

    <?= $form->field($model, 'idasal') ?>

    <?= $form->field($model, 'tgl_ok') ?>

    <?= $form->field($model, 'idjenis') ?>

    <?php // echo $form->field($model, 'iddokter') ?>

    <?php // echo $form->field($model, 'idanastesi') ?>

    <?php // echo $form->field($model, 'laporan_pembedahan') ?>

    <?php // echo $form->field($model, 'diagnosisprabedah') ?>

    <?php // echo $form->field($model, 'icd10') ?>

    <?php // echo $form->field($model, 'icd9') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
