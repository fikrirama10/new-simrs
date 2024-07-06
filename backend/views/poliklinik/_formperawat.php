<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\SoapTriase;
use common\models\SoapKeadaan;
use common\models\SoapKesadaran;
use common\models\KegiatanUgd;
?>

	
	<div class='row'>
		<div class='col-md-8'>
		<?php if(!$perawatsoapcount){ ?>
		<?php $form = ActiveForm::begin();  ?>
			<?php if($model->idjenisrawat == 3){ ?>
			<?= $form->field($perawatsoap, 'triase')->radioList(ArrayHelper::map(SoapTriase::find()->all(), 'id','triase'),['required'=>true])->label('Triase') ?>
			<h6>Keadaan Umum</h6>
			<?= $form->field($perawatsoap, 'keadaan_umum')->radioList(ArrayHelper::map(SoapKeadaan::find()->all(), 'id','keadaan'),['required'=>true])->label(false)?>
			<h6>Kesadaran</h6>
			<?= $form->field($perawatsoap, 'kesadaran')->radioList(ArrayHelper::map(Soapkesadaran::find()->all(), 'id','kesadaran'),['required'=>true])->label(false)?>
			<?php } ?>
			<?= $form->field($perawatsoap, 'anamnesa')->textarea(['maxlength' => true,'rows'=>6,'required'=>true])->label() ?>
			<?= $form->field($model, 'status')->hiddeninput(['maxlength' => true, 'value'=>$model->status])->label(false) ?>
			<?= $form->field($perawatsoap, 'idjenisrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->idjenisrawat])->label(false) ?>
			<?= $form->field($perawatsoap, 'no_rm')->hiddeninput(['maxlength' => true, 'value'=>$model->no_rm])->label(false) ?>
			<?= $form->field($perawatsoap, 'tgl_soap')->hiddeninput(['maxlength' => true, 'value'=>date('Y-m-d')])->label(false) ?>
			<?= $form->field($perawatsoap, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
			<?= $form->field($perawatsoap, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
			<div class='row'>
				<div class='col-md-12'>
					<div class="input-group">
					
					<input type='text' class='form-control' placeholder='Sistole' name='SoapRajalperawat[sistole]' id="soaprajalperawat-sistole">
					<input type='text' class='form-control' placeholder='Distole' name='SoapRajalperawat[distole]' id="soaprajalperawat-distole">
					<span class="input-group-addon" id="basic-addon1">mmHg</span>
					</div>
				</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Suhu' name='SoapRajalperawat[suhu]' id="soaprajalperawat-suhu">
					<span class="input-group-addon" id="basic-addon1">C</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Respirasi' name='SoapRajalperawat[respirasi]' id="soaprajalperawat-respirasi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
			
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='SpO2' name='SoapRajalperawat[saturasi]' id="soaprajalperawat-saturasi">
					<span class="input-group-addon" id="basic-addon1">%</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Nadi' name='SoapRajalperawat[nadi]' id="soaprajalperawat-nadi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Tinggi Badan' name='SoapRajalperawat[tinggi]' id="soaprajalperawat-tinggi">
					<span class="input-group-addon" id="basic-addon1">cm</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Berat Badan' name='SoapRajalperawat[berat]' id="soaprajalperawat-berat">
					<span class="input-group-addon" id="basic-addon1">kg</span>
					</div>
				</div>
			</div>
			<br>
			<?= $form->field($model, 'kegiatan_ugd')->radioList(ArrayHelper::map(KegiatanUgd::find()->all(), 'id','kegiatan'),['required'=>true])->label('Jenis Kegiatan')?>
			<hr>

					<div class="form-group">
						<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
					</div>
			<?php ActiveForm::end(); ?>
		<?php }else{ ?>
		<h4>SOAP Perawat</h4>
				<table class='table table-bordered'>
						<?php if($model->idjenisrawat == 3){ ?>
					
						<tr>
							<td>Triase</td>
							<?php if($perawatsoapcount->triase != null){ ?>
							<td><?= $perawatsoapcount->triases->triase ?> </td>
							<?php } ?>
						<tr>
						<tr>
							<td>Keadaan Umum</td>
							<td><?= $perawatsoapcount->keadaan->keadaan ?> </td>
						<tr>
						<tr>
							<td>Kesadaran</td>
							<td><?= $perawatsoapcount->kesadarans->kesadaran ?> </td>
						<tr>
						<?php } ?>
						<tr>
							<td>Anamnesa</td>
							<td><?= $perawatsoapcount->anamnesa ?> </td>
						<tr>
						<tr>
							<td>Tekanan Darah</td>
							<td><?= $perawatsoapcount->sistole ?> / <?= $perawatsoapcount->distole ?> mmHg</td>
						<tr>
						<tr>
							<td>Suhu</td>
							<td><?= $perawatsoapcount->suhu ?> C</td>
						<tr>
						<tr>
							<td>Saturasi</td>
							<td><?= $perawatsoapcount->saturasi ?></td>
						<tr>
						<tr>
							<td>Nadi</td>
							<td><?= $perawatsoapcount->nadi ?></td>
						<tr>
						<tr>
							<td>Respirasi</td>
							<td><?= $perawatsoapcount->respirasi ?></td>
						<tr>
						<tr>
							<td colspan=2>
								<?php if($perawatsoapcount->iduser == Yii::$app->user->identity->id){ ?>
									<a href='<?= Url::to(['/poliklinik/edit-soap?id='.$perawatsoapcount->id.'&jenis=2']);?>' class='btn btn-primary btn-xs'>Edit</a>
								<?php } ?>
							</td>
						
						<tr>
						
					</table>
					<br>
					<a href='<?= Url::to(['/poliklinik'])?>' class='btn btn-warning'>Selesai</a>
					
		<?php } ?>
		</div>
		<div class='col-md-4'>
			
		</div>
	</div>
	
	
	

<?php 

$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	
	

", View::POS_READY);

?>