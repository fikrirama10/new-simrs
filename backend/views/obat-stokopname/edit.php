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
						<label class="col-sm-4 control-label">Nama Obat</label>
						<div class="col-sm-6">
							<input type='text' name="ObatStokopnameDetail[idobat]" readonly value='<?= $model->obat->nama_obat?>' id='no_rm' class='form-control'>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">No Bacth</label>
						<div class="col-sm-6">
							<input type='text' name="RawatSpri[no_rm]" readonly value='<?= $model->bacth->no_bacth?>' id='no_rm' class='form-control'>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Qty</label>
						<div class="col-sm-6">
							<input type='text' name="ObatStokopnameDetail[jumlah]" value='<?= $model->jumlah?>' id='no_rm' class='form-control'>
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