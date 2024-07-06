<?php
	use common\models\PasienAlamat;
	$alamat = PasienAlamat::find()->where(['utama'=>1])->andwhere(['idpasien'=>$model->pasien->id])->one();
?>
<h4 align=center>Ringkasan Pasien Pulang</h4>
<div class="datapasien">
	
	<table>
		<tr>
			<td>Nama Pasien</td>
			<td>:</td>
			<td width=250px><?= $model->pasien->nama_pasien ?></td>
			
			<td>No RM</td>
			<td>:</td>
			<td><?= $model->no_rm ?></td>
		</tr>
		<tr>
			<td>Tgl Masuk</td>
			<td>:</td>
			<td width=250px><?= $model->rawat->tglmasuk ?></td>
			
			<td>Jenis Kelamin</td>
			<td>:</td>
			<td>
				<?= $model->pasien->jenis_kelamin?>
			</td>
		</tr>
		
		<tr>
			<td>Tgl Keluar</td>
			<td>:</td>
			<td width=250px><?= $model->rawat->tglpulang?></td>
			
			<td>Tgl Lahir</td>
			<td>:</td>
			<td><?= $model->pasien->tgllahir ?></td>
		</tr>
		<tr>
			<td>Lama Rawat</td>
			<td>:</td>
			<td width=250px><?= $model->rawat->los ?> hari</td>
			
			<td>Alamat</td>
			<td>:</td>
			<td><?= $alamat->alamat ?></td>
		</tr>
		
	</table>
	<table>
		<tr>
			<td width=200px>Diagnosa Primer</td>
			<td width=10px>:</td>
			<td><?= $model->diagnosa_primer?></td>
		</tr>
		<tr>
			<td width=200px>Diagnosa Sekunder</td>
			<td width=10px>:</td>
			<td><?= $model->diagnosa_sekunder?></td>
		</tr>
		<tr>
			<td width=200px>Tindakan Primer</td>
			<td width=10px>:</td>
			<td><?= $model->tindakan_primer?></td>
		</tr>
		<tr>
			<td width=200px>Tindakan Sekunder</td>
			<td width=10px>:</td>
			<td><?= $model->tindakan_sekunder?></td>
		</tr>
		<tr>
			<td width=200px>Indikasi Rawat</td>
			<td width=10px>:</td>
			<td><?= $model->indikasi_rawat ?></td>
		</tr>
		<tr>
			<td width=200px>Riwayat Penyakit</td>
			<td width=10px>:</td>
			<td><?= $model->riwayat_penyakit ?></td>
		</tr>
		<tr>
			<td width=200px>Pemeriksaan Fisik</td>
			<td width=10px>:</td>
			<td><?= $model->indikasi_rawat ?></td>
		</tr>
		<tr>
			<td width=200px>Pemeriksaan Penunjang</td>
			<td width=10px>:</td>
			<td><?= $model->indikasi_rawat ?></td>
		</tr>
		<tr>
			<td width=200px>Prognosa</td>
			<td width=10px>:</td>
			<td><?= $model->riwayat_penyakit ?></td>
		</tr>
		<tr>
			<td width=200px>Anjuran</td>
			<td width=10px>:</td>
			<td><?= $model->riwayat_penyakit ?></td>
		</tr>
		<tr>
			<td width=200px>Terapi</td>
			<td width=10px>:</td>
			<td><?= $model->indikasi_rawat ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<td colspan=3>Kondisi waktu Pulang / Keluar RS: <b><?= $model->pulang->pulang ?></b></td>
		</tr>
		<tr>
			<td colspan=3>TINDAK LANJUT / KONTROL: <i><?= $model->tgl_kontrol ?></i> <b>Poliklinik :</b> <?= $model->poli->poli?></td>
		</tr>
		<tr>
			<td width=300px></td>
			<td></td>
			<td align=center>Bandung, <?= date('d-m-Y',strtotime($model->tgl_pulang))?><br>Yang membuat<b></b><br><br><br><br><br><br><br></td>
		</tr>
	</table>
</div>
