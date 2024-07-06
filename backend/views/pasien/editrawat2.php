<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RawatBayar;
use yii\helpers\ArrayHelper;
use common\models\RawatJenis;
use common\models\Poli;
use kartik\date\DatePicker;
use common\models\Dokter;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use common\models\KategoriPenyakit;
use common\models\RuanganKelas;
use common\models\RuanganBed;
use common\models\Ruangan;
$ruangan = Ruangan::find()->where(['id'=>$model->idruangan])->all();
$kat_penyakit = KategoriPenyakit::find()->all();
$kelas = RuanganKelas::find()->all();
$bed = RuanganBed::find()->where(['id'=>$model->idbed])->all();
$kunjungan = [
	['id'=>'1','data'=>'Kunjungan Baru'],
	['id'=>'2','data'=>'Kunjungan Lama'],
	['id'=>'3','data'=>'Post Ranap'],
];
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pasiens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$bayar = RawatBayar::find()->all();
$jenisrawat = RawatJenis::find()->where(['ket'=>1])->all();
$jenisrawat = RawatJenis::find()->where(['ket'=>1])->all();
$poli = Poli::find()->all();
$dokter = Dokter::find()->where(['idpoli'=>$model->idpoli])->all();
?>
<br>
<div class="pasien-view">
	
	
			<div class='row'>
				<div class='col-md-3'>
				<div class='box box-primary'>
				<div class='box-header with-border'>
					<h3>Edit Berobat</h3>
				</div>
				<div class='box-body'>
					<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
						],
					]) ?>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'idrawat',
							'idkunjungan',
							'poli.poli',
							'tglmasuk',
							'bayar.bayar',
						],
					]) ?>
					
					
				</div>
				</div>
				</div>
				
				<div class='col-md-9'>
				<div class='box box-warning'>
				<div class='box-header with-border'>
					<h3>Data Kunjungan</h3>
				</div>
				<div class='box-body'>
				<?php if($model->idjenisrawat != 2){ ?>
				<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">No SEP</label>
							<div class="col-md-5 col-sm-5 col-xs-12" style='margin-left:-15px;'>
								<input type='text' id ='nosep' value='<?= $model->no_sep?>' name='nosep' class='form-control'>
							</div>
							
						</div>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl Layanan</label>
							<div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<input type='date' id ='tglmasuk' value='<?= date('Y-m-d',strtotime($model->tglmasuk)) ?>' name='tglrawat' class='form-control'>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12" style='margin-left:-15px;'>
								<select name='bayar' class='form-control'>
									<?php foreach($bayar as $b){ ?>
										<option value='<?= $b->id?>'  <?= $b->id ==  $model->idbayar ? ' selected' : '';?>  ><?= $b->bayar?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Rawat</label>
							<div class="col-md-4 col-sm-4 col-xs-12" style='margin-left:-15px;'>
								<select name='jenisrawat' id ='idjenisrawat' class='form-control'>
									<?php foreach($jenisrawat as $jr){ ?>
										<option value='<?= $jr->id?>'  <?= $jr->id ==  $model->idjenisrawat ? ' selected' : '';?>  ><?= $jr->jenis?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<select name='poli' id ='idpoli' class='form-control'>
									<?php foreach($poli as $p){ ?>
										<option value='<?= $p->id?>'  <?= $p->id ==  $model->idpoli ? ' selected' : '';?>  ><?= $p->poli?></option>
									<?php } ?>
								</select>
							</div>
							
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Dokter</label>
							<div class="col-md-6 col-sm-6 col-xs-12"  style='margin-left:-15px;'>
								<select name='dokter' id ='iddokter' class='form-control'>
									<?php foreach($dokter as $dr){ ?>
										<option value='<?= $dr->id?>'  <?= $dr->id ==  $model->iddokter ? ' selected' : '';?>  ><?= $dr->nama_dokter?></option>
									<?php } ?>
								</select>							
							</div>
						</div>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Kunjungan</label>
							<div class="col-md-6 col-sm-6 col-xs-12" style='margin-left:-15px;'>
								<select id='kunjungan' name='kunjungan' class='form-control'>
									<option value=''>-- Pilih Kunjungan --</option>
									<?php foreach($kunjungan as $k){ ?>
										<option value='<?= $k['id'] ?>' <?= $k['id'] ==  $model->kunjungan ? ' selected' : '';?>><?= $k['data']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
							<div class="form-group" >
							<label class="col-md-3 col-sm-3  control-label">Keterangan</label>
							<div class="col-md-5 col-sm-5 col-xs-6" style='margin-left:-15px;'>
							    <textarea class='form-control' name='keterangan'><?= $model->keterangan ?></textarea>
							</div>
							
						</div>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3  control-label">Anggota ?</label>
							<div class="col-md-1 col-sm-1 col-xs-6" style='margin-left:-15px;'>
								<input type="checkbox" id="vehicle1" name="anggota"  <?=$model->anggota == 1 ? ' checked' : '';?>  value="1">
							</div>
							<label class="col-md-1 col-sm-1 control-label">Online?</label>
							<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
								<input type="checkbox" id="vehicle1" name="online" <?=$model->online == 1 ? ' checked' : '';?> value="1">
							</div>
						</div>
					
						<div class="form-group" >
							<label class="col-md-3 col-sm-3  control-label"></label>
							<div class="col-md-6 col-sm-6 col-xs-6" style='margin-left:-15px;'>
								<a class='btn btn-primary btn-xs' id='edit'>Edit</a>
							</div>
							
						</div>
						<div id='edit_ajax'></div>
					<?php ActiveForm::end(); ?>
					<?php }else{ ?>
						<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
							<div class="form-group" >
								<label class="col-md-3 col-sm-3 col-xs-12 control-label">No SEP</label>
								<div class="col-md-5 col-sm-5 col-xs-12" style='margin-left:-15px;'>
									<input type='text' id ='nosep' value='<?= $model->no_sep?>' name='nosep' class='form-control'>
								</div>
								
							</div>
							<div class="form-group" >
								<label class="col-md-3 col-sm-3 col-xs-12 control-label">No SPRI BPJS</label>
								<div class="col-md-3 col-sm-4 col-xs-12" style='margin-left:-15px;'>
									<input type='text' id ='nosep' value='<?= $model->no_suratkontrol?>' name='nosurat' class='form-control'>
								</div>
								<span class="input-group-btn">
									<a href='<?= Url::to(['admisi/print-spri2?id='.$model->id]) ?>' target='_blank' id="button_spri" class="btn btn-warning btn-sm btn-flat">Print SPRI</a>
								</span>
								
							</div>
							<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Dokter</label>
							<div class="col-md-6 col-sm-6 col-xs-12"  style='margin-left:-15px;'>
								<select name='dokter' id ='iddokter' class='form-control'>
									<?php foreach($dokter as $dr){ ?>
										<option value='<?= $dr->id?>'  <?= $dr->id ==  $model->iddokter ? ' selected' : '';?>  ><?= $dr->nama_dokter?></option>
									<?php } ?>
								</select>							
							</div>
						</div>
							<div class="form-group" >
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl Layanan</label>
							<div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<input type='date' id ='tglmasuk' value='<?= date('Y-m-d',strtotime($model->tglmasuk)) ?>' name='tglrawat' class='form-control'>
							</div>
							
							<div class="col-md-4 col-sm-4 col-xs-12" style='margin-left:-15px;'>
								<select name='bayar' class='form-control'>
									<?php foreach($bayar as $b){ ?>
										<option value='<?= $b->id?>'  <?= $b->id ==  $model->idbayar ? ' selected' : '';?>  ><?= $b->bayar?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Rawat</label>
							<div class="col-md-4 col-sm-4 col-xs-12" style='margin-left:-15px;'>
								<input type='text' readonly class='form-control' value='<?= $model->jenisrawat->jenis?>'>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<select name='poli' id ='idpoli' class='form-control'>
									<?php foreach($poli as $p){ ?>
										<option value='<?= $p->id?>'  <?= $p->id ==  $model->idpoli ? ' selected' : '';?>  ><?= $p->poli?></option>
									<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Kategori Penyakit</label>
							<div class="col-md-5 col-sm-5 col-xs-12" style='margin-left:-15px;'>
								<select id='rawat-idjenisperawatan' required name='idjenisperawatan'  class='form-control'>
									<option value=''>-- Kategori Penyakit --</option>
									<?php foreach($kat_penyakit as $kp): ?>
										<option value='<?= $kp->id?>'  <?= $kp->id ==  $model->idjenisperawatan ? ' selected' : '';?> ><?= $kp->kategori?></option>
									<?php endforeach; ?>
								</select>
							</div>							
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Kelas Rawat</label>
							<div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<select id='kelas' name='idkelas'  class='form-control'>
									<?php foreach($kelas as $k): ?>
										<option value='<?= $k->id?>' <?= $k->id ==  $model->idkelas ? ' selected' : '';?>><?= $k->kelas?></option>
									<?php endforeach; ?>
								</select>								
							  </div>
							  <div class="col-md-3 col-sm-3 col-xs-12" style='margin-left:-15px;'>
								<select id='idruangan' name='idruangan'  class='form-control'>
									<?php foreach($ruangan as $r): ?>
										<option value='<?= $r->id?>'><?= $r->nama_ruangan?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Bed</label>
							<div class="col-md-2 col-sm-2 col-xs-12" style='margin-left:-15px;'>
								<select id='bed' required name='idbed'  class='form-control'>
									<?php foreach($bed as $b): ?>
										<option value='<?= $b->id?>' <?= $b->id ==  $model->idbed ? ' selected' : '';?>><?= $b->kodebed?></option>
									<?php endforeach; ?>
								</select>								
							  </div>
							 
						</div>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3  control-label">Anggota ?</label>
							<div class="col-md-1 col-sm-1 col-xs-6" style='margin-left:-15px;'>
								<input type="checkbox" id="vehicle1" name="anggota"  <?=$model->anggota == 1 ? ' checked' : '';?>  value="1">
							</div>
						</div>
						<div class="form-group" >
							<label class="col-md-3 col-sm-3  control-label"></label>
							<div class="col-md-6 col-sm-6 col-xs-6" style='margin-left:-15px;'>
								<a class='btn btn-primary btn-xs' id='edit'>Edit</a>
							</div>
							
						</div>
						<div id='edit_ajax'></div>
						<?php ActiveForm::end(); ?>
					<?php } ?>
					<br>		
				</div>
			</div>
		</div>
	
</div>


<?php
$urlShowAll = Url::to(['pasien/show-dokter']);
$urlShowLayanan = Url::to(['pasien/show-layanan']);
$urlPoli = Url::to(['pasien/listpoli']);
$urlDokter = Url::to(['pasien/listdokter']);
$urlRuangan = Url::to(['pasien/listruangan2']);
$urlBed = Url::to(['pasien/listbed']);
$urlEdit = Url::to(['pasien/cek-edit']);
$this->registerJs("
	$('#tampillayanan').hide();
	$('#edit_ajax').hide();
	$('#confirm2').on('click', function(event){
		age = confirm('Yakin mengedit data ??? ');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#edit').on('click', function(){
		idrawat = '{$model->id}';
		dokter = $('#iddokter').val();
		poli = $('#idpoli').val();
		tgl = $('#tglmasuk').val();
		$.ajax({
			type: 'GET',
			url: '{$urlEdit}',
			data: 'idrawat='+idrawat+'&poli='+poli+'&dokter='+dokter+'&tgl='+tgl,
			beforeSend: function(){
			// Show image container
			$('#loading').show();
			},
			success: function (data) {
				$('#edit_ajax').show();
				$('#edit_ajax').animate({ scrollTop: 0 }, 200);
				$('#edit_ajax').html(data);
				
				console.log(data);
				
			},
			complete:function(data){
			// Hide image container
			$('#loading').hide();
			}
		});
	});
	$('#confirm').on('click', function(event){
		age = confirm('Konfirmasi Untuk Edit Kunjungan berobat');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#confirm2').on('click', function(event){
		age = confirm('Konfirmasi Untuk membuat Kunjungan Baru');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#rawat-idpoli').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-idjenisrawat').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-idbayar').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-tglmasuk').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#iddokter').on('change',function(){
		$(edit_ajax).hide();
	});
	$('#idpoli').on('change',function(){
		$(edit_ajax).hide();
	});
	$('#show-pelayanan').on('click',function(){
		bayar = $('#idbayar').val();
		$('#pasien-ajax').hide();
		jenis = $('#idjenisrawat').val();
		poli = $('#idpoli').val();
		kunjungan = $('#tglmasuk').val();
		$.ajax({
			type: 'GET',
			url: '{$urlShowLayanan}',
			data: 'poli='+poli+'&bayar='+bayar+'&jenis='+jenis+'&kunjungan='+kunjungan,
			beforeSend: function(){
			// Show image container
			$('#loading').show();
			},
			success: function (data) {
				$('#pasien-ajax').show();
				$('#pasien-ajax').animate({ scrollTop: 0 }, 200);
				$('#pasien-ajax').html(data);
				
				console.log(data);
				
			},
			complete:function(data){
			// Hide image container
			$('#loading').hide();
			}
		});
		
	});
	$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			poli = $('#rawat-idpoli').val();
			rm = $('#rawat-no_rm').val();
			jenis = $('#rawat-idjenisrawat').val();
			kunjungan = $('#rawat-tglmasuk').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&rm='+rm+'&jenis='+jenis+'&kunjungan='+kunjungan,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').animate({ scrollTop: 0 }, 200);
					$('#pasien-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});
	$('#idjenisrawat').on('change',function(){
			provinsi = $('#idjenisrawat').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlPoli}',
				data: 'id='+provinsi,
				
				success: function (data) {
					$('#idpoli').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#idpoli').on('change',function(){
			provinsi = $('#idpoli').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlDokter}',
				data: 'id='+provinsi,
				
				success: function (data) {
					$('#iddokter').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	
	$('#kelas').on('change',function(){
			provinsi = $('#kelas').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlRuangan}',
				data: 'id='+provinsi,
				
				success: function (data) {
					$('#idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#idruangan').on('change',function(){
			provinsi = $('#idruangan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlBed}',
				data: 'id='+provinsi,
				
				success: function (data) {
					$('#bed').html(data);
					
					console.log(data);
					
				},
				
			});
	});

", View::POS_READY);


?>



