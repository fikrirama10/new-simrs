	<?php
		use common\models\RawatJenis;
		$jenisrawat = RawatJenis::findOne($jenis);
	?>
	<h3 align=center>Data Diagnosa <?= $jenisrawat->jenis ?></h3>
	<div class='si'>
		<table>
			<tr>
				<th width=20>No</th>
				<th>Diagnosa</th>
				<th>Jumlah</th>
			</tr>
			<?php $no=1; foreach($model as $m){ ?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $m['nama'] ?></td>
				<td><?= $m['jumlah'] ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>