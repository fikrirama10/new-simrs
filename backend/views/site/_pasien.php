<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No RM</th>
		<th>Nama Pasien</th>
		<th>Usia</th>
	</tr>
	<?php $no=1; foreach($pasien as $p){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $p->no_rm ?></td>
		<td><?= $p->nama_pasien ?></td>
		<td><?= $p->usia_tahun ?></td>
	</tr>
	<?php } ?>
</table>