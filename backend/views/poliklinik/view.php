<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use common\models\RawatRujukan;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use common\models\SoapRajaldokter;
use common\models\SoapRajalperawat;
use common\models\SoapRajalicdx;
use common\models\OperasiTindakan;
$diagnosa = SoapRajalicdx::find()->where(['idrawat'=>$model->id])->orderBy(['idjenisdiagnosa'=>SORT_ASC])->all();
$dc = SoapRajalicdx::find()->where(['idrawat'=>$model->id])->one();
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h3><a href='<?= Url::to(['/poliklinik'])?>' class='btn btn-default'><i class="fa fa-backward"></i> </a> Data Pasien</h3></div>
			<div class='box-body with-border'>
				<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							[                                             
								'label' => 'Nama Pasien',
								'value' => Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan).'. '. $pasien->nama_pasien.' ('.$pasien->jenis_kelamin.')',
								'captionOptions' => ['tooltip' => 'Tooltip'], 
							],
							'tgllahir',
							'tempat_lahir',
							'nohp',
							[                                                  // the owner name of the model
								'label' => 'Usia Pasien',
								'value' => $pasien->usia_tahun.'thn, '. $pasien->usia_bulan.'bln, '. $pasien->usia_hari.'hr',            
								
								'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
							],
							'hubungan.hubungan',
							'kepesertaan_bpjs',
							'pekerjaan.pekerjaan',
							'darah.golongan_darah',
						],
						
					]) ?>
					<table class='table table-bordered'>
						<?php foreach($rawat as $r): 
						$riwayat_soapdr = SoapRajaldokter::find()->where(['idrawat'=>$r->id])->one();
						$riwayat_soappr = SoapRajalperawat::find()->where(['idrawat'=>$r->id])->one();
						
						?>
						<tr>
							<th>	
								<?php if($r->idjenisrawat != 2){ ?>
								<a data-toggle="modal" data-target="#<?= $r->id ?>Modal" id='kunjungan-<?= $r->id ?>' class='btn btn-block btn-social btn-vk'><i class="fa <?= $r->jenisrawat->icon?>"></i><?=  date('m/d/Y',strtotime($r->tglmasuk)) ?>| <?= $r->jenisrawat->jenis?> - <?php if($r->idjenisrawat != 2){ ?><?= $r->poli->poli ?> <?php } ?></a>
								<input type='hidden' id='input-kunjungan-<?= $r->id ?>' value='<?= $r->id ?>'>
								<?php } ?>
							</th>
						</tr>

						<?php endforeach; ?>
					</table>
			</div>

	
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'><h3>Pemeriksaan Pasien</h3></div>
			<div class='box-body with-border'>
				<div class='row'>
			<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_dokter" data-toggle="tab" aria-expanded="false">Dokter</a></li>
					<li class=""><a href="#tab_perawat" data-toggle="tab" aria-expanded="false"> Perawat</a></li>
					
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_dokter">
						<?= $this->render('_formdokter',[
							'soapdokter' => $soapdokter,
							'soapdoktercount' => $soapdoktercount,
							'perawatsoap' => $perawatsoap,
							'perawatsoapcount' => $perawatsoapcount,
							'icdx' => $icdx,
							'soaptindakan' => $soaptindakan,
							'soapradiologi' => $soapradiologi,
							'soaplab' => $soaplab,
							'model' => $model,
							'rujukan' => $rujukan,
							'soapperawat' => $soapperawat,
							'spri' => $spri,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_perawat">
						<?= $this->render('_formperawat',[
							'perawatsoap' => $perawatsoap,
							'perawatsoapcount' => $perawatsoapcount,
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					
				<!-- /.tab-pane -->
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		</div>
				
			</div>
			<div class='box-footer'>
				
			</div>
		</div>
		<div class='row'>
			<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_diagnosa" data-toggle="tab" aria-expanded="false">Diagnosa Dokter</a></li>
					<li class=""><a href="#tab_tindakan" data-toggle="tab" aria-expanded="false">Tindakan</a></li>
			
					<li class=""><a href="#tab_radiologi" data-toggle="tab" aria-expanded="false">Pengantar Radiologi</a></li>
					<li class=""><a href="#tab_lab" data-toggle="tab" aria-expanded="false">Pengantar Laboratorium</a></li>
					<li class=""><a href="#tab_obat" data-toggle="tab" aria-expanded="false">Obat Obatan</a></li>
					<li class=""><a href="#tab_op" data-toggle="tab" aria-expanded="false">Operasi</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_diagnosa">
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
									<button type="button" class="btn btn-xs	btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah Diagnosa</button>
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
										age = confirm('Yakin Untuk Menghapus Diagnosa {$d->diagnosa}');
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
					
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_tindakan">
						<table class='table table-bordered'>
							<tr>
								<td colspan=2><a href='<?= Url::to(['poliklinik/tindakan?id='.$model->id]) ?>' class='btn btn-primary' >Tindakan</a></td>
							</tr>
							<tr>
								<th>Tindakan</th>
								<th>Bayar</th>
								<th>#</th>
							</tr>
							
							<?php if(count($soaptindakanlist) > 0) {
									foreach($soaptindakanlist as $stl):
							?>
								<tr>
									<td><a class='btn btn-sm btn-default'><?= $stl->tindakan->nama_tarif?></a></td>
									<td><?= $stl->bayar->bayar ?></td>
									<td><a href='<?= Url::to(['poliklinik/hapus-tindakan?id='.$stl->id])?>' class='btn btn-xs btn-danger'>Hapus</a></td>
								</tr>
							<?php endforeach; 
							}?>
						</table>
						<h5>Tindakan Penunjang</h5>
						<table class='table table-bordered'>
							<tr>
								<th>Tindakan Penunjang</th>
								<th>Bayar</th>
								<th>#</th>
							</tr>
							<?php foreach($list_tarif_trx as $lt){ ?>
							<tr>
								<td><a class='btn btn-sm btn-default'><?= $lt->tindakan->nama_tarif?></a></td>
								<td><?= $lt->bayar->bayar ?></td>
								<td><a href='<?= Url::to(['poliklinik/hapus-penunjang?id='.$lt->id]) ?>' class='btn btn-danger btn-xs'>Hapus</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				<!-- /.tab-pane -->
					
					<div class="tab-pane" id="tab_radiologi">
						<table class='table table-bordered'>
							<tr>
								<th>Tindakan Radiologi</th>
								<th>#</th>
							</tr>
							<tr>
								<td colspan=2><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mdRadiologi">Tambah Tindakan</button></td>
							</tr>
							<?php if(count($soapradiologilist) > 0) {
									foreach($soapradiologilist as $srl):
							?>
								<tr>
									<td>
									
									<a class='btn btn-sm btn-default'><?= $srl->tindakan->nama_tindakan?></a></td>
									<td>
										<?php if($srl->idhasil == null){ ?>
											<a href='<?= Url::to(['poliklinik/hapus-rad?id='.$srl->id]) ?>' class='btn btn-danger'>Hapus</a>
										<?php }else{ ?>
											<a data-toggle="modal" data-target="#mdRad<?= $srl->id?>" class='btn btn-primary btn-xs'>Lihat</a>
										<?php } ?>
									</td>
								</tr>
							<?php endforeach; 
							}?>
						</table>
					</div>
					<div class="tab-pane" id="tab_lab">
						<table class='table table-bordered'>
							<tr>
								<td>Tindakan Laboratorium</td>
								<td>#</td>
							</tr>
							<tr>
								<td colspan=2><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mdLab">Tambah Tindakan</button></td>
								<?php if(count($soaplablist) > 0) {
									foreach($soaplablist as $sll):
								?>
								<tr>
									<td><a class='btn btn-sm btn-default'><?= $sll->pemeriksaan->nama_pemeriksaan?></a></td>
									<td>
										<?php if($sll->idhasil == null){ ?>
											<a href='<?= Url::to(['poliklinik/hapus-lab?id='.$sll->id]) ?>' class='btn btn-danger'>Hapus</a>
										<?php }else{ ?>
											<a id="btlab<?= $sll->id?>"  class='btn btn-primary btn-xs'>Lihat</a>
											<iframe src="<?= Url::to(['laboratorium/hasil-print?id='.$sll->idhasil]) ?>" style="border:none; display:none;" id='myFrameLabel<?= $sll->id?>' title="Iframe Example">
											</iframe>
											<?php
											$this->registerJs("

											$('#btlab{$sll->id}').on('click',function(){
											let objFra = document.getElementById('myFrameLabel{$sll->id}');
											objFra.contentWindow.focus();
											objFra.contentWindow.print();
											});
											
											", View::POS_READY);
											?>
										<?php } ?>
									</td>
								</tr>
								<?php endforeach; 
								}?>
							</tr>
						</table>
					</div>
					<div class="tab-pane" id="tab_obat">
						<table class='table table-bordered'>
							<tr>
								<td colspan=5><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mdResep">Tambah Resep</button></td>
							</tr>
							<tr>
								<th width=10>No</th>
								<th>Tgl Resep</th>
								<th>Kode Resep</th>
								<th>Dokter</th>
								<th>#</th>
							</tr>
							
								<?php if(count($resep_list) > 0) {
									$no=1; foreach($resep_list as $lr):
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $lr->tgl_resep ?></td>
									<td><?= $lr->kode_resep ?></td>
									<td><?= $model->dokter->nama_dokter ?></td>
									<td><?php if($lr->status == 1){ ?><a href='<?= Url::to(['hapus-resep?id='.$lr->id]) ?>' class='btn btn-warning btn-xs'>Hapus</a> <?php } ?><a href='<?= Url::to(['tambah-obat?id='.$lr->id])?>' class='btn btn-primary btn-xs'>Lihat</a></td>
								</tr>
								<?php endforeach; 
								}?>
						</table>
					</div>
					<div class="tab-pane" id="tab_op">
						<table class='table table-bordered'>
							<tr>
								<td>Tindakan Operasi</td>
								<td>#</td>
							</tr>
							<tr>
								<td colspan=2><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#mdOp">Tambah Tindakan</button></td>
							</tr>
						</table>
						<table class='table table-bordered'>
							<tr>
								<th>Tgl Operasi</th>
								<th>Kode OK</th>
								<th>Diagnosa Pra Bedah</th>
								<th>ICD X</th>
								<th>ICD IX</th>
								<th>#</th>
							</tr>
							<?php foreach($operasi_list as $ok){ ?>
							<tr>
								<td><?= $ok->tgl_ok  ?></td>
								<td><?= $ok->kode_ok  ?></td>
								<td><?= $ok->diagnosisprabedah  ?></td>
								
								<td><?= $ok->icd10  ?></td>
								<td><?= $ok->icd9 ?></td>
								<td>
									<?php if($ok->status == 1){echo'<a class="btn btn-danger btn-xs" href="'.Url::to(['hapus-ok?id='.$ok->id]).'">Hapus</a>';}else{ ?>
									<a data-toggle="modal" data-target="#mdOKV<?= $ok->id?>" class='btn btn-primary btn-xs'>Lihat</a>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</table>
						<?php foreach($operasi_list as $ok){
							$okTindakan = OperasiTindakan::find()->Where(['idok'=>$ok->id])->all();
							Modal::begin([
									'id' => 'mdOKV'.$ok->id,
									'header' =>$ok->kode_ok ,
									'size'=>'modal-lg',
									'options'=>[
										'data-url'=>'transaksi',
										'tabindex' => ''
									],
								]);

									echo '<div class="modalContent">'. $this->render('_formOkV', ['model'=>$model,'okTindakan'=>$okTindakan ]).'</div>';
									 
									Modal::end();
						} ?>
					</div>
					<div class="tab-pane" id="tab_penunjang">
						<a href='<?= Url::to(['poliklinik/penunjang?id='.$model->id])?>' class='btn btn-success'>Tarif Penunjang</a>
					</div>
				<!-- /.tab-pane -->
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		</div>
		
	</div>
	</div>
<?php foreach($rawat as $r): 
	$riwayat_soapdr = SoapRajaldokter::find()->where(['idrawat'=>$r->id])->one();
	$riwayat_soappr = SoapRajalperawat::find()->where(['idrawat'=>$r->id])->one();
?>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="<?= $r->id ?>Modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><i class="fa <?= $r->jenisrawat->icon?>"></i><?= $r->idrawat ?>| <?= $r->jenisrawat->jenis?> - <?php if($r->idjenisrawat != 2){ ?><?= $r->poli->poli ?> <?php } ?> | <?= $r->dokter->nama_dokter?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <?php if($perawatsoapcount){ ?>
			<h4>SOAP Perawat</h4>
				<table class='table table-bordered'>
					<tr>
						<th>Anamnesa</th>
						<td><?= $perawatsoapcount->anamnesa ?> </td>
						<th>Respirasi</th>
						<td><?= $perawatsoapcount->respirasi ?></td>
					</tr>
					<tr>
						<th>Tekanan Darah</th>
						<td><?= $perawatsoapcount->distole ?> / <?= $perawatsoapcount->sistole ?> mmHg</td>
						<th>Suhu</th>
						<td><?= $perawatsoapcount->suhu ?> C</td>
					</tr>
					<tr>
						
					</tr>
					<tr>
						<th>Saturasi</th>
						<td><?= $perawatsoapcount->saturasi ?></td>
						<th>Nadi</th>
						<td><?= $perawatsoapcount->nadi ?></td>
					</tr>
					<tr>
						<th>Nadi</th>
						<td><?= $perawatsoapcount->nadi ?></td>
						<th>Respirasi</th>
						<td><?= $perawatsoapcount->respirasi ?></td>
					</tr>
					<tr>
						
						<th>Berat Badan</th>
						<td><?= $perawatsoapcount->berat ?> kg</td>
						<th>Tinggi Badan</th>
						<td><?= $perawatsoapcount->tinggi ?> cm</td>
					</tr>
		
				</table>
			<?php } ?>
		  <?php if($riwayat_soapdr){
			$rrujuk = RawatRujukan::find()->where(['idrawat'=>$model->id])->one(); 
			  ?>
			<h4>Pemeriksaan Dokter</h4>
			<table class='table table-bordered'>
			<tr>
				<th>Anamnesa</th>
				<td><?= $riwayat_soapdr->anamnesa ?> </td>
			</tr>
			<tr>
				<th>Planing</th>
				<td><?= $riwayat_soapdr->planing ?> </td>
			</tr>
			<tr>
				<th>Keterangan Rawat</th>
				<td><?= $model->rawatstatus->status ?> </td>
			</tr>
			<?php if($rrujuk){ ?>
			<tr>
				<th>Tujuan Rujuk</th>
				<td><?= $rrujuk->tujuan_rujuk ?>  <a href='' class='btn btn-success btn-xs'><span class='fa fa-pencil'></span></a></td>
			</tr>
			<?php } ?>
			
			</table>
			<?php }else{?>
			
			<h4>SOAP Dokter</h4>
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-warning"></i>Belum ada data terinput</h4>
			</div>
			<?php }?>
			
		  </div>
	  </div>
	</div>
</div>
<?php endforeach; ?>


<?php 
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
foreach($soaplablist as $sll):
if($sll->idhasil != null){
Modal::begin([
	'id' => 'mdLab'.$sll->id,
	'header' => '<h3>Hasil Lab</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formHasilLab', ['model'=>$model,'sll'=>$sll]).'</div>';
 
Modal::end();
}
endforeach;
Modal::begin([
	'id' => 'mdTindakan',
	'header' => '<h3>Tarif Dokter</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formTindakan', ['model'=>$model,'soaptindakan'=>$soaptindakan ,'tarif_trx'=>$tarif_trx]).'</div>';
 
Modal::end();

Modal::begin([
	'id' => 'mdResep',
	'header' => '<h3>Buat Resep</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formResep', ['model'=>$model,'resep'=>$resep ]).'</div>';
 
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
	'header' => '<h3>Laboratorium</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formLab', ['model'=>$model,'soaplab'=>$soaplab]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'mdObat',
	'header' => '<h3>Obat obatan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formObat', ['model'=>$model,'soapobat'=>$soapobat]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'mdOp',
	'header' => '<h3>Operasi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formOk', ['model'=>$model,'operasi'=>$operasi]).'</div>';
 
Modal::end();
 ?>