<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	
	</div>
</div>
<div class='header upper'>
	<b>REKAP PENYERAHAN ATK RUANGAN<br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='judul-kunjungan'>
</div>
<div class='si'>
	<table class=''>
		<tr>
			<th>No</th>
			<th>Nama Ruangan</th>
			<th colspan=2>Nama Barang</th>
			<th width=50>Jumlah Barang</th>
			<th>Satuan Barang</th>
		</tr>
		<?php $no=1; $no2=1; foreach($model as $m){ ?>
		<tr>
			<td rowspan='<?= count($m['obat'])+1?>'><?= $no++ ?></td>
			<td rowspan='<?= count($m['obat'])+1?>'><?= $m['ruangan'] ?></td>
		</tr>
		<?php  foreach($m['obat'] as $o){ ?>
		<tr>
			<td width=10><?= $no2++ ?></td>
			<td><?= $o['namaBarang'] ?></td>
			<td><?= $o['jumlah'] ?></td>
			<td><?= $o['satuan'] ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
</div>
