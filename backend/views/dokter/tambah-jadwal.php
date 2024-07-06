<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use kartik\time\TimePicker;
use kartik\checkbox\CheckboxX;
?>

<div class="dokter-form">
	<div class='row'>
	<div class='col-md-3'>
		<div class='box'>
			<div class='box-header with-border'><h4>Detail Dokter</h4></div>
			<div class='box-body'>
			
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'nama_dokter',
						'kode_dokter',
						//'sip',
						'jenis_kelamin',
						'poli.poli',
						'tgl_lahir',
					],
				]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-9'>
		<div class='box'>
			<?php $form = ActiveForm::begin(); ?>
			<div class='box-header with-border'><h4>Jadwal Dokter</h4></div>
			<div class='box-body'>
				
				<?= $form->field($jadwal, 'idhari')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$hari->id])->label(false); ?>
				<?= $form->field($jadwal, 'iddokter')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->id])->label(false); ?>
				<?= $form->field($jadwal, 'idpoli')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->idpoli])->label(false); ?>
				<label>Hari</label>
				<input type='text' readonly value='<?= $hari->hari?>' class='form-control'>
				<br>
				<?= $form->field($jadwal, 'jam_mulai')->widget(TimePicker::classname(), [
					'pluginOptions' => [
						'showSeconds' => true,
						'showMeridian' => false,
						'minuteStep' => 1,
						'secondStep' => 5,
						'required'=>true
					]
				]); ?>
				<?= $form->field($jadwal, 'jam_selesai')->widget(TimePicker::classname(), [
					'pluginOptions' => [
						'showSeconds' => true,
						'showMeridian' => false,
						'minuteStep' => 1,
						'secondStep' => 5,
						'required'=>true
					]
				]); ?>
				
				<?= $form->field($jadwal, 'kuota')->textinput(['maxlength' => true,'required'=>true]); ?>
				<?= $form->field($jadwal, 'status')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
			</div>
			<div class='box-footer'>
				<div class="form-group">
					<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
    

</div>
