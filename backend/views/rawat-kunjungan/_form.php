<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatKunjungan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-kunjungan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'idkunjungan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_kunjungan')->textInput() ?>

    <?= $form->field($model, 'jam_kunjungan')->textInput() ?>

    <?= $form->field($model, 'iduser')->textInput() ?>

    <?= $form->field($model, 'usia_kunjungan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
