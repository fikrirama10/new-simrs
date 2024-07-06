<div class="callout callout-info">
<h4>Jumlah Pasien : <?= count($ranap)?></h4>

<p>Jumlah dari tanggal <?= $start ?> - <?= $end ?></p>
</div>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No RM</th>
		<th>Nama Pasien</th>
		<th>Id Rawat</th>
		<th>Tgl</th>
		<th>%</th>
	</tr>
	<?php $no=1; foreach($ranap as $j){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $j['noRm'] ?></td>
		<td><?= $j['pasien'] ?></td>
		<td><?= $j['idrawat'] ?></td>
		<td><?= $j['tglRawat'] ?></td>
		<td><p class="text-green">+ Rp<?= Yii::$app->algo->IndoCurr($j['total'] )?></p></td>
	</tr>
	<?php } ?>
</table>