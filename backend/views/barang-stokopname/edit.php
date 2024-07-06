<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\checkbox\CheckboxX;

?>
<div class='row'>
	<div class='col-md-6'>
		<div class='box'>
			<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
			<div class='box-header'><h4>Edit Data Stok Opname</h4></div>
			<div class='box-body'>
					<div class="form-group">
						<label class="col-sm-4 control-label">Nama Barang</label>
						<div class="col-sm-6">
							<input type='text'  readonly value='<?= $model->barang->nama_barang?>' class='form-control'>
							<input type='hidden' name="BarangStokopnameDetail[idbarang]" readonly value='<?= $model->idbarang?>' class='form-control'>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label">Qty</label>
						<div class="col-sm-6">
							<input type='text' name="BarangStokopnameDetail[stokreal]" value='<?= $model->stokreal?>' id='no_rm' class='form-control'>
						</div>
					</div>
			</div>
			<div class='box-footer'>
				<button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>