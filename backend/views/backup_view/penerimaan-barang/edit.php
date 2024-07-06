	<?php
	use kartik\grid\GridView;
	use kartik\date\DatePicker;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;
	use yii\widgets\DetailView;
	use yii\helpers\Url;
	use kartik\money\MaskMoney;
	use yii\web\View;
	$ppn = ( $model->ppn / $model->total_diskon )*100;
	?>
	<div class="row">
		<div class="col-md-6">
		<?php $form = ActiveForm::begin(); ?>
			<div class="box">
				<div class="box-header">Edit Data</div>
				<div class="box-body">
					<table class='table'>
						<tr>
							<td>Nama Obat (Merk)</td>
							<td>Jumlah</td>
							<td>Harga</td>
							<td>Total</td>
						</tr>
						<tr>
							<td><?= $model->obat->nama_obat ?> (<?= $model->merk->merk ?>)</td>
							<td><?= $model->jumlah ?></td>
							<td><?= $model->harga ?></td>
							<td><?= $model->total ?></td>
						</tr>
					</table>
					<hr>
					<?= $form->field($model, 'jumlah')->textInput(['required'=>true]) ?>
					<?=  $form->field($model, 'harga')->widget(MaskMoney::classname(), [
						'pluginOptions' => [
							'prefix' => 'Rp ',
							'allowNegative' => false,
							'allowEmpty' => false,
						]
					]); ?>
					<?= $form->field($model, 'diskon')->textInput(['required'=>true])->label('Diskon %') ?>
					<?= $form->field($model, 'ppn')->textInput(['required'=>true,'value'=>$ppn])->label('PPN %') ?>
					<?= Html::submitButton('Update', ['class' => 'btn btn-success','id'=>'confirm3']) ?>
				</div>
			</div>
		<?php ActiveForm::end(); ?>
		</div>
	</div>