<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ObatTransaksiDetail;
$model = ObatTransaksiDetail::findOne($id);
?>
<div class='row'>
	<div class='col-md-8'>
		<div class='box box-body'>
				<?php $form = ActiveForm::begin(); ?>
				<label>Jumlah Obat</label>
				<input type='text' class='form-control disable' readonly  value='<?= $model->qty?>'>
				<hr>
				 <?= $form->field($retur, 'jumlah')->textInput()->label('Jumlah Retur') ?>
				 <?= $form->field($retur, 'iddetail')->hiddenInput(['value'=>$model->id])->label(false) ?>
				 <?= $form->field($retur, 'idobat')->hiddenInput(['value'=>$model->idobat])->label(false) ?>
				 <?= $form->field($retur, 'tgl')->hiddenInput(['value'=>date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))))])->label(false) ?>
				<div class="form-group">
					<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
				</div>

				<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>