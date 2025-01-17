<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\RawatCppt;
use common\models\RawatImplementasi;
use common\models\SoapDiagnosajenis;
use common\models\RawatPermintaanPindah;
use common\models\RawatAwalinap;
use common\models\SoapRajalicdx;
use common\models\OperasiTindakan;
$tanggal = date('Y-m-d',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
$awalinap = RawatAwalinap::find()->where(['idrawat'=>$model->id])->one();
$cpptlist = RawatCppt::find()->where(['idrawat'=>$model->id])->all();
$implemenlist = RawatImplementasi::find()->where(['idrawat'=>$model->id])->all();
$ppindah = RawatPermintaanPindah::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->count();
$diagnosa = SoapRajalicdx::find()->where(['idrawat'=>$model->id])->orderBy(['idjenisdiagnosa'=>SORT_ASC])->all();
$dc = SoapRajalicdx::find()->where(['idrawat'=>$model->id])->one();
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
		<div class="box box-widget widget-user">
			<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
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
				<li><a href="#tab_3" title="Histori" data-toggle="tab" id="tabHistori"><span class="fa fa-list"></span></a></li>
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
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->tgllahir ?>  (<?= $pasien->usia_tahun?>th)</span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->tglmasuk ?> </span>
						</li>
						<?php if($model->status != 2){ ?>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->tglpulang ?> </span>
						</li>
						<?php } ?>
						<li class="list-group-item">
							<span class="fa fa-user-md"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->dokter->nama_dokter ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-bed"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->ruangan->nama_ruangan ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa  fa-money"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->bayar->bayar ?> </span>
						</li>
					
					</ul>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab_2">
					<div id="divHistori" class="list-group">
					<?php foreach($pindah_list as $pl): ?>
					<a href="#" class="list-group-item"> <h5 class="list-group-item-heading"><b><u>Ruang <?= $pl->ruangan->nama_ruangan?></u></b></h5>  <small><?= $pl->tgl_masuk?> - <?= $pl->tgl_keluar?></small><p class="list-group-item-text"><small></small></p><p class="list-group-item-text"><small><?= $pl->los?> hari</small></p></a>
					<?php endforeach; ?>
					</div>
				</div>
				<div class="tab-pane" id="tab_3">
					<div id="divHistori" class="list-group">
					<?php foreach($rawat_list as $rl): ?>
					<a href="#" data-toggle="modal" data-target="#mdHistory<?= $rl->id?>" class="list-group-item"> <h5 class="list-group-item-heading"><b><u><?= $rl->idrawat?></u></b></h5>  <small><?= $rl->jenisrawat->jenis?></small><p class="list-group-item-text"><small></small></p><p class="list-group-item-text"><small><?= $rl->poli->poli?></small></p><p class="list-group-item-text"><small> Tgl. <?= $rl->tglmasuk?></small></p><p class="list-group-item-text"><small><?= $rl->dokter->nama_dokter?></small></p></a>
					<?php endforeach; ?>
					</div>
					<div>
						<button type="button" id="btnHistori" class="btn btn-xs btn-default btn-block"><span class="fa fa-cubes"></span> Histori</button>
					</div>
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
		<div class="box">
			<div class="box-header"><h3></h3></div>
			<div class="box-body">
				<?php if($ppindah > 0){ ?>
				<a class="btn btn-warning disabled">Proses Pindah Ruangan</a>
				<?php }else{ ?>
					<?php if($model->status == 2){ ?>
						<a  data-toggle="modal" data-target="#mdPindah" class='btn btn-warning'>Pindah Ruangan</a>
					<?php }?>
				<?php }?>
				<?php if($model->status == 2){ ?>
				<a class='btn btn-default' data-toggle="modal" data-target="#mdPulang">Pulang</a>
			<a href='<?= Url::to(['keperawatan/rincian?id='.$model->id])?>' class='btn btn-info'>Rincian</a>
				<a href='<?= Url::to(['keperawatan/operasi/'.$model->id])?>' class='btn btn-danger'>Operasi</a>
				<?php }else{ ?>
				<a href='<?= Url::to(['keperawatan/rincian?id='.$model->id])?>' class='btn btn-info'>Rincian</a>
				<a href='<?= Url::to(['keperawatan/ringkasan-pulang?id='.$model->id])?>' target='_blank' class='btn btn-primary'>Ringkasan Pulang</a>
				<?php } ?>
				<hr>
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li  class="active" class=""><a href="#tab_asesmen" data-toggle="tab" aria-expanded="false">Asesmen Awal</a></li>
						<li  class="" class=""><a href="#tab_cppt" data-toggle="tab" aria-expanded="false">CPPT</a></li>
						<li class=""><a href="#tab_implementasi" data-toggle="tab" aria-expanded="false">Implementasi</a></li>
						<li class=""><a href="#tab_tindakandokter" data-toggle="tab" aria-expanded="false">Tindakan</a></li>
						<li class=""><a href="#tab_obat" data-toggle="tab" aria-expanded="false">Obat Obatan</a></li>
						<li class=""><a href="#tab_penunjang" data-toggle="tab" aria-expanded="false">Pengantar</a></li>
						<li class=""><a href="#tab_operasi" data-toggle="tab" aria-expanded="false">Operasi</a></li>
					</ul>
					<div class="tab-content">
					    						<div class="tab-pane" id="tab_operasi">
						<h4>List Operasi</h4>
						<table class='table table-bordered'>
							<tr>
								<th>Tgl Operasi</th>
								<th>Kode OK</th>
								<th>Diagnosa Pra Bedah</th>
								<th>ICD X</th>
								<th>ICD IX</th>
								<th>#</th>
							</tr>
							<?php foreach($operasi as $ok){ ?>
							<tr>
								<td><?= $ok->tgl_ok  ?></td>
								<td><?= $ok->kode_ok  ?></td>
								<td><?= $ok->diagnosisprabedah  ?></td>
								
								<td><?= $ok->icd10  ?></td>
								<td><?= $ok->icd9 ?></td>
								<td>
									<?php if($ok->status == 1){echo'<a class="btn btn-danger btn-xs" href="'.Url::to(['hapus-ok?id='.$ok->id]).'">Hapus</a>';}else{ ?>
									<a data-toggle="modal" data-target="#mdOK<?= $ok->id?>" class='btn btn-primary btn-xs'>Lihat</a>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</table>
						<?php foreach($operasi as $ok){
							$okTindakan = OperasiTindakan::find()->Where(['idok'=>$ok->id])->all();
							Modal::begin([
									'id' => 'mdOK'.$ok->id,
									'header' =>$ok->kode_ok ,
									'size'=>'modal-lg',
									'options'=>[
										'data-url'=>'transaksi',
										'tabindex' => ''
									],
								]);

									echo '<div class="modalContent">'. $this->render('_formOk', ['model'=>$model,'okTindakan'=>$okTindakan ]).'</div>';
									 
									Modal::end();
						} ?>
						</div>
						<div class="tab-pane active" id="tab_asesmen">
							<?php if(!$awalinap){ ?>
								<?php if($model->status == 2){ ?>
								<a class='btn btn-primary' data-toggle="modal" data-target="#mdAwal">Tambah Asesmen Awal</a>
								<?php } ?>
							<?php }else{ ?>
								<div class='row'>
									<div class='col-md-6'>
										<table class="table table-bordered">
											<tr>
												<th width=200>Anamnesa</th>
												<td><?= $awalinap->anamnesa ?></th>
												<td width=30><a class='btn btn-primary btn-xs pull-right' data-toggle="modal" data-target="#mdInap">Edit</a></th>
											</tr>
											<tr>
												<th width=200>Keadaan Umum</th>
												<td colspan=2><?= $awalinap->keadaans->keadaan ?></th>
											</tr>
											<tr>
												<th width=200>kesadaran</th>
												<td><?= $awalinap->kesadarans->kesadaran  ?></th>
											</tr>
											<tr>
												<th width=100>Alergi</th>
												<td><?= $awalinap->alergi ?></th>
											</tr>
											<tr>
												<th width=100>Tekanan Darah</th>
												<th><?= $awalinap->distole ?> / <?= $awalinap->sistole ?> mmHg</th>
											</tr>
											<tr>
												<th width=100>Suhu</th>
												<td><?= $awalinap->suhu ?> C</td>
											</tr>
											<tr>
												<th width=100>SpO2</th>
												<td><?= $awalinap->spo2 ?>%</td>
											</tr>
											<tr>
												<th width=100>Nadi</th>
												<td><?= $awalinap->nadi ?> x/menit</td>
											</tr>
											<tr>
												<th width=100>Respirasi</th>
												<td><?= $awalinap->respirasi ?> x/menit</td>
											</tr>
											<tr>
												<th width=100>Tinggi</th>
												<td><?= $awalinap->tinggi ?> cm</td>
											</tr>
											<tr>
												<th width=100>Berat</th>
												<td><?= $awalinap->berat ?> kg</td>
											</tr>
										</table>
									</div>
									<div class='col-md-6'>
										<table class='table table-bordered'>
											<tr>
												<th>Diagnosa</th>
												<th>ICD X</th>
												<th>Jenis</th>
											</tr>
											<?php if(!$dc){ ?>
											<tr>
												<td colspan=3>Belum ada diagnosa terinput</td>
											</tr>
											<tr>
												<td colspan=3>
												<?php if($model->status == 2){ ?>
													<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah Diagnosa</button>
												<?php } ?>
												</td>
											</tr>
											<?php }else{ ?>
											<?php foreach($diagnosa as $d){ ?>
											<tr>
												<td><a href='<?= Url::to(['/poliklinik/delete-diagnosa?id='.$d->id])?>' class='btn btn-xs btn-default' id='btn-diagnosa-<?= $d->id?>'><?= $d->diagnosa ?></a></td>
												<td><?= $d->icdx ?></td>
												<td><?= $d->jenis->jenis ?></td>
											</tr>
											<?php 
												$this->registerJs("

													
													$('#btn-diagnosa-{$d->id}').on('click', function(event){
														age = confirm('Yakin Untuk Menghapus Diagnosa'+'{$d->diagnosa}');
														if(age == true){
															 return true;
														} else {
															event.preventDefault();
														}
													});
													
													

												", View::POS_READY);

											?>
											<?php } ?>
											<tr>
												<td colspan=3>
													<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah Diagnosa</button>
												</td>
											</tr>
											<?php } ?>
										</table>
									</div>
								</div>
								
								
							<?php } ?>
						</div>
					<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_cppt">
						<?php if($model->status == 2){ ?>
						<a class='btn btn-success btn-sm' data-toggle="modal" data-target="#mdTemplate">Cppt</a><hr>
						<?php } ?>
						<div class='row'>
							<div class='col-md-6'>
								<select id='ppa' class='form-control'>
									<option value=''>-- Profesi (PPA) -- </option>
									<option  value='Dokter'>Dokter</option>
									<option value='Perawat'>Perawat</option>
									<option value='Bidan'>Bidan</option>
								</select>
							</div>
							<div class='col-md-6'>
								<input type='date' id='tgl-ppa' class='form-control'>
							</div>
						</div>
						
						<br>
						<div z-index=0 id='cari-ppa'></div>
						<div id='awal'>
						<table class='table table-bordered'>
							<tr>
								<th width=100>Tanggal</th>
								<th width=100>Profesi (PPA)</th>
								<th colspan=2>Hasil Asesmen Pasien , Intruksi & Tindak Lanjut</th>
								<th>Hapus</th>
							</tr>
							<?php if($cpptlist){
									foreach($cpptlist as $cl):
							?>
							<tr>
								<td rowspan=5>
									<?php if($cl->tgl != $tanggal){echo $cl->tgl.'<br>'.date('H:i',strtotime($cl->jam));}else{ ?>
									<a data-toggle="modal" data-target="#mdCp<?=$cl->id?>" href=''><?= $cl->tgl ?><br> (<?= date('H:i',strtotime($cl->jam))?>)</a>
									<?php } ?>
									<br>
										<?= Html::a(
												'<span class="label label-danger">Hapus</span>', 
												Url::to(['hapus-cppt?id='.$cl->id]),
												[
												'title' => Yii::t('yii', 'Delete'),
												'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
												'data-method' => 'post',
												]); ?>
							
								</td>
								<td rowspan=5><?= $cl->profesi ?>
								</td>
							</tr>
							<tr>
								<th width=10>S</td>
								<td><?= $cl->subjektif?></td>
							</tr>
							<tr>
								<th>O</td>
								<td><?= $cl->objektif?></td>
							</tr>
							<tr>
								<th>A</td>
								<td><?= $cl->asesmen?></td>
							</tr>
							<tr>
								<th>P</td>
								<td><?= $cl->plan?></td>
							</tr>
							<tr>
								<td colspan=4></td>
							</tr>
								
							<?php
								endforeach;
							}?>
							
						</table>
						</div>
						
						</div>
						<div class="tab-pane" id="tab_implementasi">
							<?php if($model->status == 2){ ?>
								<a class='btn btn-success' data-toggle="modal" data-target="#mdImplementasi">Implementasi</a>
							<?php } ?>
							<hr>
							<div class='row'>							
								<div class='col-md-6'>
									<input type='date' id='tgl-implementasi' class='form-control'>
								</div>
							</div>
							<br>
							<div id='implementasi'></div>
							<div id='table-implementasi'>
							<table class='table table-bordered'>
							<tr>
								<th width=100>Tgl & Jam</th>
								<th >Implementasi</th>
								<th width=100>Petugas</th>
							</tr>
							<?php if($implemenlist){
									foreach($implemenlist as $il):
							?>
							<tr>
								<td>
								
								<?php if($il->tgl != $tanggal){echo date('d/m/Y',strtotime($il->tgl)).'<br>'.date('H:i',strtotime($il->jam));}else{ ?>
								
								<a data-toggle="modal" data-target="#mdImp<?=$il->id?>" href=''><?= date('d/m/Y',strtotime($il->tgl)) ?><br> (<?= date('H:i',strtotime($il->jam))?>) </a>
								<?php } ?>
								<br>
								<?= Html::a(
												'<span class="label label-danger">Hapus</span>', 
												Url::to(['hapus-imp?id='.$il->id]),
												[
												'title' => Yii::t('yii', 'Delete'),
												'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
												'data-method' => 'post',
												]); ?>
								</td>
								<td><?= $il->implementasi ?></td>
								<td><?= $il->user->userdetail->nama?></td>
							</tr>
							<?php
								endforeach;
							}?>
							
						</table>
						</div>
						</div>
					<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_tindakandokter">
							<table class='table table-bordered'>
								<?php if($model->status == 2){ ?>
								<tr><th colspan=4><a id='tindakanDokter' class='btn btn-success'>Tambah Tindakan</a> <a id='tindakanPenunjang' class='btn btn-primary'>Tambah Penunjang</a></th></tr>
								<tr>
									<td colspan=4>
										<div id='divTindakan'>
											<?= 
												$this->render('_formTddokter', ['model'=>$model,'tindakan'=>$tindakan ])
											?>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan=4>
										<div id='divPenunjang'>
											<?= 
												$this->render('_formTdpenunjang', ['model'=>$model,'tarif_rinci'=>$tarif_rinci ])
											?>
										</div>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<th>No</th>
									<th>Tgl</th>
									<th>Tindakan</th>
									<th>Dokter</th>
									<th>Bayar</th>
									<th>#</th>
								</tr>
								<?php $noT=1; if(count($tindakanDokter) < 1){ ?>
								<tr>
									<td colspan= 4>Belum Ada Tindakan</td>
								</tr>
								<?php }else{ ?>
								<?php foreach($tindakanDokter as $td): ?>
								<tr>
									<td><?= $noT++?></td>
									<td><?= $td->tgl ?> <?= date('H:i',strtotime($td->jam)) ?></td>
									<td><?= $td->tindakans->nama_tarif ?></td>
								
									
									<td><?php if($td->iddokter != null){echo $td->dokter->nama_dokter;} ?></td>
										<td><?= $td->bayar->bayar ?></td>
									<td><a href='<?= Url::to(['keperawatan/hapus-tindakan?id='.$td->id])?>' class='btn btn-xs btn-danger'>Hapus</a></td>
								</tr>
								<?php endforeach; ?>
								<?php } ?>
								<tr>
									<th colspan= 4>Penunjang</th>
								</tr>
								<?php $noTr=1; if(count($tarif_rinci_list) < 1){ ?>
								<tr>
									<td colspan= 4>Belum Ada Penunjang</td>
								</tr>
								<?php }else{ ?>
								<?php foreach($tarif_rinci_list as $tr): ?>
								<tr>
									<td><?= $noTr++?></td>
									<td><?= $tr->tgl ?> </td>
									<td><?= $tr->tindakan->nama_tarif ?></td>
									
									<td><?php if($tr->iddokter != null){echo $tr->dokter->nama_dokter;} ?></td>
									<td><a href='<?= Url::to(['keperawatan/hapus-penunjang?id='.$tr->id])?>' class='btn btn-xs btn-danger'>Hapus</a></td>
								</tr>
								<?php endforeach; ?>
								<?php } ?>
							</table>
						</div>
						
						<div class="tab-pane" id="tab_obat">
							<table class='table table-bordered'>
								<?php if($model->status == 2){ ?>
								<tr><th colspan=4><a id='tindakanDokter' data-toggle="modal" data-target="#mdResep"  class='btn btn-danger'>Tambah Resep</a></th></tr>
								<?php } ?>
								<tr>
									<td colspan=4>
										<div id='divTindakan'>
											
										</div>
									</td>
								</tr>
								
								<tr>
									<th>No</th>
									<th>Tgl</th>
									<th>Kode Resep</th>
									<th>Dokter</th>
									<th>#</th>
								</tr>
								<?php $no=1; foreach($list_resep as $lr): ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $lr->tgl_resep ?></td>
									<td><?= $lr->kode_resep ?></td>
									<td><?= $model->dokter->nama_dokter ?></td>
									<td><?php if($lr->status == 1){ ?><a href='<?= Url::to(['hapus-resep?id='.$lr->id])?>' class='btn btn-warning btn-sm'>Hapus</a><?php } ?><a href='<?= Url::to(['tambah-obat?id='.$lr->id])?>' class='btn btn-primary btn-sm'>Lihat</a></td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
						<div class="tab-pane" id="tab_penunjang">
							<table class='table table-bordered'>
								<?php if($model->status == 2){ ?>
								<tr><th colspan=4><a data-toggle="modal" data-target="#mdRadiologi" id='tindakanRad' class='btn btn-danger'>Radiologi</a></th></tr>
								<?php } ?>
								<tr>
									<td colspan=4>
										<div id='divTindakan'>
											
										</div>
									</td>
								</tr>
								
								<tr>
									<th>No</th>
									<th>Tgl</th>
									<th>Klinis</th>
									<th>Tindakan</th>
									<th>Dokter Pengirim</th>
									<th>#</th>
								</tr>
								<?php $norad=1; if(count($soapradiologilist) < 1){ ?>
								<tr>
									<td colspan=5>Belum ada permintaan pemeriksaan radiologi</td>
								</tr>
								<?php }else { 
									foreach($soapradiologilist as $srl):
								?>
								<tr>
									<td><?= $norad++ ?></td>
									<td><?= $srl->tgl_permintaan ?></td>
									<td><?= $srl->klinis ?></td>
									<td><?= $srl->tindakan->nama_tindakan ?></td>
									<td><?= $srl->dokter->nama_dokter ?></td>
									<td>
									    <?php if($srl->idhasil == null){ ?>
									    <a href='<?= Url::to(['/keperawatan/delete-radiologi?id='.$srl->id])?>' class='btn btn-danger btn-xs'>Hapus</a></td>
									    <?php }else{ ?>
									   <a data-toggle="modal" data-target="#mdRad<?= $srl->id?>" class='btn btn-primary btn-xs'>Lihat</a>
									    <?php } ?>
								</tr>
								<?php endforeach; ?>
								<?php }?>
							</table>
							<table class='table table-bordered'>
								<?php if($model->status == 2){ ?>
								<tr><th colspan=4><a data-toggle="modal" data-target="#mdLab" id='tindakanLab' class='btn btn-warning'>Laboratorium</a></th></tr>
								<?php } ?>
								<tr>
									<td colspan=4>
										<div id='divTindakan'>
											
										</div>
									</td>
								</tr>
								
								<tr>
									<th>No</th>
									<th>Tgl</th>
									<th>Tindakan</th>
									<th>Dokter</th>
									<th>#</th>
								</tr>
								<?php $nolab=1; if(count($soaplablist) < 1){ ?>
								<tr>
									<td colspan=4>Belum ada permintaan pemeriksaan radiologi</td>
								</tr>
								<?php }else { 
									foreach($soaplablist as $sll):
								?>
								<tr>
									<td><?= $nolab++ ?></td>
									<td><?= $sll->tgl_permintaan ?></td>
									<td><?= $sll->pemeriksaan->nama_pemeriksaan ?></td>
									<td><?= $sll->dokter->nama_dokter ?></td>
									<td>
										<?php if($sll->idhasil != null){ ?>
											<a href='<?= Url::to(['laboratorium/hasil-print?id='.$sll->idhasil])?>' class='btn btn-xs btn-success'>Lihat</a>
										<?php } ?>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php }?>
								
							</table>
							<table class='table table-bordered'>
								<?php if($model->status == 2){ ?>
								<tr><th colspan=4><a data-toggle="modal" data-target="#mdFisio" id='tindakanFisio' class='btn btn-primary'>Fisio</a></th></tr>
								<?php } ?>
								<tr>
									<td colspan=4>
										<div id='divTindakan'>
											
										</div>
									</td>
								</tr>
								
								<tr>
									<th>No</th>
									<th>Tgl</th>
									<th>Tindakan</th>
									<th>Dokter</th>
									<th>#</th>
								</tr>
								<?php $nofisio=1; if(count($list_fisio) < 1){ ?>
								<tr>
									<td colspan=4>Belum ada permintaan  Fisio</td>
								</tr>
								<?php }else { 
									foreach($list_fisio as $lf):
								?>
								<tr>
									<td><?= $nofisio++ ?></td>
									<td><?= $lf->tgl ?></td>
									<td><?= $lf->pemeriksaan->nama_tarif ?></td>
									<td><?= $lf->peminta->nama_dokter ?></td>
								</tr>
								<?php endforeach; ?>
								<?php }?>
								
							</table>
						</div>
						
					<!-- /.tab-pane -->
					</div>
					<!-- /.tab-content -->
				</div>
			</div>
		</div>
	</div>
</div>

		<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Diagnosa</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($icdx, 'idjenisrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->idjenisrawat])->label(false) ?>
					<?= $form->field($icdx, 'idrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->id])->label(false) ?>
					<?= $form->field($icdx, 'idrm')->hiddeninput(['maxlength' => true, 'value'=>$model->pasien->id])->label(false) ?>
					<?= $form->field($icdx, 'iduser')->hiddeninput(['maxlength' => true, 'value'=>Yii::$app->user->identity->id])->label(false) ?>
					<?= $form->field($icdx, 'diagnosa')->textarea(['maxlength' => true, ])->label('Diagnosis Klinis') ?>
					<?= $form->field($icdx, 'icdx')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Cari ICD X .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => "https://simrs.rsausulaiman.com/apites/listdiagnosa",
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
					])->label('ICD X');?>
					
					<?= $form->field($icdx, 'idjenisdiagnosa')->dropDownList(ArrayHelper::map(SoapDiagnosajenis::find()->all(), 'id', 'jenis'),['prompt'=>'--Jenis Diagnosa--','required'=>true])->label('Jenis Diagnosa')?>
				
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm-dokter']) ?>
			  </div>
			  <?php ActiveForm::end(); ?>
			</div>
		  </div>
		</div>
<?php

$urlShowAll = Url::to(['keperawatan/show-ppa']);
$urlShowImp = Url::to(['keperawatan/show-imp']);
$this->registerJs("	
	$('#divTindakan').hide();
	$('#divPenunjang').hide();
	$('#tindakanDokter').on('click',function(){
		$('#divTindakan').show();
		$('#divPenunjang').hide();
	});
	$('#tindakanPenunjang').on('click',function(){
		$('#divTindakan').hide();
		$('#divPenunjang').show();
	});

	$('#ppa').on('change',function(){
		ppa = $(this).val();
			tgl = $('#tgl-ppa').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'ppa='+ppa+'&idrawat='+'{$model->id}'+'&tgl='+tgl,
				
				success: function (data) {
					$('#cari-ppa').show();
					$('#cari-ppa').html(data);					
					$('#awal').hide();					
					console.log(data);					
				},
				
			});
	});
	$('#tgl-ppa').on('change',function(){
		ppa = $('#ppa').val();
		tgl = $(this).val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'ppa='+ppa+'&idrawat='+'{$model->id}'+'&tgl='+tgl,
				
				success: function (data) {
					$('#cari-ppa').show();
					$('#cari-ppa').html(data);					
					$('#awal').hide();					
					console.log(data);					
				},
				
			});
	});
	$('#tgl-implementasi').on('change',function(){
		tgl = $(this).val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowImp}',
				data: 'tgl='+tgl+'&idrawat='+'{$model->id}',
				
				success: function (data) {
					$('#implementasi').show();
					$('#implementasi').html(data);					
					$('#table-implementasi').hide();					
					console.log(data);					
				},
				
			});
	});
