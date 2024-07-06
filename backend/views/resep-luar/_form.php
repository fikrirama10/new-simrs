<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\checkbox\CheckboxX;
use common\models\ObatFarmasiJenis;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\ObatFarmasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-farmasi-form">

    <?php $form = ActiveForm::begin(); ?>

   

    <?=	$form->field($model, 'tgl')->widget(DatePicker::classname(),[
	'type' => DatePicker::TYPE_COMPONENT_APPEND,
	'pluginOptions' => [
	'autoclose'=>true,
	'format' => 'yyyy-mm-dd',
	'required'=>true

	]
	])->label('Tgl Resep')?>
	<div class='row'>
		<div class='col-md-3'><?= $form->field($model, 'nrp')->textInput() ?></div>
		<div class='col-md-6'><?= $form->field($model, 'nama_pasien')->textInput() ?></div>
		<div class='col-md-3'><?= $form->field($model, 'usia')->textInput() ?></div>

	</div>
   
    
    <?= $form->field($model, 'alamat')->textArea() ?>
	<?= $form->field($model, 'no_tlp')->textInput() ?>
    <?= $form->field($model, 'idjenis')->dropDownList(ArrayHelper::map(ObatFarmasiJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Resep -','required'=>true])->label('Jenis')?>
	<?= $form->field($model, 'keterangan')->textArea() ?>
    <?= $form->field($model, 'obat_racik')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
