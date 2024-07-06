<div class='header upper'>
	<b>REKAP FAKTUR</b>
</div>

<table>
	<tr>
		<th>No</th>
		<th>No Faktur</th>
		<th>Nilai Faktur</th>
		<th>Tgl Faktur</th>
	</tr>
	<?php $no=1; foreach($model as $m){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $m->no_faktur ?></td>
		<td><?= $m->nilai_faktur ?></td>
		<td><?= $m->tgl_faktur ?></td>
	</tr>
	<?php } ?>
</table>