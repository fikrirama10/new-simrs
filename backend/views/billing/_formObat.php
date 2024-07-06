<?php
	use common\models\ObatTransaksiDetail;
	$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$pl->id])->all();
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Obat / Alkes (Merk)</th>
		<th>Penanggung</th>
		<th>Jumlah</th>
		<th>Harga</th>
		<th>Total</th>
	</tr>
	<?php $no=1; foreach($detail as $d){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $d->obat->nama_obat ?> (<?= $d->bacth->merk ?>)</td>
		<td><?= $d->idbayar?> </td>
		<td><?= $d->qty ?> </td>
		<td><?= $d->harga ?> </td>
		<td><?= $d->total ?> </td>
	</tr>
	<?php }  ?>
</table>