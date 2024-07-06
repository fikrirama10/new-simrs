<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DaftarUmum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="daftar-umum-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_lahir')->textInput() ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_telpon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idpoli')->textInput() ?>

    <?= $form->field($model, 'iddokter')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
