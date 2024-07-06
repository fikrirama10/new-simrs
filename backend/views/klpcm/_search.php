<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KlpcmSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="klpcm-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idrawat') ?>

    <?= $form->field($model, 'tgl_kunjungan') ?>

    <?= $form->field($model, 'iddokter') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ketrangan') ?>

    <?php // echo $form->field($model, 'keterbacaan') ?>

    <?php // echo $form->field($model, 'kelengkapan') ?>

    <?php // echo $form->field($model, 'idjenisrawat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
