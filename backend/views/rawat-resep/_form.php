<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatResep */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-resep-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode_resep')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idrawat')->textInput() ?>

    <?= $form->field($model, 'no_rm')->textInput() ?>

    <?= $form->field($model, 'iddokter')->textInput() ?>

    <?= $form->field($model, 'tgl_resep')->textInput() ?>

    <?= $form->field($model, 'jam_resep')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'idjenisrawat')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
