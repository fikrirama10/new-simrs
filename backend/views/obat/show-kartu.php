<table class='table table-bordered'>
	<tr>
		<th width='10'>No</th>
		<th>Tgl</th>
		<th>Merk</th>
		<th>Jumlah</th>
		<th>Jenis</th>
	</tr>
	<?php if(count($json) < 1){ ?>
	<tr>
		<td colspan=5>Tidak ada data</td>
	</tr>
	<?php } ?>
	<?php $no=1; foreach($json as $j): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $j['tgl'] ?></td>
		<td><?= $j['merk'] ?></td>
		<td><?= $j['jumlah'] ?></td>
		<td><?= $j['jenis'] ?></td>
	</tr>
	<?php endforeach; ?>
	
</table>