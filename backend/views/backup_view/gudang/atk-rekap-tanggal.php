<div class='si'>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Ruangan</th>
			<th>Nama Barang</th>
			<th width=50>Jumlah Barang</th>
			<th>Satuan Barang</th>
		</tr>
		<?php $no=1;  foreach($model as $m){ ?>
		<tr>
			<td rowspan='<?= count($m['obat'])+1?>'><?= $no++ ?></td>
			<td rowspan='<?= count($m['obat'])+1?>'><?= $m['tgl_penyerahan'] ?></td>
		</tr>
		<?php  foreach($m['obat'] as $o){ ?>
		<tr>
			<td><?= $o['namaBarang'] ?></td>
			<td><?= $o['jumlah'] ?></td>
			<td><?= $o['satuan'] ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
</div>
