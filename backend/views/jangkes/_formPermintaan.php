<?php 
use common\models\ObatBacth;
$obat = ObatBacth::find()->where(['idobat'=>$pl->idobat])->all();
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No Bacth</th>
		<th>Merk</th>
		<th>ED</th>
		<th>Stok Gudang</th>
		<th>Stok Apotek</th>
	</tr>
	<?php $no=1; foreach($obat as $o): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $o->no_bacth ?></td>
		<td><?= $o->merk ?></td>
		<td><?= $o->tgl_kadaluarsa ?></td>
		<td><?= $o->stok_gudang ?></td>
		<td><?= $o->stok_apotek ?></td>
	</tr>
	<?php endforeach; ?>
</table>