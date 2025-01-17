<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
?>
<div class='row'>
	<div class='col-md-6'>
		<div class='box'>
			<div class='box-header'><h4>Edit Lab</h4></div>
			<div class='box-body'>
				<table class='table'>
					<tr>
						<td>Pasien </td>
						<th>: <?=$rawat->pasien->nama_pasien?> (<?= $rawat->no_rm?>)</th>
						<td>Lab ID</td>
						<th>: <?=$model->labid?></th>
					</tr>
					<tr>
						<td>Tgl Hasil </td>
						<th>: <?=$model->tgl_hasil?> </th>
					</tr>
				</table>
				<hr>
				<?php $form = ActiveForm::begin(); ?>
				<label>Edit Tgl Hasil</label>
				<input type='datetime-local' class='form-control' name='LaboratoriumHasil[tgl_hasil]' placeholder='<?=$model->tgl_hasil?>'>
				<div class="modal-footer">
					<?= Html::submitButton('Edit', ['class' => 'btn btn-success','id'=>'confirm']) ?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		
		</div>
	</div>
</div>