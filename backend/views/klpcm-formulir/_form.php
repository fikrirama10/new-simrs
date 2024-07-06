<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KlpcmFormulir */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="klpcm-formulir-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'formulir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
