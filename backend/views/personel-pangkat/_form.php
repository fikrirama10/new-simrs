<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelPangkat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personel-pangkat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pangkat')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
