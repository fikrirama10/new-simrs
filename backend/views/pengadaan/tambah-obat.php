<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\checkbox\CheckboxX;
use common\models\ObatJenis;
use common\models\ObatSatuan;
/* @var $this yii\web\View */
/* @var $obat common\models\Obat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-md-6">
		<div class="obat-form box box-body">
			<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($obat, 'nama_obat')->textInput(['maxlength' => true,'value'=>$model->nama_obat]) ?>
			<?= $form->field($obat, 'kandungan')->textarea(['rows' => 6]) ?>
			<?= $form->field($obat, 'khasiat')->textarea(['rows' => 6]) ?>
			<div class='row'>
				<div class='col-md-6'>
					<?= $form->field($obat, 'min_stokgudang')->textInput() ?>
				</div>
				<div class='col-md-6'>
					<?= $form->field($obat, 'min_stokapotek')->textInput() ?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-6'>
					<?= $form->field($obat, 'idjenis')->dropDownList(ArrayHelper::map(ObatJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Obat -','required'=>true])->label('Jenis')?>
				</div>
				<div class='col-md-6'>
					<?= $form->field($obat, 'idsatuan')->dropDownList(ArrayHelper::map(ObatSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Jenis Satuan -','required'=>true])->label('Satuan')?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					 <?= $form->field($obat, 'narkotika')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($obat, 'psikotropika')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($obat, 'antibiotik')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
				</div>
				<div class='col-md-3'>
					 <?= $form->field($obat, 'fornas')->widget(CheckboxX::classname(), [
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

