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
use kartik\checkbox\CheckboxX;
use common\models\DataGolongandarah;
use common\models\DataHubungan;
use common\models\DataHambatan;
use common\models\DataPendidikan;
use common\models\DataEtnis;
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
<?php $form = ActiveForm::begin(); ?>
	<div class='row'>
		
		<div class='col-md-6'>
			<div class='box box-primary'>
				<div class='box-header with-border'><h4>INFORMASI PASIEN</h4></div>
				<div class='box-body'>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NO RM</span></div>
						<div class='col-md-6 col-xs-6'>
							<div class="input-group">
								<?= ($model->kodepasien)? $model->kodepasien : $model->genKode() ?>
								<?= $form->field($model,'kodepasien')->textInput(['readonly'=>true])->label(false)?>
								
								<a id="manual" class="input-group-addon btn btn-success btn-sm" ><span class="fa fa-pencil"></span></a>								
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'  >NO BPJS</span></div>
						<div class='col-md-8 col-xs-8'>
							<div class="input-group">
								<input maxlength="13" type="text" id="pasien-no_bpjs" name="Pasien[no_bpjs]" autofocus class="form-control">
								<a id="cari" class="input-group-addon btn btn-success btn-sm" ><span class="fa fa-search"></span></a>								
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NIK</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type="text" maxlength="16" id="pasien-nik" name="Pasien[nik]" class="form-control">
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NAMA PASIEN</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type="text" id="pasien-nama_pasien" name="Pasien[nama_pasien]" required class="form-control">
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>JENIS KELAMIN</span></div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($model, 'jenis_kelamin')->dropDownList([ 'L' => 'L', 'P' => 'P', ], ['prompt' => '-- Jenis Kelamin  --','required'=>true])->label(false)?>
						</div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($model, 'idgolongan_darah')->dropDownList(ArrayHelper::map(DataGolongandarah::find()->all(), 'id', 'golongan_darah'),['prompt'=>'- Golongan Darah -','required'=>true])->label(false)?>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>TEMPAT LAHIR</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type="text" id="pasien-tempat_lahir" name="Pasien[tempat_lahir]" class="form-control">
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>TGL LAHIR</span></div>
						<div class='col-md-4 col-xs-6'>
							<?=	$form->field($model, 'tgllahir')->widget(DatePicker::classname(),['type' => DatePicker::TYPE_COMPONENT_APPEND,
								'pluginOptions' => [
								'autoclose'=>true,
								'format' => 'yyyy-mm-dd'
								]])->label(false)?>
						</div>
						<div class='col-md-3'>
							 <?= $form->field($model, 'barulahir')->widget(CheckboxX::classname(), [
								'initInputType' => CheckboxX::INPUT_CHECKBOX,
								'autoLabel' => false
							])->label(false); ?>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NO HP/TLP</span></div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($model, 'nohp')->textInput(['maxlength' => true,'type' => 'number'])->label(false) ?>
						</div>
						<div class='col-md-4 col-xs-4'>
							<input type="email" id="pasien-email" placeholder='Email' name="Pasien[email]" class="form-control">
						</div>
					</div>
					<div class='row'>
						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>KEPESERTAAN BPJS</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type="text" readonly id="pasien-kepesertaan_bpjs" name="Pasien[kepesertaan_bpjs]" class="form-control">
						</div>
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>STATUS PASIEN</span></div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($modelstatus, 'idstatus')->dropDownList(ArrayHelper::map(DataStatus::find()->all(), 'id', 'd_status'),['prompt'=>'- Status Pasien -','required'=>true])->label(FALSE)?>
						</div>
						<div class='col-md-4 col-xs-4'>
							 <?= $form->field($model, 'pasien_lama')->widget(CheckboxX::classname(), [
								'initInputType' => CheckboxX::INPUT_CHECKBOX,
								'autoLabel' => false
							])->label(false); ?>
						</div>
					</div>
				</div>
			</div>
			<div class='box'>
			<div class='box-header with-border'><h4>INFORMASI TAMBAHAN</h4></div>
				<div class='box-body'>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>AGAMA</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idagama')->dropDownList(ArrayHelper::map(DataAgama::find()->all(), 'id', 'agama'),['prompt'=>'- Pilih Agama -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>ETNIS / SUKU</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idetnis')->dropDownList(ArrayHelper::map(DataEtnis::find()->all(), 'id', 'etnis'),['prompt'=>'- Pilih Etnis -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>PENDIDIKAN</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idpendidikan')->dropDownList(ArrayHelper::map(DataPendidikan::find()->all(), 'id', 'pendidikan'),['prompt'=>'- Pilih Pendidikan -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>STATUS </span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idhubungan')->dropDownList(ArrayHelper::map(DataHubungan::find()->all(), 'id', 'hubungan'),['prompt'=>'- Pilih Hubungan -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>HAMBATAN KOMUNIKASI</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idhambatan')->dropDownList(ArrayHelper::map(DataHambatan::find()->all(), 'id', 'hambatan'),['prompt'=>'- Pilih Hambatan -','required'=>true])->label(false)?>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-6'>
			<div class='box box-warning'>
			<div class='box-header with-border'><h4>INFORMASI PEKERJAAN</h4></div>
				<div class='box-body'>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>PEKERJAAN</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idpekerjaan')->dropDownList(ArrayHelper::map(DataPekerjaan::find()->all(), 'id', 'pekerjaan'),['prompt'=>'- Pilih Pekerjaan -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div id='tni'>
						<div class='row'>
							<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NRP</span></div>
							<div class='col-md-8 col-xs-8'>
								<input type="text"  id="pasienstatus-nrp" name="PasienStatus[nrp]" class="form-control">
							</div>		
						</div>	
						<div class='row'>
							<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>PANGKAT</span></div>
							<div class='col-md-8 col-xs-8'>
								<input type="text"  id="pasienstatus-pangkat" name="PasienStatus[pangkat]" class="form-control">
							</div>		
						</div>	
						<div class='row'>
							<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>KESATUAN</span></div>
							<div class='col-md-8 col-xs-8'>
								<input type="text"  id="pasienstatus-kesatuan" name="PasienStatus[kesatuan]" class="form-control">
							</div>		
						</div>							
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>KETERANGAN</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type='hidden' id='coba' name='coba'>
							<?= $form->field($modelstatus, 'keterangan')->textarea(['rows' => 2])->label(false) ?>
						</div>						
					</div>
					
				</div>
			</div>
			<div class='box box-success'>
			<div class='box-header with-border'><h4>INFORMASI ALAMAT</h4></div>
				<div class='box-body'>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>KELURAHAN</span></div>
						<div class='col-md-8 col-xs-8'>
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
							])->label(false);?>
						</div>						
					</div>
					
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>ALAMAT</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type='hidden' id='coba' name='coba'>
							<?= $form->field($modelalamat, 'alamat')->textarea(['rows' => 2])->label(false) ?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>KODE POS</span></div>
						<div class='col-md-8 col-xs-8'>
							<input type='hidden' id='coba' name='coba'>
							<?= $form->field($modelalamat, 'kodepos')->textInput(['maxlength' => true])->label(false)?>
						</div>						
					</div>
					
				</div>
			</div>
			<div class='box box-success'>
			<div class='box-header with-border'><h4>INFORMASI PENANGGUNG JAWAB</h4></div>
				<div class='box-body'>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>PENANGGUNG JAWAB</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'penanggung_jawab')->textInput(['maxlength' => true])->label(false)?>
						</div>						
					</div>
					
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>HUBUNGAN</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'idsb_penanggungjawab')->dropDownList(ArrayHelper::map(PasienPenanggungjawab::find()->all(), 'id', 'penaggungjawab'),['prompt'=>'- Hubungan dengan pasien-','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>NO TELP</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'nohp_penanggungjawab')->textInput(['maxlength' => true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-4 col-xs-4 '><span class='pull-right pd-top'>ALAMAT</span></div>
						<div class='col-md-8 col-xs-8'>
							<?= $form->field($model, 'alamat_penanggunjawab')->textarea(['maxlength' => true])->label(false)?>
						</div>						
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
<?php ActiveForm::end(); ?>
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
				if(data == 201){
					alert('data tidak ditemukan');
					
				}else{
					
					var res = JSON.parse(JSON.stringify(data));
					var dinsos = '';
					var prb = '';
					var nomr = res.mr.noMR;
					var aktif = res.statusPeserta.keterangan;
					if(res.informasi.dinsos == 1){
						dinsos = 'Peserta Dinsos';
					}
					if(res.informasi.prolanisPRB == 1){
						prb = 'Peserta PRB';
					}
					alert('Data Ditemukan '+dinsos+' , '+prb+' , Status:'+aktif);
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
					
				}
			},
			error: function (exception) {
				alert(exception);
			}
		});	
	}) ;
	$('#pasien-no_bpjs').on('keypress',function(e) {
		id = $('#kode-pasien').val();
		if(e.which === 13){
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
		}
	});
				

", View::POS_READY);
?>
