<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Tindakan</th>
		<th>Jumlah</th>
		<th>Harga</th>
		<th>Total</th>
	</tr>
	<?php $no=1; $total=0; foreach($model as $m){ 
	$total += $m['total'];
	?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $m['nama_tarif'] ?></td>
		<td><?= $m['jumlah'] ?> x</td>
		<td><?= Yii::$app->algo->IndoCurr(round($m['harga'])) ?></td>
		<td><?= Yii::$app->algo->IndoCurr(round($m['total'])) ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan=4><span class='pull-right'>Total Biaya</span></td>
		<td><?= Yii::$app->algo->IndoCurr(round($total)) ?></td>
	</tr>
</table>