<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use common\models\DataPekerjaan;
use common\models\DataAgama;
use common\models\DataStatus;
use common\models\DataGolongandarah;
use common\models\DataHubungan;
use common\models\DataHambatan;
use common\models\DataPendidikan;
use common\models\PasienPenanggungjawab;
use kartik\date\DatePicker;
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama + " - " + repo.Kec + " - " + repo.Kab;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}
JS;
$this->registerJs($formatJs, View::POS_HEAD);
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
?>
<br>
<div class="pasien-form">
	<div class='row'>
		<div id='datapasien' class='col-md-4'>
			<div class='box box-primary'>
				<div class='box-header with-border'><h4>Data Pasien</h4></div>
				<div class='box-body'>
				<div class="callout callout-info">
                <h5>Pastikan Nomer Rekamedis Sesuai</h5>
				</div>
				<?php $form = ActiveForm::begin(); ?>
				
				<div class='row'>
					<div class='col-sm-9 col-xs-9'><?= $form->field($model, 'kodepasien')->textInput(['maxlength' => true,'mainlength'=>true,'readonly'=>true])->label('Nomor Rekam medis') ?></div>
					<div class='col-sm-2 col-xs-2'>
						<a style='margin-top:25px;' id="manual" class="btn btn-success" ><span class="fa fa-pencil" style="width: 20px;"></span>Manual</a>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5 col-xs-6'><?= $form->field($model, 'no_bpjs')->textInput(['maxlength' => true,]) ?></div>
					
					<div class='col-md-2 col-xs-2'>
						<a style='margin-top:25px;' id="cari" class="btn btn-warning" ><span class="fa fa-search" style="width: 20px;"></span>Cari</a>
					</div>
					<div class='col-md-5 col-xs-4'><?= $form->field($model, 'nik')->textInput(['maxlength' => true,]) ?></div>
				</div>
				<div class='row'>
					<div class='col-md-7 col-xs-7'><?= $form->field($model, 'nama_pasien')->textInput(['maxlength' => true,]) ?></div>
					
					<div class='col-md-5 col-xs-5'><?= $form->field($model, 'jenis_kelamin')->dropDownList([ 'L' => 'L', 'P' => 'P', ], ['prompt' => 'Jenis Kelamin'])?></div>
				</div>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'tempat_lahir')->textInput(['maxlength' => true,]) ?></div>
					
					<div class='col-md-6 col-xs-6'><?=	$form->field($model, 'tgllahir')->widget(DatePicker::classname(),[
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd'
					]
					])->label('Tgl Lahir')?></div>
				</div>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'nohp')->textInput(['maxlength' => true,]) ?></div>
					
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'email')->textInput(['maxlength' => true,]) ?></div>
				</div>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'kepesertaan_bpjs')->textInput(['maxlength' => true,]) ?></div>
					<div class='col-md-6 col-xs-6'><?= $form->field($modelstatus, 'idstatus')->dropDownList(ArrayHelper::map(DataStatus::find()->all(), 'id', 'd_status'),['prompt'=>'- Status Pasien -','required'=>true])->label('Status Pasien')?></div>
					<?= $form->field($model, 'idkepesertaan')->hiddenInput(['maxlength' => true,])->label(false) ?>
				</div>
				
				</div>
			</div>
		</div>
		<div id='golongan' class='col-md-4'>
			<div class='box box-warning'>
				<div class='box-header with-border'><h4>Detail Pasien</h4></div>
				<div class='box-body'>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'idagama')->dropDownList(ArrayHelper::map(DataAgama::find()->all(), 'id', 'agama'),['prompt'=>'- Pilih Agama -','required'=>true])->label('Agama')?></div>
					
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'idpendidikan')->dropDownList(ArrayHelper::map(DataPendidikan::find()->all(), 'id', 'pendidikan'),['prompt'=>'- Pilih Pendidikan -','required'=>true])->label('Pendidikan')?></div>
				</div>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'idhubungan')->dropDownList(ArrayHelper::map(DataHubungan::find()->all(), 'id', 'hubungan'),['prompt'=>'- Pilih Hubungan -','required'=>true])->label('Status Pernikahan')?></div>
					
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'idhambatan')->dropDownList(ArrayHelper::map(DataHambatan::find()->all(), 'id', 'hambatan'),['prompt'=>'- Pilih Hambatan -','required'=>true])->label('Hambatan Komunikasi')?></div>
				</div>
				
				
				
				
				<?= $form->field($model, 'idgolongan_darah')->dropDownList(ArrayHelper::map(DataGolongandarah::find()->all(), 'id', 'golongan_darah'),['prompt'=>'- Pilih Golongan Darah -','required'=>true])->label('Golongan Darah')?>
				
				</div>
			</div>
			<div class='box box-danger'>
				<div class='box-header with-border'><h4>Pekerjaan Pasien</h4></div>
				<div class='box-body'>
				<?= $form->field($model, 'idpekerjaan')->dropDownList(ArrayHelper::map(DataPekerjaan::find()->all(), 'id', 'pekerjaan'),['prompt'=>'- Pilih Pekerjaan -','required'=>true])->label('Pekerjaan')?>
				<div id='tni'>
				<div class='row'>
					<div class='col-md-4 col-xs-4'><?= $form->field($modelstatus, 'nrp')->textInput(['maxlength' => true])->label('NRP / NIP')?></div>
					<div class='col-md-4 col-xs-4'><?= $form->field($modelstatus, 'pangkat')->textInput(['maxlength' => true])?></div>
					<div class='col-md-4 col-xs-4'><?= $form->field($modelstatus, 'kesatuan')->textInput(['maxlength' => true])?></div>
				</div>
				
				
				
				</div>
				<input type='hidden' id='coba' name='coba'>
				<?= $form->field($modelstatus, 'keterangan')->textarea(['rows' => 5])->label('keterangan') ?>
				</div>
			</div>
		</div>
		<div id='alamat' class='col-md-4'>
			<div class='box box-success'>
				<div class='box-header with-border'><h4>Alamat Pasien</h4></div>
				<div class='box-body'>
					<?= $form->field($modelalamat, 'idkel')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Pilih Kelurahan'],
						'pluginOptions' => [
						 'initialize' => true,
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => "https://simrs.rsausulaiman.com/api/alamat",
						'dataType' => 'json',
						'delay' => 250,
						'data' => new JsExpression('function(params) { return {q:params.term};}'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true
						],
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
						'templateResult' => new JsExpression('formatRepo'),
						'templateSelection' => new JsExpression('formatRepoSelection'),
					],
					]);?>
					<?= $form->field($modelalamat, 'alamat')->textarea(['rows' => 5])->label('alamat') ?>
					<?= $form->field($modelalamat, 'kodepos')->textInput(['maxlength' => true])?>
					
				</div>
				
					
			</div>
			<div class='box box-default'>
				<div class='box-header with-border'><h4>Penanggung Jawab Pasien</h4></div>
				<div class='box-body'>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'penanggung_jawab')->textInput(['maxlength' => true])?></div>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'idsb_penanggungjawab')->dropDownList(ArrayHelper::map(PasienPenanggungjawab::find()->all(), 'id', 'penaggungjawab'),['prompt'=>'- Hubungan dengan pasien-','required'=>true])->label('Hubungan Penanggung Jawab')?></div>
				</div>
				<div class='row'>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'nohp_penanggungjawab')->textInput(['maxlength' => true])->label('No Telepon')?></div>
					<div class='col-md-6 col-xs-6'><?= $form->field($model, 'alamat_penanggunjawab')->textarea(['maxlength' => true])->label('alamat')?></div>
				</div>
				
				
				</div>
				<div class='box-footer'>
					<div class="form-group">
						<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
					</div>
				</div>
			</div>
		</div>
		
		</div>
	</div>


	<?php ActiveForm::end(); ?>
