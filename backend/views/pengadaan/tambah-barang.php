<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\DataBarangKategori;
use common\models\DataSatuan;
use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\DataBarang */
/* @var $form yii\widgets\ActiveForm */
?>
<div class='row'>
	<div class='col-md-6'>
		<div class='box'>
			<div class='box-body'>
				<?php $form = ActiveForm::begin(); ?>

					<?= $form->field($barang, 'nama_barang')->textInput(['maxlength' => true,'value'=>$model->nama_barang]) ?>
				   <?= $form->field($barang, 'idkategori')->dropDownList(ArrayHelper::map(DataBarangKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- kategori barang -'])->label('Kategori')?>
				   <?= $form->field($barang, 'idsatuan')->dropDownList(ArrayHelper::map(DataSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Satuan barang -'])->label('Satuan')?>
					<?= $form->field($barang, 'harga')->textInput(['value'=>$model->harga]) ?>
					<?= $form->field($barang, 'stok')->textInput() ?>
					<?= $form->field($barang, 'status')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>

					<div class="form-group">
						<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
					</div>

					<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>