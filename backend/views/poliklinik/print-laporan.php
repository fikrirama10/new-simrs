<?php
use common\models\PasienKategori;
use common\models\SoapRajalicdx;
?>
<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU Lanud Sulaiman
	</div>
	
</div>
<div class='header upper'>
	<b>LAPORAN RAWATJALAN <?= $kunjungan->poli ?>  BULAN <?= $bulann ?> TAHUN <?= $tahun ?></b>
</div>
<div class='judul-kunjungan'>
</div>
<div class='si'>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Pasien</th>
			<th>Tgl Kunjungan</th>
			<th>Dokter</th>
			<th>Kunjungan</th>
			<th>ICD 10</th>
			<th>Jenis Bayar</th>
			<th>Ket</th>
		</tr>
		<?php $no=1; foreach($rawat_bpjs as $rb){ 
		$kategori = PasienKategori::findOne($rb->kat_pasien);
		$diagnosa = SoapRajalicdx::find()->where(['idrawat'=>$rb->id])->all();
		$rr ='';
		?>
		<?php 
			if($kategori){
				if($kategori->id < 7){$rr='background:#fbffab;';} 
			}else{
				$rr = '';
			}
			
		?>
		<tr style='<?= $rr ?>'>
			<td><?= $no++ ?></td>
			<td>(<?= $rb->no_rm ?>) - <?= $rb->pasien->nama_pasien ?></td>
			<td><?= date('Y/m/d',strtotime($rb->tglmasuk)) ?></td>
			<td><span style='text-transform: capitalize;'><?= $rb->dokter->nama_dokter ?></span></td>
			<td><?= $rb->poli->poli ?></td>
			<td>
				<?= $rb->icdx ?>
			</td>
			<td><?= $rb->bayar->bayar ?></td>
			<td>
			<?php if($kategori){ ?>
				<?= $kategori->nama ?>
				<?php } ?>	
			</td> 
		</tr>
		<?php } ?>
		<?php  foreach($rawat_umum as $ru){ 
		$kategori = PasienKategori::findOne($ru->kat_pasien);
		$diagnosas = SoapRajalicdx::find()->where(['idrawat'=>$ru->id])->all();
		$rr ='';
		?>
		<?php if($kategori){
				if($kategori->id < 7){$rr='background:#fbffab;';} 
			}else{
				$rr = '';
			} ?>
		<tr style='<?= $rr ?>'>
			<td><?= $no++ ?></td>
			<td>(<?= $ru->no_rm ?>) - <?= $ru->pasien->nama_pasien ?></td>
			<td><?= date('Y/m/d',strtotime($ru->tglmasuk)) ?></td>
			<td><span style='text-transform: capitalize;'><?= $ru->dokter->nama_dokter ?></span></td>
			<td><?= $ru->poli->poli ?></td>
			<td>
				<?php foreach($diagnosas as $ds){ ?>
					<?= $ds->icdx?> ,
				<?php } ?>
			</td>
			<td><?= $ru->bayar->bayar ?></td>
			<td>
			<?php if($kategori){ ?>
				<?= $kategori->nama ?>
				<?php } ?>	
			
			</td>
		</tr>
		<?php } ?>
	</table>
</div>