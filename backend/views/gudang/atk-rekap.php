<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	
	</div>
</div>
<div class='header upper'>
	<b>REKAP PENYERAHAN ATK <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='judul-kunjungan'>
</div>
<div class='si'>
	<table class=''>
		<tr>
			<th>No</th>
			<th>Nama Barang</th>
			<th>Satuan Barang</th>
			<th>Jumlah Barang</th>
		</tr>
		<?php $no=1; foreach($model as $m){ ?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $m['namaBarang'] ?></td>
			<td><?= $m['jumlah'] ?></td>
			<td><?= $m['satuan'] ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
