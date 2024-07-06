<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\DataPulang;
use common\models\Poli;
use kartik\date\DatePicker;
use kartik\widgets\DateTimePicker;
?>

<?php $form = ActiveForm::begin(); ?>
	<div class='row'>
		<div class='col-md-6'>
			<?=	$form->field($pulang, 'tgl_pulang')->widget(DatePicker::classname(),[
				'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'startDate' => date('Y-m-d',strtotime($model->tglmasuk)),
			]])?>
		</div>
		<div class='col-md-6'>
			<label>Jam Pulang</label>
			<input type='time' name='RawatRingkasanpulang[jam_pulang]' class='form-control'>
		</div>
	</div>
	
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'diagnosa_primer')->textarea(['maxlength' => true,])?>
		</div>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'diagnosa_sekunder')->textarea(['maxlength' => true,])?>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'tindakan_primer')->textarea(['maxlength' => true,])?>
		</div>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'tindakan_sekunder')->textarea(['maxlength' => true,])?>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'pemeriksaan_fisik')->textarea(['maxlength' => true,])?>
		</div>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'penunjang')->textarea(['maxlength' => true,])?>
		</div>
	</div>
	
	<?= $form->field($pulang, 'prognosa')->textarea(['maxlength' => true,])?>
	<?= $form->field($pulang, 'anjuran')->textarea(['maxlength' => true,])?>
	<?= $form->field($pulang, 'terapi')->textarea(['maxlength' => true,])?>
	<?= $form->field($pulang, 'kondisi_waktupulang')->dropDownList(ArrayHelper::map(DataPulang::find()->all(), 'id', 'pulang'),['prompt'=>'- Kondisi Pulang -','required'=>true])?>
	<hr>
	<div class='row'>
		<div class='col-md-6'>
			<?=	$form->field($pulang, 'tgl_kontrol')->widget(DatePicker::classname(),[
				'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'startDate' => date('Y-m-d',strtotime($model->tglmasuk)),
			]])?>
		</div>
		<div class='col-md-6'>
			<?= $form->field($pulang, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->all(), 'id', 'poli'),['prompt'=>'- Nama Poli -'])->label('Nama Poli')?>
		</div>
	</div>
	<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>

<?php ActiveForm::end(); ?>