<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Obat / Alkes</th>
		<th>Jumlah</th>
		<th>Aturan Minum</th>
	</tr>
	<?php $no=1; foreach($list as $l): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $l->merk->merk ?></td>
		<td><?= $l->qty ?></td>
		<td><?= $l->signa1 ?> X <?= $l->signa2?></td>
	</tr>
	<?php endforeach; ?>
</table>