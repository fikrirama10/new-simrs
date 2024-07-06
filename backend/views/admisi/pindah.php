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
use yii\widgets\ActiveForm;
$kelas = RuanganKelas::find()->all();
$ruangan_list = Ruangan::find()->where(['id'=>0])->all();
$bayar = RawatBayar::find()->all();
?>
<div class='row'>
	<div class='col-md-3'>
		<div class="box box-widget widget-user-2">
			<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
			</div>
			<div class="box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a title="Profile Peserta" href="#tab_1" data-toggle="tab"><span class="fa fa-user"></span></a></li>
						
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<span class="fa fa-sort-numeric-asc"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->nik?></span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-credit-card"></span>  <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->no_bpjs?></span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->tgllahir ?> </span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->usia_tahun ?> Thn </span>
								</li>
							
							</ul>
						</div>
						<!-- /.tab-pane -->
						
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
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Permintaan Pindah Ruangan</h4>
				<hr>
				<table class='table table-bordered'>
					<tr>
						<th>Tgl Permintaan</th>
						<th>Ruangan Asal</th>
						<th>Ruangan Tujuan</th>
					<tr>
					<tr>
						<td><?= date('d/m/Y',strtotime($model->tgl)) ?></td>
						<td><?= $model->ruanganasal->nama_ruangan ?></td>
						<td><?= $model->ruangantujuan->nama_ruangan ?></td>
					</tr>
				</table>
			</div>
			<div class='box-body'>
				<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
					<div class="form-group">
					  <label class="col-sm-4 control-label">Kelas</label>
					  <div class="col-sm-3">
						<select id='kelas2' name='Rawat[idkelas]'  class='form-control'>
							<option value=''>-- Kelas --</option>
							<?php foreach($kelas as $k): ?>
								<option value='<?= $k->id?>'><?= $k->kelas?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
	
					</div>
					<div class="form-group">
					  <label class="col-sm-4 control-label">Ruangan</label>
					  <div class="col-sm-3">
						<select id='rawat-idruangan' name='Rawat[idruangan]'  class='form-control'>
							<?php foreach($ruangan_list as $r): ?>
								<option value='<?= $r->id?>'><?= $r->nama_ruangan?></option>
							<?php endforeach; ?>
						</select>
						
					  </div>
					 <span class="input-group-btn">
						<button type="button" id="show-ruangan" class="btn btn-success btn-sm btn-flat">Cek Tempat Tidur</button>
					</span>
					
					</div>
					<div class="form-group">
							<label class="col-sm-4 control-label">Penanggung</label>
							<div class="col-sm-4">
								<select id='bayar' name='RawatRuangan[idbayar]'  class='form-control'>
									<?php foreach($bayar as $b): ?>
										<option value='<?= $b->id?>'><?= $b->bayar?></option>
									<?php endforeach; ?>
								</select>								
							</div>
	
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
					<?= $form->field($rawat, 'idbed')->hiddeninput(['maxlength' => true,'readonly'=>true,])->label(false) ?>
					<?= $form->field($ruangan, 'idrawat')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$rawat->id])->label(false) ?>
					<?= $form->field($ruangan, 'no_rm')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$rawat->no_rm])->label(false) ?>
					<div class="box-footer">
               <a href='<?= Url::to(['admisi/'])?>' class='btn btn-danger pull-left'>Batal</a>
                <button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
              </div>
				<?php ActiveForm::end(); ?>
			</div>			
		</div>
	</div>
</div>
<?php
$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowRuangan = Url::to(['admisi/show-ruangan']);
$this->registerJs("
	$('#admisi-ranap').hide();
	$('#kelas2').on('change',function(){
			kelas = $('#kelas2').val();
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
	$('#show-ruangan').on('click',function(){
			$('#ruangan-ajax').hide();
			ruangan = $('#rawat-idruangan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowRuangan}',
				data: 'id='+ruangan,
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