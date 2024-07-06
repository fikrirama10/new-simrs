<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatBayar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-bayar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bayar')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
