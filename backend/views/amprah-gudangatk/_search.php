<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangatkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="amprah-gudangatk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idamprah') ?>

    <?= $form->field($model, 'idpermintaan') ?>

    <?= $form->field($model, 'tgl_permintaan') ?>

    <?= $form->field($model, 'tgl_penyerahan') ?>

    <?php // echo $form->field($model, 'idasal') ?>

    <?php // echo $form->field($model, 'idpeminta') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
