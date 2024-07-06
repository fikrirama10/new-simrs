<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\Rawat;
use common\models\RawatBayar;
use common\models\KategoriPenyakit;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
if($model->idbayar == 2){
	$kelas = RuanganKelas::find()->where(['between','id',$bpjs['peserta']['hakKelas']['kode'],3])->all();
	$naik  = $bpjs['peserta']['hakKelas']['kode']+1;
	$kelasnaik = RuanganKelas::find()->where(['naik'=>$naik])->all();
}else{
	$kelas = RuanganKelas::find()->where(['ket'=>1])->all();
} 
if($model->idbayar == 2){
	$ruangan = Ruangan::find()->where(['idkelas'=>$bpjs['peserta']['hakKelas']['kode']])->all();
	
}else{
	$ruangan = Ruangan::find()->where(['id'=>0])->all();
}

$bayar = RawatBayar::find()->all();
$kat_penyakit = KategoriPenyakit::find()->all();
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
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
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
		<div id='katarak_info' class="alert alert-info alert-dismissible" id="divInfoKatarak" style="">
                    <h4><i class="icon fa fa-volume-up"></i> INFORMASI....</h4>
                    <p id="pKatarak">Centang Katarak <i class="fa fa-check"></i>, Jika Peserta Tersebut Mendapatkan Surat Perintah Operasi katarak</p>
                </div>
		<div class='box'>
			<div class='box-header with-border'><h4>Rawat Inap</h4></div>
			<div class='box-body'>
			<?php if($model->status == 2){ ?>
				<div class="callout callout-success">
                <h4>SPRI Sudah digunakan</h4>

                <p>Pasien sudah masuk ruangan rawat inap menggunakan SPRI tersebut</p>
				</div>
				<a href='<?= Url::to(['admisi/'])?>' class='btn btn-info'>Kembali</a>
			<?php }else{ ?>
	
				<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
			
				<div class="box-body">
					<div class="form-group">
					  <label class="col-sm-4 control-label">Tgl Rujukan</label>
					  <div class="col-sm-8">
						<input type='text' class='form-control' name='tglRujuk' readonly value='<?= date('Y-m-d',strtotime($model->tgl_rawat)) ?>'>
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">DPJP Pemberi Surat SKDP/SPRI</label>
					  <div class="col-sm-8">
						<input type='hidden' class='form-control' name='Rawat[iddokter]' readonly value='<?= $model->iddokter ?>'>
						<input type='hidden' class='form-control' name='Rawat[idpoli]' readonly value='<?= $model->idpoli ?>'>
						<input type='text' class='form-control' readonly value='<?= $model->dokter->nama_dokter?>'>
					  </div>
					</div>
					
					<div class="form-group">
					  <label class="col-sm-4 control-label">No.Registrasi Rawat Inap</label>
					  <div class="col-sm-8">
						<input type='text' name='Rawat[no_referal]' readonly  class='form-control'  value='<?= $model->no_spri	?>'>
					  </div>
					  
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">No.SPRI BPJS</label>
					  <div class="col-sm-6">
						<input type='text' name='Rawat[no_suratkontrol]' readonly class='form-control'  value='<?= $model->spri_bpjs?>'>
					  </div>
					  <span class="input-group-btn">
						<button type="button" id="button_spri" class="btn btn-warning btn-sm btn-flat">Print SPRI</button>
						</span>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">No.Rujukan</label>
					  <div class="col-sm-8">
						<input type='text' required name='Rawat[no_rujukan]'  class='form-control'  value=''>
					  </div>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">Kategori Perawatan</label>
					  <div class="col-sm-4">
						<select id='rawat-idjenisperawatan' required name='Rawat[idjenisperawatan]'  class='form-control'>
							<option value=''>-- Kategori Penyakit --</option>
							<?php foreach($kat_penyakit as $kp): ?>
								<option value='<?= $kp->id?>'><?= $kp->kategori?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
						<div class="col-md-3"><input type="checkbox" id="operasi" name="Rawat[operasi]" value="1">
						<label for="operasi">Tindakan Bedah ?</label></div>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">Hak Kelas</label>
					  <div class="col-sm-4">
	
						<select id='kelas' name='Rawat[idkelas]'  class='form-control'>
							<?php foreach($kelas as $k): ?>
								<option value='<?= $k->id?>'><?= $k->kelas?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
						<div class="col-md-3"><input type="checkbox" id="naik_kelas" name="Rawat[naik_kelas]" value="1">
						<label for="operasi">Naik Kelas ?</label></div>
					</div>
					<div id='naik_kelas_layout' class="form-group">
					  <label class="col-sm-4 control-label"></label>
					 
							<div class='col-md-8'>
								<div style=' width:100%; padding-top:10px; padding-left:10px; padding-bottom:10px; min-height:100px; overflow:hidden; background:#eeeeee;'>
									<div class="form-group">
									  <label class="col-sm-3 control-label">Hak Kelas</label>
									  <div class="col-sm-4">

										<select id='kelas_naik' name='Rawat[idkelas_naik]'  class='form-control'>
												<option value=''></option>
											<?php foreach($kelasnaik as $kn): ?>
												<option value='<?= $kn->id?>'><?= $kn->kelas?></option>
											<?php endforeach; ?>
										</select>
										
									  </div>
										
									</div>
									<div class="form-group">
									  <label class="col-sm-3 control-label">Pembiayaan</label>
									  <div class="col-sm-5">

										<select id='pembiayaan' name='Rawat[pembiayaan]'  class='form-control'>											
												<option value=''>--- Pilih ----</option>
												<option value='1'>Pribadi</option>
												<option value='2'>Pemberi Kerja</option>
												<option value='3'>Asuransi Kesehatan Tambahan</option>
										</select>
										
									  </div>
										
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Penanggung Jawab</label>
										<div class="col-sm-8">
											<textarea id='penanggungjawab' nama='Rawat[penanggungjawab]' class='form-control' rows=4></textarea>
										</div>
									</div>
									</div>
								</div>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">Ruangan</label>
					  <div class="col-sm-3">
						<select id='rawat-idruangan' name='Rawat[idruangan]'  class='form-control'>
							<?php foreach($ruangan as $r): ?>
								<option value='<?= $r->id?>'><?= $r->nama_ruangan?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
					 <span class="input-group-btn">
						<button type="button" id="show-ruangan" class="btn btn-success btn-sm btn-flat">Cek Tempat Tidur</button>
						</span>
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label"></label>
					  <div class="col-sm-8">
						<div id='loading' style='display:none;'>
						<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
						</div>
						<div id='ruangan-ajax'></div>
					  </div>
					
					</div>
					<div id='admisi-ranap'>
						<div class="form-group">
							<label class="col-sm-4 control-label">Diagnosa ranap</label>
							<div class="col-sm-6" style='margin-left:15px;'>
								<?= $form->field($rawat, 'icdx')->widget(Select2::classname(), [
									'name' => 'kv-repo-template',
									'options' => ['placeholder' => 'Cari ICD X .....'],
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
							<label class="col-sm-4 control-label">Bed</label>
							<div class="col-sm-8">
								<input type='text' id='kodebed' class='form-control' readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label">Penanggung</label>
							<div class="col-sm-4">
								<select id='bayar' name='Rawat[idbayar]'  class='form-control'>
									<?php foreach($bayar as $b): ?>
										<option value='<?= $b->id?>'><?= $b->bayar?></option>
									<?php endforeach; ?>
								</select>								
							</div>
	
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"></label>
							<div class="col-sm-4">
								<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
								<label for="vehicle1">Anggota ?</label><br>				
							</div>
	
						</div>
						<div class="form-group" style="">
							<label class="col-md-4 control-label">Katarak <input name="Rawat[katarak]"  type="checkbox" id="chkkatarak" value="1"></label>
							<div class="col-md-8">
								<p class="text-muted well well-sm no-shadow">Centang Katarak <i class="fa fa-check"></i>, Jika Peserta Tersebut Mendapatkan Surat Perintah Operasi katarak</p>
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
					</div>
					
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              
              </div>
			  	<?= $form->field($rawat, 'idbed')->hiddeninput(['maxlength' => true,'readonly'=>true,])->label(false) ?>
				<?= $form->field($rawat, 'no_rm')->hiddeninput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
				<?= $form->field($rawat, 'idjenisrawat')->hiddeninput(['maxlength' => true,'value'=>2])->label(false) ?>
				<button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
				<?php ActiveForm::end(); ?>
				
			<?php } ?>
			</div>		
		</div>
	</div>
</div>
	<iframe src="<?= Url::to(['admisi/print-spri?id='.$model->id]) ?>" style="border:none; display:none;" id='myFrameForm' title="Iframe Example"></iframe>
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

echo '<div class="modalContent">'. $this->render('_form-kecelakaan',['nobpjs'=>$pasien->no_bpjs,'tgl'=>$model->tgl_rawat]).'</div>';
 
Modal::end();

$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowRuangan = Url::to(['admisi/show-ruangan']);
$urlKab = Url::to(['admisi/get-kab']);
$urlKec = Url::to(['admisi/get-kec']);
$this->registerJs("
	$('#button_spri').on('click',function(){
		let objFra = document.getElementById('myFrameForm');
		objFra.contentWindow.focus();
		objFra.contentWindow.print();
		// alert('aaa');
	});
	$('#admisi-ranap').hide();
	$('#naik_kelas_layout').hide();
	$('#confirm22').hide();
	$('#katarak_info').hide();
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
	$('#chkkatarak').on('change', function(event){
		
		if($(this).is(':checked')){
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$('#katarak_info').show();
		}else{
			$('#katarak_info').hide();
		}
		
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
	$('#kelas_naik').on('change', function(event){
		kelas = $('#kelas_naik').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKelas}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#rawat-idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#naik_kelas').on('change', function(event){
		if($(this).is(':checked')){
			kelas = $('#kelas_naik').val();
			$('#kelas_naik').val('');
			// alert(kelas);
			$('#naik_kelas_layout').show();
			$.ajax({
				type: 'GET',
				url: '{$urlKelas}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#rawat-idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
		}else{
			$('#kelas_naik').val('');
			$('#naik_kelas_layout').hide();
			$('#pembiayaan').val('');
			kelas = $('#kelas').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKelas}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#rawat-idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
		}
		
	});
	$('#kelas').on('change',function(){
			kelas = $('#kelas').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKelas}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#rawat-idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
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
	$('#show-ruangan').on('click',function(){
			$('#ruangan-ajax').hide();
			ruangan = $('#rawat-idruangan').val();
		bayi = '{$model->bayi_lahir}';
			$.ajax({
				type: 'GET',
				url: '{$urlShowRuangan}',
				data: 'id='+ruangan+'&bayi='+bayi,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#ruangan-ajax').show();
					$('#ruangan-ajax').animate({ scrollTop: 0 }, 200);
					$('#ruangan-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});
	$('#confirm2').on('click', function(event){
		diagnosa = $('#rawat-icdx').val();
		if(diagnosa == ''){
			alert('Kode Diagnosa kosong');
			event.preventDefault();
		}else{
			//naikkelas = $('#naik_kelas').val();
			jaminan = $('#jaminan_value').val();
			if($('#naik_kelas').is(':checked')){
				penanggungjawab = $('#penanggungjawab').val();
				pembiayaan = $('#pembiayaan').val();
				if(penanggungjawab == ''){
					alert('penanggungjawab kosong');
					event.preventDefault();
				}else if(pembiayaan == ''){
					alert('pembiayaan kosong');
					event.preventDefault();
				}
			}else if(jaminan > 0){
				
				
					provinsi = $('#provinsi_value').val();
					kecamatan =  $('#idkec_value').val();
					kabupaten = $('#idkab_value').val();
					tglkejadian = $('#tglkejadian_value').val();
					if(provinsi == '' || kecamatan =='' || kabupaten ==''){
						alert('Lengkapi data provinsi,kabupaten , dan kecamatan');
						event.preventDefault();
					}else if(tglkejadian == ''){
						alert('Tgl Kejadian tidak valid');
						event.preventDefault();
					}				
			}else{
				age = confirm('Yakin Untuk menyimpan data');
				if(age == true){
					 return true;
				} else {
					event.preventDefault();
				}				
			}
			
		}
		
	});
", View::POS_READY);


?>
