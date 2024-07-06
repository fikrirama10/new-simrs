<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelProfesi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personel-profesi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'profesi')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
