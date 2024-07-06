<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use common\models\ObatKategori;
use common\models\RawatBayar;
use common\models\ObatSuplier;
use yii\helpers\Url;
use yii\web\View;
use kartik\checkbox\CheckboxX;
use yii\widgets\ActiveForm;

?>
<div class='row'>
	<div class='col-md-6'>
		<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
		<div class='box'>
		<div class='box-header with-border'><h4>Edit Batch Obat</h4></div>
		<div class='box-body'>

				<div class="form-group">
					<label class="col-sm-4 control-label">No Batch</label>
					<div class="col-sm-7">
						<input type='text' name='ObatBacth[no_bacth]' class='form-control' value='<?= $model->no_bacth?>'>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Merk</label>
					<div class="col-sm-7">
						<input type='text' name='ObatBacth[merk]' class='form-control' value='<?= $model->merk?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Harga Beli</label>
					<div class="col-sm-7">
						<input type='text' name='ObatBacth[harga_beli]' class='form-control' value='<?= $model->harga_beli?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Harga Jual</label>
					<div class="col-sm-7">
						<input type='text' name='ObatBacth[harga_jual]' class='form-control' value='<?= $model->harga_jual?>'>
					</div>
				</div>
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Stok Gudang</label>
					<div class="col-sm-2">
						<input type='text' readonly name='ObatBacth[stok_gudang]' class='form-control' value='<?= $model->stok_gudang?>'>
					</div>
					<label class="col-sm-2 control-label">Stok Apotek</label>
					<div class="col-sm-2">
						<input type='text' readonly name='ObatBacth[stok_apotek]' class='form-control' value='<?= $model->stok_apotek?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Stok Koreksi</label>
					<div class="col-sm-2">
						<input type='text' name='koreksi' class='form-control' >
					</div>
					
				</div>
				<div class="form-group">
					
					<label class="col-sm-4 control-label">Kategori</label>
					<div style='margin-left:15px;' class="col-sm-2">
						<?= $form->field($model, 'kat_obat')->dropDownList(ArrayHelper::map(ObatKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Kategori Obat -','required'=>true])->label(false)?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Suplier</label>
					<div style='margin-left:15px;' class="col-sm-4">
						<?= $form->field($model, 'idsuplier')->dropDownList(ArrayHelper::map(ObatSuplier::find()->all(), 'id', 'suplier'),['prompt'=>'- Suplier -','required'=>true])->label(false)?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div style='margin-left:15px;' class="col-sm-4">
						 <?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
					</div>
				</div>
			
		</div>
		<div class="box-footer">
			<button type="submit" id='confirm' class="btn btn-info pull-right ">Simpan</button>
		</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>