<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganKelas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ruangan-kelas-form box box-body">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'kelas')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
