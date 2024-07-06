<?php 
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\helpers\Url;
	use yii\web\View;
	use common\models\Peserta;
	use kartik\date\DatePicker;
	$tgl = date('Y-m-d');
	$hari = date('N',strtotime($tgl));
?>
<div style='background:<?= $kuota['alert'] ?>; color:#fff;' class="alert alert-primary" role="alert">
	<?= $kuota['response'] ?> , <br><b>( Muat ulang halaman untuk mengulang proses )</b>
</div>
<?php if($kuota['kode'] == 200){ ?>
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'tglvaksin')->hiddenInput(['maxlength' => true,'class'=>'form-control input-sm','value'=>$kuota['tgl']])->label(false)?>
	
	<?= $form->field($model, 'nik')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true]) ?>
	<?= $form->field($model, 'nama')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true]) ?>
	<?= $form->field($model, 'nohp')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])->label('Nomer Telpon yng bisa menerima SMS') ?>
	<?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control input-sm'])->label('Email') ?>
	<?= $form->field($model, 'usia')->hiddenInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])->label(false) ?>
	<label> Tanggal Lahir (Bulan / Tanggal / Tahun)</label>
	<input id='tgllahir' name='Vaksin[tgllahir]' required type='date' class='form-control'>
	<br>
	
	<?= $form->field($model, 'alamat')->textarea(['rows'=>'4','maxlength' => true,'class'=>'form-control input-sm','required'=>true]) ?>
	<?= $form->field($model, 'vaksin')->dropDownList(['1'=>'1','2' => '2'], ['required'=>true,'prompt' => 'Vaksin Ke'])->label('Vaksin Ke') ?>
	<?= $form->field($model, 'idpeserta')->dropDownList(ArrayHelper::map(Peserta::find()->all(), 'id', 'peserta') ,['required'=>true,'prompt' => 'Jenis Peserta'])->label('Jenis Peserta')?>
	<div class="form-grup">
		<?= Html::submitButton($model->isNewRecord ? 'Daftar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary btn-sm' : 'btn btn-primary btn-lg']) ?>
	</div>
	<hr>
<?php } ?>
<?php 
$this->registerJs("
$('#tgllahir').on('change',function() {
	var dob = new Date(this.value);
	var today = new Date();
	var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
	$('#vaksin-usia').val(age);
});      
", View::POS_READY);
?>
