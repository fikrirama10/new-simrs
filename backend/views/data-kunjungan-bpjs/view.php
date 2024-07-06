<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use common\models\RuanganKelas;
$kelas = RuanganKelas::find()->where(['between','id',$peserta['response']['peserta']['hakKelas']['kode'],3])->all();
$naik  = $peserta['response']['peserta']['hakKelas']['kode']+1;
$kelasnaik = RuanganKelas::find()->where(['naik'=>$naik])->all();
if($data_sep['response']['poliEksekutif'] == 1){
	$ck = 'checked';
}else{
	$ck='';
}
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
	<div class='col-md-3'>
			<div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-purple-active">
                        <div class="widget-user-image">
                   
                        </div>
                        <h4 class="widget-user-username" id="lblnama"><?= $peserta['response']['peserta']['nama']?></h4>
                        <p class="widget-user-desc" id="lblnoka"><?= $peserta['response']['peserta']['noKartu']?></p>	
                    </div>
                    
                    <!-- /.box-body -->
                    
                    <!-- /.box -->
                    <!-- About Me Box -->
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a title="Profile Peserta" href="#tab_1" data-toggle="tab"><span class="fa fa-user"></span></a></li>
                                <li><a href="#tab_2" title="COB" data-toggle="tab"><span class="fa fa-building"></span></a></li>
                              
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <span class="fa fa-sort-numeric-asc"></span> <a title="NIK" class="pull-right-container" id="lblnik"><?= $peserta['response']['peserta']['nik']?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-credit-card"></span> <a title="No.Kartu Bapel JKK" class="pull-right-container" id="lblnokartubapel"></a>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-calendar"></span> <a title="Tanggal Lahir" class="pull-right-container" id="lbltgllahir"><?= $peserta['response']['peserta']['tglLahir']?></a>
                                        </li>
                                      
                                        <li class="list-group-item">
                                            <span class="fa fa-hospital-o"></span> <a title="Hak Kelas Rawat" class="pull-right-container" id="lblhakkelas"><?= $peserta['response']['peserta']['hakKelas']['keterangan']?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-stethoscope"></span>  <a title="Faskes Tingkat 1" class="pull-right-container" id="lblfktp"><?= $peserta['response']['peserta']['provUmum']['kdProvider']?> - <?= $peserta['response']['peserta']['provUmum']['nmProvider']?></a>
                                            <input type="hidden" id="txtppkasalpst" value="0120B013">
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-calendar"></span>  <a title="TMT dan TAT Peserta" class="pull-right-container" id="lbltmt_tat"><?= $peserta['response']['peserta']['tglTMT']?> s.d <?= $peserta['response']['peserta']['tglTAT']?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-calendar"></span>  <a title="Jenis Peserta" class="pull-right-container" id="lblpeserta"><?= $peserta['response']['peserta']['jenisPeserta']['keterangan']?></a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <span class="fa fa-sort-numeric-asc"></span> <a title="No. Asuransi" class="pull-right-container" id="lblnoasu"></a>
                                            <input type="hidden" id="txtkdasu" value="">
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-windows"></span> <a title="Nama Asuransi" class="pull-right-container" id="lblnmasu"></a>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-calendar"></span> <a title="TMT dan TAT Asuransi" class="pull-right-container" id="lbltmt_tatasu">null s.d null</a>
                                            <input type="hidden" id="txttmtasu" value="">
                                            <input type="hidden" id="txttatasu" value="">
                                        </li>
                                        <li class="list-group-item">
                                            <span class="fa fa-bank"></span> <a title="Nama Badan Usaha" class="pull-right-container" id="lblnamabu"></a>
                                            <input type="hidden" id="txtkdbu" value="">
                                        </li>
                                    </ul>
                                </div>
                              
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <div id="divriwayatKK" style="display: none;">
                            <button type="button" id="btnRiwayatKK" class="btn btn-danger btn-block"><span class="fa fa-th-list"></span> Pasien Memiliki Riwayat KLL/KK/PAK <br><i>(klik lihat data)</i></button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
	</div>
	<div class='col-md-9'>
		<?php if($peserta['response']['peserta']['informasi']['prolanisPRB'] !=null ){ ?>
		<div class="callout callout-danger" id="divPotensiPRB" style="">
			<p id="pPotensiPRB">Peserta : <?= $peserta['response']['peserta']['informasi']['prolanisPRB'] ?></p>
			<input type="hidden" id="txtpotensiprb" value="1">
		</div>
		<?php } ?>
		<div id='katarak_info' class="alert alert-info alert-dismissible" id="divInfoKatarak" style="">
			<h4><i class="icon fa fa-volume-up"></i> INFORMASI....</h4>
			<p id="pKatarak">Centang Katarak <i class="fa fa-check"></i>, Jika Peserta Tersebut Mendapatkan Surat Perintah Operasi katarak</p>
		</div>
		<?php $form = ActiveForm::begin([
					'method' => 'post',
					'options' => [
						'enctype' => 'multipart/form-data',
						'class' => 'form-horizontal'
					]
				]); ?>
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $data_sep['response']['noSep']?><label class="pull-right" style="font-size:larger" id="lblnosep"></label> </h3>
				<label class="pull-right" style="font-size:larger" id="lbljenpel">Rawat Jalan</label>
				<input type="hidden" id="txtjenpel" value="2">
			</div>
			<div class='box-body'>
				
				<?php if($data_sep['response']['poli'] != null){ ?>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="input-group">
							<span class="input-group-addon">
								<label><input type="checkbox" id="chkpoliesekutif" <?=$data_sep['response']['poliEksekutif'] == 1 ? ' checked' : '';?> value=1> Eksekutif</label>
							</span>	
							<input type='text' disabled class='form-control' value='<?= $data_sep['response']['poli']?>'>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP yang Melayani <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">						
						<select id='dpjp' name='dpjp' class='form-control' value='<?= $data_sep['response']['dpjp']['kdDPJP']?>' >
							<?php if($dpjp['metaData']['code'] == 200){ ?>
								<?php foreach($dpjp['response']['list'] as $dokter){ ?>
									<option value='<?= $dokter['kode']?>' <?=$data_sep['response']['dpjp']['kdDPJP'] ==  $dokter['kode'] ? ' selected' : '';?>><?= $dokter['nama']?></option>
								<?php } ?>
							<?php }else{ ?>
								
							<?php } ?>
						</select>
					</div>
				</div>
				<?php }?>
				<?php if($rujukan['metaData']['code'] == 200){
				?>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Asal Rujukan <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type='text' disabled class='form-control' value='Faskes Tingkat <?=$rujukan['response']['asalFaskes']?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">PPK Asal Rujukan <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type='text' disabled class='form-control' value='<?=$rujukan['response']['rujukan']['provPerujuk']['nama']?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label"><label style="color:gray;font-size:x-small">(yyyy-mm-dd)</label>Tgl.Rujukan<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<input type='date' disabled class='form-control' value='<?=$rujukan['response']['rujukan']['tglKunjungan']?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">No. Rujukan <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type='text' disabled class='form-control' value='<?=$rujukan['response']['rujukan']['noKunjungan']?>'>
					</div>
				</div>
				<?php } ?>
				<?php if($data_sep['response']['kontrol']['kdDokter'] != null){?>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">No.Surat Kontrol/SKDP<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type='text' disabled class='form-control' value='<?=$data_sep['response']['kontrol']['noSurat']?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP Pemberi Surat SKDP/SPRI <label style="color:red;font-size:small">*</label></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type='text' disabled class='form-control' value='<?=$data_sep['response']['kontrol']['nmDokter']?>'>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label"><label style="color:gray;font-size:x-small">(yyyy-mm-dd)</label>Tgl.Sep<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<input type='date' disabled class='form-control' value='<?=$data_sep['response']['tglSep']?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">No. MR<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-5 col-sm-5 col-xs-12">
						<div class="input-group">
							<input type="text" class="form-control" id="txtnomr" name='nomr' value='<?=$data_sep['response']['peserta']['noMr']?>' placeholder="ketik nomor MR" maxlength="10">
							<span class="input-group-addon">
								<label><input type="checkbox" id="chkCOB" <?=$data_sep['response']['informasi']['cob'] == 1 ? ' checked' : 'disabled';?>> Peserta COB</label>
							</span>
						</div>
					</div>
				</div>
				<?php if($data_sep['response']['jnsPelayanan'] == 'Rawat Inap'){ ?>
				<div class="form-group">
					  <label class="col-sm-3 control-label">Hak Kelas</label>
					  <div class="col-sm-4">
	
						<select id='kelas' name='Rawat[idkelas]'  class='form-control'>
							<?php foreach($kelas as $k): ?>
								<option value='<?= $k->id?>'><?= $k->kelas?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
						<div class="col-md-3"><input type="checkbox" <?=$data_sep['response']['klsRawat']['klsRawatNaik'] > 0 ? ' checked' : '';?> id="naik_kelas" name="naik_kelas" value="1">
						<label for="operasi">Naik Kelas ?</label></div>
				</div>
				<div id='naik_kelas_layout' class="form-group">
					  <label class="col-sm-3 control-label"></label>
		 
				<div class='col-md-8'>
					<div style=' width:100%; padding-top:10px; padding-left:10px; padding-bottom:10px; min-height:100px; overflow:hidden; background:#eeeeee;'>
						<div class="form-group">
						  <label class="col-sm-3 control-label">Hak Kelas</label>
						  <div class="col-sm-4">

							<select id='kelas_naik' name='kelas_naik'  class='form-control'>
									<option value=''></option>
								<?php foreach($kelasnaik as $kn): ?>
									<option value='<?= $kn->naik?>' <?=$data_sep['response']['klsRawat']['klsRawatNaik'] ==  $kn->naik ? ' selected' : '';?>><?= $kn->kelas?></option>
								<?php endforeach; ?>
							</select>
							
						  </div>
							
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label">Pembiayaan</label>
						  <div class="col-sm-5">

							<select id='pembiayaan' name='pembiayaan'  class='form-control'>											
									<option value=''>--- Pilih ----</option>
									<?php foreach($pembiayaan as $pb){ ?>
										<option value='<?= $pb['id']?>' <?=$data_sep['response']['klsRawat']['pembiayaan'] ==  $pb['id'] ? ' selected' : '';?>><?= $pb['data']?></option>
									<?php } ?>
							</select>
							
						  </div>
							
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Penanggung Jawab</label>
							<div class="col-sm-8">
								<textarea id='penanggungjawab' name='penanggungjawab' class='form-control' rows=4><?= $data_sep['response']['klsRawat']['penanggungJawab'] ?></textarea>
							</div>
						</div>
						</div>
					</div>
				</div>	
				<?php } ?>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?= Select2::widget([
							'name' => 'icdx',
							'id'=>'kdiagnosa',
							// 'value'=> $data_sep['response']['informasi']['cob'],
							'options' => ['placeholder' => $data_sep['response']['diagnosa']],
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
						]);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">No. Telepon<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="form-control" id="txtnomr" name='nohp' value='<?=$peserta['response']['peserta']['mr']['noTelepon']?>' placeholder="ketik nomor MR">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Catatan<label style="color:red;font-size:small">*</label></label>
					<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea class='form-control' name='catatan'><?= $data_sep['response']['catatan']?></textarea>
					</div>
				</div>
				<div class="form-group" style="">
							<label class="col-md-3 control-label">Katarak <input name="Rawat[katarak]"  type="checkbox" id="chkkatarak" <?=$data_sep['response']['katarak'] == 1 ? 'checked' : '';?> value=1></label>
							<div class="col-md-8">
								<p class="text-muted well well-sm no-shadow">Centang Katarak <i class="fa fa-check"></i>, Jika Peserta Tersebut Mendapatkan Surat Perintah Operasi katarak</p>
							</div>
						</div>
						
				<div class="form-group">
					<label class="col-sm-3 control-label">Status Kecelakaan </label>
					<div class="col-sm-6">
						<select id='kecelakaan' name='jaminan' required class='form-control'>											
								<option value=''>-- Silahkan pilih --</option>
								<?php foreach($kecelakaan as $kc){ ?>
								
									<option value='<?= $kc['id']?>' <?=$data_sep['response']['kdStatusKecelakaan'] ==  $kc['id'] ? ' selected' : '';?>><?= $kc['data']?></option>
								<?php } ?>
						</select>					
					</div>
				</div>
					<div id='suplesi' class="form-group">
						<div class='col-md-12'>
							<div style=' width:100%; padding-top:10px; padding-left:10px; padding-bottom:10px; min-height:100px; overflow:hidden; background:#f5f5f5;'>
								<div class="form-group">
								  <label class="col-sm-4 control-label">Tanggal Kejadian</label>
								  <div class="col-sm-4">
										<input type='date' name='tglkejadian' id='tglkejadian'  value='<?=$data_sep['response']['lokasiKejadian']['tglKejadian']?>' class='form-control' max='<?=$data_sep['response']['lokasiKejadian']['tglKejadian']?>'>
								  </div>
									
								</div>
								
								<?php $provinsi = Yii::$app->bpjs->get_provinsi(); ?>
								<div class="form-group">
									  <label class="col-sm-4 control-label">Lokasi Kejadian</label>
									  <div class="col-sm-7">
											<select id='provinsi' name='propinsi' class='form-control'>
												<option value=''>-- Silahkan Pilih --</option>
												<?php foreach($provinsi['response']['list'] as $prov){ ?>
													<option value='<?= $prov['kode']?>'   <?=$data_sep['response']['lokasiKejadian']['kdProp'] ==  $prov['kode'] ? ' selected' : '';?> ><?= $prov['nama']?></option>
												<?php } ?>
											</select>
									  </div>										
								</div>
								<?php $kabupaten = Yii::$app->bpjs->get_kabupaten($data_sep['response']['lokasiKejadian']['kdProp']); ?>
								<div class="form-group">
									  <label class="col-sm-4 control-label"></label>
									  <div class="col-sm-7">
											<select id='idkabupaten' name='kabupaten'  class='form-control'>
												<?php if($data_sep['response']['kdStatusKecelakaan'] > 0){ ?>
												<?php foreach($kabupaten['response']['list'] as $kab){ ?>
													<option value='<?= $kab['kode']?>'   <?=$data_sep['response']['lokasiKejadian']['kdKab'] ==  $kab['kode'] ? ' selected' : '';?> ><?= $kab['nama']?></option>
												<?php } ?>
												<?php } ?>
											</select>
									  </div>										
								</div>
								<?php $kecamatan = Yii::$app->bpjs->get_kecamatan($data_sep['response']['lokasiKejadian']['kdKab']); ?>
								<div class="form-group">
									  <label class="col-sm-4 control-label"></label>
									  <div class="col-sm-7">
											<select id='idkecamatan' name='kecamatan'   class='form-control'>
												<?php if($data_sep['response']['kdStatusKecelakaan'] > 0){ ?>
													<?php foreach($kecamatan['response']['list'] as $kec){ ?>
														<option value='<?= $kec['kode']?>'   <?=$data_sep['response']['lokasiKejadian']['kdKec'] ==  $kec['kode'] ? ' selected' : '';?> ><?= $kec['nama']?></option>
													<?php } ?>
												<?php } ?>
											</select>
									  </div>										
								</div>
								
								<div class="form-group">
									<label class="col-sm-4 control-label"></label>
									<div class="col-sm-7">
										<textarea class='form-control' name='keterangan' placeholder='ketik keterangan kejadian' rows=4><?=$data_sep['response']['lokasiKejadian']['ketKejadian'] ?></textarea>
										<input type='hidden' value=0 name='suplesi'  id='suplesi_bpjs'>
										<input type='hidden' value=0 name='nosep_suplesi'  id='nosep_suplesi'>
									</div>
								</div>
								</div>
						</div>
						</div>
						<input type='hidden' id='tglkejadian_value'>
						<input type='hidden' id='jaminan_value'>
						<input type='hidden' id='provinsi_value'>
						<input type='hidden' id='idkab_value'>
				
				
			</div>
			<div class='box-footer'>
				<button type="submit" id='confirm2' class="btn btn-info pull-left ">Simpan</button>
				<a id='confirm2' href='<?= Url::to(['/data-kunjungan-bpjs'])?>' class="btn btn-danger pull-right ">Batal</a>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
<?php

Modal::begin([
	'id' => 'mdPasien',
	'header' => '<h5>Data Penjaminan an. '.$peserta['response']['peserta']['nama'].'</h5>',
	'footer' => '  <button type="button" id="btnKasusKLLBaru" class="btn btn-primary pull-left"> Kasus KLL Baru</button> <button type="button" id="btnTutupJaminan" class="btn btn-danger pull-right"> Batal</button>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_form-kecelakaan',['nobpjs'=>$peserta['response']['peserta']['noKartu'],'tgl'=>$data_sep['response']['tglSep']]).'</div>';
 
Modal::end();

$urlKab = Url::to(['admisi/get-kab']);
$urlKec = Url::to(['admisi/get-kec']);
$this->registerJs("
	kelasrawatnaik = '{$data_sep['response']['klsRawat']['klsRawatNaik']}';
	if(kelasrawatnaik > 0){
		$('#naik_kelas_layout').show();
	}else{
		$('#naik_kelas_layout').hide();
	}
	$('#admisi-ranap').hide();
	$('#confirm22').hide();
	$('#katarak_info').hide();
	$('#suplesi').hide();
	$('#naik_kelas').on('change', function(event){
		if($(this).is(':checked')){
			kelas = $('#kelas_naik').val();
			$('#kelas_naik').val('');
			// alert(kelas);
			$('#naik_kelas_layout').show();
		}else{
			$('#kelas_naik').val('');
			$('#naik_kelas_layout').hide();
			$('#pembiayaan').val('');
		}
	});
	$('#kecelakaan').on('change', function(event){
		$('#provinsi_value').val('');
		$('#idkab_value').val('');
		$('#idkec_value').val('');
		$('#tglkejadian_value').val('');
		$('#keterangan').val('');
	});
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
		diagnosa = $('#kdiagnosa').val();
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
			}else if(jaminan < 0){
				
				
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
