<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
sleep(3);
?>

<?php if($data_json['metadata']['code'] == 401){ ?>
	<div class="alert alert-danger" role="alert">
		<?= $data_json['metadata']['message'] ?>
	</div>
<?php }else{ ?>
	<?php if($countdatadaftar > 0){ ?>
		<hr>
		<div class="alert alert-danger" role="alert">
			Anda sudah terdaftar di tanggal tersebut dengan NOMER REGISTRASI <b><a href='<?= Url::to([''])?>'><?= $datadaftar->noregistrasi ?></a></b><br> Silahkan konfirmsai ke bagian pendaftaran jika ingin merubah jadwal kunjungan
		</div>
		<hr>
	<?php }else{ ?>
	<div class="alert alert-info" role="alert">
		<?= $data_json['metadata']['message'] ?>
	</div>
	<div class='row'>
		<div class='col-md-12'>
			<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($daftar, 'jenisrujukan')->hiddenInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true,'value'=>$jenis])->label(false) ?>
				<?= $form->field($daftar, 'notlp')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])?>
				<?= $form->field($daftar, 'email')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])?>
				<?= $form->field($daftar, 'iddokter')->hiddenInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true,'value'=> $data_json2['response']['id']])->label(false) ?>
				<?= $form->field($daftar, 'idpoli')->hiddenInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true,'value'=> $data_json2['response']['idpoli']])->label(false) ?>
				<?= $form->field($daftar, 'tglberobat')->hiddenInput(['maxlength' => true,'value'=>$tgl,'class'=>'form-control input-sm'])->label(false)?>
				<?= $form->field($daftar, 'norujukan')->hiddenInput(['maxlength' => true,'value'=>$rujukan,'class'=>'form-control input-sm'])->label(false)?>
				<div class="form-grup">
					<?= Html::submitButton($daftar->isNewRecord ? 'Daftar' : 'Update', ['class' => $daftar->isNewRecord ? 'btn btn-primary btn-sm' : 'btn btn-primary btn-lg']) ?>
				</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>