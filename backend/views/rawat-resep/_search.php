<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatResepSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-resep-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_resep') ?>

    <?= $form->field($model, 'idrawat') ?>

    <?= $form->field($model, 'no_rm') ?>

    <?= $form->field($model, 'iddokter') ?>

    <?php // echo $form->field($model, 'tgl_resep') ?>

    <?php // echo $form->field($model, 'jam_resep') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'idjenisrawat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
