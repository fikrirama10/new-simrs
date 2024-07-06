<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\checkbox\CheckboxX;
use common\models\ObatJenis;
use kartik\date\DatePicker;
use common\models\ObatSatuan;
/* @var $this yii\web\View */
/* @var $model common\models\Obat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-md-10">
		<div class="obat-form box box-body">
			<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($dataBarang, 'nama_obat')->textInput(['maxlength' => true]) ?>
			
			<div class='row'>
				<div class='col-md-6'>
				<?=	$form->field($dataBarang, 'kadaluarsa')->widget(DatePicker::classname(),[
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd',
					'required'=>true

					]
				])->label('Tgl ED')?>
				</div>
				<div class='col-md-6'>
				<?= $form->field($dataBarang, 'harga_beli')->textInput(['maxlength' => true]) ?>
				</div>
			</div>
				<div class='row'>
				<div class='col-md-6'>
					<?= $form->field($dataBarang, 'idjenis')->dropDownList(ArrayHelper::map(ObatJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Obat -','required'=>true])->label('Jenis')?>
				</div>
				<div class='col-md-6'>
					<?= $form->field($dataBarang, 'idsatuan')->dropDownList(ArrayHelper::map(ObatSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Jenis Satuan -','required'=>true])->label('Satuan')?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					 <?= $form->field($dataBarang, 'narkotika')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($dataBarang, 'psikotropika')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($dataBarang, 'antibiotik')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($dataBarang, 'fornas')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
			</div>
		   
			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

			<?php ActiveForm::end(); ?>

		</div>
	</div>
</div>

