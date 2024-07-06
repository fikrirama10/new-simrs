<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No RM</th>
		<th>Nama Pasien</th>
		<th>Poliklinik</th>
		<th>Penanggung</th>
		<th>Usia</th>
	</tr>
	<?php $no=1; foreach($rajal as $p){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $p->no_rm ?></td>
		<td><?= $p->pasien->nama_pasien ?></td>
		<td><?= $p->poli->poli ?></td>
		<td><?= $p->bayar->bayar ?></td>
		<td><?= $p->pasien->usia_tahun ?></td>
	</tr>
	<?php } ?>
</table>