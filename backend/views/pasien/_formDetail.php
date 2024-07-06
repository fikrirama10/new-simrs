<?php
use common\models\SoapRajaldokter;
use common\models\SoapRajalperawat;
use common\models\SoapRajalicdx;
use common\models\SoapLab;
use common\models\SoapRadiologi;
use common\models\SoapRajalobat;

$soapdr = SoapRajaldokter::find()->where(['idrawat'=>$lr->id])->one();
$soappr = SoapRajalperawat::find()->where(['idrawat'=>$lr->id])->one();
$soapicdx = SoapRajalicdx::find()->where(['idrawat'=>$lr->id])->all();
$soaplab = SoapLab::find()->where(['idrawat'=>$lr->id])->all();
$soaprad = SoapRadiologi::find()->where(['idrawat'=>$lr->id])->all();
$soapobat = SoapRajalobat::find()->where(['idrawat'=>$lr->id])->all();
?>

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
					<td><?= $lr->rawatstatus->status ?> </td>
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