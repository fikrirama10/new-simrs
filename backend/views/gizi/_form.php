<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gizi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gizi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idrawat')->textInput() ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl')->textInput() ?>

    <?= $form->field($model, 'diit')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'iddokter')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
