<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsBridging */
/* @var $form yii\widgets\ActiveForm */
?>

<div class=" box box-body setting-simrs-bridging-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cons_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'secret_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'DEV' => 'DEV', 'PRO' => 'PRO', ], ['prompt' => '']) ?>

     <?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
