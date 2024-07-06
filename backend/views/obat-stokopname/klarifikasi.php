<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>
<div class='box'>
	<div class='box-header'><h4>Klarifikasi Stok Opname</h4></div>
	<div class='box-body'>
		<table class='table table-bordered'>
			<tr>
				<th>Obat / Alkes</th>
				<th>No Bacth</th>
				<th>Stok Asal</th>
				<th>Stok Opname</th>
				<th>Selisih</th>
				<th>Keterangan</th>
			</tr>
			<tr>
				<td><?= $model->obat->nama_obat ?></td>
				<td><?= $model->bacth->no_bacth ?></td>
				<td><?= $model->stok_asal ?></td>
				<td><?= $model->jumlah ?></td>
				<td><?= $model->selisih ?></td>
				<td><?= $model->keterangan ?></td>
			</tr>
		</table>
		<hr>
		  <?php $form = ActiveForm::begin(); ?>
		   <?= $form->field($model, 'klarifikasi')->textarea(['rows' => 6]) ?>

			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>
		  <?php ActiveForm::end(); ?>
	</div>
</div>
