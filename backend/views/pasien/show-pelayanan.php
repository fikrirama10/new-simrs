<?php 
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use kartik\date\DatePicker;
	use yii\web\JsExpression;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use common\models\JenisDiagnosa;
	use common\models\Dokter;
	use common\models\RawatBayar;
	use yii\web\View;
	use common\models\Poli;
	use common\models\Kamar;
	use common\models\DokterJadwal;
	use common\models\DokterKuota;
	use yii\bootstrap\Modal;
	use kartik\checkbox\CheckboxX;
?>
	<div class='box-header with-border'><h3>Riwayat Pelayanan</h3></div>
	<div class='box-body'>
		<table class='table tabel-rawat'>
			<tr>
				<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Id Rawat</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->idrawat ?>'></td>
			</tr>
			<?php if($rawat->idbayar == 2){ ?>
			<tr>
				<td class='pd-top'><span class='pull-right' style='line-height:20px;'>No SEP</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->no_sep ?>'></td>
			</tr>
			<?php } ?>
			<tr>
				<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Jenis Rawat</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->jenisrawat->jenis?>'></td>
			</tr>
			<tr>
				<td class='pd-top'><span class='pull-right' style='line-height:20px;'>PoliKlinik</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->poli->poli?>'></td>
			</tr>
			<tr>
				<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Tgl Masuk</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->tglmasuk?>'></td>
			</tr>
			<tr>
				<td><span class='pull-right' style='line-height:20px;'>Dokter</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->dokter->nama_dokter?>'></td>
			</tr>
			<tr>
				<td><span class='pull-right' style='line-height:20px;'>Penanggung</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->bayar->bayar?>'><br>
				<?php if($rawat->idbayar == 2){ ?>
					<?php if($rawat->no_sep == null){ ?>
						<a class='btn btn-success btn-sm'>BUAT SEP</a>
					<?php }else{ ?>
						<a class='btn btn-primary btn-sm'>LIHAT SEP</a>
					<?php } ?>
				<?php } ?>
				</td>
			</tr>
			
			
			
			
			<?php if($rawat->status == 5){ ?>
			<tr>
				<td><span class='pull-right' style='line-height:20px;'>Keterangan</span></td>
				<td><input type='text' class='form-control' readonly value='<?= $rawat->keterangan ?>'></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<div class='box-footer'>
		<?php if($rawat->status == 5){ ?>
			<div class="callout callout-danger">
				<h5>Kunjungan Sudah Dibatalkan</h5>
			</div>
		<?php }else{ ?>
			<a href='<?= Url::to(['pasien/edit-rawat?id='.$rawat->id])?>' class='btn btn-primary'>Edit</a>
			<a data-toggle="modal" data-target="#pelayananModal" class='btn btn-warning'>Lihat</a>
			<a href='<?= Url::to(['pasien/batal-rawat?id='.$rawat->id])?>' class='btn btn-danger'>Batalkan</a>
		<?php } ?>
	</div>
	
	<div class="modal fade bd-example-modal-lg" tabindex="-1" id="pelayananModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><i class="fa <?= $rawat->jenisrawat->icon?>"></i><?= $rawat->idrawat ?>| <?= $rawat->jenisrawat->jenis?> - <?php if($rawat->idjenisrawat != 2){ ?><?= $rawat->poli->poli ?> <?php } ?> | <?= $rawat->dokter->nama_dokter?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <?php if($soapdr){ ?>
			<h4>SOAP Dokter</h4>
			<table class='table table-bordered'>
				<tr>
					<th>Anamnesa</th>
					<td><?= $soapdr->anamnesa ?> </td>
				</tr>
				<tr>
					<th>Planing</th>
					<td><?= $soapdr->planing ?> </td>
				</tr>
				<tr>
					<th>Keterangan Rawat</th>
					<td><?= $rawat->rawatstatus->status ?> </td>
				</tr>		
			</table>
			<?php }else{?>
			
			<h4>SOAP Dokter</h4>
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-warning"></i>Belum ada data terinput</h4>
			</div>
			<?php }?>
			<?php if($soappr){ ?>
				<h4>SOAP Perawat</h4>
				<table class='table table-bordered'>
					<tr>
						<th>Anamnesa</th>
						<td><?= $soappr->anamnesa ?> </td>
						<th>Respirasi</th>
						<td><?= $soappr->respirasi ?></td>
					</tr>
					<tr>
						<th>Tekanan Darah</th>
						<td><?= $soappr->distole ?> / <?= $soappr->sistole ?> mmHg</td>
						<th>Suhu</th>
						<td><?= $soappr->suhu ?> C</td>
					</tr>
					<tr>
						
					</tr>
					<tr>
						<th>Saturasi</th>
						<td><?= $soappr->saturasi ?></td>
						<th>Nadi</th>
						<td><?= $soappr->nadi ?></td>
					</tr>
					<tr>
						<th>Nadi</th>
						<td><?= $soappr->nadi ?></td>
						<th>Respirasi</th>
						<td><?= $soappr->respirasi ?></td>
					</tr>
					<tr>
						
						<th>Berat Badan</th>
						<td><?= $soappr->berat ?> kg</td>
						<th>Tinggi Badan</th>
						<td><?= $soappr->tinggi ?> cm</td>
					</tr>
		
				</table>
			<?php }else{?>
			<h4>SOAP Perawat</h4>
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-warning"></i>Belum ada data terinput</h4>
			</div>
			<?php }?>
			<?php if($soapicdx){ ?>
				<h4>Diagnosis</h4>
				<table class='table table-bordered'>
						<tr>
							<th>Diagnosa</th>
							<th>ICD X</th>
							<th>Jenis</th>
						</tr>
				
						<?php foreach($soapicdx as $d){ ?>
						<tr>
							<td><a  id='btn-diagnosa'><?= $d->diagnosa ?></a></td>
							<td><?= $d->icdx ?></td>
							<td><?= $d->jenis->jenis ?></td>
						</tr>

						<?php } ?>
				</table>
			<?php }else{?>
			<h4>Diagnosis</h4>
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-warning"></i>Belum ada data terinput</h4>
			</div>
			<?php }?>
				<h4>Pemeriksaan Penunjang</h4>
				<table class='table table-bordered'>
					<tr>
						<th>Lab</th>
						<td>
							<?php if($soaplab){ ?>
								<?php foreach($soaplab as $sl): ?>
									<?= $sl->pemeriksaan->nama_pemeriksaan?>,
								<?php endforeach; ?>
							<?php } ?>
						</td>
					</tr>		
					<tr>
						<th>Radiologi</th>
						<td>
							<?php if($soaprad){ ?>
								<?php foreach($soaprad as $sr): ?>
									<?= $sr->tindakan->nama_tindakan?>,
								<?php endforeach; ?>
							<?php } ?>
						</td>
					</tr>		
				</table>
				<h4>Obat obatan</h4>
				<?php $no=1; if($soapobat){ ?>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Obat</th>
						<th>Jumlah</th>
						<th>Signa</th>
						<th>Catatan</th>
					</tr>
					<?php foreach($soapobat as $sol): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $sol->obat->nama_obat?> (<?= $sol->bacth->merk?>)</td>
						<td><?= $sol->jumlah ?></td>
						<td><?= $sol->signa1?> x <?= $sol->signa2?></td>
						<td><?= $sol->catatan ?></td>
					</tr>
					<?php endforeach;?>
				</table>
				<?php } ?>
		  </div>
	  </div>
	</div>
</div>
	