</div>
<?php 
$urlGet = Url::to(['pasien/get-pasien']);
$this->registerJs("
	$('#hilangkan').hide();
	$('#tni').hide();
	$('#manual').on('click',function() {
		$('#pasien-kodepasien').prop('readonly', false);
		$('#pasien-kodepasien').val('P-');
		$('#pasien-kodepasien').focus();
	});
	$('#pasien-idpekerjaan').on('change',function() {
		var dob = $('#pasien-idpekerjaan').val();
		$('#coba').val(dob);
		if(dob < 5){
		$('#tni').show();
		
		}else{
		$('#tni').hide();
		}
	});
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	
	$('#cari').on('click',function(){
		id = $('#pasien-no_bpjs').val();
			$.ajax({
			type: 'POST',
			url: '{$urlGet}',
			data: {id: id},
			dataType: 'json',
			success: function (data) {
				if(data !== null){
					var res = JSON.parse(JSON.stringify(data));
					var nomr = res.mr.noMR;
					$('#pasien-nik').val(res.nik);
					$('#pasien-nama_pasien').val(res.nama);
					$('#pasien-tgllahir').val(res.tglLahir);
					$('#pasien-nohp').val(res.mr.noTelepon);
					$('#pasien-jenis_kelamin').val(res.sex);
					$('#pasien-kepesertaan_bpjs').val(res.jenisPeserta.keterangan);
					$('#pasien-idkepesertaan').val(res.jenisPeserta.kode);
					if(nomr != null){
						$('#pasien-kodepasien').val('P-'+res.mr.noMR);
					}
					
				}else{
					alert('data tidak ditemukan');
				}
			},
			error: function (exception) {
				alert(exception);
			}
		});	
	}) ;
	
				

", View::POS_READY);
?>

