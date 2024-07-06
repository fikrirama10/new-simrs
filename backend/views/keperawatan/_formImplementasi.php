<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterSpesialis;
use kartik\checkbox\CheckboxX;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
?>

<div class="dokter-form box box-body">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($implementasi, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($implementasi, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
	<?= $form->field($implementasi, 'idpetugas')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
	<?= $form->field($implementasi, 'jam')->widget(TimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
			'minuteStep' => 1,
			'secondStep' => 5,
			'required'=>true
		]
	]); ?>
	<?= $form->field($implementasi, 'implementasi')->textArea(['rows' => 5])->label('Implementasi Kemerawatan') ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
