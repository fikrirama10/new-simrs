<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\RadiologiHasilfoto;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
$hasilFoto = RadiologiHasilfoto::find()->where(['idhasil'=>$model->id])->all();
?>
<div class="row">
	<div class="col-md-6">
		<div class="box">
			<div class="box-header with-border"></div>
			<div class="box-body">
				<table class='table table-bodered'>
					<tr>
						<th>No Foto</th>
						<th>Foto</th>
						<th>#</th>
					</tr>
					<?php if(count($hasilFoto) < 1){ ?>
					<tr>
						<td colspan='2'>Belum Ada Foto ter upload</td>
					</tr>
					<?php }else{ ?>
					<?php foreach($hasilFoto as $hf): ?>
					<tr>
						<td><?= $hf->nofoto ?></td>
						<td><?= $hf->foto ?></td>
						<td><a href='<?= Url::to(['/radiologi-order/delete-foto?id='.$hf->id])?>' class='btn btn-danger btn-xs'>Hapus</a></td>
					</tr>
					<?php endforeach; ?>
					<?php } ?>
					<tr>
						<td colspan=3><a href='<?= Url::to(['/radiologi-order/view?id='.$model->idrawat])?>' class='btn btn-info btn-sm'>Selesai</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box">
			<div class="box-header with-border"></div>
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($foto, 'nofoto')->textInput(['maxlength' => true]) ?>
				<?= $form->field($foto, 'foto')->widget(FileInput::classname(), [
					'options' => ['accept' => 'Image/*'],
					'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png']]]);
				?>	
				<div class="form-group">
					<?= Html::submitButton('+', ['class' => 'btn btn-success']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>