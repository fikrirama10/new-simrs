<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatKunjunganSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-kunjungan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idkunjungan') ?>

    <?= $form->field($model, 'no_rm') ?>

    <?= $form->field($model, 'tgl_kunjungan') ?>

    <?= $form->field($model, 'jam_kunjungan') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <?php // echo $form->field($model, 'usia_kunjungan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