", View::POS_READY);

 Modal::begin([
	'id' => 'mdImplementasi',
	'header' => '<h3>Asesmen Awal</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formImplementasi', ['implementasi'=>$implementasi,'model'=>$model ]).'</div>';

Modal::end();

Modal::begin([
	'id' => 'mdResep',
	'header' => '<h3>Tambah Resep</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formResep', ['model'=>$model,'resep'=>$resep ]).'</div>';
 
Modal::end();

if($implemenlist){
	foreach($implemenlist as $il):
	Modal::begin([
	'id' => 'mdImp'.$il->id,
	'header' => $il->id,
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

	echo '<div class="modalContent">'. $this->render('_formEdit-imp', ['il'=>$il,'model'=>$model ]).'</div>';
	 
	Modal::end();
	endforeach;
	
}

foreach($soapradiologilist as $srl):
if($srl->idhasil != null){
Modal::begin([
	'id' => 'mdRad'.$srl->id,
	'header' => '<h3>Hasil Radiologi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formHasilRad', ['model'=>$model,'srl'=>$srl]).'</div>';
 
Modal::end();
}
endforeach;
Modal::begin([
	'id' => 'mdTemplate',
	'header' => '<h3>Input Pemeriksaan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formCppt', ['cppt'=>$cppt,'model'=>$model ]).'</div>';
 
Modal::end();
if($awalinap){
	Modal::begin([
	'id' => 'mdInap',
	'header' => '<h3>Asesmen Awal Keperawatan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

	echo '<div class="modalContent">'. $this->render('_formEdit-awal', ['awalinap'=>$awalinap,'model'=>$model ]).'</div>';
	Modal::end();
}
if($cpptlist){
	foreach($cpptlist as $cl):
	Modal::begin([
	'id' => 'mdCp'.$cl->id,
	'header' => $cl->id,
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

	echo '<div class="modalContent">'. $this->render('_formEdit-cppt', ['cl'=>$cl,'model'=>$model ]).'</div>';
	 
	Modal::end();
	endforeach;
	
}


Modal::begin([
	'id' => 'mdPindah',
	'header' => '<h3>Pindah Ruangan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formPindah', ['pindah'=>$pindah,'model'=>$model ]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'mdAwal',
	'header' => '<h3>Asesmen Awal</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formAsesmen', ['model'=>$model,'awal'=>$awal ]).'</div>';
 
Modal::end();

Modal::begin([
	'id' => 'mdRadiologi',
	'header' => '<h3>Radiologi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formRadiologi', ['model'=>$model,'soapradiologi'=>$soapradiologi]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'mdLab',
	'header' => '<h3>Radiologi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
echo '<div class="modalContent">'. $this->render('_formLab', ['model'=>$model,'soaplab'=>$soaplab]).'</div>';
Modal::end();
Modal::begin([
	'id' => 'mdFisio',
	'header' => '<h3>Fisio Terapi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
echo '<div class="modalContent">'. $this->render('_formFisio', ['model'=>$model,'fisio'=>$fisio]).'</div>';
Modal::end();
Modal::begin([
	'id' => 'mdPulang',
	'header' => '<h3>Ringkasan Pasien Pulang</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
echo '<div class="modalContent">'. $this->render('_formPulang', ['model'=>$model,'pulang'=>$pulang]).'</div>';
Modal::end();
foreach($rawat_list as $rl):
	Modal::begin([
	'id' => 'mdHistory'.$rl->id,
	'header' => '<h3>Histori Pasien</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
echo '<div class="modalContent">'. $this->render('_formHistory', ['model'=>$model,'rl'=>$rl]).'</div>';
Modal::end();
endforeach;
?>