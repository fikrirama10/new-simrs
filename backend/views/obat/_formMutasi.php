<table class='table table-bordered'>
	<tr>
		<th>Tgl</th>
		<th>Stok Awal</th>
		<th>Jumlah</th>
		<th>Stok Akhir</th>
		<th>Jenis Mutasi</th>
		<th>Detail Mutasi</th>
	</tr>
	<?php foreach($json as $j){ ?>
	<tr>
		<td><?= $j['tgl']?></td>
		<td><?= $j['stokawal']?></td>
		<td><?= $j['jumlah']?></td>
		<td><?= $j['stokakhir']?></td>
		<td><?= $j['jenisMutasi']?></td>
		<td><?= $j['mutasiDetail']?></td>
	</tr>
	<?php } ?>
</table>