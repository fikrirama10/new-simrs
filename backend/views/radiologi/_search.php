<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RadiologiTindakanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="radiologi-tindakan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idpelayanan') ?>

    <?= $form->field($model, 'idrad') ?>

    <?= $form->field($model, 'kode_tindakan') ?>

    <?= $form->field($model, 'nama_tindakan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'idtindakan') ?>

    <?php // echo $form->field($model, 'idbayar') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
