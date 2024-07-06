<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumLayanan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="laboratorium-layanan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_layanan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'urutan')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
