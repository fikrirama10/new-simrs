<div style='text-align:center;'><b><?= Yii::$app->kazo->getSbb($model->pasien->usia_tahun,$model->pasien->jenis_kelamin,$model->pasien->idhubungan); ?>.<?= $model->pasien->nama_pasien?></b></center>
<table>
	<tr>
		<td>No Rm</td>
		<td>: <b><?= $model->no_rm?></b></td>
		<td>Tgl Periksa</td>
		<td>: <b><?= date('Y/m/d',strtotime($model->rawat->tglmasuk))?></b></td>
	<tr>
	<tr>
		<td>Tgl Lahir</td>
		<td>: <b><?= date('Y/m/d',strtotime($model->pasien->tgllahir))?></b></td>
		<td colspan=2><?= $model->rawat->dokter->nama_dokter?></td>
	<tr>
</table>
<div class='olab'>
	<table>
		<tr>
			<th>Nama Obat</th>
			<th>Qty</th>
			<th>Aturan Minum</th>
		</tr>
		<?php foreach($resep as $r){ ?>
		<?php if($r->qty > 0){ ?>
		<tr>
			<td width=200><?= $r->obat->nama_obat?></td>
			<td width=10><?= $r->qty ?></td>
			<td><?= $r->signa ?> sehari <?= $r->dosis ?> <?= $r->takaran ?>  <?= $r->diminum ?> <?php if($r->diminum != null){echo'makan';} ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
</div>