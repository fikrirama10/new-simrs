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
						<input type='text' name='ObatDropingBatch[no_batch]' class='form-control' value='<?= $model->no_batch?>'>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Merk</label>
					<div class="col-sm-7">
						<input type='text' name='ObatDropingBatch[merk]' class='form-control' value='<?= $model->merk?>'>
					</div>
				</div>
			
	
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Stok Gudang</label>
					<div class="col-sm-2">
						<input type='text' readonly name='ObatDropingBatch[stok]' class='form-control' value='<?= $model->stok?>'>
					</div>
					
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Stok Koreksi</label>
					<div class="col-sm-2">
						<input type='text' name='koreksi' class='form-control' >
					</div>
					
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