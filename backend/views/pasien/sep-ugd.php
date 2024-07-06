<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
$this->title = 'SEP';
$this->params['breadcrumbs'][] = ['label' => 'Pasien', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien </h4></div>
			<div class='box-body'>
				<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
							'tgllahir',
							'tempat_lahir',
							'nohp',
							'usia_tahun',
							'kepesertaan_bpjs',
							'pekerjaan.pekerjaan',
						],
					]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'><h4><?= $model->jenisrawat->jenis?> (Rawat Jalan)</h4></div>
			<div class='box-body'>
					<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Spesialis/SubSpesialis</label>
						<div class="col-sm-8">
						<input type='text' class='form-control' readonly value='<?= $model->poli->poli ?>'>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">DPJP yang Melayani</label>
						<div class="col-sm-8">
						<input type='text' class='form-control' readonly value='<?= $model->dokter->nama_dokter ?>'>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Tgl.SEP
						</label>
						<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control" id="txttglsep" value="<?= date('Y-m-d',strtotime($model->tglmasuk))?>" maxlength="10" disabled="">
							<span class="input-group-addon">
								<span class="fa fa-calendar">
								</span>
							</span>
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Diagnosa</label>
						<div class="col-sm-6" style='margin-left:15px;'>
								<?= $form->field($model, 'icdx')->widget(Select2::classname(), [
									'name' => 'kv-repo-template',
									//'value' => $rujukan['rujukan']['diagnosa']['nama'],
									'options' => ['placeholder' =>'ketik kode atau nama diagnosa min 3 karakter'],
									'pluginOptions' => [
									'allowClear' => true,
									'minimumInputLength' => 3,
									'ajax' => [
									'url' => "https://new-simrs.rsausulaiman.com/auth/listdiagnosa",
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
					<div class="form-group">
						<label class="col-sm-4 control-label">Catatan</label>
						<div class="col-sm-8">
						<textarea class='form-control' placeholder='ketik catatan apabila ada'></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Status Kecelakaan </label>
						<div class="col-sm-6">
							<select id='kecelakaan' name='Rawat[jaminan]' required class='form-control'>											
									<option value=''>-- Silahkan pilih --</option>
									<option value='0'>Bukan Kecelakaan</option>
									<option value='1'>Kecelakaan Lalu lintas dan bukan kecelakaan Kerja</option>
									<option value='2'>Kecelakaan Lalu lintas dan kecelakaan Kerja</option>
									<option value='3'>Kecelakaan Kerja</option>
							</select>					
						</div>

					</div>
					<div id='suplesi' class="form-group">
					 
							<div class='col-md-12'>
								<div style=' width:100%; padding-top:10px; padding-left:10px; padding-bottom:10px; min-height:100px; overflow:hidden; background:#f5f5f5;'>
									<div class="form-group">
									  <label class="col-sm-4 control-label">Tanggal Kejadian</label>
									  <div class="col-sm-4">
											<input type='date' name='RawatJaminanBpjs[tglkejadian]' id='tglkejadian'  class='form-control' max='<?= date('Y-m-d',strtotime('+7 hour'))?>'>
									  </div>
										
									</div>
									<div class="form-group">
										  <label class="col-sm-4 control-label">No. LP</label>
										  <div class="col-sm-7">
												<input type='text' name='RawatJaminanBpjs[noLp]' class='form-control' >
										  </div>										
									</div>
									<?php $provinsi = Yii::$app->vclaim->get_provinsi(); ?>
									<div class="form-group">
										  <label class="col-sm-4 control-label">Lokasi Kejadian</label>
										  <div class="col-sm-7">
												<select id='provinsi' name='RawatJaminanBpjs[propinsi]' class='form-control'>
														<option value=''>-- Silahkan Pilih --</option>
													<?php foreach($provinsi['list'] as $prov){ ?>
														<option value='<?= $prov['kode']?>'><?= $prov['nama']?></option>
													<?php } ?>
												</select>
										  </div>										
									</div>
									<div class="form-group">
										  <label class="col-sm-4 control-label"></label>
										  <div class="col-sm-7">
												<select id='idkabupaten' name='RawatJaminanBpjs[kabupaten]'  class='form-control'>
													
												</select>
										  </div>										
									</div>
									<div class="form-group">
										  <label class="col-sm-4 control-label"></label>
										  <div class="col-sm-7">
												<select id='idkecamatan' name='RawatJaminanBpjs[kecamatan]'   class='form-control'>
													
												</select>
										  </div>										
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label"></label>
										<div class="col-sm-7">
											<textarea class='form-control' name='RawatJaminanBpjs[keterangan]' placeholder='ketik keterangan kejadian' rows=4></textarea>
											<input type='hidden' value=0 name='RawatJaminanBpjs[suplesi]'  id='suplesi_bpjs'>
											<input type='hidden' value=0 name='RawatJaminanBpjs[nosep_suplesi]'  id='nosep_suplesi'>
										</div>
									</div>
									</div>
							</div>
					</div>
					<input type='hidden' id='tglkejadian_value'>
					<input type='hidden' id='jaminan_value'>
					<input type='hidden' id='provinsi_value'>
					<input type='hidden' id='idkab_value'>
					<input type='hidden' id='idkec_value'>
					<button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
					<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<?php

Modal::begin([
	'id' => 'mdPasien',
	'header' => '<h5>Data Penjaminan an. '.$pasien->nama_pasien.'</h5>',
	'footer' => '  <button type="button" id="btnKasusKLLBaru" class="btn btn-primary pull-left"> Kasus KLL Baru</button> <button type="button" id="btnTutupJaminan" class="btn btn-danger pull-right"> Batal</button>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_form-kecelakaan',['nobpjs'=>$pasien->no_bpjs,'tgl'=>date('Y-m-d',strtotime($model->tglmasuk))]).'</div>';
 
Modal::end();

$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowRuangan = Url::to(['admisi/show-ruangan']);
$urlKab = Url::to(['admisi/get-kab']);
$urlKec = Url::to(['admisi/get-kec']);
$this->registerJs("
	
	$('#confirm22').hide();
	$('#suplesi').hide();
	$('#btnTutupJaminan').on('click', function(event){
		 $('#mdPasien').modal('hide');
		 $('#kecelakaan').val('');
		 $('#jaminan_value').val(0);
	});
	$('#btnKasusKLLBaru').on('click', function(event){
		 $('#mdPasien').modal('hide');
		 $('#suplesi').show();
		 $('#suplesi_bpjs').val(0);
	});
	$('#provinsi').on('change', function(event){
		prov = $('#provinsi').val();
		$('#provinsi_value').val(prov);
	});
	$('#idkabupaten').on('change', function(event){
		prov = $('#idkabupaten').val();
		$('#idkab_value').val(prov);
	});
	$('#idkecamatan').on('change', function(event){
		prov = $('#idkecamatan').val();
		$('#idkec_value').val(prov);
	});
	$('#tglkejadian').on('change', function(event){
		prov = $('#tglkejadian').val();
		$('#tglkejadian_value').val(prov);
	});

	$('#kecelakaan').on('change', function(event){
		kecelakaan = $('#kecelakaan').val();
		if(kecelakaan == 1 || kecelakaan == 2){
			 $('#mdPasien').modal('show');
			 $('#suplesi').hide();
			 $('#jaminan_value').val(kecelakaan);
		}else if(kecelakaan == 3 ){
			$('#suplesi').show();
			$('#jaminan_value').val(kecelakaan);
		}else{
			$('#suplesi').hide();
		}
	});

	
	$('#provinsi').on('change',function(){
			provinsi = $('#provinsi').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlKab}',
				data: 'kode='+provinsi,
				
				success: function (data) {
					$('#idkabupaten').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#idkabupaten').on('change',function(){
			idkabupaten = $('#idkabupaten').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKec}',
				data: 'kode='+idkabupaten,
				
				success: function (data) {
					$('#idkecamatan').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	
	$('#confirm2').on('click', function(event){
		diagnosa = $('#rawat-icdx').val();
		if(diagnosa == ''){
			alert('Kode Diagnosa kosong');
			event.preventDefault();
		}else{			
			age = confirm('Yakin Untuk menyimpan data');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}				
		}
		
	});
", View::POS_READY);


?>
