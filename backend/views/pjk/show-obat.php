<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Obat / Alkes</th>
		<th>Satuan</th>
		<th>Harga Satuan</th>
		<th>Jumlah</th>
		<th>Total</th>
	</tr>
	<?php $no=1; foreach($model as $m){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $m['barang'] ?></td>
		<td><?= $m['satuan'] ?></td>
		<td><?= $m['harga'] ?></td>
		<td><?= $m['jumlah'] ?></td>
		<td><?= $m['total'] ?></td>
	</tr>
	<?php } ?>
</table>