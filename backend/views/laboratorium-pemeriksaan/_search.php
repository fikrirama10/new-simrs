<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumPemeriksaanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="laboratorium-pemeriksaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idlab') ?>

    <?= $form->field($model, 'nama_pemeriksaan') ?>

    <?= $form->field($model, 'idjenis') ?>

    <?= $form->field($model, 'urutan') ?>

    <?php // echo $form->field($model, 'kode_lab') ?>

    <?php // echo $form->field($model, 'idtindakan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
